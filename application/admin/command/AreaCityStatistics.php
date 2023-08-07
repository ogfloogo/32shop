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

class AreaCityStatistics extends Command
{
    protected function configure()
    {
        $this->setName('AreaCityStatistics')
            ->setDescription('近30天统计');
    }

    protected function execute(Input $input, Output $output)
    {
        $shop = Jwd::where('1=1')->select();
        foreach ($shop as $value){
            $this->createOrder($value);
        }
    }

    public function createOrder($info){
        $money = \app\admin\model\Order::where(['jwd'=>$info['id']])->whereTime('createtime','-30 days')->sum('amount');
        (new Jwd())->where(['id'=>$info['id']])->update(['month_money'=>$money]);
    }

}
