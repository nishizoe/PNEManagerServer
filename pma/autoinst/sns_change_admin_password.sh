#!/bin/bash

G_HOSTNAME=$1

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
    pne_log "script argments number must be given 1, hostname" "error"
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
    pne_log "database config file is not exist ${G_DBCNF_FILE}" "error"
    exit 1
  fi

  . $G_DBCONF_FILE

  G_DBUSER=$DBUSER
  G_DBPASS=$DBPASS

  if [ "${DBHOST}" == "" ]; then
    G_DBHOST="localhost"
  else
    G_DBHOST=$DBHOST
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

function create_password(){
  local len=$1
  local char='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
  local i=1

  while [ $i -le $len ]; do
    dm=$(( (RANDOM % ${#char}) ))
    PASS="${PASS}${char:${dm}:1}"
    i=$(( i+1 ))
  done

  echo $PASS
}

function get_db_args(){
  local ARGS="$G_DATABASE --user=$G_DBUSER --password=$G_DBPASS"
  if [ "${G_DBSOCK}" != "" ]; then
    ARGS="${ARGS} --socket=${G_DBSOCK}"
  elif [ "${G_DBPORT}" ]; then
    ARGS="${ARGS} --port=${G_DBPORT}"
  fi

  echo "${ARGS}"
}

load_db_conf
PASS=`create_password 8`
ARGS=`get_db_args`

echo "UPDATE admin_user SET password= MD5('${PASS}') WHERE username = 'admin'" | mysql $ARGS

echo "admin_pass = ${admin_pass}"
