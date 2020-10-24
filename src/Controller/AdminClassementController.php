<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\Classement;
use App\Form\ClassementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminClassementController extends AbstractController
{
    
    public function create(EntityManagerInterface $manager, Request $request) 
    {
        $classement = new Classement();

        $form = $this->createForm(ClassementType::class, $classement);

        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid()){ 
            $manager->persist($classement);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le classement <strong>{$classement->getId()}</strong> a bien été enregistré !"
            );

            return $this->redirectToRoute('admin_classements_create');
        }

        return $this->render('admin/classement/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function edit(Classement $classement, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(ClassementType::class, $classement);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($classement);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le classement <strong>{$classement->getId()}</strong> a bien été enregistré"
            );
        }

        return $this->render('admin/classement/edit.html.twig', [
            'classement' => $classement,
            'form' => $form->createView()
        ]);
    }

    public function delete(Classement $classement, EntityManagerInterface $manager){
        
        $classement->setClubs(new Club());

        $manager->remove($classement);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le classement <strong>{$classement->getId()}</strong> a bien été supprimé !"
        );
        
    return $this->redirectToRoute('admin_classements_index');
}
}
