<?php

namespace Domain\Entity;

/**
 * @Entity(repositoryClass="Domain\Repository\TimeSheetRepository")
 */
class TimeSheet
{
    /**
     * @Id @GeneratedValue
     * @Column(type="bigint")
     * @var integer
     */
    private $id;

    /**
     * Get id
     *
     * @return bigint $id
     */
    public function getId()
    {
        return $this->id;
    }
}