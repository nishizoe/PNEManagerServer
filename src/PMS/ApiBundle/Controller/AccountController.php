<?php

namespace PMS\ApiBundle\Controller;

class AccountController extends BaseApiActionController
{
    public function actionList()
    {
        return array(
            'action' => array(
                'login',
            )
        );
    }

    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $this->getSession();

        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR))
        {
             $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        }
        else
        {
             $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        if ((bool)$error) 
        {
            return $this->renderJson(array('result' => true));
        }
        else
        {
            return $this->renderErrorJson(401, array('result' => false));
        }
    }

}
