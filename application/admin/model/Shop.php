<?php

namespace app\admin\model;

use think\Model;


class Shop extends Model
{

    

    

    // 表名
    protected $name = 'shop';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'robot_text'
    ];
    

    
    public function getRobotList()
    {
        return ['0' => __('Robot 0'), '1' => __('Robot 1')];
    }


    public function getRobotTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['robot']) ? $data['robot'] : '');
        $list = $this->getRobotList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
