<?php

use think\cache\driver\Redis;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;
use think\Log;
use think\Exception;


class Robot extends Command
{
    protected $model = null;

    protected function configure()
    {
        // 指令配置
        $this->setName('Robot')
            ->setDescription('机器人下单');
    }

    protected function execute(Input $input, Output $output)
    {
        // 指令输出
        $output->writeln('Robot');
        $ws = new \Swoole\WebSocket\Server('0.0.0.0', 9513);
        //守护进程模式
        $ws->set([
            'daemonize' => true,
            'worker_num' => 1,
            // 'task_worker_num' => 4,
        ]);
        //监听WebSocket连接打开事件
        $ws->on('Open', function ($ws, $request) {
            $ws->push($request->fd, "hello, welcome\n");
        });

        //监听WebSocket消息事件
        $ws->on('Message', function ($ws, $frame) {
            echo "Message: {$frame->data}\n";
            $ws->push($frame->fd, "server: {$frame->data}");
        });
        $ws->on('WorkerStart', function ($ws, $worker_id) {
            echo "workerId:{$worker_id}\n";
            $redis = new Redis();
            \Swoole\Timer::tick(1500, function () use ($redis, $worker_id) {
                $redis->handler()->select(6);
                $list = $redis->handler()->Hgetall("shop1:goods:robot");
                foreach ($list as $k => $v) {
                    try {
                        $v_arr = explode('-', $v);
                        if ($v_arr[0] < time()) { //到期时间
                            $order_sn = $this->createorder();
                            $create = [
                                'order_sn' => $order_sn,
                                'goods_id' => $k,
                                'num' => 1,
                                'amount' => $v_arr[1] ?? 0, //机器人投资金额
                                'createtime' => time()
                            ];
                            (new \app\admin\model\Order())->create($create);
                        }
                    } catch (Exception $e) {
                        Db::rollback();
                        Log::mylog('机器人下单', $e, 'Robot');
                    }
                }
            }, $ws);
        });
        //监听WebSocket连接关闭事件
        $ws->on('Close', function ($ws, $fd) {
            echo "client-{$fd} is closed\n";
        });
        $ws->start();
    }

    public function createorder()
    {
        $msec = substr(microtime(), 2, 2);        //	毫秒
        $subtle = substr(uniqid('', true), -8);    //	微妙
        return date('YmdHis') . $msec . $subtle;  // 当前日期 + 当前时间 + 当前时间毫秒 + 当前时间微妙
    }

    public function statistics(){

    }
}
