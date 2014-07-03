<?php

class Item extends Eloquent {
  protected $fillable = array('user_id','source_id','source','name','description');
	public static $rules = array(
		'source_id' => 'required'
	);
  public function user(){
    return $this->belongsTo('User');
  }
}
