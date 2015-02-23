<?php
namespace OTT\Roadmap\Module\Roadmap\Model\Entity;

/**
 * Class EntityAbstract
 * @package OTT\Roadmap\Module\Roadmap\Model\Entity
 */
abstract class EntityAbstract
{
    /**
     * @param $date
     * @return \DateTime|null
     */
    protected function setDate($date)
    {
        $result = null;
        if ($date instanceof \DateTime) {
            $result = $date;
        } elseif (null !== $date) {
            $result = new \DateTime($date);
        }
        return $result;
    }
}