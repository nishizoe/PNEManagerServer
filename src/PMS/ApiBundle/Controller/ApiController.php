<?php

namespace PMS\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;


class ApiController extends BaseApiController
{

  public function indexAction(Request $request)
  {
    $query = $request->query;
    if (0 === count($query->all()))
    {
      return $this->forward('PMSApiBundle:Api:list');
    }

    $type = $query->get('type');
    $action = $query->get('action', 'index');
    $form = $this->createApiForm();
    $form->bind(array(
      'type'    => $type,
      'action'  => $action,
      'format'  => $query->get('format', 'json'),
    ));

    if (!$form->isValid())
    {
      return $this->renderErrorJson(400, $this->buildErrorMessages($form));
    }

    $typeList = $this->typeList();
    if (!in_array($type, $typeList['type']))
    {
      return $this->renderErrorJson(400, 'the type "'.$type.'" not found');
    }

    $controller = $this->get('pms_api.'.$type);
    $actions = $controller->actionList();
    if ('index' !== $action && !in_array($action, $actions['action']))
    {
      return $this->renderErrorJson(400, 'the action "'.$action.'" not found');
    }

    $query->remove('type');
    $query->remove('action');
    $query->remove('format');

    $query->add(array('_format' => $form->get('format')->getData()));

    return $this->forward('PMSApiBundle:'.ucfirst($type).':'.$action, array(), $query->all());
  }

  public function listAction()
  {
    return $this->renderJson($this->typeList());
  }

  public function typeList()
  {
    return array(
      'type' => array(
        'domain',
        'server',
        'sns',
      )
    );
  }

  private function createApiForm()
  {
    $formBuilder = $this->createFormBuilder(null, array('csrf_protection' => false));
    $formBuilder
      ->add('type', 'text', array('property_path' => false))
      ->add('action', 'text', array('property_path' => false))
      ->add('format', 'text', array('property_path' => false));

    $formBuilder->addValidator(new CallbackValidator(function(FormInterface $form) {
      foreach ($form as $name => $data)
      {
        if (!ctype_lower($data->getData()))
        {
          $data->addError(new FormError('invalid'));
        }
      }
    }));

    return $formBuilder->getForm();
  }

  private function buildErrorMessages($form)
  {
    $errors = array();
    foreach ($form as $name => $child)
    {
      if ($child->hasErrors())
      {
        $errors[$name] = array();
        foreach ($child->getErrors() as $error)
        {
          $errors[$name][] = $error->getMessageTemplate();
        }
      }
    }

    return $errors;
  }

}
