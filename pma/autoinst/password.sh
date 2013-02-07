#!/bin/bash

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

echo `create_password 8`
