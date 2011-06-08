<?php

namespace Domain\Entity;

/**
 * @Entity(repositoryClass="Domain\Repository\TimeSheetRepository")
 */
class TimeSheet
{
    /**
     * @Id @GeneratedValue
     * @Column(type="bigint")
     * @var integer
     */
    private $id;

    /**
	 * @OneToOne(targetEntity="User")
 	 */
    private $registrant;

    /**
     * Constructor requiring a registrant user instance
     * 
     * @param User $registrant
     */
    public function __construct(User $registrant)
    {
    	$this->registrant = $registrant;	
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
     * Get registrant
     *
     * @return User $registrant
     */
    public function getRegistrant()
    {
        return $this->registrant;
    }    
}