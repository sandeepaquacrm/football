<?php

namespace App\Model;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Common extends Model{

  public function find($tableName,$select=array(),$where=array()){
      $result = DB::table($tableName)
                ->select($select)
                ->where($where)
                ->get();
      return $result;
  }
}
