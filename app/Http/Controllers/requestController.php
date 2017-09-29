<?php

namespace App\Http\Controllers;

use App\Tracking;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class requestController extends BaseController
{
	public function getIndex(){
		date_default_timezone_set("Asia/Ho_Chi_Minh");
		echo json_encode(date('m/d/Y H:i:s',Tracking::find(1)->created_at->timestamp));
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
}
