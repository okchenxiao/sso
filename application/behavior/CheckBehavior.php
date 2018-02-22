<?php

/**
 * Created by Xiao.
 * Date: 2018/2/20
 * email: via_chen@126.com
 */
namespace app\behavior;

use think\Request;

class CheckBehavior
{
    public function appInit(&$params)
    {
        //获取 配置跳转地址 和 配置免登陆数组 数据
        $url_config = config('deal_login_url');
        $allows_path = array_merge(array_values($url_config),array_values(config('allows_path')));
        $request = Request::instance();//获取当前请求url
        $current_url = $request->url();
        if(in_array($current_url,$allows_path)){//免登陆检测
            return true;
        }
        //获取cookie
        $cookie_key = cookie('user_key');
        //是否有cookie登录信息
        if($cookie_key != false && isset($cookie_key['key']) && isset($cookie_key['remark'])){
            $redis_info = cache($cookie_key['key']);
            //检测是否登录且验证是否为唯一登录
            if($redis_info != false && is_array($redis_info) && isset($redis_info['rand_str']) && $redis_info['rand_str'] == $cookie_key['remark']){
                //检测是否有访问权限  todo 先检测是否为超级管理员权限
//                if(in_array($current_url,$allows_path)){
                    return true;
//                }else{
//                    echo '<script>alert("抱歉!您暂无访问权限!");location.href = "'.$url_config['default_index'].'"</script>';
//                    die();
//                }
            }else{
                echo '<script>alert("账号在其他客户端登录!若非本人操作请及时修改密码!");location.href = "'.$url_config['login_url'].'"</script>';
                die();
            }
        }else{
            echo '<script>alert("您还未登录或登录已过期!");location.href = "'.$url_config['login_url'].'"</script>';
            die();
        }
    }
}