<?php


namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Competition;
use AppBundle\Entity\TypeOfGame;
use AppBundle\Entity\GameMode;


/**
 * Description of CompetitionController
 *
 * @Route("/competition")
 */
class CompetitionController extends Controller
{
    /**
     * Test sans Route list avec render sur la page (voir layout du dossier entrainement ligne 34)
     * @Route("/list")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $competitions = $em->getRepository('AppBundle:Competition')->findAll();
        
        return $this->render('admin/competition.html.twig', 
        [
            'competitions' => $competitions,
        ]);
    }
}
