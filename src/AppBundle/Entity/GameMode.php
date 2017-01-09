<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * GameMode
 *
 * @ORM\Table(name="game_mode")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameModeRepository")
 */
class GameMode
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
     *
     * @var string
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $name;
   
    /**
     *
     * @var arrayCollection 
     * @ORM\OneToMany(targetEntity="Competition", mappedBy="gamemode")
     */
    private $competitions;
    
    /**
     *
     * @var DateTime
     * @ORM\Column(type="time")
     * @Assert\NotBlank()
     */
    private $gametime;
    
    /**
     * 
     * @var string
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $description;
    
    /***** CONSTRUCT *****/
    public function __construct()
    {
        $this->competitions = new ArrayCollection();
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
    
    public function getName() {
        return $this->name;
    }

    public function getCompetitions() {
        return $this->competitions;
    }

    public function getGametime() {
        return $this->gametime;
    }
    
    public function getDescription() {
        return $this->description;
    }



    /***** SETTERS *****/
    public function setName($name) {
        $this->name = $name;
        return $this;
    }
    
    public function setGametime(DateTime $gametime) {
        $this->gametime = $gametime;
        return $this;
    }
    
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

        /***** OTHER *****/
    /**
     * @return string
     * Retourne le nom en chaine de caractère
     */
    public function __toString()
    {
        return $this->name;
    }
}

