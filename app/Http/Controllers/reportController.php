<?php

namespace App\Http\Controllers;

use App\Tracking;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class reportController extends BaseController
{
	public function getIndex(){
        return Tracking::all()->toJson();

	}
}
