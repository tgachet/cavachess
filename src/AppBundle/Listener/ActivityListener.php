<?php

namespace AppBundle\Listener;

use AppBundle\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Description of ActivityListener
 *
 * @author SAB
 */
class ActivityListener
{
    protected $context;
    protected $em;
 
    public function __construct(TokenStorage $context, EntityManager $manager)
    {
        $this->context = $context;
        $this->em = $manager;
    }
 
    /**
    * Update the user "lastActivity" on each request
    * @param FilterControllerEvent $event
    */
    public function onCoreController(FilterControllerEvent $event)
    {
        // ici nous vérifions que la requête est une "MASTER_REQUEST" pour que les sous-requête soit ingoré (par exemple si vous faites un render() dans votre template)
        if ($event->getRequestType() !== HttpKernel::MASTER_REQUEST) {
            return;
        }
 
        // Nous vérifions qu'un token d'autentification est bien présent avant d'essayer manipuler l'utilisateur courant.
        if ($this->context->getToken()) {
            $user = $this->context->getToken()->getUser();
 
            // Nous utilisons un délais pendant lequel nous considèrerons que l'utilisateur est toujours actif et qu'il n'est pas nécessaire de refaire de mise à jour
            $delay = new DateTime();
            $delay->setTimestamp(strtotime('2 minutes ago'));
 
            // Nous vérifions que l'utilisateur est bien du bon type pour ne pas appeler getLastActivity() sur un objet autre objet User
            if ($user instanceof User && $user->getLastActivity() < $delay) {
                $user->isActiveNow();
                $this->em->flush($user);
            }
        }
    }
}