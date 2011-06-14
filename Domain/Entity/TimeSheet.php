<?php

namespace Domain\Entity;

/**
 * Timesheet 
 * 
 * Has a registrant, contains registrations, has a history of status changes of 
 * which the most recent one is the current status.
 * 
 * @Entity(repositoryClass="Domain\Repository\TimeSheetRepository")
 */
use Doctrine\Common\Collections\ArrayCollection;

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
     * @OneToMany(targetEntity="Domain\Entity\TimeSheetStatusChange", mappedBy="timeSheet", cascade={"persist"}, orphanRemoval=true)
     * @OrderBy({"dateApplied" = "ASC", "id" = "ASC"})
     */
    private $statusChanges;
    
    /**
     * Constructor requiring a registrant user instance
     * 
     * @param User $registrant
     */
    public function __construct(User $registrant)
    {
    	$this->registrant = $registrant;
    	
    	$this->statusChanges = new ArrayCollection();
    	$this->addStatusChange(new \Domain\Entity\TimeSheetStatusChange('open'));
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

    /**
     * Adds a new statusChange
     * 
     * @todo validate order of statusChanges
     * @param \Domain\Entity\TimeSheetStatusChange $statusChange
     */
    public function addStatusChange(\Domain\Entity\TimeSheetStatusChange $statusChange)
    {
    	$this->statusChanges[] = $statusChange;
    	
    	$statusChange->setTimeSheet($this);
    }
    
    /**
     * Get statusChanges
     *
     * @return Doctrine\Common\Collections\Collection $statusChanges
     */
    public function getStatusChanges()
    {
        return $this->statusChanges;
    }
    
    /**
     * Returns the most recent status change
     * 
     * @return Domain\Entity\TimeSheetStatusChange $statusChange
     */
    public function getLastStatusChange()
    {
    	return $this->statusChanges[(count($this->statusChanges) -1)];
    }
    
    /**
     * Returns string representation of the last (=current) status
     * 
     * @return string
     */
    public function getStatus()
    {
    	return $this->getLastStatusChange()->getStatus();
    }
}