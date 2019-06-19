## 环境依赖
- php >= 7.1.3    
- redis >= 2.8
- composer  
- supervisor  
```
supervisor配置文件模版：
[program:laravel-queue-worker]
process_name=%(program_name)s_%(process_num)02d
directory={code_ducument_root}   ;低版本不支持此指令
command=/usr/bin/php {code_ducument_root}/artisan queue:work --delay=3 --sleep=1 --tries=3 --timeout=60
autostart=true
autorestart=true
startretries=3
user=nginx
numprocs=1
redirect_stderr=true
stdout_logfile=/data/log/supervisor/%(program_name)s.log
stdout_logfile_maxbytes=100MB
stdout_logfile_backups=10

容器环境模版：
[program:laravel-queue-worker]
#process_name=%(program_name)s_%(process_num)02d
#directory=
command=/usr/local/bin/docker-compose -f /data/docker/docker-compose.yml exec -T php /bin/bash -c "runuser -u www-data -- php /data/www/app_base/artisan queue:work --delay=3 --sleep=1 --tries=3 --timeout=60"
autostart=true
autorestart=true
startretries=3
user=root
#numprocs=1
redirect_stderr=true
stdout_logfile=/data/log/supervisor/%(program_name)s.log
stdout_logfile_maxbytes=100MB
stdout_logfile_backups=10
```  
- docker   
- node & npm

```
安装node和npm环境：
curl --silent --location https://rpm.nodesource.com/setup_10.x | sudo bash -
yum -y install nodejs
```

## 单元测试与代码覆盖率
测试并生成代码覆盖率报表：  
```
cd ${code_ducument_root}
./vendor/bin/phpunit --coverage-html public/test/
```
查看代码覆盖率：
> URI: /test/index.html

## 查看api文档（Swagger UI）
> URI: /api/documentation

## 生产环境代码发布  

```
cd ${code_ducument_root}
git pull                #获取最新代码
composer install        #安装laravle依赖
cp .env.example .env    #配置文件(根据生产环境配置对应的参数)
php artisan migrate     #创建表(database要提前创建)
php artisan key:generate #生成key
ln -sv ../storage/app/public/ public/storage    #创建符号链接到文件上传目录
chmod -R {phpfpm_runner}.{phpfpm_runner} ./ #更改代码目录的权限为phpfpm程序的运行用户
chmod +x vendor/phpunit/phpunit/phpunit #添加执行权限
./vendor/bin/phpunit    #代码测试
```  

## cron计划任务
```
crontab -e
* * * * * sudo -u nginx /usr/bin/php {code_ducument_root}/artisan schedule:run >> /dev/null 2>&1  

#注意：.env里面正确配置好日志输出文件"CRON_TASK_LOG"

#运行容器时命令：
* * * * * /usr/local/bin/docker-compose -f /data/docker/docker-compose.yml exec -T php /bin/bash -c "runuser -u www-data php /data/www/app_base/artisan schedule:run" >> /dev/null 2>&1
```  

**任务列表**  

### 使用post-merge钩子脚本  
```
#!/bin/sh

codeDir=$(cd $(dirname $0); pwd)'/../../'
queueName="laravel-queue-worker"

supervisorctl restart $queueName #重启队列

cd $codeDir
composer install
#docker环境
#/usr/local/bin/docker-compose -f /data/docker/docker-compose.yml exec -T php /bin/bash -c "cd ${codeDir}; composer install"

cd client
npm install
npm run build
```

## 开发环境规范
### 开发环境使用pre-push钩子
```
#!/bin/sh

codeDir=$(cd $(dirname $0); pwd)'/../../'
cd $codeDir
./vendor/bin/phpunit
```

