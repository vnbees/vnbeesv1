<?php

namespace App\Http\Controllers;

use DB;
use App\Tracking;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Users;

class reportController extends BaseController
{
	public function getIndex(Request $request){
		$url = $this->getUrlById($request->input('id'));
		if($url != null){
			$respose = DB::select("
				SELECT *, SUBSTRING_INDEX(url, '/', 3) as domain, count(url) as traffic FROM tracking WHERE SUBSTRING_INDEX(url, '/', 3) = '".$url."' GROUP BY url ORDER BY id DESC
			");
			// (SELECT *, SUBSTRING_INDEX(url, '/', 3) AS domain FROM tracking WHERE SUBSTRING_INDEX(url, '/', 3) = 'http://blog.vnbees.com' GROUP BY url ORDER BY id DESC)
			// echo json_encode( $respose );die;
			foreach ($respose as $key => $value) {
				$respose[$key]->userCount = count(Tracking::where('url',$value->url)->groupBy('userId')->get());
				$respose[$key]->lastActive = \Carbon\Carbon::parse(Tracking::where('url',$value->url)->orderBy('id','DESC')->first()->created_at)->format('h:i:s d-m-Y');
			}
			return json_encode($respose);
		}else{
			return json_encode([]);
		}
        // return Tracking::all()->sortByDesc('created_at')->groupBy(function($date) {
        //         return \Carbon\Carbon::parse($date->created_at)->format('d-M-y');
        //     })->toJson();
	}


	public function getUrlById($id){
		$user = Users::find($id);
		if( $user != null){
			return $user->website;
		}else{
			return null;
		}
	}

	public function getTest(){
		$test = [];
		$respose = DB::select('SELECT *, DATE_FORMAT(created_at, "%Y-%m-%d") as created_at_format FROM tracking GROUP BY url');
		// foreach ($respose as $key => $value) {
		// 	$respose[$key]->userCount = Tracking::groupBy('userId')->where('url',$value->url)->count();
		// }
		return $respose;
	}

	public function getBitcoinPrice(){
		// $request->input('min'); // mua
		// $request->input('max'); // ban
		echo "YUB";
	}


	public function getSetPrice(){
		
	}
}
