<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
        
        /*
         * Variables à passer en js
         */
        $user = $this->get('security.token_storage')->getToken()->getUser()->getUsername();
        $vars = array('user' => $user, 'competition' => $competition );
        
        $this->get('app.js_vars')->chartData = $vars;

        
//        $player = $this->getUser(); => Coté controller pour stocker l'utilisateur
        return $this->render('game/display.html.twig', 
        [
        ]);        
    }
}
