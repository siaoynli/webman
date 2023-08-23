<?php

/*
 * @Author     : lixiaoyun
 * @Email      : 120235331@qq.com
 * @Github     : http://www.github.com/siaoynli
 * @Date       : 2023/08/23 10:03
 * @Description:
 * @Copyright (c) 2023 http://www.codedonuts.com All rights reserved.
 */

declare(strict_types=1);

namespace app\command;

use app\model\User;
use Illuminate\Console\Command;


class SendEmails  extends Command
{

    protected $signature = 'mail:send {userId}';

    protected $description = 'Send a marketing email to a user';

    public function handle(): void
    {
       $this->info(User::find($this->argument('userId'))->name);
    }

}