<?php

namespace PMS\ApiBundle\Entity;

use Doctrine\ORM\EntityRepository;

class DomainRepository extends EntityRepository
{


  public function existsByDomain($domain)
  {
    return ($this->findOneByDomain($domain) !== null);
  }

  public function deleteByDomain($domain)
  {
    $snsRepository = $this->getEntityManager()->getRepository('PMSApiBundle:Sns');

    if (!$this->existsByDomain($domain))
    {
      throw new \LogicException("削除しようとした{$domain}は存在しません");
    }

    $sns = $this->findOneByDomain($domain);

    $em = $this->getEntityManager();
    $em->remove($sns);
    $em->flush();

    if ($snsRepository->existsByDomain($domain))
    {
      $snsRepository->deleteByDomain($domain);
    }

    return true;
  }

}
