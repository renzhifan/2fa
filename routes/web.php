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
use ShaoZeMing\LaravelTranslate\Facade\Translate;
use Earnp\GoogleAuthenticator\GoogleAuthenticator;
//第1种
Route::get('/', function () {
//    $result = Translate::setDriver('youdao')->translate('你知道我对你不仅仅是喜欢');
//    print_r($result);die;
    if(!session('createSecret')){
        $createSecret = Google::CreateSecret();
        session(['secret' =>$createSecret['secret']]);
        session(['createSecret'=>$createSecret]);
    }
    $createSecret=session('createSecret');
    $parameter = [["name"=>"usename","value"=>"123"]];
    return view('login.google.google', ['createSecret' => $createSecret,"parameter" => $parameter]);
});
use Illuminate\Http\Request;
Route::any('/check',function (Request $request){
    if(Google::CheckCode(session('secret'),$request->onecode)) {
        // 绑定场景：绑定成功，向数据库插入google参数，跳转到登录界面让用户登录
        // 登录认证场景：认证成功，执行认证操作
        dd("认证成功");
    }
    else
    {
        // 绑定场景：认证失败，返回重新绑定，刷新新的二维码
        return back()->with('msg','请正确输入手机上google验证码 ！')->withInput();
        // 登录认证场景：认证失败，返回重新绑定，刷新新的二维码
        return back()->with('msg','验证码错误，请输入正确的验证码 ！')->withInput();
    }
});
