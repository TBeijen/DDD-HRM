<?php
namespace Test\Domain\Entity;

use Test\BaseTestCase;
use Domain\Entity\TimeSheet;

class TimeSheetTest extends BaseTestCase
{
	/**
	 * A TimeSheet, provided with a registrant User argument, should be newable.
	 */	
	public function testIsNewable()
	{
		// get mock without calling constructor to reduce depency to constructor args
		$user = $this->getMock('Domain\Entity\User', array(), array(), '', false); 
		
		$timeSheet = new TimeSheet($user);
		$this->assertInstanceOf('Domain\Entity\TimeSheet', $timeSheet);
	}
	
	/**
	 * getRegistrant should return reference to same object as provided to constructor
	 */
	public function testGetRegistrantReturnsConstructorArgument()
	{
		// get mock without calling constructor to reduce depency to constructor args
		$user = $this->getMock('Domain\Entity\User', array(), array(), '', false); 
		
		$timeSheet = new TimeSheet($user);
		$returnedUser = $timeSheet->getRegistrant();

		$this->assertSame($user, $returnedUser);
	}
}
