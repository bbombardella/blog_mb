<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
    * @Route("/", name="home_user")
    */
    public function user(): Response {
        return $this->render('home/user.html.twig');
    }

    /**
    * @Route("/admin", name="home_admin")
    */
    public function admin(): Response {
        return $this->render('home/admin.html.twig');
    }
}
