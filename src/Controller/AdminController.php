<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/newuser", name="new_user")
     */
    public function createUser(Request $req, ManagerRegistry $mr, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);
            $user->setDateEmbauche(new \DateTime());

            if($user->getIsComptable())
                $user->setRoles(array("ROLE_COMPTABLE"));
            else
                $user->setRoles(array("ROLE_VISITEUR"));

            $manager = $mr->getManager();
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('dashboard-admin',[
                "valid_message" => "Action bien prise en compte !"
            ]);

        }        
        return $this->render('admin/newuser.html.twig', [
            "form" => $form->createView()
        ]);
    }


    /**
     * @Route("/admin/edituser/{id}", name="edit_user")
     */
    public function editUser(User $user, Request $req, ManagerRegistry $mr)
    {
        if($user->getRoles()[0] == "ROLE_ADMIN")
            return $this->redirectToRoute('dashboard-admin',[
                "error_message" => "Vous ne pouvez pas modifier un compte administrateur !"
            ]);
        
        $form = $this->createForm(UserEditType::class, $user);

        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getPassword());

            if($user->getIsComptable())
                $user->setRoles(array("ROLE_COMPTABLE"));
            else
                $user->setRoles(array("ROLE_VISITEUR"));

            $manager = $mr->getManager();
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('dashboard-admin',[
                "valid_message" => "Action bien prise en compte !"
            ]);

        }        
        return $this->render('admin/edituser.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/delete_user/{id}", name="delete_user")
     */
    public function removeUser(User $user, ManagerRegistry $mr)
    {
        if($user->getRoles()[0] == "ROLE_ADMIN")
            return $this->redirectToRoute('dashboard-admin',[
                "error_message" => "Vous ne pouvez pas supprimer un compte administrateur !"
            ]);

        $manager = $mr->getManager();
        $manager->remove($user);
        $manager->flush();

        return $this->redirectToRoute('dashboard-admin',[
            "valid_message" => "Utilisateur bien supprimé !"
        ]);        
    }

    /** 
     * @Route("/admin/dashboard", name="dashboard-admin")
     */
    public function dashboard(UserRepository $repo){
        $users = $repo->findAll();

        if(isset($_GET['valid_message']))
            return $this->render('admin/dashboard.html.twig',[
                "users" => $users,
                "valid_message" => $_GET['valid_message']
            ]);

        if(isset($_GET['error_message']))
            return $this->render('admin/dashboard.html.twig',[
                "users" => $users,
                "error_message" => $_GET['error_message']
            ]);

        return $this->render('admin/dashboard.html.twig',[
            "users" => $users
        ]);
    }
}
