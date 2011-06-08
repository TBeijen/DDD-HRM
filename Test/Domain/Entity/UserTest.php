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
}