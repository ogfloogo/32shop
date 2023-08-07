<?php

namespace app\api\controller;

use app\admin\model\Goods;
use app\admin\model\Monthstatistics;
use app\admin\model\Order;
use app\common\controller\Api;

/**
 * 示例接口
 */
class Jwd extends Api
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';

    public function index(){
        $info = (new \app\admin\model\Jwd())->select();
        $total = array_sum(array_column($info,'month_money'));
        $this->success('',['list'=>$info,'total'=>$total?($total/10000).'万':0]);
    }

    public function goods(){
        $info = (new Goods())->order('sales asc')->limit(10)->select();
        $sum = array_sum(array_column($info,'sales'));
        foreach ($info as &$value){
            $value['rate'] = bcmul($value['sales']/$sum,100,2);
        }
        $this->success('',$info);
    }

    public function area(){
        $info = (new \app\admin\model\Jwd())->order('money desc')->limit(12)->select();
        $this->success('',$info);
    }

    public function order(){
        $info = (new Order())->where(['createtime'=>['<=',time()]])->order('createtime desc')->limit(20)->select();
        foreach ($info as &$value){
            $goods_name = (new Goods())->where(['id'=>$value['goods_id']])->value('name');
            $value['info'] = $goods_name.'&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp'.$value['amount'];
        }
        $this->success('',$info);
    }

    public function month(){
        $year = date('Y') - 1;
        $info = (new Monthstatistics())->where(['year'=>$year])->order('month asc')->select();
        foreach ($info as &$value){
            $value['time'] = $value['year'].'年'.$value['month'].'月';
        }
        $this->success('',$info);
    }
}
