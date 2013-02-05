<?php

namespace PMS\ApiBundle\Entity;

use Doctrine\ORM\EntityRepository;

class SnsRepository extends EntityRepository
{
  public function existsByDomain($domain)
  {
    return ($this->findOneByDomain($domain) !== null);
  }

  public function deleteByDomain($domain)
  {
    if (!$this->existsByDomain($domain))
    {
      throw new \LogicException("削除しようとした{$domain}は存在しません");
    }

    $sns = $this->findOneByDomain($domain);

    $em = $this->getEntityManager();
    $em->remove($sns);
    $em->flush();

    return true;
  }

}
