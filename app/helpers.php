<?php

/*
 * @Author     : lixiaoyun
 * @Email      : 120235331@qq.com
 * @Github     : http://www.github.com/siaoynli
 * @Date       : 2023/08/10 14:04
 * @Description:
 * @Copyright (c) 2023 http://www.codedonuts.com All rights reserved.
 */

declare(strict_types=1);

use support\Db;

function ping()
{
    return 'pong';
}


/**
 * @Author: lixiaoyun
 * @Email: 120235331@qq.com
 * @Date: 2023/4/10 15:01
 * @Description: 计算到凌晨时间戳
 * @return int
 */
function get_timestamp_until_midnight(): int
{
    $midnight = strtotime('tomorrow');
    $now = time();
    return $midnight - $now;
}

/**
 * @Author: lixiaoyun
 * @Email: 120235331@qq.com
 * @Date: 2023/8/23 9:40
 * @Description: 获取转义编码后的值
 * @param $value
 * @return string
 */
function escape($value): string
{
    if (is_array($value)) {
        $value = json_encode($value, JSON_UNESCAPED_UNICODE);
    }
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
}


/**
 * @Author: lixiaoyun
 * @Email: 120235331@qq.com
 * @Date: 2023/4/10 15:02
 * @Description: 富文本编辑器图片地址添加域名
 * @param string $text
 * @param string $domain
 * @return string
 */
function add_domain_to_image_urls(string $text, string $domain): string
{
    $pattern = '/(<img[^>]*src=["\'])(?!http)([^"\'>]+)(["\'][^>]*>)/i';
    $replacement = '$1' . $domain . '$2$3';
    return preg_replace($pattern, $replacement, $text);
}


/**
 * @Author: lixiaoyun
 * @Email: 120235331@qq.com
 * @Date: 2023/8/23 9:32
 * @Description: 获取客户端IP
 * @return string
 */
function get_client_ip(): string
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    }
    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '';
}

/**
 * @Author: lixiaoyun
 * @Email: 120235331@qq.com
 * @Date: 2023/8/23 9:32
 * @Description: 获取目录下所有文件
 * @param string $dir
 * @param string $extension
 * @return array
 */
function get_dir_files(string $dir, string $extension = ""): array
{
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS | FilesystemIterator::FOLLOW_SYMLINKS),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    $fileList = [];
    foreach ($files as $file) {
        if (!$file->isFile()) {
            continue;
        }
        if ($extension && $file->getExtension() !== $extension) {
            continue;
        }
        $filePath = $file->getRealPath();
        $name = str_replace([$dir, DIRECTORY_SEPARATOR], ['', '/'], $filePath);
        $fileList[$name] = $name;
    }
    return $fileList;
}

/**
 * @Author: lixiaoyun
 * @Email: 120235331@qq.com
 * @Date: 2023/8/23 9:37
 * @Description: 删除目录
 * @param string $directory
 * @param bool $rmdir
 * @return void
 */
function delete_directory(string $directory, bool $rmdir = false): void
{
    if (!is_dir($directory)) {
        return;
    }
    $files = array_diff(scandir($directory), ['.', '..']);
    foreach ($files as $file) {
        $path = $directory . '/' . $file;
        if (is_dir($path)) {
            delete_directory($path, $rmdir);
        } else {
            @unlink($path);
        }
    }
    if ($rmdir) {
        @rmdir($directory);
    }
}


/**
 * @Author: lixiaoyun
 * @Email: 120235331@qq.com
 * @Date: 2023/8/23 9:33
 * @Description:获取所有表
 * @return array
 */
function get_table_list(): array
{
    $tableList = [];
    $database = config('database.connections.mysql.database');
    $tables = DB::select("SELECT TABLE_NAME,TABLE_COMMENT FROM information_schema.TABLES WHERE table_schema = ? ", [$database]);
    foreach ($tables as $row) {
        $tableList[$row->TABLE_NAME] = $row->TABLE_NAME . ($row->TABLE_COMMENT ? ' - ' . str_replace('表', '', $row->TABLE_COMMENT) : '');
    }
    return $tableList;
}

/**
 * @Author: lixiaoyun
 * @Email: 120235331@qq.com
 * @Date: 2023/8/23 9:34
 * @Description: 获取表字段
 * @param string $table
 * @param bool $onlyCleanComment
 * @return array
 */
function get_table_fields(string $table, bool $onlyCleanComment = false): array
{
    if (!$table) return [];

    $dbname = config('database.connections.mysql.database');
    $prefix = config('database.connections.mysql.prefix');

    // 从数据库中获取表字段信息
    $sql = "SELECT * FROM `information_schema`.`columns` "
        . "WHERE TABLE_SCHEMA = ? AND table_name = ? "
        . "ORDER BY ORDINAL_POSITION";
    $columnList = DB::select($sql, [$dbname, $table]);
    if (!$columnList) {
        $columnList = DB::select($sql, [$dbname, $prefix . $table]);
    }

    $fieldList = [];
    foreach ($columnList as $item) {
        if ($onlyCleanComment) {
            $fieldList[$item->COLUMN_NAME] = '';
            if ($item->COLUMN_COMMENT) {
                $comment = explode(':', $item->COLUMN_COMMENT);
                $fieldList[$item->COLUMN_NAME] = $comment[0];
            }
            continue;
        }
        $fieldList[$item->COLUMN_NAME] = $item;
    }
    return $fieldList;
}

/**
 * @Author: lixiaoyun
 * @Email: 120235331@qq.com
 * @Date: 2023/8/23 9:34
 * @Description: 加密解密
 * @param string $string
 * @param string $operation
 * @param string $key
 * @param int $expiry
 * @param int $key_length
 * @return string
 */
function sys_auth(string $string, string $operation = 'ENCODE', string $key = '', int $expiry = 0, int $key_length = 4): string
{
    $string = trim($string);
    if ($operation == 'ENCODE') {
        $string = urlencode($string);
    }
    $key = md5($key ?: env('APP_KEY'));
    $fixed_key = md5($key);
    $aegis_keys = md5(substr($fixed_key, 16, 16));
    $unto_key = $key_length ? ($operation == 'ENCODE' ? substr(md5((string)microtime(true)), -$key_length) : substr($string, 0, $key_length)) : '';
    $keys = md5(substr($unto_key, 0, 16) . substr($fixed_key, 0, 16) . substr($unto_key, 16) . substr($fixed_key, 16));
    $string = $operation == 'ENCODE' ? sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $aegis_keys), 0, 16) . $string : base64_decode(substr($string, $key_length));
    $result = '';
    $string_length = strlen($string);
    for ($i = 0; $i < $string_length; $i++) {
        $result .= chr(ord($string[$i]) ^ ord($keys[$i % 32]));
    }
    if ($operation == 'ENCODE') {
        return $unto_key . base64_encode($result);
    }
    if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $aegis_keys), 0, 16)) {
        return urldecode(substr($result, 26));
    }
    return '';
}