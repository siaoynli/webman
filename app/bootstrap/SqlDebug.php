<?php

namespace app\bootstrap;

use Illuminate\Database\Events\QueryExecuted;
use support\Db;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Webman\Bootstrap;

/**
 * 在控制台打印执行的SQL语句
 */
class SqlDebug implements Bootstrap
{
    /**
     * 自定义输出格式，否则输出前面会带有当前文件，无用信息
     * @param $var
     * @return void
     */
    public static function dumpvar($var)
    {
        $cloner = new VarCloner();
        $dumper = new CliDumper();
        $output = fopen('php://memory', 'r+b');
        $dumper->dump($cloner->cloneVar($var), $output);
        $output = stream_get_contents($output, -1, 0);
        echo $output;
    }

    public static function start($worker)
    {
        $is_console = !$worker;
        if ($is_console) {
            return;
        }

        Db::connection()->listen(function (QueryExecuted $queryExecuted) {
            if (isset($queryExecuted->sql) and $queryExecuted->sql !== "select 1") {
                $bindings = $queryExecuted->bindings;
                $sql = $queryExecuted->connection->getQueryGrammar()->substituteBindingsIntoRawSql(
                    $queryExecuted->sql,
                    $queryExecuted->connection->prepareBindings($bindings)
                );
                self::dumpvar("[sql] [time:{$queryExecuted->time} ms] [{$sql}]");
            }
        });
    }

}