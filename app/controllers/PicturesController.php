<?php

class PicturesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('pictures.index')->with('pictures',Picture::idDescending()->get()->toArray());
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('pictures.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		
/*
		if ($validator->passes()) {
			$pix = new Picture;
			$pix->name = Input::get('name');
			$pix->url = Input::get('url');
			$user->save();
			return Redirect::to('users/login')->with('message', 'Thanks for registering!');
		} else {
			return Redirect::to('users/register')->with('message', 'The following errors occurred')->withErrors($validator)->withInput();
		}
		*/

		foreach(Input::file('pictures') as $picture){
			
			$picturename = $picture->getClientOriginalName();
			$validator = \Validator::make(array('picture'=> $picture,'name' => $picturename), Picture::$rules);

			if($validator->passes()){
				//echo $picturename;
				$picture->move(public_path('upload'), $picturename);
				
				$pix = new Picture;
				$pix->name = $picturename;
				//URL::asset(public_path('upload').'/'.$picturename);
				$pix->url = URL::asset('upload/'.$picturename);
				$pix->save();
				return Redirect::to('pictures')->with('message', 'Thanks for registering!');
			}else{
				//Does not pass validation
				return Redirect::to('pictures/create')->with('message', 'The following errors occurred')->withErrors($validator);
			}

		}
		//echo Input::file('picture')->getRealPath();
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
