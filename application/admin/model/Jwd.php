<?php

namespace app\admin\model;

use think\Model;


class Jwd extends Model
{





    // 表名
    protected $name = 'jwd';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];



    public function getjwd($name)
    {
        $name = str_replace('省', '', $name);
        $name = str_replace('市', '', $name);
        $name = str_replace('区', '', $name);
        $jwd = self::where(['name' => ['like', "%{$name}%"]])->find();
        if ($jwd) {
            return ['code' => 1, 'data' => $jwd['id']];
        } else {
            return ['code' => 0, 'msg' => '找不到省份经纬度'];
        }
    }



}
