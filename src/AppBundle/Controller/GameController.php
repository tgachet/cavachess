<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ranking;
use AppBundle\Entity\Competition;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Page Jeu front-office
 * @Route("/game")
 */
class GameController extends Controller
{
    /**
     * @Route("/{competition}", requirements={"competition" : "\d+"})
     */
    public function displayGameAction($competition)
    {
        /*
         * Vérifie si l'utilisateur est connecté
         */
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        
        /* Pseudo utilisateur */
        $username = $this->get('security.token_storage')->getToken()->getUser()->getUsername();
        
        /* Id utilisateur */
        $userid = $this->getUser()->getId();
        
        /* Rank de l'utilisateur */
        $em = $this->getDoctrine()->getManager();
        $rank = $em->getRepository('AppBundle:Ranking')->findOneBy(array('user_id' => $userid, 'competition_id' => $competition));
        $compet = $em->getRepository('AppBundle:Competition')->findOneBy(array('id' => $competition));
        if(!is_null($rank))
        {
            $points = $rank->getPoints();
        }
        else {
            /* Ajout d'un nouveau rank */  
            $ranking = new Ranking();
            $ranking->setUser_id($this->getUser());
            $ranking->setCompetition_id($compet);
            $ranking->setPoints(1500);
            
            $em->persist($ranking);
            $em->flush();
            
            $points = 1500;
        }
        
        
        /*
         * Variables à passer en js
         */
        
        $vars = array('user' => $username, 'competition' => $competition, 'rank' => $points );
        
        $this->get('app.js_vars')->chartData = $vars;

        return $this->render('game/display.html.twig', 
        [
            'rank' => $points,
        ]);        
    }
}
