<?php

namespace PMS\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;


abstract class BaseApiActionController extends BaseApiController
{
    public function indexAction()
    {
        return $this->renderJson($this->actionList());
    }

    abstract public function actionList();

}
