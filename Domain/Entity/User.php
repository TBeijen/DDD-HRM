<?php

namespace Domain\Entity;

/**
 * @Entity(repositoryClass="Domain\Repository\UserRepository")
 */
class User
{
    /**
     * @Id @GeneratedValue
     * @Column(type="bigint")
     * @var integer
     */
    private $id;

    /**
     * @todo add framework-specific e-mail validator (in setter and constructor)
     * @Column(type="string", length=128, unique=true)
     * @var string
     */
    private $email;
    
    /**
     * Constructor requiring email attribute
     * 
     * @param string $email
     */
    public function __construct($email)
    {
    	$this->email = $email;
    }
    
    /**
     * Get id
     *
     * @return bigint $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }
}