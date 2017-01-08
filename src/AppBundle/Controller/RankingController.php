<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/ranking")
 */
class RankingController extends Controller
{
    
    /**
     * LISTE COMPETITIONS
     * @Route("/{id}", defaults={"id": null})
     */
    public function displayRankingAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $rankings = $em->getRepository('AppBundle:Ranking')->findBy(array('competition_id' => $id));
        
        return $this->render('/ranking/display.html.twig', 
        [
            'rankings' => $rankings,
        ]);
    }    
}
