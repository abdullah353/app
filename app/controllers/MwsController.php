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
		$orderitems = array();
		$products = array();
		$token = null;
		$status = Input::get('status');
		if($origDate = Input::get('from')){
			$account = Input::get('account');
			$sellerid		= ($account == "1")? "A013695218D3GFTNCCXMX": "A1WBEEKNE10PYT";
			$makrketid  =	($account == "1")? "ATVPDKIKX0DER": "A2EUQ1WTGCTBG2";

			$newDate = gmdate("Y-m-d\TH:i:s\Z", strtotime($origDate));

			$url	 = "https://mws.amazonservices.com/Orders/2013-09-01";
			$url	.= "?Action=ListOrders";
			$url	.= "&MarketplaceId.Id.1=".$makrketid;
			$url	.= "&SellerId=".$sellerid;
			$url	.= "&SignatureVersion=2";
			$url	.= "&SignatureMethod=HmacSHA256";
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

			$sercretk = "XcWYM+HpwE2yrbo7SL4MQ1ONYtNF0kwOdbPjyeB9";
			$accessid = "AKIAJUYJSUM5MS6FNFYA";
			// echo $this->getRequest($sercretk,$url,$accessid);
			$resp = $this->MakeRequest($this->getRequest($sercretk,$url,$accessid));
			//print_r($this->MakeRequest($this->getRequest($sercretk,$url,$accessid)));
			//return View::make('mws.index');
			echo "<pre>";
			print_r($resp);
			echo "</pre>";
			// return "";
			$t = (array) $resp->ListOrdersResult;
			if(!empty($t) && !empty($t->Orders)){
				$token = (isset($t['NextToken']))? $t['NextToken']: null;
				$orders = (array) $t['Orders'];
				$orders = (isset($orders['Order'] ))? $orders['Order']:null;
				if(gettype($orders) == "object"){array_push($orderlist, $orders);}else{$orderlist = $orders;}
			}

			//*
			$orderitems = array();
			$allasin = array();
			$allasin = array();
			$asinlist = array();
			foreach (array_chunk($orderlist, 10) as $orderlistchunk) {
				foreach ($orderlistchunk as $order) {
					$url	= "https://mws.amazonservices.com/Orders/2013-09-01";
					$url .= "?Action=ListOrderItems";
					$url .= "&SellerId=".$sellerid;
					$url .= "&AmazonOrderId=".$order->AmazonOrderId;
					$url .= "&SignatureVersion=2";
					$url .= "&SignatureMethod=HmacSHA256";
					// echo $this->getRequest($sercretk,$url,$accessid);
					$xmlresp = $this->MakeRequest($this->getRequest($sercretk,$url,$accessid));
					$resp = json_decode(json_encode($xmlresp), true);
					$orderitems["".$order->AmazonOrderId] = $resp["ListOrderItemsResult"];

					// Getting Original Orders
					foreach( $resp["ListOrderItemsResult"]["OrderItems"] as $orderitem ){
						array_push($asinlist, $orderitem["ASIN"] );
					}

					// echo implode("&", $asinlist);
					// echo '<pre>';
					// print_r( $resp->ListOrderItemsResult );
					// $order->orderitems = array();
					// $json = json_encode($resp->ListOrderItemsResult );
					// echo '</pre>';
					// array_push($order->orderitems, $resp->ListOrderItemsResult);//$order->orderitems = json_decode($json, true);
					// break;
				}
				// echo "Next Ten";
			}//'ASINList.ASIN.'.++$loopcounter.'='.
			$uniqueasins = array_unique( $asinlist );
			foreach (array_chunk($uniqueasins, 10) as $asins) {
				$url	= "https://mws.amazonservices.com/Products/2011-10-01";
				$url .= "?Action=GetMatchingProduct";
				$url .= "&SellerId=".$sellerid;
				$url .= "&MarketplaceId=".$makrketid;
				$url .= "&SignatureVersion=2";
				$url .= "&SignatureMethod=HmacSHA256";
				for ($i=0; $i < count($asins); $i++) { 
					$url .= '&ASINList.ASIN.'.( $i+1 ).'='.$asins[$i];
				}
				// echo '<pre>';
				$xmlresp = $this->MakeRequest( $this->getRequest($sercretk,$url,$accessid, "2011-10-01" ) );
				foreach ($xmlresp as $matcheditem) {
					// print_r($matcheditem->attributes());
					$currentasin = "";
					foreach ($matcheditem->attributes() as $key => $value) {
						if($key == "ASIN"){
							$currentasin = $value;
						}
					}
					// echo $currentasin."<br>";
					if( isset( $matcheditem->Product ) ){
						$products["".$currentasin] = array( "image"	=> preg_replace("/._(.*)?_./", ".", $matcheditem->Product->AttributeSets->children('ns2', true)->ItemAttributes->SmallImage->URL)
											,"smallimage" => $matcheditem->Product->AttributeSets->children('ns2', true)->ItemAttributes->SmallImage->URL
											,"height" => (string) $matcheditem->Product->AttributeSets->children('ns2', true)->ItemAttributes->PackageDimensions->Height
											,"width"	=> (string) $matcheditem->Product->AttributeSets->children('ns2', true)->ItemAttributes->PackageDimensions->Width
											,"length" => (string) $matcheditem->Product->AttributeSets->children('ns2', true)->ItemAttributes->PackageDimensions->Length
											,"weight" => (string) $matcheditem->Product->AttributeSets->children('ns2', true)->ItemAttributes->PackageDimensions->Weight);
					}
				}
				// echo '</pre>';
			}
			// $url .= "&".implode("&", $asinlist);
			// echo $url;

				// print_r( $orderitems );
			// print_r($orderlist);
		}
		//print_r($orderlist);
		return View::make('mws.index')
			->with('orders',$orderlist)
			->with('token',$token)
			->with('orderitems',$orderitems)
			->with('products',$products)
			->withInput(Input::all());
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
		$maporder = Input::get('maporder');
		$boxc = Excel::create( 'Boxc'.str_random(6), function($excel) {
			$excel->sheet('Boxc', function($sheet) {
					$sheet->fromArray(Input::get('order'));
			});			
		})->store('csv', public_path().'/excel', true);

		$pfc = Excel::create( 'pfc'.str_random(6), function($excel) {
			$excel->sheet('pfc', function($sheet) {
					$sheet->fromArray(Input::get('pfc'));
			});			
		})->store('xls', public_path().'/excel', true);		

		$shipmentconfirm = Excel::create( 'shipmentconfirmation'.str_random(6), function($excel) {
			$excel->sheet('shipmentconfirmation', function($sheet) {
					$sheet->fromArray(Input::get('shipmentconfirmation'));
			});			
		})->store('xls', public_path().'/excel', true);
		
		$headers = array(
			"Content-type"=>"text/html",
			"Content-Disposition"=>"attachment;Filename=myfile.doc"
		);
		$content = '<html><head><meta charset="utf-8"></head><body style="font-size:10px;margin:0px;padding:0px;">';
		$content .= 'BOXC: '.url('excel/'.$boxc['file'], $parameters = array(), $secure = null);
		$content .= '<p></p>';
		$content .= 'PFC: '.url('excel/'.$pfc['file'], $parameters = array(), $secure = null);
		$content .= '<p></p>';
		$content .= 'MWS: '.url('excel/'.$shipmentconfirm['file'], $parameters = array(), $secure = null);
		$content .= '<p></p>';
		$content .= "<table><thead><tr>";
		$content .= "<td>Orderid</td><td>image</td><td>QTY</td><td>Name</td><td>PostalCode</td><td>State::City</td><td>Phone</td><td>Street1::Street2</td>";
		$content .= "</tr></thead><tbody>";
		foreach ($maporder as $order) {
			$content .= '<tr>';
			$content .= "<td>".$order["OrderID"]."</td>";
			$content .= "<td><img src=".$order["image"]."></td>";
			$content .= "<td>".$order["Items"]."</td>";
			$content .= "<td>".$order["Name"]."</td>";
			$content .= "<td>".$order["PostalCode"]."</td>";
			$content .= "<td>".$order["StateCity"]."</td>";
			$content .= "<td>".$order["Phone"]."</td>";
			$content .= "<td>".$order["Streets"]."</td>";
	 		$content .= '</tr>';
		}
		$content .= '</tbody></table>';
		$content .= '</body></html>';
/*		echo '<pre>';
		print_r(Input::all());
		echo '</pre>';*/
		return Response::make($content,200, $headers);
	}

	public function getWord(){
	$headers = array(
		"Content-type"=>"text/html",
		"Content-Disposition"=>"attachment;Filename=myfile.doc"
	);
	$content = '<html>
							<head>
							<meta charset="utf-8">
							</head>
							<body>
									<p>
											My Content
									</p>
									<img src="http://ecx.images-amazon.com/images/I/41yVzXO4bRL.jpg">
							</body>
							</html>';
	return Response::make($content,200, $headers);

	}
}

