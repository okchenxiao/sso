<?php

/**
 * Created by Xiao.
 * Date: 2018/2/20
 * email: via_chen@126.com
 */

namespace app\Model;


class Log extends BasicModel
{
    public function getOne()
    {
        $res = $this->select()->toArray();
        dump($res);exit;
    }
}