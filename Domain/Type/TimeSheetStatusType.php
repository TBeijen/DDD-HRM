<?php

namespace Domain\Type;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class TimeSheetStatusType extends Type
{
    const TIMESHEET_STATUS = 'timeSheetStatusType';
    const STATUS_OPEN = 'open';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_APPROVED = 'approved';
    const STATUS_DISAPPROVED = 'disapproved';
    const STATUS_FINAL = 'final';
    
    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
    	return $platform->getVarcharTypeDeclarationSQL(array('length' => 32));
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!self::isValid($value)) {
            throw new \InvalidArgumentException('Invalid status: ' . $value);
        }
        return $value;
    }

    public function getName()
    {
        return self::TIMESHEET_STATUS;
    }
    
    /**
     * Validate if $value is one of the allowed status values
     * 
     * @param mixed $value
     * @return boolean
     */
    public static function isValid($value)
    {
        if (!in_array($value, array(
        	self::STATUS_OPEN, 
        	self::STATUS_SUBMITTED,
        	self::STATUS_APPROVED, 
        	self::STATUS_DISAPPROVED, 
        	self::STATUS_FINAL
        ), true)) {
        	return false;
        }
        return true;
    }
}