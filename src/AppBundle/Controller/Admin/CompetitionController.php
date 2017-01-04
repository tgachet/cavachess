<?php


namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Competition;
use AppBundle\Entity\GameMode;
use AppBundle\Entity\TypeOfGame;
use AppBundle\Form\CompetitionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
    
    /**
     * 
     * @Route("/list")
     */
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
    

    
    /**
     * @Route("/edit/{id}", defaults={"id": null})
     */
    public function editCompetitionAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        
        if(is_null($id)) // création
        {
            $new= true;
            $competition = new Competition(); // On crée un objet de la classe Category du namespace AppBundle\Entity
        }
        else // modification
        {
            $new = false;
            // Raccourci pour $em->getReposiroty('AppBundle:Category', $id)->find($id)
            $competition = $em->find('AppBundle:Competition', $id);
            
            if(is_null($competition))
            {
                // On redirige vers la route de la liste s'il n'y a pas de categories
                return $this->redirectToRoute('app_admin_competition_listcompetitions');
            }
        }
        
        $form = $this->createForm(CompetitionType::class, $competition);
        
        // Auto gereur de requête (utilise les éléments du formulaire). Le formulaire anaylyse la requête HTTP
        $form->handleRequest($request);
        
        if($form->isSubmitted()) // Si le formulaire a été soumis
        {
            if($form->isValid()) // S'il n'y a pas eu d'erreur de validation du formulaire
            {
                $em->persist($competition); // persist = on garde en persistance les infos
                $em->flush(); // execution des éléments gardés en persistance (enregistrement en BDD)
                
                // Forme ternaire. Si new existe => ? sinon :
                $msg = ($new)
                        ? 'La compétition a bien été crée'
                        : 'La compétition a bien été modifié'
                      ;
                $this->addFlash('success', $msg);
                return $this->redirectToRoute('app_admin_competition_gestioncompetitions');
            }
            else
            {
                // Ajout le message flash
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }        
        
        return $this->render('admin/competition/edit.html.twig', 
        [
            'new' => $new,
            'form' => $form->createView(),
        ]);
    }
   
    /**
     * @Route("/gametype")
     */
    public function addTypeOfGameAction(Request $request) 
    {
        $em = $this->getDoctrine()->getManager();

        $typeOfGame = new TypeOfGame(); 
        
        // Création du formulaire associé à l'instance de TypeOfGame
        $formBuilder = $this->createFormBuilder($typeOfGame);
        
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
                $typeOfGame = $form->getData();
                $em->persist($typeOfGame); // 
                $em->flush(); // Execute()

                $this->addFlash('success', 'Le type de jeu a bien été crée');
                return $this->redirectToRoute('app_admin_competition_gestioncompetitions');
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
    
    /**
     * @Route("/gamemode")
     */
    public function addGameModeAction(Request $request) 
    {
        //$em = $this->getDoctrine()->getManager();

        $gamemode = new GameMode(); 
        
        // Création du formulaire associé à l'instance de gamemode
        $formBuilder = $this->createFormBuilder($gamemode);
        
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
               
                $gamemode = $form->getData();
                
                $em = $this->getDoctrine()->getManager();
                
                $em->persist($gamemode); // 
                $em->flush(); // Execute()
                
                

                $this->addFlash('success', 'Le mode de jeu a bien été crée');
                return $this->redirectToRoute('app_admin_competition_gestioncompetitions');
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
