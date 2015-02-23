<?php
namespace OTT\Roadmap\Module\Roadmap\Model\Helper;

/**
 * Class Client
 * @package OTT\Roadmap\Module\Roadmap\Model\Helper
 */
abstract class HelperAbstract
{

    /**
     * @param array $datas
     * @param null $element
     * @return array|null
     */
    public static function fromDalToEntity($datas = [], &$element = null)
    {
        $instance = new static();
        $result = null;
        if (is_array($datas)) {
            foreach ($datas as $data) {
                if (method_exists($instance, 'fromDalToEntitySingle')) {
                    $result[] = $instance::fromDalToEntitySingle($data, $element);
                }
            }
        }

        return $result;
    }

    /**
     * @param $array
     * @param $key
     * @return null
     */
    protected static function get($array, $key)
    {
        return isset($array[$key]) ? $array[$key] : null;
    }
} 