<?php

/**
 * Created by Xiao.
 * Date: 2018/2/20
 * email: via_chen@126.com
 */

namespace app\Model;

use think\Request;

class User extends BasicModel
{
    //用户登录
    public function login($data)
    {
        if(empty($data) || !isset($data['username']) || !isset($data['password'])){
            return ['status'=>-1,'msg'=>'请确认输入登录用户和登录密码!'];
        }
        //获取用户数据
        $source = $this->where('user_name',$data['username'])->find();
        if($source == false){
            return ['status'=>-1,'msg'=>'请确认输入登录用户或登录密码正确!'];
        }
        $source = $source->toArray();
        //检测账号状态
        if($source['status'] == 1){
            return ['status'=>-1,'msg'=>'账号未激活!'];
        }
        if($source['status'] == 3){
            return ['status'=>-1,'msg'=>'账号已禁用!请及时联系管理员!'];
        }
        //验证密码是否正确
        $current_secret = code_secret($data['password']);
        if($current_secret != $source['password']){
            return ['status'=>-1,'msg'=>'请确认输入登录用户或登录密码正确!!'];
        }
        $ip = Request::instance()->ip();
        $now_time = date('Y-m-d H:i:s');
        $source['last_ip'] = $ip;
        $source['last_time'] = $now_time;
        //保存更新登录时间和ip
        $save_res = $this->isUpdate(true)->save($source);
        if($save_res == true){
            //获取唯一标识作为主键写入redis
            $unique_code = get_redis_key();
            //写入一个随机字符串  作为判断唯一/异地登录的标识
            $source['rand_str'] = rand(1000,9999).time().rand(1000,9999);
            $redis_write = cache($unique_code,$source);
            if($redis_write == true){
                //写入cookie
                cookie('user_key',['key'=>$unique_code,'remark'=>$source['rand_str']],3600);//1小时过期时间
                return ['status'=>1,'msg'=>'登录成功!'];
            }
            return ['status'=>1,'msg'=>'登录同步失败!请刷新后重试...'];
        }
        return ['status'=>-1,'msg'=>'请确认输入登录用户或登录密码正确!!!'];
    }
}