<?php

class Picture extends Eloquent {
  protected $fillable = array('url','name');
  
	public static $rules = array(
		'picture'=>'required|mimes:png,gif,jpeg|max:20000',
		'name' =>'required|unique:pictures'
	);
	public function scopeIdDescending($query)
    {
        return $query->orderBy('id','DESC');
    }  
}
