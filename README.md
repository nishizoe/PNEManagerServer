PNE Manager Server 
==================

PNEホスティングの管理用アプリケーション

Installation
------------

### Server Installation

Debian

    # aptitude install sqlite3 php5-sqlite
    # vi /etc/php5/cli/php.ini

以下のように変更する．

    [Date]
    ; Defines the default timezone used by the date functions
    ; http://php.net/date.timezone;date.timezone =
    date.timezone = Asia/Tokyo

Webサーバをリスタートする．

    # /etc/init.d/apache restart

### Local Installation

Server Installation を実行した後ソースを取得する．

    $ git clone git@github.com:tejimaya/PNEManagerServer.git
    $ cd PNEManagerServer
    $ mkdir app/cache; mkdir app/logs
    $ chmod 2775 app/cache app/logs
    $ sudo chgrp www-data app/cache app/logs
    $ php app/check.php
    $ vi app/config/parameters.ini

下記のように編集する．

    [parameters]
        database_driver   = pdo_mysql
        database_host     = localhost
        database_port     =
        database_name     = pms
        database_user     = pms
        database_password = （パスワード）
    
        mailer_transport  = smtp
        mailer_host       = localhost
        mailer_user       =
        mailer_password   =
    
        locale            = ja
    
        secret            = 39136675ae741e8550f6c42836b46e4e9721e544


環境を整える．

    $ php bin/vendors install
    $ php app/console doctrine:database:create
    $ php app/console doctrine:schema:create


Deployment
----------

### First Deployment

デプロイツールをインストールする．

    $ gem install capifony

デプロイサーバ側にログインできる状態になっているかどうかを確認する．

最初の一回だけ下記コマンドを実行する．

    $ cap deploy:setup

ここでデプロイ先で app/config/deploy.rb にある shared_files のファイルを編集する．

    $ cap deploy:cold

Webサーバで閲覧できる位置にシンボリックリンクを貼る．

    $ ln -s /opt/sabakan/PNEManagerServer/current /var/www/sns/pne.cc

### Normal Deployment

    $ cap deploy

### Notice

現状だとキャッシュの削除などがちゃんとできないので，適宜サーバ側でキャッシュの削除やパーミッションの解決などを行う必要がある．

TODO
----

環境依存の部分が分散しすぎているので統括的に扱える方法を検討．

Using
-----

* **Symfony2** (http://www.symfony-project.org/)
* **Capifony** (http://capifony.org/) 
* **HTML5 ADMIN** (http://www.html5admin.com/)
