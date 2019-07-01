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
     * @Route("/admin/{roles}/{id}", name="adminUser")
     * @param UserRepository $userRepository
     * @param User $user
     * @param string $roles
     * @return Response
     */
    public function switchAdmin(UserRepository $userRepository, User $user, string $roles=''):Response
    {
        $user->setRoles(['ROLE_'.$roles]);
        $manager= $this->getDoctrine()->getManager();
        $manager->persist($user);
        $manager->flush();
        $this->addFlash('success', 'L\'utilisateur a bien été mis à jour');
        return $this->redirectToRoute('admin');
    }


}
