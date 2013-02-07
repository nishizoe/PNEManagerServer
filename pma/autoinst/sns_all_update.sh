#!/bin/bash

G_SNS_DIR=/var/www/sns

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
    pne_log "script argments number must be given 1, target update OpenPNE version" "error"
    exit 1
  fi
}


is_correct_options "${#@}"
G_TARGET=$1
pne_log "start update to version $1" "info"

cat /opt/sabakan/autoinst/installed_domain_list.txt |  while read sns_domain
do
  TMPFILE=/tmp/update_$sns_domain.log
  echo -n > $TMPFILE
  cd $G_SNS_DIR/$sns_domain
  git fetch origin 
  git fetch --tags
  git checkout $G_TARGET
  ./symfony openpne:migrate 2>&1 1>/dev/null | tee $TMPFILE
  if [ -s $TMPFILE ]; then
    pne_log "$sns_domain's update was probably not succeeced" "error"
  fi
done

pne_log "end update to version $1" "info"
