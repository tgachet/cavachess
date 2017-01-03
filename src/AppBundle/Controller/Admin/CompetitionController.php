<?php


namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Competition;
use AppBundle\Entity\GameMode;
use AppBundle\Entity\TypeOfGame;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\CompetitionType;


/**
 * Description of CompetitionController
 *
 * @Route("/competition")
 */
class CompetitionController extends Controller
{
    /**
     * 
     * @Route()
     */
    public function gestionCompetitionsAction() 
    {
        return $this->render('admin/competition/gestion.html.twig');
    }
    
    
    public function listCompetitionsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $competitions = $em->getRepository('AppBundle:Competition')->findAll();
        $columns = $em->getClassMetadata('AppBundle:Competition')->getFieldNames();
        
        return $this->render('admin/competition/list.html.twig', 
        [
            'competitions' => $competitions,
            'columns' => $columns,
        ]);
    }
    
    public function ajoutCompetitionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $competition = new Competition(); 
        
        // Création du formulaire associé à l'instance de Competition
        $form = $this->createForm(CompetitionType::class, $competition);

        //
        $form->handleRequest($request);
        
        // Soumission formulaire
        if($form->isSubmitted())
        {
            if($form->isValid()) // No error
            {
                $em->persist($competition); // 
                $em->flush(); // Execute()

                $this->addFlash('success', 'La compétition a bien été crée');
                return $this->redirectToRoute('app_admin_gestioncompetitions');
            }
            else
            {
                // Ajout le message flash
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }
        
        // Render
        return $this->render('admin/competition/edit.html.twig',
        [
           'form' => $form->createView(),
        ]);         
    }
    
    /**
     * 
     * @Route("/{id}", defaults={"id":null})
     */
    public function editCompetitionAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $competition = $em->find('AppBundle:Competition', $id);
                
        if(is_null($competition))
        {
            // On redirige vers la page de gestion de compétition
            return $this->redirectToRoute('app_admin_competition_gestioncompetitions');
        }

        // Création du formulaire associé à l'instance de Competition
        $form = $this->createForm(CompetitionType::class, $competition);

        //
        $form->handleRequest($request);
        
        // Soumission formulaire
        if($form->isSubmitted())
        {
            if($form->isValid()) // No error
            {
                $em->persist($competition); // 
                $em->flush(); // Execute()

                $this->addFlash('success', 'La compétition a bien été modifiée');
                return $this->redirectToRoute('app_admin_competition_gestioncompetitions');
            }
            else
            {
                // Ajout le message flash
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }
        
        // Render
        return $this->render('admin/competition/gestion.html.twig',
        [
           'form' => $form->createView(),
        ]);         
    }
}
