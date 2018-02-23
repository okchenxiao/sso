<?php

/**
 * Created by Xiao.
 * Date: 2018/2/20
 * email: via_chen@126.com
 */

namespace app\Model;


use think\Model;

class Log extends Model
{
    public function getOne()
    {
        $res = $this->select()->toArray();
        dump($res);exit;
    }
}