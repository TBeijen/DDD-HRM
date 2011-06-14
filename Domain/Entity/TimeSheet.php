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
    	$this->addStatusChange(new TimeSheetStatusChange('open'));
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
    public function addStatusChange(TimeSheetStatusChange $statusChange)
    {
		$validateResult = $this->validateNextStatus($statusChange->getStatus());    	
    	if (!$validateResult) {
    		throw new \LogicException('Unallowed status change to: ' . $statusChange->getStatus());
    	}
    	
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
    
    /**
     * Validates if a TimeSheetStatusChange's $status value would be allowed
     * 
     * @param string $status
     * @return boolean
     */
    public function isValidNextStatus($status)
    {
		return $this->validateNextStatus($status);    	
    }
    
    /**
     * Validates if the given TimeSheetStatusChange is allowed considering 
     * the last status change
     * 
     * @param \Domain\Entity\TimeSheetStatusChange $statusChange
     * @return boolean
     */
    public function isValidNextStatusChange(TimeSheetStatusChange $statusChange)
    {
		return $this->validateNextStatus($statusChange->getStatus());    	
    }
    
    /**
     * Performs status change validation logic
     * 
     * @param string $statusChange
     * @return boolean
     */
    protected function validateNextStatus($nextStatus)
    {
    	// make exception for initial adding of open status
    	if ($nextStatus === 'open' && count($this->statusChanges) === 0) {
    		return true;
    	}
    	
    	// validate status changes map
    	$allowedChangeMap = array(
    		'open' => array('submitted'),
    		'submitted' => array('approved', 'disapproved'),
    		'approved' => array('final', 'disapproved'),
    		'disapproved' => array('submitted', 'approved'),
    		'final' => array(),
    	);

    	$currentStatus = $this->getStatus();
    	if (in_array($nextStatus, $allowedChangeMap[$currentStatus], true)) {
    		return true;
    	}
    	
    	return false;
    }
}