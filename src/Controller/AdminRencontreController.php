<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\Rencontre;
use App\Entity\Championnat;
use App\Form\RencontreType;
use App\Repository\RencontreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminRencontreController extends AbstractController
{
    public function create(EntityManagerInterface $manager, Request $request, RencontreRepository $repoRenc) 
    {
        $rencontre = new Rencontre();

        $form = $this->createForm(RencontreType::class, $rencontre);

        $form->handleRequest($request); 
        
        if($form->isSubmitted() && $form->isValid()){ 

            $eqD = $repoRenc->findMatchEquipe($rencontre->getClubDom()->getSlug());
            $eqE = $repoRenc->findMatchEquipe($rencontre->getClubExt()->getSlug());

            $dateRencontre = $rencontre->getMatchDate()->format('Y-m-d');
            $existeD = false;
            $existeE = false;

            foreach($eqD as $eq)
            {
                if($dateRencontre == $eq->getMatchDate()->format('Y-m-d'))
                    $existeD = true;
            }

            foreach($eqE as $eq)
            {
                if($dateRencontre == $eq->getMatchDate()->format('Y-m-d'))
                    $existeE = true;
            }

            if(($existeE == false) AND ($existeD == false))
            {
                $manager->persist($rencontre);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "La rencontre <strong>{$rencontre->getId()}</strong> a bien été enregistrée !"
                );

                return $this->redirectToRoute('admin_rencontres_create');
            }
            elseif($existeD == true)
            {
                $this->addFlash(
                    'danger',
                    "L'équipe <strong>{$rencontre->getClubDom()->getNom()}</strong> joue une rencontre ce jour là !"
                );

                return $this->redirectToRoute('admin_rencontres_create');
            }
            else
            {
                $this->addFlash(
                    'danger',
                    "L'équipe <strong>{$rencontre->getClubExt()->getNom()}</strong> joue une rencontre ce jour là !"
                );

                return $this->redirectToRoute('admin_rencontres_create');
            }
        }

        return $this->render('admin/rencontre/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function edit(Rencontre $rencontre, Request $request, EntityManagerInterface $manager, RencontreRepository $repoRenc){
        $form = $this->createForm(RencontreType::class, $rencontre);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $eqD = $repoRenc->findMatchEquipe($rencontre->getClubDom()->getSlug());
            $eqE = $repoRenc->findMatchEquipe($rencontre->getClubExt()->getSlug());

            $dateRencontre = $rencontre->getMatchDate()->format('Y-m-d');
        
            $existeD = false;
            $existeE = false;

            foreach($eqD as $eq)
            {
                if(($dateRencontre == $eq->getMatchDate()->format('Y-m-d')) AND $rencontre->getId() != $eq->getId())
                    $existeD = true;
            }

            foreach($eqE as $eq)
            {
                if($dateRencontre == $eq->getMatchDate()->format('Y-m-d') AND $rencontre->getId() != $eq->getId())
                    $existeE = true;
            }

            if(($existeE == false) AND ($existeD == false))
            {
                $manager->persist($rencontre);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "La rencontre <strong>{$rencontre->getId()}</strong> a bien été enregistré"
                );
            }
            elseif($existeD == true)
            {
                $this->addFlash(
                    'danger',
                    "L'équipe <strong>{$rencontre->getClubDom()->getNom()}</strong> joue une rencontre ce jour là !"
                );
            }
            else
            {
                $this->addFlash(
                    'danger',
                    "L'équipe <strong>{$rencontre->getClubExt()->getNom()}</strong> joue une rencontre ce jour là !"
                );
            }
        }

        return $this->render('admin/rencontre/edit.html.twig', [
            'rencontre' => $rencontre,
            'form' => $form->createView()
        ]);
    }

    public function delete(Rencontre $rencontre, EntityManagerInterface $manager){
        $rencontre->setChampionnats(new Championnat());
        $rencontre->setClubDom(new Club);
        $rencontre->setClubExt(new Club);
        
        $manager->remove($rencontre);
        $manager->flush();

        $this->addFlash(
            'success',
            "La rencontre <strong>{$rencontre->getId()}</strong> a bien été supprimée !"
        );
        
    return $this->redirectToRoute('admin_rencontres_index');
}
}
