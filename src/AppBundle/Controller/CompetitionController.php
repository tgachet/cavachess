<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Page Compétition front-office
 * @Route("/competition")
 */
class CompetitionController extends Controller
{
    /**
     * WARNING : LES TRIS ASC/DESC SE FONT SUR LES ID DES TABLES DE JOINTURE ET PAS LES NAME
     * DISPLAY COMPETITIONS
     * @Route("/{orderby}/{sort}", defaults={"orderby": null, "sort": null})
     */
    public function displayCompetitionsAction($sort = 'ASC', $orderby = 'id') 
    {
        $em = $this->getDoctrine()->getManager();
        $competitions = $em->getRepository('AppBundle:Competition')->findBy(array(), array($orderby => $sort));
        $rankings = $em->getRepository('AppBundle:Ranking')->findBy(array(), array('points'=> 'DESC'));
        $gameModes = $em->getRepository('AppBundle:GameMode')->findAll();
        
        return $this->render('competition/display.html.twig', 
        [
            'competitions' => $competitions,
            'rankings' => $rankings,
            'gamemodes' => $gameModes,
        ]);
    }    
}
