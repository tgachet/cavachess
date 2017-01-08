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
    /***** PROPERTIES *****/
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
        
    /**
     * @var \datetime
     * @ORM\Column(name="date_game", type="datetime")
     * @Assert\NotBlank()
     */
    private $dategame;
    
    /**
     * @var DateTime
     * @ORM\Column(name="game_length", type="time")
     * @Assert\NotBlank()
     */
    private $gamelength;
    
    /**
     * @var DateTime
     * @ORM\Column(type="time")
     * @Assert\NotBlank()
     */
    private $gamelengthwinner;
    
    /**
     * @var DateTime
     * @ORM\Column(type="time")
     * @Assert\NotBlank()
     */
    private $gamelengthlooser;
    
    /**
     * @var int
     *
     * @ORM\Column(name="nb_plays", type="integer")
     * @Assert\NotBlank()
     */
    private $nbplays;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $nbplayswinner;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $nbplayslooser;
    
    /**
     *
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="gamewinner")
     * @ORM\JoinColumn(name="id_winner", referencedColumnName="id", nullable=false)
     */
    private $idwinner;
    
    /**
     *
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="gamelooser")
     * @ORM\JoinColumn(name="id_looser", referencedColumnName="id", nullable=false)
     */
    private $idlooser;
    
    /**
     *
     * @var Competition
     * @ORM\ManyToOne(targetEntity="Competition", inversedBy="gamefinished")
     * @ORM\JoinColumn(name="id_competition", referencedColumnName="id", nullable=false)
     */
    private $id_competition;
    
    /***** CONSTRUCT *****/
    public function __construct()
    {
        $this->dategame = new \DateTime();
    }    
    
    /***** GETTERS *****/
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

    public function getIdlooser() {
        return $this->idlooser;
    }
    
    public function getId_competition() {
        return $this->id_competition;
    }
    
    public function getGamelengthwinner() {
        return $this->gamelengthwinner;
    }

    public function getGamelengthlooser() {
        return $this->gamelengthlooser;
    }

    public function getNbplayswinner() {
        return $this->nbplayswinner;
    }

    public function getNbplayslooser() {
        return $this->nbplayslooser;
    }

        
    /***** SETTERS *****/
    public function setDategame(\DateTime $dategame) {
        $this->dategame = $dategame;
        return $this;
    }

    public function setGamelength(\DateTime $gamelength) {
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

    public function setIdlooser(User $idlooser) {
        $this->idlooser = $idlooser;
        return $this;
    }

    public function setId_competition(Competition $id_competition) {
        $this->id_competition = $id_competition;
        return $this;
    }
    public function setGamelengthwinner(\DateTime $gamelengthwinner) {
        $this->gamelengthwinner = $gamelengthwinner;
        return $this;
    }

    public function setGamelengthlooser(\DateTime $gamelengthlooser) {
        $this->gamelengthlooser = $gamelengthlooser;
        return $this;
    }

    public function setNbplayswinner($nbplayswinner) {
        $this->nbplayswinner = $nbplayswinner;
        return $this;
    }

    public function setNbplayslooser($nbplayslooser) {
        $this->nbplayslooser = $nbplayslooser;
        return $this;
    }





}

