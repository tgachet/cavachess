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
     * @Route("/list")
     */
    public function listRankingsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $rankings = $em->getRepository('AppBundle:Ranking')->findAll();
        
        return $this->render('/ranking/list.html.twig', 
        [
            'rankings' => $rankings,
        ]);
    }    
}
