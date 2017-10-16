<?php

namespace App\Http\Controllers;

use App\Tracking;
use App\Users;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class requestController extends BaseController
{
	public function getIndex(){
		date_default_timezone_set("Asia/Ho_Chi_Minh");
		echo json_encode(date('m/d/Y H:i:s',Tracking::find(1)->created_at->timestamp));
	}

    public function getUserLogin(Request $request){
        $count = Users::where('name',$request->input('name'))->count();
        if($count > 0){
            $user = Users::where('name',$request->input('name'))->first();
            if(Hash::check($request->input('password'), $user->password)){
                return $user->toJson();
            }else{
                return "Fail: Password not match";
            }
        }else{
            return "Fail : Username not match";
        }
    }

    public function getTracking(Request $request){
    	$tracking = new Tracking;
        $tracking->url = $request->input('url');
        $tracking->userId = $request->input('token');
        $tracking->save();

    	$url = $request->input('url');
    	$token = $request->input('token');
    	return "url: ".$url."<br/> token: ".$token;
    }


    public function getGenerateModal(){
        $response = (object) [];
        $response->body = '<!-- Modal content --> <div class="modal-content"> <div class="vnbees-modal-header"> <span class="vnbees-modal-close">&times;</span> <h4 style="_padding: 2px 16px !important;background-color: #5cb85c !important;color: white !important;clear: none !important;">VNBees Modal</h4> </div> <div class="modal-body"> <p>Hi, this is modal can custom for your lead marketing</p> <!-- <p>Some other text...</p> --> </div> <!-- <div class="modal-footer"> <h3>Modal Footer</h3> </div> --> </div>'; // nõi dung modal
        $response->status = false; // modal có show hay không
        $response->type = 1; // 1: scroll theo body, 2: hover tắt
        return json_encode($response);
    }
}
