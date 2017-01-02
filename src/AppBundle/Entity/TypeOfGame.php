<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * TypeOfGame
 *
 * @ORM\Table(name="type_of_game")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TypeOfGameRepository")
 */
class TypeOfGame
{
    /***** PROPERIES *****/
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $name;
    
    /**
     * 
     * @var ArrayCollection 
     * @ORM\OneToMany(targetEntity="Competition", mappedBy="type_of_game_id")
     */
    private $competitions;
    
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
    
    /***** SETTERS *****/
    public function setName($name) {
        $this->name = $name;
        return $this;
    }
}

