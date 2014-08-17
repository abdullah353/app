<?php

class UsersController extends BaseController {

	//protected $layout = "login.main";

	public function __construct() {
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->beforeFilter('auth', array('only'=>array('getDashboard')));
	}

	public function getRegister() {
		return View::make('users.register');
	}

	public function postCreate() {
		$validator = Validator::make(Input::all(), User::$rules);

		if ($validator->passes()) {
			$user = new User;
			$user->firstname = Input::get('firstname');
			$user->lastname = Input::get('lastname');
			$user->email = Input::get('email');
			$user->password = Hash::make(Input::get('password'));
			$user->save();

			return Redirect::to('users/login')->with('message', 'Thanks for registering!');
		} else {
			return Redirect::to('users/register')->with('message', 'The following errors occurred')->withErrors($validator)->withInput();
		}
	}

	public function getLogin() {
		return View::make('users.login');
	}

	public function postSignin() {
		if (Auth::attempt(array('email'=>Input::get('email'), 'password'=>Input::get('password')))) {
			return Redirect::to('users/dashboard')->with('message', 'You are now logged in!');
		} else {
			return Redirect::to('users/login')
				->with('message', 'Your username/password combination was incorrect')
				->withInput();
		}
	}

	public function getDashboard() {
		$items = array();
		$summary = $this->genSummary();

		$hasitems = false;
		if(Input::get('from') && Input::get('to')){
			$cal = new Ebay;
		 	$sessionIdXml = $cal->GetSellerList(Input::get('from'),Input::get('to'),20,1);
	    $sessionIdResponse = $cal->parseXml($sessionIdXml);
	    $respxml = simplexml_load_string($sessionIdXml);
	    if($respxml->ReturnedItemCountActual > 0){
	    	$tmpitems = (array) $respxml->ItemArray;
	    	//echo gettype($tmpitems["Item"]);
	    	if(gettype($tmpitems["Item"]) == "object") echo "single";
	    	$items = ( (gettype($tmpitems["Item"]) != "object") )?(array) $tmpitems["Item"]: $tmpitems;
	    	$hasitems = true;
	    }else{
	    	//$items = (array) $respxml->ItemArray;
	    	echo "eeeeeeeeeeeFNOONASD";
	    }
	    //print_r($itemsxml);
	    //echo json_encode($items);
	   	//echo json_encode($respxml);
	  }
		return View::make('users.dashboard')->with('items',$items)->with('hasitems',$hasitems)
				->with('totalOrders',$summary['totalorders'])->with('completed',$summary['completed'])
				->with('incomplete',$summary['incomplete'])->with('series',json_encode($summary['series']));

	}

	public function getLogout() {
		Auth::logout();
		return Redirect::to('users/login')->with('message', 'Your are now logged out!');
	}
	public function genSummary(){
		$summary = array();
		$cal = new Ebay;
	 	$sessionIdXml = $cal->GetOrders("All");
    $sessionIdResponse = $cal->parseXml($sessionIdXml);
    $respxml = simplexml_load_string($sessionIdXml);
		//Getting Total Orders

		//Getting Completed And Incompleted Orders
		$ordlists = (array) $respxml->OrderArray;
		$ordlist = $ordlists['Order'];
		$ordcomp = 0;
		$ordincomp = 0;
		$itemsids = array();
		foreach ($ordlist as $order) {
			//print_r($order->OrderID);
			if($order->OrderStatus == "Completed"){
				$ordcomp++;
			}else if($order->OrderStatus == "Active"){
				$ordincomp++;
			}
			$tmporders = (array) $order->TransactionArray;
			$tmporder =  $tmporders['Transaction'];
			//$tmp = (array) $tmporder['0'];
			if(gettype($tmporder) == 'array'){
				foreach ($tmporder as $tmplist) {
					array_push($itemsids, (int) $tmplist->Item->ItemID);
				}
			}else{
				//echo $tmporder->Item->ItemID;	
				array_push($itemsids, (int) $tmporder->Item->ItemID);
			}


		}
		//print_r($itemsids);
		//print_r(array_count_values ( $itemsids ));
		//print_r($summary);
		$series = array();
		foreach (array_count_values ($itemsids) as $key => $value) {
			array_push($series, array('name'=>$key,'data'=>json_decode("[".$value."]")));
		}

		$summary['totalorders'] = (int) $respxml->ReturnedOrderCountActual;
		$summary['completed'] = $ordcomp;
		$summary['incomplete'] = $ordincomp;
		$summary['series'] = $series;
		return $summary;
	}
}