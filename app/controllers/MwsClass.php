<?php
Class AmazonMws{
	private $sellerid;
	private $marketid;
	private $seceretkey;
	private $accessid;
	private $url;
	private $mode;
	/**
	 * Configuring Applicatoin keys
	 * 
	 * @author Abdullah Bashir <mabdullah353@gmail.com>
	 * @date 29-Aug-2014
	 * @param access id and secret id of your amazon account 
	 */
	public function __construct($sellerid, $marketid, $acckey="AKIAJUYJSUM5MS6FNFYA", $seckey="XcWYM+HpwE2yrbo7SL4MQ1ONYtNF0kwOdbPjyeB9", $url = "https://mws.amazonservices.com"){
		$this->mode = "";
		$this->sellerid 	= $sellerid;
		$this->marketid 	= $marketid;
		$this->accessid 	= $acckey;
		$this->seceretkey = $seckey;
		$this->url 				= $url;
	}
	/**
	 * Selecting Store From Amazon
	 * 
	 * @author Abdullah Bashir <mabdullah353@gmail.com>
	 * @date 29-Aug-2014
	 * @param seller and market id of your amazon account 
	 */
	public function selectstore($sellerid,$marketid){

	}
	/**
		*	Fetching list of orders from the provided date
		* @param Order Id 
		*	@return Item Detail
		*
		*	@author Abdullah Bashir <mabdullah353@gmail.com>
		*	@date 29-Aug-2014
		*/
	public function ListOrderItems($orderId,$version = "2013-09-01"){
		$url	= $this->url."/Orders/".$version;
		$url .= "?Action=ListOrderItems";
		$url .= "&SellerId=".$this->sellerid;
		$url .= "&AmazonOrderId=".$orderId;
		return $this->MakeRequest($url,$version);
	}
	
	/**
		*	Fetching list of orders from the provided date
		* @param Order Id 
		*	@return Item Detail
		*
		*	@author Abdullah Bashir <mabdullah353@gmail.com>
		*	@date 29-Aug-2014
		*/
	public function productsByIds($codes, $version = "2011-10-01"){
		$url	= $this->url."/Products/".$version;
		$url .= "?Action=GetMatchingProduct";
		$url .= "&SellerId=".$this->sellerid;
		$url .= "&MarketplaceId=".$this->marketid;
		for ($i=0; $i < count($codes); $i++) { 
			$url .= '&ASINList.ASIN.'.( $i+1 ).'='.$codes[$i];
		}
		return $this->MakeRequest($url,$version);
	}
	
	/**
	*	Fetching list of orders from the provided date
	*
	* @param $from is a date in the form of "yyyy-mm-dd", $status is you orders status like "Unshipped", "pending" etc.
	* @param $verion - (Optional) if not provided then version 2013-09-01 will be used by default
	* @return array of orders
	*
	*	@author Abdullah Bashir <mabdullah353@gmail.com>
	*	@date 29-Aug-2014
	*/
	public function ListOrders($from,$status = "Unshipped",$version = "2013-09-01"){
		$newDate = gmdate("Y-m-d\TH:i:s\Z", strtotime($from));
		$url	 = $this->url."/Orders/".$version;
		$url	.= "?Action=ListOrders";
		$url	.= "&MarketplaceId.Id.1=".$this->marketid;
		$url	.= "&SellerId=".$this->sellerid;
		$url	.= "&CreatedAfter=".$newDate;
		
		switch ($status) {
			case 'PendingAvailability':
				$url	.= "&OrderStatus.Status.1=PendingAvailability";
				break;
			case 'Pending':
				$url	.= "&OrderStatus.Status.1=Pending";
				break;
			case 'Unshipped':
				$url	.= "&OrderStatus.Status.1=Unshipped";
				$url	.= "&OrderStatus.Status.2=PartiallyShipped";
				break;
			case 'PartiallyShipped':
				$url	.= "&OrderStatus.Status.1=Unshipped";
				$url	.= "&OrderStatus.Status.2=PartiallyShipped";
				break;
			case 'Shipped':
				$url	.= "&OrderStatus.Status.1=Shipped";
				break;
			case 'InvoiceUnconfirmed':
				$url	.= "&OrderStatus.Status.1=InvoiceUnconfirmed";
				break;
			case 'Canceled':
				$url	.= "&OrderStatus.Status.1=Canceled";
				break;
			case 'Unfulfillable':
				$url	.= "&OrderStatus.Status.1=Unfulfillable";
				break;
		}

		$resp = $this->MakeRequest($url,$version);
		if($this->mode == "developer"){
			echo "<h1>ListOrders Response</h1>";
			echo "<span>Class Name ".get_class($this)."</span>";
			echo "<span>URL ".$url."</span>";
			echo "<pre>";
			print_r($resp);
			echo "</pre>";
		}
		$t = (array) $resp->ListOrdersResult;
		if(!empty($t)){
			$token = (isset($t['NextToken']))? $t['NextToken']: null;
			$orders = (array) $t['Orders'];
			$orders = (isset($orders['Order'] ))? $orders['Order']:null;
			if(gettype($orders) == "object"){array_push($orderlist, $orders);}else{$orderlist = $orders;}
		}
		return $orderlist;
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
	protected function getRequest($request, $version) {
			$secret_key = $this->seceretkey;
			$access_key = $this->accessid;			
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
			if(!isset($parameters['SignatureVersion'])){ $parameters['SignatureVersion'] = "2";}
			if(!isset($parameters['SignatureMethod'])){ $parameters['SignatureMethod'] = "HmacSHA256";}
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
	/**
		*	This function make actual request to the server
		* @param $url - amazon complete url with signature
		*	@return server response
		*/
	protected function MakeRequest($url, $version){
		$signedurl = $this->getRequest($url, $version);
		// Use curl to retrieve data from Amazon
		$session = curl_init( $signedurl );
		curl_setopt( $session, CURLOPT_HEADER, false );
		curl_setopt( $session, CURLOPT_RETURNTRANSFER, true );
		$response = curl_exec( $session );
		curl_close( $session );
		// Interpret data as XML
		$parsedXml = simplexml_load_string( $response );
		return $parsedXml;
	}
}