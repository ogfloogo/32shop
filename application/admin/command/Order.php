<?php

namespace app\admin\command;

use app\admin\model\Goods;
use app\admin\model\Jwd;
use app\admin\model\Monthstatistics;
use app\admin\model\Shop;
use app\admin\model\Statistics;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class Order extends Command
{
    protected function configure()
    {
        $this->setName('Order')
            ->setDescription('机器人下单');
    }

    protected function execute(Input $input, Output $output)
    {
        $shop = Shop::where(['robot'=>1])->select();
        foreach ($shop as $value){
            $this->createOrder($value);
        }
    }

    public function createOrder($info){
        $order = \app\admin\model\Order::where(['shop_id'=>$info['id']])->whereTime('createtime','today')->find();
        if(!$order){
            $turnover = 0;
            $sales = 0;
            $statistics_turnover = rand($info['turnover'],$info['turnover2']);
            while ($turnover < $statistics_turnover){
                $goods = Goods::where(['shop_id'=>$info['id'],'status'=>1])->orderRaw("rand()")->find();
                if(!$goods){
                    break;
                }
                $time = $this->randtime();
                $create[] = [
                    'order_sn' => $this->createordersn($time),
                    'shop_id' => $info['id'],
                    'goods_id' => $goods['id'],
                    'num' => 1,
                    'amount' => $goods['price'],
                    'createtime' => $time,
                    'jwd' => $info['jwd']
                ];
                $turnover += $goods['price'];
                $sales += 1;
                (new Goods())->where(['id'=>$goods['id']])->setInc('sales',1);
            }
            if($turnover){
                \app\admin\model\Order::insertAll($create);
                $create2 = [
                    'date' => date('Y-m-d'),
                    'shop_id' => $info['id'],
                    'turnover' => $turnover,
                    'sales' => $sales
                ];
                Statistics::create($create2);
                $this->monthStatistics($turnover,$info['jwd']);
            }
        }
//        else{
//            $statistics = Statistics::where(['date'=>date('Y-m-d'),'shop_id'=>$info['id']])->find();
//            $turnover = $statistics['turnover'];
//            $sales = $statistics['sales'];
//            $create = [];
//            while ($turnover < $info['turnover']){
//                $goods = Goods::where(['shop_id'=>$info['id'],'status'=>1])->orderRaw("rand()")->find();
//                if(!$goods){
//                    break;
//                }
//                $time = $this->randtime();
//                $create[] = [
//                    'order_sn' => $this->createordersn($time),
//                    'shop_id' => $info['id'],
//                    'goods_id' => $goods['id'],
//                    'num' => 1,
//                    'amount' => $goods['price'],
//                    'createtime' => $time
//                ];
//                $turnover += $goods['price'];
//                $sales += 1;
//            }
//            if($create){
//                \app\admin\model\Order::insertAll($create);
//                $statistics->turnover = $turnover;
//                $statistics->sales = $sales;
//                $statistics->save();
//            }
//        }
    }

    public function randtime(){
        return rand(strtotime(date('Y-m-d 00:00:00')),strtotime(date('Y-m-d 23:59:59')));
    }

    public function createordersn($time)
    {
        $msec = substr(microtime(), 2, 2);        //	毫秒
        $subtle = substr(uniqid('', true), -8);    //	微妙
        return date('YmdHis',$time) . $msec . $subtle;  // 当前日期 + 当前时间 + 当前时间毫秒 + 当前时间微妙
    }

    public function monthStatistics($money,$jwd){
        $month_statistics = (new Monthstatistics())->where(['year'=>date('Y'),'month'=>date('m')])->find();
        if(!$month_statistics){
            $create = [
                'year'=>date('Y'),
                'month'=>date('m'),
                'money' => $money
            ];
            (new Monthstatistics())->create($create);
        }else{
            $month_statistics->money = $month_statistics->money + $money;
            $month_statistics->save();
        }
        $city_statistics = (new Jwd())->where(['id'=>$jwd])->find();
        if($city_statistics){
            $city_statistics->money = $city_statistics->money + $money;
            $city_statistics->save();
        }
    }
}
