<?php

namespace PMS\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;


abstract class BaseApiController extends Controller
{

    protected function renderJson($data)
    {
      $response = $this->render('PMSApiBundle::output.json.twig', array(
        'text' => $data
      ));
      $response->headers->set('Content-Type', 'application/json');
      return $response;
    }

    protected function renderErrorJson($statusCode, $errors)
    {
      $response =  $this->render('PMSApiBundle:Exception:error.json.twig', array(
        'status_code' => $statusCode,
        'status_text' => $errors
      ));
      $response->headers->set('Content-Type', 'application/json');
      $response->setStatusCode($statusCode);

      return $response;
    }

}
