#!/bin/sh

#変数の設定
G_CONF_FILE=./pms.conf
if [ ! -e $G_CONF_FILE ]; then
  echo "config file is not exist ${G_CONF_FILE}"
  exit 1
fi  

. $G_CONF_FILE
DOMAIN=$DOMAIN
GIT_REPOSITORY=$GIT_REPOSITORY
USERNAME=$USERNAME
APACHE_USER=$APACHE_USER
RUBY_VERSION=$RUBY_VERSION
DBUSER=$DBUSER
DBNAME=$DBNAME
DBPASS=$DBPASS


#すでにインストールしてあったら、削除する
rm -Rf /var/www/sites/${DOMAIN}

#PMSをインストールする
cd /var/www/sites/
git clone ${GIT_REPOSITORY} ${DOMAIN}
cd ${DOMAIN}
ln -s web public_html

tee app/config/parameters.ini << EOF
[parameters]
    database_driver   = pdo_mysql
    database_host     = localhost
    database_port     =
    database_name     = ${DBNAME}
    database_user     = ${DBUSER}
    database_password = ${DBPASS}

    mailer_transport  = smtp
    mailer_host       = localhost
    mailer_user       =
    mailer_password   =

    locale            = ja

    secret            = 39136675ae741e8550f6c42836b46e4e9721e544
    deploy_domain     = http://hosting.pne.jp
EOF


mkdir app/cache; mkdir app/logs

php bin/vendors install
php app/console doctrine:database:create
php app/console doctrine:schema:create

#PMA用の設定をする
tee /var/www/sites/${DOMAIN}/pma/autoinst/db.conf << EOF
DBUSER=${DBUSER}
DBPASS=${DBPASS}
DBHOST=
DBPORT=
DBSOCK=
EOF

sed -i'' -e "s/pms_domain/${DOMAIN}/g" /var/www/sites/${DOMAIN}/pma/pma.rb
sed -i'' -e "s/pms_domain/${DOMAIN}/g" /var/www/sites/${DOMAIN}/pma/pma_notifier.rb
sed -i'' -e "s/SITE_DOMAIN/${DOMAIN}/g" /var/www/sites/${DOMAIN}/pma/autoinst/install.sh
sed -i'' -e "s/SITE_DOMAIN/${DOMAIN}/g" /var/www/sites/${DOMAIN}/pma/autoinst/sns_delete.sh



#一般ユーザーでも作業できるようにするのとログをApacheユーザーが作成できるようにする
chown -R ${USERNAME} .
chgrp -R ${USERNAME} .
chown -R ${USERNAME} .git/
chgrp -R ${USERNAME} .git/
chmod -R 2775  app/cache app/logs
chgrp -R ${APACHE_USER} app/cache app/logs


#PMAの設定
#フルパスじゃないとRVM上のRubyを実行できない
tee /var/spool/cron/root << EOF
*/2 * * * * /home/${USERNAME}/.rvm/rubies/ruby-${RUBY_VERSION}/bin/ruby      /var/www/sites/${DOMAIN}/pma/pma.rb >> /tmp/cron.log 2>>/tmp/cronerror.log; /home/${USERNAME}/.rvm/rubies/ruby-${RUBY_VERSION}/bin/ruby /var/www/sites/${DOMAIN}/pma/pma_notifier.rb >> /tmp/cron.log 2>>/tmp/cronerror.log
EOF

#serverデータが表示されればPMSのインストールは成功している
curl "http://${DOMAIN}/api/server/add" -d "host=${DOMAIN}"
curl "http://${DOMAIN}/api/server/list"
