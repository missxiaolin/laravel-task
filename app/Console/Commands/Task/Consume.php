<?php

namespace App\Console\Commands\Task;

use Carbon\Carbon;
use Illuminate\Console\Command;

class Consume extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:consume
        
        {--id=      : 当前编号}
        {--max=     : 最大进程}
        {--sleep=   : 休眠多少毫秒}
        {--debug=   : 是否调试模式}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '任务消费,轮训守护进程';

    /**
     * 任务编号
     *
     * @var int
     */
    protected $id;

    /**
     * 最大线程
     *
     * @var int
     */
    protected $max;

    /**
     * 休眠时间
     *
     * @var int
     */
    protected $sleep;

    /**
     * 是否开启debug
     *
     * @var bool
     */
    protected $debug;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->id = $this->option('id') ?? '00';
        $this->max = $this->option('max') ?? 32;
        $this->sleep = $this->option('sleep') ?? 700;
        $this->debug = $this->option('debug') ?? false;

        if ($this->id > $this->max) {
            return true;
        }

        while (true) {
            $this->doRun();
            $this->wait($this->sleep);
        }
    }

    /**
     * 执行业务
     */
    public function doRun()
    {
        $lock = sprintf('task:%03d:%s', $this->id, time());
        $data = [
            'id' => $this->id,
            'max' => $this->max,
            'key' => $lock,
            'time' => (new Carbon)->format('Y-m-d H:i:s.u'),
        ];
        try {
            $data['message'] = 'Task Executed.';
            $this->logger($data);

        } catch (\Exception $ex) {
            $data['message'] = $ex->getMessage();
        }
    }

    /**
     * 日志
     * @param $message
     */
    protected function logger($message)
    {
        if ($this->debug) {
            $time = (new Carbon)->format('Y-m-d H:i:s.u');
            $this->line(array_get($message, 'message') . ' - ' . $time);
        }

        logger_instance('task-consume', $message);
    }

    /**
     * 毫秒
     * @param string $time
     */
    protected function wait($time)
    {
        $wait = $time * 1000;
        usleep($wait);
    }
}
