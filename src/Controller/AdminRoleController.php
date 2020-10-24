<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\RoleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminRoleController extends AbstractController
{
    public function create(EntityManagerInterface $manager, Request $request) 
    {
        $role = new Role();
        $user = new User();
    
        $form = $this->createForm(RoleType::class);

        $form->handleRequest($request); 
        
        if($form->isSubmitted() && $form->isValid()){ 
            
            $data = $form->getData();

            $user = $data['User'];
            $role = $data['Role'];

            $user->addUserRole($role);

            $manager->persist($user);

            $manager->flush();

            $this->addFlash(
                'success',
                "L'utilisateur <strong>{$user->getPseudo()}</strong> a bien été ajouté !"
            );

            return $this->redirectToRoute('admin_roles_create');
        }

        return $this->render('admin/role/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function delete(Role $role, User $user, EntityManagerInterface $manager){
        $user->removeUserRole($role);

        $manager->persist($user);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le rôle <strong>{$role->getTitle()}</strong> a bien été supprimé chez l'User <strong>{$user->getPseudo()}</strong> !"
        );
        
        return $this->redirectToRoute('admin_roles_index');
    }
}
