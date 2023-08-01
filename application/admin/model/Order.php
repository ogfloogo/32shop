<?php

namespace app\admin\model;

use think\Model;


class Order extends Model
{

    

    

    // 表名
    protected $name = 'order';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];


    public function shop(){
        return $this->belongsTo(Shop::class,'shop_id','id',[],'LEFT')->setEagerlyType(0);
    }

    public function goods(){
        return $this->belongsTo(Goods::class,'goods_id','id',[],'LEFT')->setEagerlyType(0);
    }





}
