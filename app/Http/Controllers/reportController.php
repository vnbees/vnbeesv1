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
	public function createDateRangeArray($strDateFrom,$strDateTo){
	    // takes two dates formatted as YYYY-MM-DD and creates an
	    // inclusive array of the dates between the from and to dates.

	    // could test validity of dates here but I'm already doing
	    // that in the main script

	    $aryRange=array();

	    $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
	    $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

	    if ($iDateTo>=$iDateFrom)
	    {
	        array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
	        while ($iDateFrom<$iDateTo)
	        {
	            $iDateFrom+=86400; // add 24 hours
	            array_push($aryRange,date('Y-m-d',$iDateFrom));
	        }
	    }
	    return $aryRange; //print_r(createDateRangeArray('2017-12-20','2018-01-20'));
	}

	public function chartReport($url,$dateFrom,$dateTo){

		// CẦN REPORT MẤY NGUỒN
		// - DIRECT LÀ NULL
		// - UTM THEO URL ?UTM_SOURCE
		// - SEARCH LÀ THEO URL ?Q

		$query = "SELECT
					id,
					SUBSTRING_INDEX(url, '/', 3) as domain, 
					updated_at,
					source,
					url FROM `tracking` WHERE 
						(SUBSTRING_INDEX(url, '/', 3) = '".$url."') AND
						(source LIKE '%?q%') AND
						(DATE_FORMAT(`updated_at`,'%m/%d/%Y') BETWEEN '".$dateFrom."' AND '".$dateTo."')
				";
		return DB::select($query);
	}

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
				$respose[$key]->source =  DB::select("SELECT source FROM tracking WHERE (DATE_FORMAT(`updated_at`,'%m/%d/%Y') BETWEEN '".$dateFrom."' AND '".$dateTo."') AND (url = '".$value->url."') GROUP BY source") ;
				// DB::select("
						// SELECT id,updated_at,url FROM tracking WHERE
							// (DATE(`updated_at`) = CURDATE()) AND
							// (url = ".$value->url.")
						// GROUP BY userId
					// ")
				// Tracking::where('url',$value->url)->groupBy('userId')->get()
			}
			// $this->chartSource($respose);
			// return json_encode( $respose );
			return json_encode( $this->chartReport($url,$dateFrom,$dateTo) );
			// return json_encode($this->DirectSource($respose,$dateFrom,$dateTo));
		}else{
			return json_encode([]);
		}
        // return Tracking::all()->sortByDesc('created_at')->groupBy(function($date) {
        //         return \Carbon\Carbon::parse($date->created_at)->format('d-M-y');
        //     })->toJson();
	}

	public function chartSource($data){
		// 
	}

	public function filter($data){

		$sum = 0;
		$utm_source = [];
		foreach($data as $k => $item){
			$data[$k]->percent = null;
			$data[$k]->utm_source = null;
			$result = $this->detectUTM($item->url);
			if(isset( $result['utm_source'] )){
				$data[$k]->utm_source = $result['utm_source'];
			}
			$sum = $sum + $item->traffic;
		}

		foreach($data as $k => $item){
			// (số đó x 100) / tổng
			if(!empty($item->utm_source)){
				$data[$k]->percent = round( ($item->traffic * 100) / $sum ,2);
			}
		}
		// return array_filter($utm_source);
		return $data;
	}


	public function DirectSource($data,$dateFrom,$dateTo){
		
		$traffic = 0;
		foreach ($data as $key => $value) {

			foreach ($value->source as $k => $v) {
			 	if($v->source == null){
			 		$traffic += DB::select("SELECT count(url) as traffic FROM `tracking` WHERE url = '".$value->url."' AND (DATE_FORMAT(`updated_at`,'%m/%d/%Y') BETWEEN '".$dateFrom."' AND '".$dateTo."') AND (source IS NULL)")[0]->traffic;
			 	} // direct
			 	break;
			 } 
		}
		return $traffic;
	}

	public function SearchSource(){
		// 
	}

	public function detectUTM($url){

		// http://lamme.blog/be-hoc-toan-cung-finger-math?utm_source=adwords
		$query_str = parse_url($url, PHP_URL_QUERY);
		parse_str($query_str, $query_params);
		// print_r($query_params);
		return $query_params;
	}

	public function getUsersByUrl(Request $request){
		$dateFrom = $request->input('dateFrom');
		$dateTo = $request->input('dateTo');
		$url = $request->input('url');
		$response = DB::select("SELECT userId FROM tracking WHERE (DATE_FORMAT(`updated_at`,'%m/%d/%Y') BETWEEN '".$dateFrom."' AND '".$dateTo."') AND url = '".$url."' GROUP BY userId");
		foreach ($response as $k => $v) {
			$userId = $v->userId;
			$response[$k]->visit = count( DB::select("SELECT id FROM tracking WHERE (DATE_FORMAT(`updated_at`,'%m/%d/%Y') BETWEEN '".$dateFrom."' AND '".$dateTo."') AND url = '".$url."' AND userId = '".$userId."'"));
		}
		$pTag = '';
		foreach ($response as $key => $value) {
			$pTag .= '<div style="margin-bottom:20px;" class="col-md-3 text-center">- <a href="#" class="label bg-blue">'.$value->userId.'</a> đã truy cập <span class="label bg-blue">'.$value->visit.'</span> lần</div>';
		}
		$htmlRes = '<div class="modal modal-info" id="modal-user-visit">
					  <div class="modal-dialog">
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					        <h4 class="modal-title">Users đã truy cập '.$url.'</h4>
					      </div>
					      <div class="modal-body">
					      	<div class="row">
					      		'.$pTag.'
					      	</div>
					      </div>
					      <div class="modal-footer">
					        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Đóng</button>
					      </div>
					    </div><!-- /.modal-content -->
					  </div><!-- /.modal-dialog -->
					</div><!-- /.modal -->';

		return $htmlRes;
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
