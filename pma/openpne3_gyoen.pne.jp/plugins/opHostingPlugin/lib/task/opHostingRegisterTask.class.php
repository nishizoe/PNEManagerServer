<?php

class opHostingRegisterTask extends sfBaseTask
{

  protected function  configure()
  {
    $this->namespace = 'opHosting';
    $this->name = 'register';

    $this->addOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', true);
    $this->addOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev');

  }

  protected function execute($arguments = array(), $options = array())
  {    
    new sfDatabaseManager($this->configuration); 

    $snsManager = new opHostingSnsManager();
    $snsManager->registerHostingPaidService();

    echo "本登録サービスを登録しました\n";
  }

}
