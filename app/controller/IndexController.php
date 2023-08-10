<?php

namespace app\controller;

use support\Request;
use support\Db;

class IndexController
{
    public function index(Request $request)
    {

        $users = Db::table('users')->get();
        return json(["users"=>$users,"name"=>"webman"]);
    }

    public function view(Request $request)
    {
        return view('index/index', ['name' => 'webman']);
    }

    public function json(Request $request)
    {
        return json(['code' => 0, 'msg' => 'ok']);
    }

}
