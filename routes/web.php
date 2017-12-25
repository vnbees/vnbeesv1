<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
// 	return "Hello Word";
//     // return view('welcome');
// });
// AdvancedRoute::controller('/', ['middleware' => 'auth', 'uses' => 'IndexController']);

AdvancedRoute::controller('/', 'IndexController');
AdvancedRoute::controller('/request', 'requestController');
AdvancedRoute::controller('/report', 'reportController');

// Route::get('profile', ['middleware' => 'auth.basic', function()
// {
//     return "test";
// }]);

Route::get('login',function(){
	return view('home.login');
});

Route::post('do-login',function(\Illuminate\Http\Request $request){
	
	if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')]))
	{
	       return redirect()->intended('/');
	}else{
		return redirect()->back();
	} 
});

Route::get('logout',function(){
	Auth::logout();
	return redirect()->intended('login');
});