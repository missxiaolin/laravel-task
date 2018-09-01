### task

supervisor

1.消费

~~~
[program:task_dispatch]
command                 = /usr/local/opt/php71/bin/php artisan task:consume --id=%(process_num)02d --max=8
directory               = /Users/mac/web/miss/laravel-task
process_name            = %(program_name)s_%(process_num)02d
stdout_logfile          = /Users/mac/web/miss/laravel-task/storage/logs/supervisor.log
user                    = nginx
stdout_logfile_maxbytes = 10MB
stderr_logfile          = /Users/mac/web/miss/laravel-task/storage/logs/supervisor.log
stderr_logfile_maxbytes = 10MB
autostart               = true
autorestart             = true
numprocs                = 2
stopasgroup             = true
killasgroup             = true
~~~ 

### go rpc

[goRpc连接](https://github.com/missxiaolin/go-rpc)

### elk

[日志服务](https://github.com/missxiaolin/laravel-elk)

### 机器学习
[链接](https://github.com/missxiaolin/laravel-swoole-ml)
 
elk 安装 （mac）

~~~
brew install kibana
brew install elasticsearch
~~~

启动

~~~
brew services start kibana
brew services start elasticsearch
~~~

