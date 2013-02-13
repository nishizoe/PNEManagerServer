#!/bin/sh

#シェルの実行に必要な定数を設定する
G_CONF_FILE=./pms.conf
if [ ! -e $G_CONF_FILE ]; then
  echo "config file is not exist ${G_CONF_FILE}"
  exit 1
fi  

. $G_CONF_FILE

USERNAME=$USERNAME
DBPASS=$DBPASS
DOMAIN=$DOMAIN 


#Apacheのインストールをする
yum -y install httpd
mkdir /var/www/sites

tee /etc/httpd/conf.d/virtualhost.conf << EOF
VirtualDocumentRoot /var/www/sites/%0/public_html

<Directory "/var/www/sites/*/public_html/">
  Options FollowSymLinks ExecCGI
  AllowOverride all
  Order Allow,Deny
  Allow from all
</Directory>
EOF

#Rubyのインストールに必要なものをインストールする
yum install -y curl curl-devel gcc-c++ patch readline readline-devel zlib zlib-devel libyaml-devel libffi-devel openssl-devel make bzip2 autoconf automake libtool bison glibc


#Symfony2に必要な物をインストールする
yum install -y php php-pear php-mysql php-mbstring php-gd php5-cli php-apc  mysql-server php-posix php-intl php-devel pcre-devel php-xml php-pecl-apc

tee -a /etc/php.ini << EOF
;Customize Settings
date.timezone="Asia/Tokyo"
EOF

#ApacheとMySQLを自動で起動するようにする
/etc/init.d/httpd start
/etc/init.d/mysqld start

chkconfig httpd on
chkconfig mysqld on

mysqladmin -u root password ${DBPASS}

yum -y install git

#作業ユーザーで作業できるようにするために権限を設定する
chgrp  -R ${USERNAME} /var/www/
chmod -R g+rw /var/www

#送信メールの設定 PMAではpostfixを使用するので、sendmailをoffにする
yum  install -y postfix
service sendmail stop
chkconfig sendmail off

service postfix start
chkconfig postfix on
update-alternatives --set mta /usr/sbin/sendmail.postfix

# インストールに成功したかを確認するためにphpinfoのページを作成する
cd /var/www/sites/
sudo -u ${USERNAME} mkdir -p ${DOMAIN}/public_html
echo "<?php phpinfo();" > ${DOMAIN}/public_html/phpinfo.php
echo "ブラウザで以下のURLにアクセスして正しくphpinfoのページが表示されればインストール成功です"
echo "http://${DOMAIN}/phpinfo.php"
