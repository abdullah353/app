<?php

class Item extends Eloquent {
  protected $fillable = array('user_id','source_id','source','name','description');
	public static $rules = array(
		'name' => 'required',
		'description' => 'required',
		'source' => 'required|max:20',
		'source_id' => 'required|Integer|unique:items',
		'image' => 'required_without:imageurl',
		'imageurl' => 'required_without:image',
	);
  public function user(){
    return $this->belongsTo('User');
  }
  public function images(){
    return $this->hasMany('Image');
  }
}
