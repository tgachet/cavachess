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
        /* ENTITY MANAGER */
        $em = $this->getDoctrine()->getManager();

        $competition = $em->getRepository('AppBundle:Competition')->findOneBy(array('id' => $id));
        if(!$competition){ 
            return $this->redirectToRoute('homepage');
        }       
        $competitionanme = $competition->getName();
        
        /* GET RANKINGS */
        $rankings = $em->getRepository('AppBundle:Ranking')->findBy(array('competition_id' => $id));
        
        /* GET GAMES FINISHED */
        $games = $em->getRepository('AppBundle:GamesFinished')->findBy(array('id_competition' => $id));    
        
        /* STATISTICS */
        $times = [];
        $tabplays = [];
        $players = [];
        foreach ($games as $game){
            $times[] = $game->getGamelength()->format('H:i:s');
            $tabplays[] = $game->getNbplays();
            $players[] = $game->getIdwinner()->getUsername();
            $players[] = $game->getIdlooser()->getUsername();
        }        
        
        /* time */
        $seconds = 0;
        foreach ($times as $value) {
           list($hour,$minute,$second) = explode(':', $value);
            $seconds += $hour*3600;
            $seconds += $minute*60;
            $seconds += $second;
        }
        $avgseconds = round($seconds / count($times), 2);
        
        $hours = floor($seconds/3600);
        $seconds -= $hours*3600;
        $minutes  = floor($seconds/60);
        $seconds -= $minutes*60;
        
        $avghours = floor($avgseconds/3600);
        $avgseconds -= $avghours*3600;
        $avgminutes  = floor($avgseconds/60);
        $avgseconds -= $avgminutes*60;        
        
        $totaltime = sprintf('%02d heure(s) %02d minute(s) %02d seconde(s)', $hours, $minutes, $seconds);
        $avgtime = sprintf('%02d heure(s) %02d minute(s) %02d seconde(s)', $avghours, $avgminutes, $avgseconds);             
        /* max */
        $nbmaxplays = max($tabplays);
        $nbplays = array_sum($tabplays);
        $avgplays = round($nbplays / count($tabplays), 1);
        $maxgamelength = max($times);
        $countPlayers = array_count_values($players);
        $topplayer = array_search(max($countPlayers),$countPlayers); 
        
        return $this->render('/ranking/display.html.twig', 
        [
            'rankings' => $rankings,
            'competition' => $competitionanme,
            'games' => $games,
            'nbplays' => $nbplays,
            'totaltime' => $totaltime,
            'nbmaxplays' => $nbmaxplays,
            'avgplays' => $avgplays,
            'maxgamelength' => $maxgamelength,
            'topplayer' => $topplayer,
            'avgtime' => $avgtime,
        ]);
    }    
}
