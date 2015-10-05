<?php namespace Ao\Data\Models;

class Staff extends \Eloquent{

	protected $table = "tprl_staff";

	protected $fillable = ["name", "description", "image", "thumbnail", "office", "pos"];

}
