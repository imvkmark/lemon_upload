## 系统部署
- [F]php 5.5.9+
- [P]php-bcmath(*inx)
- [F]php-pdo
- [F]php-gd
- [F]php-curl
- [F]php-openssl
- [F]php-mbstring
- [F]php-tokenizer
- [F]php-mcrypt


## 上线注意
- 删除 `global.js` 文件 `php56 artisan lemon:front`
- 清空后台并且需要重新生成缓存文件
- 运行计划任务 `php artisan schedule:run`
- 运行队列 `php artisan queue:listen`

## 生成 apidoc 文档
```
apidoc -i app/Http/Controllers/Api/ -o  public/docs/api
```

## 生成 PHP 文档
```
php vendor/sami/sami/sami.php update config/sami.php
```

## key
```
er5zHW  dba74c8bd7475c75e4ed73c097a11869    2010-10-10 10:10:10
```

## 技术文档
### session
```
desktop_auth    : 后台对前台的授权
front_validated : 前台进行安全保护时候是否已经验证过
```


## supervisor 配置
```
[program:dailian-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /media/web/www/1dailian/artisan queue:work --sleep=3 --tries=3 --daemon
autostart=true
autorestart=true
user=mark
numprocs=8
redirect_stderr=true
stdout_logfile=/media/web/www/1dailian/storage/server/job.log
```


## 计划任务
```
* * * * * php /media/web/www/1dailian/artisan schedule:run 1>> /dev/null 2>&1
```