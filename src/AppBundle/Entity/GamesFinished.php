<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * GamesFinished
 *
 * @ORM\Table(name="games_finished")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GamesFinishedRepository")
 */
class GamesFinished
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
        
    /**
     * @var datetime
     * @ORM\Column(name="date_game", type="datetime")
     * @Assert\NotBlank()
     */
    private $dategame;
    
    /**
     * @var time
     * @ORM\Column(name="game_length", type="time")
     * @Assert\NotBlank()
     */
    private $gamelength;
    
    /**
     * @var int
     *
     * @ORM\Column(name="nb_plays", type="integer")
     * @Assert\NotBlank()
     */
    private $nbplays;
    
    /**
     *
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="competition_winner")
     * @ORM\JoinColumn(name="id_winner", referencedColumnName="id", nullable=false)
     */
    private $idwinner;
    
    /**
     *
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="competition_looser")
     * @ORM\JoinColumn(name="id_looser", referencedColumnName="id", nullable=false)
     */
    private $idloser;
    
    /**
     *
     * @var Competition
     * @ORM\ManyToOne(targetEntity="Competition", inversedBy="gamefinished")
     * @ORM\JoinColumn(name="id_competition", referencedColumnName="id", nullable=false)
     */
    private $id_competition;
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function getDategame() {
        return $this->dategame;
    }

    public function getGamelength() {
        return $this->gamelength;
    }

    public function getNbplays() {
        return $this->nbplays;
    }

    public function getIdwinner() {
        return $this->idwinner;
    }

    public function getIdloser() {
        return $this->idloser;
    }

    public function setDategame(datetime $dategame) {
        $this->dategame = $dategame;
        return $this;
    }

    public function setGamelength(time $gamelength) {
        $this->gamelength = $gamelength;
        return $this;
    }

    public function setNbplays($nbplays) {
        $this->nbplays = $nbplays;
        return $this;
    }

    public function setIdwinner(User $idwinner) {
        $this->idwinner = $idwinner;
        return $this;
    }

    public function setIdloser(User $idloser) {
        $this->idloser = $idloser;
        return $this;
    }


}

