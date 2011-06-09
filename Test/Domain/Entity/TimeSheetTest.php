<?php
namespace Test\Domain\Entity;

use Test\BaseTestCase;
use Domain\Entity\TimeSheet;

class TimeSheetTest extends BaseTestCase
{
	/**
	 * @var \Domain\Entity\User
	 */
	protected $mockUser;
	
	/**
	 * Sets up mock user
	 */
	public function setUp()
	{
		// get mock without calling constructor to reduce depency to constructor args
		$this->mockUser = $this->getMock('Domain\Entity\User', array(), array(), '', false); 
	}
	
	/**
	 * A TimeSheet, provided with a registrant User argument, should be newable.
	 */	
	public function testIsNewable()
	{
		$timeSheet = new TimeSheet($this->mockUser);
		$this->assertInstanceOf('Domain\Entity\TimeSheet', $timeSheet);
	}
	
	/**
	 * getRegistrant should return reference to same object as provided to constructor
	 */
	public function testGetRegistrantReturnsConstructorArgument()
	{
		$timeSheet = new TimeSheet($this->mockUser);
		$returnedUser = $timeSheet->getRegistrant();

		$this->assertSame($this->mockUser, $returnedUser);
	}
	
	/**
	 * A new timesheet should by default have one statuschange representing the
	 * 'open' status
	 */
	public function testNewTimeSheetHasStatusChangeOpen()
	{
		$timeSheet = new TimeSheet($this->mockUser);
		$statusChanges = $timeSheet->getStatusChanges();
		
		$this->assertEquals(1, count($statusChanges));
		$this->assertEquals('open', $statusChanges[0]->getStatus());
	}
}
