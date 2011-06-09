<?php

namespace Domain\Entity;

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
    public function __construct($status)
    {
    	$this->status = $status;
    	$this->dateApplied = new \DateTime('now');
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
     * Get status
     *
     * @return string $status
     */
    public function getStatus()
    {
	    return $this->status;	
    }

    /**
     * Set dateApplied
     *
     * @param date $dateApplied
     */
    public function setDateApplied($dateApplied)
    {
        $this->dateApplied = $dateApplied;
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