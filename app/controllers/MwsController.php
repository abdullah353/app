<?php

class MwsController extends \BaseController {
	

	public function __construct() {
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->beforeFilter('auth');
	}

	public function getIndex(){
		$resp = null;
		$orders = null;
		$orderlist = array();
		$token = null;
		$status = Input::get('status');
		if($origDate = Input::get('from')){
			$newDate = gmdate("Y-m-d\TH:i:s\Z", strtotime($origDate));

			$url   = "https://mws.amazonservices.com/Orders/2013-09-01";
			$url  .= "?Action=ListOrders";
			$url  .= "&MarketplaceId.Id.1=ATVPDKIKX0DER";
			$url  .= "&SellerId=A013695218D3GFTNCCXMX";
			$url  .= "&SignatureVersion=2";
			$url  .= "&SignatureMethod=HmacSHA256";
			$url  .= "&CreatedAfter=".$newDate;
			switch ($status) {
				case 'PendingAvailability':
					$url  .= "&OrderStatus.Status.1=PendingAvailability";
					break;
				case 'Pending':
					$url  .= "&OrderStatus.Status.1=Pending";
					break;
				case 'Unshipped':
					$url  .= "&OrderStatus.Status.1=Unshipped";
					$url  .= "&OrderStatus.Status.2=PartiallyShipped";
					break;
				case 'PartiallyShipped':
					$url  .= "&OrderStatus.Status.1=Unshipped";
					$url  .= "&OrderStatus.Status.2=PartiallyShipped";
					break;
				case 'Shipped':
					$url  .= "&OrderStatus.Status.1=Shipped";
					break;
				case 'InvoiceUnconfirmed':
					$url  .= "&OrderStatus.Status.1=InvoiceUnconfirmed";
					break;
				case 'Canceled':
					$url  .= "&OrderStatus.Status.1=Canceled";
					break;
				case 'Unfulfillable':
					$url  .= "&OrderStatus.Status.1=Unfulfillable";
					break;
			}

			$sercretk = "XcWYM+HpwE2yrbo7SL4MQ1ONYtNF0kwOdbPjyeB9";
			$accessid = "AKIAJUYJSUM5MS6FNFYA";
			echo $this->getRequest($sercretk,$url,$accessid);
			$resp = $this->MakeRequest($this->getRequest($sercretk,$url,$accessid));
			//print_r($this->MakeRequest($this->getRequest($sercretk,$url,$accessid)));
			//return View::make('mws.index');

			$t = (array) $resp->ListOrdersResult;
			if(!empty($t)){
				$token = (isset($t['NextToken']))? $t['NextToken']: null;
				$orders = (array) $t['Orders'];
				$orders = (isset($orders['Order'] ))? $orders['Order']:null;
				if(gettype($orders) == "object"){array_push($orderlist, $orders);}else{$orderlist = $orders;}
			}
			
		}
		//print_r($orderlist);
		return View::make('mws.index')->with('orders',$orderlist)->with('token',$token)->withInput(Input::all());
	}

	/**
	  * This function will take an existing Amazon request and change it so that it will be usable 
	  * with the new authentication.
	  *
	  * @param string $secret_key - your Amazon AWS secret key
	  * @param string $request - your existing request URI
	  * @param string $access_key - your Amazon AWS access key
	  * @param string $version - (optional) the version of the service you are using
	  */
	protected function getRequest($secret_key, $request, $access_key = false, $version = '2013-09-01') {
	    // Get a nice array of elements to work with
	    $uri_elements = parse_url($request);
	 
	    // Grab our request elements
	    $request = $uri_elements['query'];
	    $parameters = array();
	    foreach (explode("&", $request) as $st) {
	      $inter = explode("=", $st);
	      $parameters[$inter[0]]= $inter[1];
	    }

	    // Add the new required paramters
	    $parameters['Timestamp'] = gmdate("Y-m-d\TH:i:s\Z");
	    $parameters['Version'] = $version;
	    if (strlen($access_key) > 0) {
	        $parameters['AWSAccessKeyId'] = $access_key;
	    }   
	 
	    // The new authentication requirements need the keys to be sorted
	    ksort($parameters);
	    //print_r($parameters);
	    // Create our new request
	    foreach ($parameters as $parameter => $value) {
	        // We need to be sure we properly encode the value of our parameter
	        $parameter = str_replace("%7E", "~", rawurlencode($parameter));
	        $value = str_replace("%7E", "~", rawurlencode($value));
	        $request_array[] = $parameter . '=' . $value;
	    }   
	 
	    // Put our & symbol at the beginning of each of our request variables and put it in a string
	    $new_request = implode('&', $request_array);
	 
	    // Create our signature string
	    $signature_string = "GET\n{$uri_elements['host']}\n{$uri_elements['path']}\n{$new_request}";
	 
	    // Create our signature using hash_hmac
	    $signature = urlencode(base64_encode(hash_hmac('sha256', $signature_string, $secret_key, true)));
	 
	    // Return our new request
	    return "https://{$uri_elements['host']}{$uri_elements['path']}?{$new_request}&Signature={$signature}";
	}

	protected function MakeRequest( $url ){
		// Use curl to retrieve data from Amazon
		$session = curl_init( $url );
		curl_setopt( $session, CURLOPT_HEADER, false );
		curl_setopt( $session, CURLOPT_RETURNTRANSFER, true );
		$response = curl_exec( $session );
		curl_close( $session );
		// Interpret data as XML
		$parsedXml = simplexml_load_string( $response );
		return $parsedXml;
  }

  public function postExcel(){
  	
  	Excel::create('Filename', function($excel) {
			$excel->sheet('Boxc', function($sheet) {
	        $sheet->fromArray(Input::get('order'));
	    });  		
		})->export('csv');
  }
}

