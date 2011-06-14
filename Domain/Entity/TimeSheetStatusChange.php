<?php

namespace Domain\Entity;

use Domain\Type\TimeSheetStatusType;

/**
 * @Entity(repositoryClass="Domain\Repository\TimeSheetStatusChangeRepository")
 */
class TimeSheetStatusChange
{
    /**
     * @Id 
     * @GeneratedValue
     * @Column(type="bigint")
     * @var integer
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="Domain\Entity\TimeSheet", inversedBy="statusChanges")
     * @JoinColumn(name="timesheet_id", referencedColumnName="id", nullable=false)
     */
    private $timeSheet;

    /** 
     * @Column(type="timeSheetStatusType") 
     */
    private $status;
    
    /**
     * @Column(type="date") 
     */
    private $dateApplied;
    
    /**
     * Constructor requiring a timesheet instance
     * 
     * @param TimeSheet $timeSheet
     */
    public function __construct($status, \DateTime $dateApplied = null)
    {
    	if (!TimeSheetStatusType::isValid($status)) {
    		throw new \InvalidArgumentException('Invalid status: ' . $status);
    	}
    	$this->status = $status;
    	
    	if (!is_null($dateApplied)) {
	    	$this->dateApplied = $dateApplied;
    	} else {
	    	$this->dateApplied = new \DateTime('now');
    	}    	
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
     * Get timeSheet
     *
     * @return TimeSheet $timeSheet
     */
    public function getTimeSheet()
    {
        return $this->timeSheet;
    }
    
    /**
     * Sets the timeSheet.
     * 
     * Purpose is to have the reference to the timeSheet set when adding a 
     * new TimeSheetStatusChange to a TimeSheet. Therefore this method validates
     * if the timeSheet has the this instance as the last statusChange.
     * 
     * @param TimeSheet $timeSheet
     */
    public function setTimeSheet(TimeSheet $timeSheet)
    {
    	if ($timeSheet->getLastStatusChange() !== $this) {
    		throw new \InvalidArgumentException('Cannot set TimeSheet if not having current instance as currentStatusChange');
    	}
    	$this->timeSheet = $timeSheet;
    }
    
    /**
     * Get status
     *
     * @return string $status
     */
    public function getStatus()
    {
	    return $this->status;	
    }

    /**
     * Get dateApplied
     *
     * @return date $dateApplied
     */
    public function getDateApplied()
    {
        return $this->dateApplied;
    }
}