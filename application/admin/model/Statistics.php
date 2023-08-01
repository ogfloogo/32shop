<?php

namespace app\admin\model;

use think\Model;


class Statistics extends Model
{

    

    

    // 表名
    protected $name = 'statistics';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];

    public function shop(){
        return $this->belongsTo(Shop::class,'shop_id','id',[],'LEFT')->setEagerlyType(0);
    }
    







}
