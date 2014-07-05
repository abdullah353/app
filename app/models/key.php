<?php
class Key extends Eloquent {
  protected $fillable = array("devname","appname","cert","gtoken","complvl","siteID","serverUrl","runame");


  public function user(){
    return $this->belongsTo('User');
  }
}
