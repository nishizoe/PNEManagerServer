#!/bin/bash

G_HOSTNAME=$1
G_ESCAPED_HOSTNAME=`echo "${G_HOSTNAME}" | sed -e 's/\./\\\./g'`

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
    pne_log "script argments number must be given 2, hostname and admin email" "error"
    exit 1
  fi
}

## main
pne_log "${G_HOSTNAME}: start change admin email alias" "info"
is_correct_options "${#@}"

sed -i "/\# ${G_ESCAPED_HOSTNAME}/N;//N;//N;s/^sns@example\.com.*/sns@example.com ${G_CHANGE_ADMIN_EMAIL}/" /etc/postfix/virtual.openpne
/usr/sbin/postmap /etc/postfix/virtual.openpne

pne_log "${G_HOSTNAME}: info change admin email alias" "info"
