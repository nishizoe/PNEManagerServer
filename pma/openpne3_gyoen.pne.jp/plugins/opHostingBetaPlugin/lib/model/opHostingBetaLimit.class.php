<?php

class opHostingBetaLimit
{

  const USER_LIMIT = 100;

  public function isOverUserLimit()
  {
    return ($this->countRegistUser() > self::USER_LIMIT);
  }

  public function countRegistUser()
  {

    $q = opDoctrineQuery::create();

    $q->from('Member m');
    $q->select('COUNT(*)');
    $q->where('is_active = ?', true);
    $searchResult = $q->fetchArray();

    return (int) $searchResult[0]['COUNT'];
  }

  public function canUserAddByAddCount($addCount)
  {
    return ($this->countRegistUser() + $addCount <= self::USER_LIMIT);
  }

}
