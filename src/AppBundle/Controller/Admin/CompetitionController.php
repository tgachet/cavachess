<?php


namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Competition;
use AppBundle\Entity\GameMode;
use AppBundle\Entity\TypeOfGame;
use AppBundle\Form\CompetitionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;


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
    
    public function addCompetitionAction(Request $request)
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
        return $this->render('admin/competition/add.html.twig',
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
    
    public function addTypeOfGameAction(Request $request) 
    {
        $em = $this->getDoctrine()->getManager();

        $typeOfGame = new TypeOfGame(); 
        
        // Création du formulaire associé à l'instance de TypeOfGame
        $formBuilder = $this->createFormBuilder();
        
        $formBuilder
            ->add('name',
                TextType::class, // input type text
                [
                    'label' => 'Nom du type de compétition',
                ]);
        
        $form = $formBuilder->getForm();
        
        $form->handleRequest($request);
        
        // Soumission formulaire
        if($form->isSubmitted())
        {
            if($form->isValid()) // No error
            {
                $em->persist($typeOfGame); // 
                $em->flush(); // Execute()

                $this->addFlash('success', 'Le type de jeu a bien été crée');
                return $this->redirectToRoute('app_admin_gestioncompetitions');
            }
            else
            {
                // Ajout le message flash
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }
        
        // Render
        return $this->render('admin/competition/typeofgame/add.html.twig',
        [
           'form' => $form->createView(),
        ]);        
    }

    public function addGameModeAction(Request $request) 
    {
        $em = $this->getDoctrine()->getManager();

        $gamemode = new GameMode(); 
        
        // Création du formulaire associé à l'instance de TypeOfGame
        $formBuilder = $this->createFormBuilder();
        
        $formBuilder
            ->add('name',
                TextType::class, // input type text
                [
                    'label' => 'Nom du mode de jeu',
                ]);
        
        $form = $formBuilder->getForm();
        
        $form->handleRequest($request);
        
        // Soumission formulaire
        if($form->isSubmitted())
        {
            if($form->isValid()) // No error
            {
                $em->persist($gamemode); // 
                $em->flush(); // Execute()

                $this->addFlash('success', 'Le mode de jeu a bien été crée');
                return $this->redirectToRoute('app_admin_gestioncompetitions');
            }
            else
            {
                // Ajout le message flash
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }
        
        // Render
        return $this->render('admin/competition/gamemode/add.html.twig',
        [
           'form' => $form->createView(),
        ]);        
    }    
}
