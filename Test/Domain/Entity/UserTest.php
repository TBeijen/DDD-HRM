<?php
namespace Test\Domain\Entity;

use Test\BaseTestCase;
use Domain\Entity\User;

class UserTest extends BaseTestCase
{
	/**
	 * A User, provided with an e-mail constructor argument, should be newable
	 */	
	public function testIsNewable()
	{
		$user = new User('blackhole@universe.com');
		$this->assertInstanceOf('Domain\Entity\User', $user);
	}
	
	/**
	 * getEmail should return e-mail addreass as provided to constructor
	 */
	public function testGetEmailReturnsConstructorArgument()
	{
		$user = new User('blackhole@universe.com');
		$this->assertEquals('blackhole@universe.com', $user->getEmail());
	}
	
	/**
	 * setEmail() should set property which will be returned by getEmail()
	 */
	public function testSetEmail()
	{
		$user = new User('blackhole@universe.com');
		$user->setEmail('another@email.com');
		$this->assertEquals('another@email.com', $user->getEmail());
	}
	
	/**
	 * Persising a User should succeed and set the id property
	 */
	public function testUserCanBePersisted()
	{
		$user = new User('blackhole@universe.com');
		
		$this->em->persist($user);
		$this->em->flush();
		
		$this->assertEquals(1, $user->getId());
	}
	
	/**
	 * No two Users can be persisted having the same e-mail address.
	 */
	public function testUserEmailShouldBeUnique()
	{
		$user1 = new User('blackhole@universe.com');
		$user2 = new User('blackhole@universe.com');
		
		$this->em->persist($user1);
		$this->em->persist($user2);
		
		// Catch generic exception as storage engine cannot be assumed
		$this->setExpectedException('Exception');
		$this->em->flush();
	}
}