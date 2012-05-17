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
    $ cp app/config/parameters.ini.sample app/config/parameters.ini
    $ vi app/config/parameters.ini

下記のように編集する．注意としてはdeploy\_domain は現状デプロイ先ではなく，Access-Control-Allow-Originヘッダの値として用いる．

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
    
        secret            = （あああ）

        deploy_domain     = http://hosting.pne.jp

環境を整える．

    $ php bin/vendors install
    $ php app/console doctrine:database:create
    $ php app/console doctrine:schema:create

### Testing

実行環境やDBの設定が終わった後にテストを実行する．

    $ ./bin/execute_pmsapi_tests.sh

テスト実行後はDBにゴミが残っているので初期化を行う．

    $ ./bin/init_schema.sh

Deployment
----------

### First Deployment

デプロイツールをインストールする．

    $ gem install capifony

デプロイサーバ側にログインできる状態になっているかどうかを確認する．

最初の一回だけ下記コマンドを実行する．

    $ cap deploy:setup

ここでデプロイ先で app/config/deploy.rb にある shared\_files のファイルを編集する．
現状では下記ファイルが shared ディレクトリに必要．

    "app/config/parameters.ini",
    "src/PMS/ViewerBundle/Resources/views/Viewer/sns.html.twig",
    "web/js/pne.js",
    "src/Deploy/HtmlBundle/Resources/config/routing.yml",
    "src/PMS/ApiBundle/Controller/Listener/RequestListener.php",
    "web/.htaccess",

デプロイを行う．

    $ cap deploy

Webサーバで閲覧できる位置にシンボリックリンクを貼る．

    $ ln -s /opt/sabakan/PNEManagerServer/current /var/www/sns/pne.jp

### Normal Deployment

サーバ側で/opt/sabakan/PNEManagerServer/shared/app/cache を削除．

ローカルでデプロイコマンドを実行．

    $ cap deploy
 
サーバ側で/opt/sabakan/PNEManagerServer/shared/app/cache を削除．


### Notice

現状だとキャッシュの削除などがちゃんとできないので，適宜サーバ側でキャッシュの削除やパーミッションの解決などを行う必要がある．
この問題は，デプロイ先が稼働中だとデプロイプロセス中にキャッシュが生成されてデプロイを成功させられない場合がある可能性を示唆しています．


TODO
----

環境依存の部分が分散しすぎているので統括的に扱える方法を検討．

Using
-----

* **Symfony2** (http://www.symfony-project.org/)
* **Capifony** (http://capifony.org/) 
* **HTML5 ADMIN** (http://www.html5admin.com/)
