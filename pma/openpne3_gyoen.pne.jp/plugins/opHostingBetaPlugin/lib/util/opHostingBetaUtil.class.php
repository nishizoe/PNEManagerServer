<?php

class opHostingBetaUtil
{

  public static function isUseBetaServie()
  {
    return true;
  }

  public static function canUserAdd()
  {
    $limit = new opHostingBetaLimit();

    return $limit->canUserAddByAddCount(1);
  }

}
