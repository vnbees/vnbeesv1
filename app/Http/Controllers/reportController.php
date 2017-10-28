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
		$dateFrom = $request->input('dateFrom');
		$dateTo = $request->input('dateTo');
		if($url != null){
			$respose = DB::select("
				SELECT max(id) as id,SUBSTRING_INDEX(url, '/', 3) as domain, count(url) as traffic, updated_at,url FROM `tracking` WHERE 
						(SUBSTRING_INDEX(url, '/', 3) = '".$url."') AND
						(DATE_FORMAT(`updated_at`,'%m/%d/%Y') BETWEEN '".$dateFrom."' AND '".$dateTo."')
					GROUP BY url ORDER BY id DESC
			");
			// BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() 10/28/2017 
			foreach ($respose as $key => $value) {
				$respose[$key]->userCount = count(DB::select("SELECT id,updated_at,url FROM tracking WHERE (DATE_FORMAT(`updated_at`,'%m/%d/%Y') BETWEEN '".$dateFrom."' AND '".$dateTo."') AND (url = '".$value->url."') GROUP BY userId"));
				// DB::select("
						// SELECT id,updated_at,url FROM tracking WHERE
							// (DATE(`updated_at`) = CURDATE()) AND
							// (url = ".$value->url.")
						// GROUP BY userId
					// ")
				// Tracking::where('url',$value->url)->groupBy('userId')->get()
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
