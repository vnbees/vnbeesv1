<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class requestController extends BaseController
{
    public function getIndex(Request $request){
    	$url = $request->input('url');
    	$token = $request->input('token');
    	return "url: ".$url."<br/> token: ".$token;
    }
}
