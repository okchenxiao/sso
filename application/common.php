<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

//获取加密的唯一标识.
function unique_code()
{
    return trim(base64_encode(md5(str_replace('.','',uniqid('cookie',true)).rand(10000,99999))),'=');
}

/**
 * 统一的MD5加盐加密方法.
 * @param $code
 * @return bool|string
 */
function code_secret($code)
{
    if($code == '' || is_array($code) || is_object($code)){
        return false;
    }
    $code_salt = substr(md5($code),11,11);
    $secret = md5(md5($code).md5($code_salt));
    return $secret;
}

/**
 * 获取redis的key  并检测是否存在  最后返回不存在的key
 * @return string
 */
function get_redis_key()
{
    $unique_code = unique_code();
    $redis_isset = cache($unique_code);
    if($redis_isset != false){
        get_redis_key();
    }
    return $unique_code;
}