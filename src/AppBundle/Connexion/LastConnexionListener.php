<?php

namespace AppBundle\Connexion;
namespace AppBundle\Connexion;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Description of LastConnexionListener
 *
 * @author Etudiant
 */
class LastConnexionListener
{
    // Notre processeur
    protected $betaHTML;

    // La date de fin de la version bêta :
    // - Avant cette date, on affichera un compte à rebours (J-3 par exemple)
    // - Après cette date, on n'affichera plus le « bêta »
    protected $endDate;

    public function __construct(LastConnexion $betaHTML, $endDate)
    {
        $this->betaHTML = $betaHTML;
        $this->endDate  = new \Datetime($endDate);
    }

    public function processBeta(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest())
        {
            return;
        }
        
        $remainingDays = $this->endDate->diff(new \Datetime())->format('%d');

        if ($remainingDays <= 0) {
            // Si la date est dépassée, on ne fait rien
            return;
        }

        // Ici on appelera la méthode
        // $this->betaHTML->displayBeta()
         // On utilise notre BetaHRML
        $response = $this->betaHTML->displayBeta($event->getResponse(), $remainingDays);
        // On met à jour la réponse avec la nouvelle valeur
        $event->setResponse($response);
    }
}
