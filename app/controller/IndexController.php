<?php

namespace app\controller;

use app\model\User;
use support\Request;
use support\Db;
use Webman\RedisQueue\Redis;
use Webman\RedisQueue\Client;

class IndexController
{
    public function index(Request $request)
    {

        $users= User::all();
        return json(["users"=>$users,"name"=>get_table_list()]);
    }

    public function view(Request $request)
    {
        return view('index/index', ['name' => 'webman']);
    }

    public function json(Request $request)
    {
        return json(['code' => 0, 'msg' => 'ok']);
    }


    public function queue(Request $request)
    {
        // 队列名
        $queue = 'send-mail';
        // 数据，可以直接传数组，无需序列化
        $data = ['to' => 'tom@gmail.com', 'content' => 'hello'];
        // 投递消息
        Redis::send($queue, $data);
        // 投递延迟消息，消息会在60秒后处理
        Redis::send($queue, $data, 60);

        return response('redis queue test');
    }


    public function async(Request $request)
    {
        // 队列名
        $queue = 'send-mail';
        // 数据，可以直接传数组，无需序列化
        $data = ['to' => 'tom@gmail.com', 'content' => 'hello'];
        // 投递消息
        Client::send($queue, $data);
        // 投递延迟消息，消息会在60秒后处理
        Client::send($queue, $data, 60);

        return response('redis queue test');
    }

}
