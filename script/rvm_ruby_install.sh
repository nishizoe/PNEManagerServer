#!/bin/bash

#シェルの実行に必要な定数を設定する
G_CONF_FILE=./pms.conf
if [ ! -e $G_CONF_FILE ]; then
  echo "config file is not exist ${G_CONF_FILE}"
  exit 1
fi  

. $G_CONF_FILE
RUBY_VERSION=$RUBY_VERSION

echo 'スクリプトを実行するには、sourceコマンドで実行してください'
echo 'source rvm_ruby_install.sh'

wget "https://raw.github.com/wayneeseguin/rvm/master/binscripts/rvm-installer"
bash "rvm-installer"
echo 'PATH=$PATH:$HOME/.rvm/bin # Add RVM to PATH for scripting' >> ~/.bashrc
source "${HOME}/.bashrc"

rvm install ${RUBY_VERSION}
echo 'source "$HOME/.rvm/scripts/rvm"' >> ~/.bashrc

source "${HOME}/.bashrc"
rvm use ${RUBY_VERSION} --default
