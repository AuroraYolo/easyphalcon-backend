# easyphalcon-backend

基于phaclon搭建的后台管理服务系统，里面配置了一些注解，路由注解。中间件的 处理

安装

php版本要求>=7.0
phaclon版本要求>=3.0

Nginx 配置:
server {
    listen 80;
    charset utf-8;
    server_name  www.easyphalcon-backend.com;

    index index.html index.htm index.php;
    root /$dir/easyphalcon-backend/public;

    location @rewrite {
        rewrite ^/(.*)$ /index.php?_url=/$1;
    }
    location / {
        try_files $uri $uri/ @rewrite;
    }
    location ~ [^/]\.php(/|$) {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        include fastcgi.conf;
    }
}

