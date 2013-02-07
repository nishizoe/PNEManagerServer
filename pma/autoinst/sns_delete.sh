#!/bin/bash

G_HOSTNAME=$1
G_DATABASE=`echo "${G_HOSTNAME}" | sed -e 's/[\.-]/_/g'`
G_ESCAPED_HOSTNAME=`echo "${G_HOSTNAME}" | sed -e 's/\./\\\./g'`

G_DBCONF_FILE=/var/www/sites/SITE_DOMAIN/pma/autoinst/db.conf

function pne_log(){
  local msg=$1
  local type=$2

  if [ "${type}" == "" ]; then
    echo "${G_HOSTNAME} ${msg}"
  elif [ "${type}" == "info" ]; then
    echo "${G_HOSTNAME} ${msg}"
    logger ${G_HOSTNAME} "${msg}"
  elif [ "${type}" == "error" ]; then
    echo "${G_HOSTNAME} ${msg}" 2>&1
    logger ${G_HOSTNAME} "${msg}"
  fi
}

function is_correct_options(){
  local len=$1

  if [ 1 -gt "${len}" ]; then
    pne_log "script argments number must be given 1, domain" "error"
    exit 1
  fi
}

# CONFFIlE must be follow format
# DBUSER=****
# DBPASS=****
# DBHOST= (optional)
# DBPORT= (optional)
# DBSOCK= (optional)
function load_db_conf(){
  pne_log "load db conf"

  if [ ! -e $G_DBCONF_FILE ]; then
    pne_log "database config file is not exist ${G_DBCONF_FILE}" "error"
    exit 1
  fi

  . $G_DBCONF_FILE

  G_DBUSER=$DBUSER
  G_DBPASS=$DBPASS

  if [ "${DBHOST}" != "" ]; then
    G_DBHOST=$DBHOST
  else
    G_DBHOST="localhost"
  fi

  if [ "${DBSOCK}" != "" ]; then
    G_DBSOCK=$DBSOCK
  else
    G_DBPORT=3306
    if [ "${DBPORT}" != "" ]; then
      G_DBPORT=$DBPORT
    fi
  fi
}

function get_db_args(){
  local ARGS="--user=$G_DBUSER --password=$G_DBPASS --host=$G_DBHOST"
  if [ "${G_DBSOCK}" != "" ]; then
    ARGS="${ARGS} --protocol=SOCKET --socket=${G_DBSOCK}"
  elif [ "${G_DBPORT}" ]; then
    ARGS="${ARGS} --port=${G_DBPORT}"
  fi

  echo "${ARGS}"
}


## main
is_correct_options "${#@}"

load_db_conf

echo $G_ESCAPED_HOSTNAME;

crontab -l > /tmp/crontab.admin.tmp
sed -i "/\# ${G_ESCAPED_HOSTNAME}/N;//N;//N;//d" /tmp/crontab.admin.tmp
cat /tmp/crontab.admin.tmp | crontab
rm /tmp/crontab.admin.tmp

sed -i "/\# ${G_ESCAPED_HOSTNAME}/N;//N;//N;//d" /etc/postfix/virtual.openpne
/usr/sbin/postmap /etc/postfix/virtual.openpne
sed -i "/\# ${G_ESCAPED_HOSTNAME}/N;//d" /etc/aliases.openpne
/usr/sbin/postalias /etc/postfix/aliases.openpne

G_DBARGS=`get_db_args`
mysqladmin $G_DBARGS -f drop $G_DATABASE

sed -i "/$G_HOSTNAME/d" /var/www/sites/SITE_DOMAIN/pma/autoinst/installed_domain_list.txt

rm -rf /var/www/sns/$G_HOSTNAME
