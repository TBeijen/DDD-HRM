<?php
namespace Test;
use Doctrine\ORM\Configuration,
    Doctrine\ORM\EntityManager,
    Doctrine\ORM\Tools\SchemaTool;

/**
 * Base TestCase
 * 
 * Creates and stores entity manager in $this->em in setUp 
 */
abstract class BaseTestCase extends \PHPUnit_Framework_TestCase
{
	/**
	 * Connection options
	 * 
	 * @var array
	 */
    private static $connectionOptions;
    
    /**
     * Doctrine config
     * 
     * @var \Doctrine\ORM\Configuration
     */
    private static $config;
    
    /**
     * Entity Manager instance
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * Stores static config/connection details used to create entity manager
     * 
     * @param array $connectionOptions
     * @param \Doctrine\ORM\Configuration $config
     */
    public static function setConfiguration(array $connectionOptions, \Doctrine\ORM\Configuration $config)
    {
        self::$connectionOptions = $connectionOptions;
        self::$config = $config;
    }

    /**
     * Sets up entity manager and creates new database
     */
    public function setUp()
    {
        $this->em = EntityManager::create(self::$connectionOptions, self::$config);
        $tool = new SchemaTool($this->em);
        $classes = array(
            $this->em->getClassMetadata('Domain\Entity\TimeSheet'),
        );
        $tool->createSchema($classes);
    }
}
