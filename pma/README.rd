== README
author: 渡辺優也
date: 2012/03/01

= 概要

自動設置システムである PMS の SNS サーバ側エージェント である PMA の説明

= 必要環境

ruby 1.8
gem の json パッケージ

= スクリプト説明

pma.rb : PMS から SNS サーバ情報を取得して来て同期を行う
pma_notifier.rb : SNS サーバ情報を PMS 側に通知する
pma_register.rb : SNS 設置サーバとして PMS 側に通知を行う
