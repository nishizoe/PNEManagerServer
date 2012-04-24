<?php

namespace PMS\ViewerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;


class ViewerController extends Controller
{
    
    public function indexAction($_route)
    {
        return $this->render('PMSViewerBundle:Viewer:index.html.twig', array('_route' => $_route));
    }

    public function snsAction($_route)
    {
        return $this->render('PMSViewerBundle:Viewer:sns.html.twig', array('_route' => $_route));
    }

    public function serverAction($_route)
    {
        return $this->render('PMSViewerBundle:Viewer:server.html.twig', array('_route' => $_route));
    }

    public function helpAction($_route)
    {
        return $this->render('PMSViewerBundle:Viewer:help.html.twig', array('_route' => $_route));
    }

    public function loginAction(Request $request)
    {
        $session = $request->getSession();

        $error = null;

        // ログインエラーがあれば、ここで取得
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        // Sessionにエラー情報があるか確認
        } elseif ($session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            // Sessionからエラー情報を取得
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            // 一度表示したらSessionからは削除する
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render('PMSViewerBundle::login.html.twig', array(
            'error' => $error,
        ));
    }

}
