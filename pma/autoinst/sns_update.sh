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

  if [ 2 -gt "${len}" ]; then
    pne_log "script argments number must be given 2, target SNS domain and target update OpenPNE version" "error"
    exit 1
  fi
}


is_correct_options "${#@}"
G_TARGET_DOMAIN=$1
G_TARGET=$2
pne_log "start update $1 to version $2" "info"

TMPFILE=/tmp/update_$sns_domain.log
echo -n > $TMPFILE
cd $G_SNS_DIR/$G_TARGET_DOMAIN
git fetch origin 
git fetch --tags
git checkout $G_TARGET
./symfony openpne:migrate 2>&1 1>/dev/null | tee $TMPFILE
if [ -s $TMPFILE ]; then
  pne_log "$G_TARGET_DOMAIN's update was probably not succeeced" "error"
fi

pne_log "end update $1 to version $2" "info"
