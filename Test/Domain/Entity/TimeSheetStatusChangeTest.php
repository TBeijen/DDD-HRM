<?php
namespace Test\Domain\Entity;

use Test\BaseTestCase;
use Domain\Entity\TimeSheet;
use Domain\Entity\TimeSheetStatusChange;
use Domain\Entity\User;

class TimeSheetStatusChangeTest extends BaseTestCase
{
	/**
	 * A TimeSheetStatusChange can be constructed with a valid status value
	 * 
	 * @dataProvider validStatusProvider
	 */	
	public function testConstructWithValidStatus($status)
	{		
		$timeSheetStatusChange = new TimeSheetStatusChange($status);
		$this->assertInstanceOf('Domain\Entity\TimeSheetStatusChange', $timeSheetStatusChange);
	}
	
	/**
	 * Attempting to construct a TimeSheetStatusChange with a non-existing 
	 * status value should throw an exception
	 * 
	 * @dataProvider inValidStatusProvider
	 */	
	public function testConstructWithInvalidStatusThrowsException($status)
	{		
		$this->setExpectedException('InvalidArgumentException');
		$timeSheetStatusChange = new TimeSheetStatusChange($status);
	}
	
	/**
	 * A new TimeSheetStatusChange instance should by default have a dateApplied
	 */
	public function testNewInstanceHasDateApplied()
	{
		$timeSheetStatusChange = new TimeSheetStatusChange('open');
		$dateApplied = $timeSheetStatusChange->getDateApplied();	
	
		$this->assertInstanceOf('\DateTime', $dateApplied);
	}
		
	/**
	 * It should be possible to set a TimeSheet if the TimeSheet
	 * has the timeSheetStatusChange as it's current status change.
	 */
	public function testSetTimeSheetIfBeingCurrentStatusChange()
	{
		$user = new User('some@email.com');
		$timeSheet = new TimeSheet($user);
		
		$timeSheetStatusChange = $timeSheet->getCurrentStatusChange();
		$timeSheetStatusChange->setTimeSheet($timeSheet);
		
		$this->assertSame($timeSheet, $timeSheetStatusChange->getTimeSheet());
	}

	/**
	 * It should not be possible to set a timeSheet to a timeSheetStatusChange if
	 * it doesn't already contain the timeSheetStatusChange as the most recent 
	 * timeSheetStatusChange
	 */
	public function testSetTimeSheetIfNotBeingCurrentStatusChangeThrowsException()
	{
		$user = new User('some@email.com');
		$timeSheet = new TimeSheet($user);
		
		$timeSheetStatusChange = new TimeSheetStatusChange('open');
		$this->setExpectedException('InvalidArgumentException');
		$timeSheetStatusChange->setTimeSheet($timeSheet);
	}

	/**
	 * A TimeSheetStatusChange should not be able to be persisted if it has 
	 * no reference to a TimeSheet.
	 */
	public function testCannotBePersistedWithoutTimeSheet()
	{
		$timeSheetStatusChange = new TimeSheetStatusChange('open');
		
		$this->em->persist($timeSheetStatusChange);
		
		// Catch generic exception as storage engine cannot be assumed
		$this->setExpectedException('Exception');
		$this->em->flush();
	}
	
	/**
	 * Provides status values that can be persisted
	 * 
	 * @see \Domain\Type\TimeSheetStatusType
	 */
	public static function validStatusProvider()
	{
		return array(
			array('open'),
			array('submitted'),
			array('approved'),
			array('disapproved'),
			array('final'),
		);
	}

	/**
	 * Provides status values that can not be persisted
	 * 
	 * @see \Domain\Type\TimeSheetStatusType
	 */
	public static function inValidStatusProvider()
	{
		return array(
			array(''),
			array(null),
			array(false),
			array(true),
			array('somethingwrong'),
			array(array('open')),
		);
	}
}
