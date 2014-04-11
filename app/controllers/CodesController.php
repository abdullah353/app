<?php

class CodesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('code.index')
			->with('codes',Upckey::all()->sortBy('created_at'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('code.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		/**
		Validate for qty and number fields later
		*/

		$qty = Input::get('qty');
		$number = Input::get('number');
		if($qty){
			$newCodes = array();
			for ($i=0; $i < $qty; $i++) { 
				$upckey = new Upckey();
				$upckey->code = $this->upca();
				$upckey->save();
				array_push($newCodes, array('key'=>$upckey->code) );
			}
			
			//Excel::create('Codes')->sheet('SheetName')->with($newCodes)->export('csv');
			$file = Excel::create('Codes-'.date("Ymdhis"))
        ->sheet('codes')->with($newCodes)
        ->store('xls', public_path().'/excel',true);
			/**
			recentExcell make session correctly
			*/
			return Redirect::action('CodesController@index')
				->with('recentexcel',$file);
		}elseif ($number) {
			
			//return Redirect::('codes');
		}

	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return Upckey::findorfail($id);
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

	/**

	MOVE TO GLOBAL FUNCTION

	*/



	/*
	 * Generate empty products field.
	 *
	 * @return Product array
	 */
  protected function upca(){
    do{
	    $sum = 0;
	    $code = '0';
	    for ($i = 1; $i < 11; $i++){
	      $num = mt_rand(1, 9);
	      $code.= $num;
	      $sum+= ($i % 2 == 0) ? $num   * 3: $num;
	    }
	    $code.= (10 - ($sum % 10)) < 10 ? (10 - ($sum % 10)) : 0 ;

	  }while(Upckey::where("code","=",$code)->first());

    return $code;
  }

}
