<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/")
     * @return Response
     */
    public function index():Response
    {
        //$response = new Response('<h1>hello</h1>',200, []);
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/contact", name="app_contact")
     * @return Response
     */
    public function contact():Response
    {
        return $this->render('contact.html.twig');
    }
}
