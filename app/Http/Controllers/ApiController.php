<?php

namespace App\Http\Controllers;

use Validator;
use DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Model\Common;

class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function login(Request $request){
      $this->common = new Common();

      $validator = Validator::make($request->all(), [
            'username' => 'alpha_num|max:255',
            'email_id' => 'email|max:255',
            'phone_no' => 'numeric|max:255',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors = json_decode($errors);
            if(!empty($errors->email_id)){
              $responseArray['message'] = $errors->email_id[0];
              $responseArray['status'] = "201";
              $responseArray['detail'] = array();
            }


            echo '<pre>';
            print_r($errors);
            die;
        }else{
          $allInput = $request->input();
          $type = $allInput['type'];
          if($type == 'facebook'){

          }else if($type == 'google'){

          }else if($type == 'twitter'){

          }else{
            $arrayPost = array('email_id'=>$allInput['email_id'],'password'=>md5($allInput['password']));
            $query = DB::table('football_users')->select('*')->where($arrayPost)->first();
            if(empty($query)){
              $responseArray['message'] = "Invalid Credentail";
              $responseArray['status'] = "201";
              $responseArray['detail'] = array();
            }else{
              $responseArray['message'] = "Done";
              $responseArray['status'] = "200";
              $responseArray['detail'] = $query;
            }
          }
        }
        echo json_encode($responseArray);
        exit();
    }
}
