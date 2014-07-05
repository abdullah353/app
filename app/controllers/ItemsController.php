<?php


class ItemsController extends BaseController {
	public function __construct() {
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->beforeFilter('auth');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		
		return Item::all()->toJson();
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		/*
		$cal = new Ebay;
	 	$sessionIdXml = $cal->GetUser();
    $sessionIdResponse = $cal->parseXml($sessionIdXml);
    $xml = simplexml_load_string($sessionIdXml);
		$json = json_encode($xml);
		echo $json;
   	//$obj = json_decode($json);
   	*/
   	
		
		
    //var_dump($xml);

    //print_r((array) $xml->ItemArray);
		//$json = json_encode($xml);
		//echo $json;
   	//$items = json_decode($json);
   	

		return View::make('items.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
	
		//$validator = Validator::make(Input::all(), Item::$rules);
		
		var_dump(Input::all());
    if(true)
    {
    	$sourceid = Input::get('sourceid');
    	$source = Input::get('source');
    	$name = Input::get('name');
    	$desc = Input::get('description');

      $item = new Item(array(
				'source_id'=> $sourceid,
				'source'=> $source,
				'name'=> $name,
				'description'=> $desc,
			));

			$user = User::find(Auth::user()->id);
			$item->user()->associate($user);

			$item->save();
			return Redirect::to('items');
    }
    return 'failed';

	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}




