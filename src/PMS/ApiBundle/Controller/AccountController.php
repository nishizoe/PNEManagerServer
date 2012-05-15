<?php

namespace PMS\ApiBundle\Controller;

class AccountController extends BaseApiActionController
{
    public function actionList()
    {
        return array(
            'action' => array(
                'list',
            )
        );
    }

    public function listAction()
    {
        $list = array();
        $rep = $this->getDoctrine()->getRepository('PMSApiBundle:Account');
        $accounts = $rep->findAll();
        foreach ($accounts as $account)
        {
            $list[] = array(
                'email' => $account->getEmail(),
            );
        }
        
        return $this->renderJson($list);
    }

}
