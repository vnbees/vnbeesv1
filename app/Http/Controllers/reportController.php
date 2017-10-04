<?php

namespace App\Http\Controllers;

use DB;
use App\Tracking;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class reportController extends BaseController
{
	public function getIndex(){
        $respose = DB::select('SELECT COUNT(url) AS traffic,url,userId,created_at,updated_at,id FROM tracking GROUP BY url  ORDER BY `id` DESC');
        foreach ($respose as $key => $value) {
        	$respose[$key]->userCount = Tracking::where('url',$value->url)->groupBy('userId')->count();
        }
        return json_encode($respose);
        // return Tracking::all()->sortByDesc('created_at')->groupBy(function($date) {
        //         return \Carbon\Carbon::parse($date->created_at)->format('d-M-y');
        //     })->toJson();
	}

	public function getTest(){
		$respose = DB::select('SELECT COUNT(url) AS traffic,url,userId,created_at,updated_at,id FROM tracking GROUP BY url  ORDER BY `id` DESC');
		foreach ($respose as $key => $value) {
			$respose[$key]->userCount = Tracking::where('url',$value->url)->groupBy('userId')->count();
		}
		return json_encode($respose);
	}
}
