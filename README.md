PNE Manager Server 
==================

PNEホスティングの管理用アプリケーション

Installation
------------

### Local Installation

デプロイツールをインストールする．

    $ gem install capifony

ソースを取得する．

    $ git clone git@github.com:tejimaya/PNEManagerServer.git

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

Deployment
----------

### First Deployment

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
