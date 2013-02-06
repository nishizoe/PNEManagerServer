<?php

namespace PMS\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PMS\ApiBundle\Entity\Domain;
use PMS\ApiBundle\Entity\Sns;

class DomainController extends BaseApiActionController
{

  public function actionList()
  {
    return array(
        'action' => array(
            'list',
            'add',
            'available',
            'delete',
        )
    );
  }

  public function listAction()
  {
    $list = array();
    $rep = $this->getDoctrine()->getRepository('PMSApiBundle:Domain');
    $domains = $rep->findAll();
    foreach ($domains as $domain)
    {
      $list[] = array(
          'domain' => $domain->getDomain(),
          'type' => $domain->getType()
      );
    }

    return $this->renderJson($list);
  }

  public function addAction()
  {
    $params = $this->getRequest()->request;
    if (is_null($params->get('domain')))
    {
      return $this->renderErrorJson(400, array(
          'param' => array(
              'domain'
          )
      ));
    }

    $dom = new Domain();
    $dom->setDomain($params->get('domain'));
    $dom->setType($params->get('domainType', ''));

    $em = $this->getDoctrine()->getEntityManager();
    $em->persist($dom);
    $em->flush();

    return $this->renderJson(array('result' => true));
  }

  public function availableAction()
  {
    $domain = $this->getRequest()->query->get('domain', '');
    $domains = $this->getDoctrine()->getEntityManager()->getRepository('PMSApiBundle:Domain');
    $result = true;
    if ('' === $domain || !is_null($domains->findOneByDomain($domain)))
    {
      $result = false;
    }

    $response = $this->renderJson(array('result' => $result));
    //$response->headers->set('Access-Control-Allow-Origin', $this->container->getParameter('deploy_domain'));
    $response->headers->set('Access-Control-Allow-Origin', 'http://cqc.jp');
    $response->headers->set('Content-type', 'application/json; charset=UTF-8');
    return $response;
  }

  public function deleteAction()
  {
    $params = $this->getRequest()->request;

    $domain = $params->get('domain', 'test123.pne.jp');

    $em = $this->getDoctrine()->getEntityManager();
    $domainRepository = $em->getRepository('PMSApiBundle:Domain');

    if (!$domainRepository->existsByDomain($domain))
    {
      return $this->renderJson(array('result' => false, 'message' => "指定した{$domian}は存在しません"));
    }

    if (!$domainRepository->deleteByDomain($domain))
    {
      return $this->renderJson(array('result' => false, 'message' => "指定した{$domain}のデータを削除できませんでした"));
    }

    return $this->renderJson(array('result' => true));
  }

}
