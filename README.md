# PHP-MSF DEMO

PHP微服务框架即“[Micro Service Framework For PHP](https://github.com/pinguo/php-msf)”示例项目


# How to Start

```bash
# Step1. 下载项目
git clone https://github.com/pinguo/php-msf-demo.git
# Step2. 安装docker镜像构建开发环境.进入项目目录
sh build.sh
# Step3. 参考Step2输出的信息,使用ssh或者exec进入容器, 到达项目根目录, 安装依赖
composer install
# Step4. 启动服务器, 可以使用supervisorctl执行update或者执行php server.php 即可。
php server.php
```


# Notice

- php-msf-demo示例项目使用的是最新的[php-msf](https://github.com/pinguo/php-msf)代码，即dev-master分支，生产环境请使用release的相关版本。
- 在有些环境中执行`php server.php`，由于`inotify`的原因并不能自动热更新代码，请尝试使用`nodemon --exec "php" server.php`
- 使用过程中如果有问题可以参见框架文档 [PHP-MSF开发手册](https://pinguo.gitbooks.io/php-msf-docs/) 以及 [常见问题及其解决方式](https://pinguo.gitbooks.io/php-msf-docs/chapter-6/6.0-%E5%B8%B8%E8%A7%81%E9%97%AE%E9%A2%98.html)


# LICENSE

GNU General Public License, version 2 see [https://www.gnu.org/licenses/gpl-2.0.html](https://www.gnu.org/licenses/gpl-2.0.html)
