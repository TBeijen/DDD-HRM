<?php
namespace Test\Domain\Entity;

use Test\BaseTestCase;
use Domain\Entity\TimeSheet;
use Domain\Entity\TimeSheetStatusChange;

class TimeSheetStatusChangeTest extends BaseTestCase
{
	/**
	 * A TimeSheetStatusChange can be constructed with a valid status value
	 * 
	 * @dataProvider validStatusProvider
	 */	
	public function testValidStatusCanBePersisted($status)
	{		
		$timeSheetStatusChange = new TimeSheetStatusChange($status);
		
		$this->em->persist($timeSheetStatusChange);
        $this->em->flush();
		$this->assertEquals(1, $timeSheetStatusChange->getId());
	}
	
	/**
	 * A TimeSheetStatusChange can be constructed with a valid status value
	 * 
	 * @dataProvider inValidStatusProvider
	 */	
	public function testInValidStatusCanNotBePersisted($status)
	{		
		$timeSheetStatusChange = new TimeSheetStatusChange($status);
		
		$this->setExpectedException('InvalidArgumentException');
		$this->em->persist($timeSheetStatusChange);
        $this->em->flush();
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
	 * A new TimeSheetStatusChange instance should by default have a dateApplied
	 */
	public function testSetGetDateApplied()
	{
		$timeSheetStatusChange = new TimeSheetStatusChange('open');
		
		$date = new \DateTime();
		$timeSheetStatusChange->setDateApplied($date);	
		$retrievedDate = $timeSheetStatusChange->getDateApplied();
		
		$this->assertSame($date, $retrievedDate);
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
