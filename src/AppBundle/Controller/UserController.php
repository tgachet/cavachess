<?php

namespace AppBundle\Controller;

use AppBundle\Form\UserType;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * 
     * @param Request $request
     * @Route("/list")
     */
    public function displayAllAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findAll();
        
        return $this->render(
            'user/list.html.twig',
            [
                'users' => $users,
            ]
        );
    }
    
    /**
     * 
     * @param Request $request
     * @param int $id
     * @Route("/{id}")
     */
    public function displayInfo($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        /* USER INFOS FROM USER */
        $user = $em->find('AppBundle:User', $id);
        $friends = $user->setAllFriends();
//        $friends2 = $user->getFriendsWithMe();
//        $allfriends 
        
        if(!$user){ 
            return $this->redirectToRoute('app_user_displayall');
        }
        else{
            $gameswon = count($user->getGamewinner());
            $gameslost = count($user->getGamelooser());
            $gamesplayed = $gameswon + $gameslost;

            /* USER POSTS INFO FROM POST */
            $posts = $em->getRepository('AppBundle:Post')->findByAuthor($id);


            /* GET GAMES WON AND GAMES LOST */
            $gamewon = $em->getRepository('AppBundle:GamesFinished')->findBy(array('idwinner' => $id));
            $gamelost = $em->getRepository('AppBundle:GamesFinished')->findBy(array('idlooser' => $id));

            $times = [];
            $opponents = [];
            $competitions = [];
            foreach ($gamewon as $game){
                $times[] = $game->getGamelength()->format('H:i:s');
                $opponents[] = $game->getIdlooser()->getUsername();
                $competitions[] = $game->getId_competition()->getName();
            }
            foreach ($gamelost as $game) {
                $times[] = $game->getGamelength()->format('H:i:s');
                $opponents[] = $game->getIdwinner()->getUsername();
                $competitions[] = $game->getId_competition()->getName();
            }

            /* TIME PLAYED FOR GAMES WON AND LOST */
            $seconds = 0;
            foreach ($times as $value) {
               list($hour,$minute,$second) = explode(':', $value);
                $seconds += $hour*3600;
                $seconds += $minute*60;
                $seconds += $second;
            }
            $hours = floor($seconds/3600);
            $seconds -= $hours*3600;
            $minutes  = floor($seconds/60);
            $seconds -= $minutes*60;
            /* Time played */
            $totaltime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

            /* MOST PLAYED OPPONENT */
            if (!empty($opponents))
            {
                $countPerOpponent = array_count_values($opponents);
                $playermostplayed = array_search(max($countPerOpponent),$countPerOpponent); 
            }
            else
            {
                $playermostplayed ='';
            }
            
            /* MOST PLAYED COMPETITION */
            if (!empty($opponents))
            {
                $countPerCompetition = array_count_values($competitions);
                $competitionmostplayed = array_search(max($countPerCompetition),$countPerCompetition); 
            }
            else {
                $competitionmostplayed ='';
            }
            
            /* GET RANKINGS */
            $rankings = $em->getRepository('AppBundle:Ranking')->findBy(array('user_id' => $id));
        }
        
        /* Variable pour la gestion du STATUT (En ligne / Hors-ligne) */
        $delay = new DateTime();
        $delay->setTimestamp(strtotime('5 minutes ago'));
        
        /* RENDER */
        return $this->render(
            'user/profile.html.twig',
            [
                'user' => $user,
                'id' => $id,
                'posts' =>$posts,
                'games' => array('played' => $gamesplayed, 'won' => $gameswon, 'lost' => $gameslost, 'timeplayed' => $totaltime, 'playermostplayed' => $playermostplayed, 'competitionmostplayed' => $competitionmostplayed),
                'rankings' => $rankings,
                'delay' => $delay,
                'friends' => $friends,
            ]
        );
    }
    
    /**
     * @param int $id
     * @Route("/edit/{id}")
     */
    public function editUsersAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        
        
        
            $new = false;
        
            // raccourci pour $em->getRepository('AppBundle:User')->find($id)
            $user = $em->find('AppBundle:User', $id);
            
            // si il n'y a pas de user avec cet id en bdd,
            // on redirige vers la liste
            if (is_null($user)) {
                return $this->redirectToRoute('homepage');
            }

        $prevAvatar = $user->getAvatar();
        
        $form = $this->createForm(UserType::class, $user);
              
        $form->handleRequest($request); // le formulaire analyse la requête HTTP 
        
        if ($form->isSubmitted()) { // si le formulaire a été envoyé
            if ($form->isValid()) { // s'il n'y a pas eu d'erreur de validation du formulaire
                if (!empty($user->getPlainPassword())) {// si le mdp existe: pas besoin de le retaper pour valider le form
                    $passwordEncoder = $this->get('security.password_encoder');
                    $user->setPassword(
                        $passwordEncoder->encodePassword($user, $user->getPlainPassword())
                    );
                }
                /** @var Symfony\Component\HttpFondation\UploadedFile|null */
                $avatar = $user->getAvatar();
                
                if ($avatar instanceof UploadedFile) {
                    if (!is_string($avatar)){// évite guessExtension() error
                    // on donne un nom unique au fichier que l'on va enregistrer
                    $fileName = md5(uniqid()) . '.' . $avatar->guessExtension();
                    
                    // gère le move_uploaded_file() vers notre répertoire d'upload
                    $avatar->move(
                        $this->getParameter('upload_dir'), // répertoire destination
                        $fileName // nom du fichier dans le répertoire destination
                    );
                    
                    // on va stocker le nom du fichier en bdd pour notre User
                    $user->setAvatar($fileName);
                    }
                } else {
                    $user->setAvatar($prevAvatar);
                }
                
                $em->persist($user); //prépare l'enregistrement de l'object en bdd
                $em->flush(); // enregistre en bdd
                
                $msg = ($new)
                    ? 'L\'utilisateur a bien été créé'
                    : 'L\'utilisateur a bien été modifié'
                ;
                $this->addFlash('success', $msg);
                return $this->redirectToRoute('app_user_displayinfo', array('id' => $id));
            } else {
                // ajoute un message flash
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }            
        }
        
        return $this->render(
            'user/edit.html.twig',
            [
                'user' => $user,
                'new' => $new,
                'form' => $form->createView(),
            ]
        );
    }
//    /**
//     * @param Request $request
//     * 
//     */
//    public function whoIsOnlineAction()
//    {
//        $cons = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->getActive();
// 
//        return $this->render(
//            'default/index.html.twig',
//            [
//                'cons' => $cons,
//            ]
//        );
////        return array('cons' => $cons);
//    }
    
    /**
     * 
     * @param Request $request
     * @param int $id
     * @Route("/add/{id}")
     */
    public function addFriendAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $friend = $em->getRepository('AppBundle:User')->find($id);
        if (!$friend) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }
        
        $user = $this->getUser();
        $user->addMyFriends($friend);
        $user->setAllFriends();
        
        $em->persist($user);
        $em->flush();
            
        return $this->redirectToRoute('app_user_displayinfo', ['id' => $user->getId()]);
//        return $this->render(
//            'user/profile.html.twig',
//            [
//                'user' => $user,
//            ]
//        );
//        $user = $this->getDoctrine()
//                     ->getRepository('AppBundle:User')
//                     ->findOneById($id);
//        
//        $friends = $user->getMyFriends();
//        $names = array();
//        foreach($friends as $friend)
//        {
//            $names[] = $friend->getName();
//        }
    }
}
