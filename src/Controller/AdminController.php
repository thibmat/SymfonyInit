<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(UserRepository $userRepository)
    {
        return $this->render('admin/index.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }

    /**
     * @Route("/admin/{id}")
     * @param $user
     * @return Response
     */
    public function switchAdmin(User $user):Response
    {

       $user->setRoles(['SUPER_ADMIN']);
       return $this->redirectToRoute('admin');
    }
}
