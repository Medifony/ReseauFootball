<?php

namespace App\Controller;

use App\Entity\Pays;
use App\Form\PaysType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ChampionnatRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminPaysController extends AbstractController
{
    public function create(EntityManagerInterface $manager, Request $request) 
    {
        $pays = new Pays();

        $form = $this->createForm(PaysType::class, $pays);

        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid()){ 
            $manager->persist($pays);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le pays <strong>{$pays->getNomFr()}</strong> a bien été enregistré !"
            );

            return $this->redirectToRoute('admin_pays_create');
        }

        return $this->render('admin/pays/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function edit(Pays $pays, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(PaysType::class, $pays);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($pays);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le pays <strong>{$pays->getNomFr()}</strong> a bien été enregistré"
            );
        }

        return $this->render('admin/pays/edit.html.twig', [
            'pays' => $pays,
            'form' => $form->createView()
        ]);
    }

    public function delete(Pays $pays, EntityManagerInterface $manager, ChampionnatRepository $repoChamp, UserRepository $repoUser){
        $championnats = $repoChamp->findByPayss($pays);
        $users = $repoUser->findByPayss($pays);

        foreach($championnats as $championnat)
            {
                $championnat->setPayss(null);
            }
            
        foreach($users as $user)
            {
                $user->setPayss(null);
            }
        
        $manager->remove($pays);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le pays <strong>{$pays->getNomFr()}</strong> a bien été supprimé !"
        );
        
    return $this->redirectToRoute('admin_pays_index');
}
}
