<?php

class opHostingCancelTask extends sfBaseTask
{

  protected function  configure()
  {
    $this->namespace = 'opHosting';
    $this->name = 'cancel';

    $this->addOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', true);
    $this->addOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev');

  }

  protected function execute($arguments = array(), $options = array())
  {    
    new sfDatabaseManager($this->configuration); 

    $snsManager = new opHostingSnsManager();

    $snsManager->cancelHostingPaidService();

    echo "本登録サービスを解約しました\n";


    

  }

}
