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
     * DISPLAY COMPETITIONS
     * @Route("/{orderby}/{sort}", defaults={"orderby": "id", "sort": "ASC"})
     */
    public function displayCompetitionsAction($sort, $orderby) 
    {
        $em = $this->getDoctrine()->getManager();
        $competitions = $em->getRepository('AppBundle:Competition')->findBy(array(), array($orderby => $sort));
        
        return $this->render('competition/display.html.twig', 
        [
            'competitions' => $competitions,
        ]);
    }    
}
