<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\DemandeAmi;
use App\Form\EditAmisType;
use App\Form\DemandeAmiType;
use App\Repository\DemandeAmiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAmisController extends AbstractController
{

    public function create(EntityManagerInterface $manager, Request $request, DemandeAmiRepository $repoAmis) 
    {
        $demandeAmi = new DemandeAmi();

        $form = $this->createForm(DemandeAmiType::class, $demandeAmi);

        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid()){ 
            $existe = $repoAmis->findIfAmis($demandeAmi->getUserAjout()->getSlug(), $demandeAmi->getUserRec()->getSlug());

            if(!$existe){
                $demandeAmi->setEnvoiDate(new DateTime('now'));
                if ($demandeAmi->getamiStatut() == 1)
                {
                    $demandeAmi->setValidationDate(new DateTime('now'));
                }
                $manager->persist($demandeAmi);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "La demande d'ami <strong>{$demandeAmi->getId()}</strong> a bien été enregistrée !"
                );

                return $this->redirectToRoute('admin_demandeAmis_create');
            }
            else
            {
                $this->addFlash(
                    'danger',
                    "Ces deux utilisateurs possedent déjà une demande d'amis ensemble !"
                );

                return $this->redirectToRoute('admin_demandeAmis_create');
            }
        }

        return $this->render('admin/demandeAmi/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function edit(DemandeAmi $demandeAmi, Request $request, EntityManagerInterface $manager, DemandeAmiRepository $repoAmis){
        $form = $this->createForm(EditAmisType::class, $demandeAmi);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if ($demandeAmi->getAmiStatut() == 1)
            {
                $demandeAmi->setValidationDate(new DateTime('now'));
            }

            $manager->persist($demandeAmi);
            $manager->flush();

            $this->addFlash(
                 'success',
                "La demande d'amis <strong>{$demandeAmi->getId()}</strong> a bien été enregistrée"
            );
        }

        return $this->render('admin/demandeAmi/edit.html.twig', [
            'demandeAmi' => $demandeAmi,
            'form' => $form->createView()
        ]);
    }

    public function delete(DemandeAmi $demandeAmi, EntityManagerInterface $manager){
        $demandeAmi->setUserAjout(new User());
        $demandeAmi->setUserRec(new User());

        $manager->remove($demandeAmi);
        $manager->flush();

        $this->addFlash(
            'success',
            "La demande d'amis <strong>{$demandeAmi->getId()}</strong> a bien été supprimée !"
        );
        
    return $this->redirectToRoute('admin_amis_index');
    }
}
