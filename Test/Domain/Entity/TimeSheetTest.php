<?php
namespace Test\Domain\Entity;

use Test\BaseTestCase;
use Domain\Entity\User;
use Domain\Entity\TimeSheet;
use Domain\Entity\TimeSheetStatusChange;

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
		parent::setUp();
		
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
	public function testGetRegistrant()
	{
		$timeSheet = new TimeSheet($this->mockUser);
		$this->assertSame($this->mockUser, $timeSheet->getRegistrant());
	}

	/**
	 * getStatus should return the status property of the most recent statusChange
	 */
	public function testGetStatus()
	{
		$timeSheet = new TimeSheet($this->mockUser);
		
		$this->assertEquals('open', $timeSheet->getStatus());
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
	
	/**
	 * A new timesheet should by default have a statuschange representing the
	 * 'open' status, which should be returned by getLastStatusChange()
	 */
	public function testNewTimeSheetGetLastStatusChange()
	{
		$timeSheet = new TimeSheet($this->mockUser);
		$lastStatusChange = $timeSheet->getLastStatusChange();
		
		$this->assertEquals('open', $lastStatusChange->getStatus());
	}
	
	/**
	 * A new TimeSheet can be persisted (provided the user has already been persisted)
	 */
	public function testNewTimeSheetCanBePersisted()
	{
		$user = new User('some@email.com');
		$timeSheet = new TimeSheet($user);

		$this->em->persist($user);
		$this->em->persist($timeSheet);
		$this->em->flush();
		
		$this->assertEquals(1, $timeSheet->getId());
	}
	
	/**
	 * Adding a statusChange should return in an additional statusChange being 
	 * returned by getStatusChanges()
	 */
	public function testAddStatusChange()
	{
		$user = new User('some@email.com');
		$timeSheet = new TimeSheet($user);
		$this->assertEquals(1, count($timeSheet->getStatusChanges()));
		
		$timeSheet->addStatusChange(new TimeSheetStatusChange('submitted'));
		$this->assertEquals(2, count($timeSheet->getStatusChanges()));
	}

	/**
	 * statusChanges should only be allowed in a specific order.
	 * 
	 * @dataProvider validStatusChangeProvider
	 * @param string $status
	 * @param array $prepare
	 */
	public function testAddingValidStatusChangeSucceeds($status, $prepare)
	{
		$user = new User('some@email.com');
		$timeSheet = new TimeSheet($user);
		
		// add prepare statusChanges to construct test state
		foreach($prepare as $prepareStatus) {
			$prepareStatusChange = new TimeSheetStatusChange($prepareStatus);
			$timeSheet->addStatusChange($prepareStatusChange);
		}
		
		// add the tested statusChange
		$statusChange = new TimeSheetStatusChange($status);
		$timeSheet->addStatusChange($statusChange);
		
		$this->assertEquals($status, $timeSheet->getStatus());
	}
	
	/**
	 * Adding an invalid statusChange should throw an exception.
	 * 
	 * @dataProvider invalidStatusChangeProvider
	 * @param string $status
	 * @param array $prepare
	 */
	public function testAddingInvalidStatusChangeThrowsException($status, $prepare)
	{
		$user = new User('some@email.com');
		$timeSheet = new TimeSheet($user);
		
		// add prepare statusChanges to construct test state
		foreach($prepare as $prepareStatus) {
			$prepareStatusChange = new TimeSheetStatusChange($prepareStatus);
			$timeSheet->addStatusChange($prepareStatusChange);
		}
		
		// add the tested statusChange
		$statusChange = new TimeSheetStatusChange($status);		
		$this->setExpectedException('LogicException');
		$timeSheet->addStatusChange($statusChange);
	}
	
	/**
	 * Persisting a timesheet should propagate to the statusChanges contained.
	 */
	public function testPersistShouldPropagateToStatusChanges()
	{
		// create TimeSheet and add additional statusChange
		$user = new User('some@email.com');
		$timeSheet = new TimeSheet($user);
		$timeSheet->addStatusChange(new TimeSheetStatusChange('submitted'));
		
		$this->em->persist($user);
		$this->em->persist($timeSheet);
		$this->em->flush();
		
		// clear and reload
		$this->em->clear();
		$reloadedTimeSheet = $this->em->find('Domain\Entity\TimeSheet', $timeSheet->getId());

		// test properties of reloaded TimeSheet
		$this->assertEquals(2, count($reloadedTimeSheet->getStatusChanges()));		
		$this->assertEquals('submitted', $reloadedTimeSheet->getStatus());		
	}
	
	/**
	 * Provides statuses and sequences to construct a state where 
	 * that allows adding the new statusChange
	 */
	public static function validStatusChangeProvider()
	{
		return array(
			// allowed status changes given status open		
			array('submitted', array()),
			
			// allowed status changes given status submitted
			array('approved', array('submitted')),
			array('disapproved', array('submitted')),
			
			// allowed status changes given status approved
			array('disapproved', array('submitted', 'approved')),
			array('final', array('submitted', 'approved')),
			
			// allowed status changes given status disapproved
			array('submitted', array('submitted', 'disapproved')),
			array('approved', array('submitted', 'disapproved')),
		);
	}

	/**
	 * Provides statuses and sequences to construct a state where 
	 * disallowed statusChanges can be tested
	 */
	public static function invalidStatusChangeProvider()
	{
		return array(
			// disallowed status changes given status open
			array('open', array()),
			array('approved', array()),
			array('disapproved', array()),
			array('final', array()),
			
			// disallowed status changes given status submitted
			array('open', array('submitted')),
			array('final', array('submitted')),
			
			// disallowed status changes given status approved
			array('open', array('submitted', 'approved')),
			array('submitted', array('submitted', 'approved')),
			array('approved', array('submitted', 'approved')),
			
			// disallowed status changes given status disapproved
			array('open', array('submitted', 'disapproved')),
			array('final', array('submitted', 'disapproved')),
			array('disapproved', array('submitted', 'disapproved')),
						
			// disallowed status changes given status final
			array('open', array('submitted', 'approved', 'final')),
			array('submitted', array('submitted', 'approved', 'final')),
			array('approved', array('submitted', 'approved', 'final')),
			array('disapproved', array('submitted', 'approved', 'final')),
			array('final', array('submitted', 'approved', 'final')),
		);
	}
}
