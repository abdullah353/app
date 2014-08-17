<?php

class Image extends Eloquent {
  protected $fillable = array('item_id','origurl','copiurl','featurl');
  

  public function item(){
    return $this->belongsTo('Item');
  }
}
