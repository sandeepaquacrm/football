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
            'phone_no' => 'numeric',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors = json_decode($errors);
            $errMsg = array();
            // echo '<pre>';
            // print_r($errors);
            // die;
            if(!empty($errors->email_id)){
              $errMsg[] = $errors->email_id[0];
            }
            if(!empty($errors->phone_no)){
              $errMsg[] = $errors->phone_no[0];
            }
            if(!empty($errors->username)){
              $errMsg[] = $errors->username[0];
            }

            $responseArray['message'] = implode('<br>',$errMsg);
            $responseArray['status'] = "201";
            $responseArray['detail'] = array();
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

    public function signup(Request $request){
      $this->common = new Common();

      $validator = Validator::make($request->all(), [
            'username' => 'required|unique:football_users|max:255',
            'password' => 'required|alpha_num|min:5|max:10',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors = json_decode($errors);
            $errMsg = array();
            // echo '<pre>';
            // print_r($errors);
            // die;
            if(!empty($errors->username)){
              $errMsg[] = $errors->username[0];
            }
            if(!empty($errors->password)){
              $errMsg[] = $errors->password[0];
            }


            $responseArray['message'] = implode('<br>',$errMsg);
            $responseArray['status'] = "201";
            $responseArray['detail'] = array();
        }else{
          $allInput = $request->input();
            $arrayPost = array('username'=>$allInput['username']);
            $query = DB::table('football_users')->select('*')->where($arrayPost)->first();
            if(!empty($query)){
              $responseArray['message'] = "Username already exists";
              $responseArray['status'] = "201";
              $responseArray['detail'] = array();
            }else{
              $insertField = array('name'=>'','phone_no'=>'0','username'=>$allInput['username'],'password'=>md5($allInput['password']),'email_id'=>'','salt'=>'','facebook_id'=>'','google_id'=>'','twitter_id'=>'','profile_image'=>'','gender'=>'','token'=>'','platform'=>'','added_on'=>date('Y-m-d H:i:s'),'last_updated'=>date('Y-m-d H:i:s'));

              $lastInsertId = DB::table('football_users')->insertGetId($insertField);
              $arrayPost = array('id'=>$lastInsertId);
            $fData =
             DB::table('football_users')->select('*')->where($arrayPost)->first();
              $responseArray['message'] = "Done";
              $responseArray['status'] = "200";
              $responseArray['detail'] = $fData;
            }

        }
        echo json_encode($responseArray);
        exit();
    }

    public function avatar_change(Request $request){
      $this->common = new Common();

      $validator = Validator::make($request->all(), [
        'photo' => 'required|file|image|mimes:jpeg,png,gif,webp,jpg|max:2048',
        'userid' => 'required|numeric'
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors();
            $errors = json_decode($errors);
            $errMsg = array();

            if(!empty($errors->photo)){
              $errMsg[] = $errors->photo[0];
            }
            if(!empty($errors->userid)){
              $errMsg[] = $errors->userid[0];
            }

            $responseArray['message'] = implode('<br>',$errMsg);
            $responseArray['status'] = "201";
            $responseArray['detail'] = array();
        }else{
            $allInput = $request->input();
            $allInputFile = $request->file('photo');
            $extension = $allInputFile->getClientOriginalExtension();
            $newName = '_AVATAR_'.time().'_'.time().'.'.$extension;
            $destinationPath = 'uploads/avatars';
            $paths = $allInputFile->move($destinationPath,$newName);

            $arrayPost = array('profile_image'=>$newName);
            $query = DB::table('football_users')->where('id',$allInput['userid'])->update($arrayPost);
            if(empty($query)){
              $responseArray['message'] = "Profile image not updated";
              $responseArray['status'] = "201";
              $responseArray['detail'] = array();
            }else{
              $responseArray['message'] = "profile updated successfully";
              $responseArray['status'] = "200";
              $responseArray['detail'] = array();
            }

        }
        echo json_encode($responseArray);
        exit();
    }

    public function language_change(Request $request){
      $this->common = new Common();

      $validator = Validator::make($request->all(), [
        'er_language' => 'required|max:10',
        'userid' => 'required|numeric'
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors();
            $errors = json_decode($errors);
            $errMsg = array();

            if(!empty($errors->er_language)){
              $errMsg[] = $errors->er_language[0];
            }
            if(!empty($errors->userid)){
              $errMsg[] = $errors->userid[0];
            }

            $responseArray['message'] = implode('<br>',$errMsg);
            $responseArray['status'] = "201";
            $responseArray['detail'] = array();
        }else{
            $allInput = $request->input();

            $arrayPost = array('cr_language'=>$allInput['er_language']);
            $query = DB::table('football_users')->where('id',$allInput['userid'])->update($arrayPost);
            if(empty($query)){
              $responseArray['message'] = "try again";
              $responseArray['status'] = "201";
              $responseArray['detail'] = array();
            }else{
              $responseArray['message'] = "Language updated successfully";
              $responseArray['status'] = "200";
              $responseArray['detail'] = array();
            }

        }
        echo json_encode($responseArray);
        exit();
    }

    public function get_language(){
      $this->common = new Common();
      $query = DB::table('football_language')->select('*')->get();

      $responseArray['message'] = "done";
      $responseArray['status'] = "200";
      $responseArray['detail'] = $query;

      echo json_encode($responseArray);
      exit();
    }

    public function get_user_detail(Request $request){
      $this->common = new Common();

      $validator = Validator::make($request->all(), [
            'userid' => 'numeric|max:255'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors = json_decode($errors);
            $errMsg = array();

            if(!empty($errors->userid)){
              $errMsg[] = $errors->userid[0];
            }

            $responseArray['message'] = implode('<br>',$errMsg);
            $responseArray['status'] = "201";
            $responseArray['detail'] = array();
        }else{
          $allInput = $request->input();
          $arrayPost = array('id'=>$allInput['userid']);
            $query = DB::table('football_users')->select('*')->where($arrayPost)->first();
            if(empty($query)){
              $responseArray['message'] = "user not found";
              $responseArray['status'] = "201";
              $responseArray['detail'] = array();
            }else{
              $query->profile_image = url("").'/uploads/avatars/'.$query->profile_image;

              $responseArray['message'] = "Done";
              $responseArray['status'] = "200";
              $responseArray['detail'] = $query;
            }

        }
        echo json_encode($responseArray);
        exit();
    }

    public function get_faq(Request $request){
      $this->common = new Common();

      $validator = Validator::make($request->all(), [
            'language' => 'string|max:20'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors = json_decode($errors);
            $errMsg = array();

            if(!empty($errors->language)){
              $errMsg[] = $errors->language[0];
            }

            $responseArray['message'] = implode('<br>',$errMsg);
            $responseArray['status'] = "201";
            $responseArray['detail'] = array();
        }else{
          $allInput = $request->input();
          $arrayPost = array('lang_localise'=>$allInput['language']);
            $query = DB::table('football_faqs')->select('*')->where($arrayPost)->first();
            if(empty($query)){
              $responseArray['message'] = "No FAQ found";
              $responseArray['status'] = "201";
              $responseArray['detail'] = array();
            }else{
                          $responseArray['message'] = "Done";
              $responseArray['status'] = "200";
              $responseArray['detail'] = $query;
            }

        }
        echo json_encode($responseArray);
        exit();
    }
}
