<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use DB;
use Auth;

class IndexController extends Controller
{
    /**
     * Tiêu đề function.
     *
     * @return Mô tả tính năng
     */
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            if(!Auth::user()){
                return redirect()->intended('login');
            }else{
                return $next($request);
            }
        });
    }

    public function getIndex()
    {
        return view('home.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getStore(Request $request){

        // GET REQUEST
        $url = $this->getUrlById($request->input('id'));
        $dateFrom = $request->input('dateFrom');
        $dateTo = $request->input('dateTo');


        // GET ALL DAY BETWEN 2 DAY (is array)
        $allDayBet = $this->createDateRangeArray(\Carbon\Carbon::createFromFormat('m/d/Y', $dateFrom)->format('Y-m-d'),
            \Carbon\Carbon::createFromFormat('m/d/Y', $dateTo)->format('Y-m-d'));


        // QUERY
        return json_encode( $this->charReport($allDayBet,$url) );        
    }
    
     /**
     * Tiêu đề function.
     *
     * @return Mô tả tính năng
     */

    public function charReport($allDayBet,$url){

        $char = [];
        foreach ($allDayBet as $date) {
            // UTM_SOURCE
            $query = "SELECT
                        id,
                        SUBSTRING_INDEX(url, '/', 3) as domain, 
                        updated_at,
                        source,
                        url FROM `tracking` WHERE 
                            (SUBSTRING_INDEX(url, '/', 3) = '".$url."') AND
                            (url LIKE '%?utm_source%') AND
                        (DATE_FORMAT(`updated_at`,'%Y-%m-%d') BETWEEN '".$date."' AND '".$date."')
                    ";
            $item1 = count(DB::select($query));

            // DIRECT NULL NOT LIKE '%test%'
            $query = "SELECT
                        id,
                        SUBSTRING_INDEX(url, '/', 3) as domain, 
                        updated_at,
                        source,
                        url FROM `tracking` WHERE 
                            (SUBSTRING_INDEX(url, '/', 3) = '".$url."') AND
                            (source IS NULL) AND
                            (url NOT LIKE '%?utm_source%') AND
                        (DATE_FORMAT(`updated_at`,'%Y-%m-%d') BETWEEN '".$date."' AND '".$date."')
                    ";
            $item2 = count(DB::select($query));

            // ORGANIC_SEARCH
            $query = "SELECT
                        id,
                        SUBSTRING_INDEX(url, '/', 3) as domain, 
                        updated_at,
                        source,
                        url FROM `tracking` WHERE 
                            (SUBSTRING_INDEX(url, '/', 3) = '".$url."') AND
                            (source LIKE '%?q%') AND
                            (url NOT LIKE '%?utm_source%') AND
                        (DATE_FORMAT(`updated_at`,'%Y-%m-%d') BETWEEN '".$date."' AND '".$date."')
                    ";
            $item3 = count(DB::select($query));
            $char[] = (object) ['d' => $date, 'item1' => $item1, 'item2' => $item2, 'item3' => $item3];
            // {d: '2017-12-01', item1: 2666, item2: 2666}
        }
        return $char;
    }

     /**
     * Tiêu đề function.
     *
     * @return Mô tả tính năng
     */

    public function createDateRangeArray($strDateFrom,$strDateTo){
        // takes two dates formatted as YYYY-MM-DD and creates an
        // 11/01/2017&dateTo=11/30/2017
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

     /**
     * Tiêu đề function.
     *
     * @return Mô tả tính năng
     */

    public function getUrlById($id){

        $user = Users::find($id);

        if( $user != null){
            return $user->website;
        }else{
            return null;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
