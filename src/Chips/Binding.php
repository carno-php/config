<?php
/**
 * Options binding
 * User: moyo
 * Date: 18/10/2017
 * Time: 2:23 PM
 */

namespace Carno\Config\Chips;

use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

trait Binding
{
    /**
     * @var ReflectionProperty[]
     */
    private $references = [];

    /**
     * @var array
     */
    private $bound = [];

    /**
     * @param object $options
     * @param array $map
     * @return object|mixed
     */
    public function bind($options, array $map)
    {
        $this->unbind($options);

        $this->bindPTKeys($options, '', $map);

        return $options;
    }

    /**
     * @param object $options
     * @return bool
     */
    public function unbind($options) : bool
    {
        if ($wss = $this->bound[$oid = spl_object_hash($options)] ?? []) {
            unset($this->bound[$oid]);
            unset($this->references[$oid]);
            foreach ($wss as $wid) {
                $this->unwatch($wid);
            }
            return true;
        }
        return false;
    }

    /**
     * @param object $options
     * @param string $prefix
     * @param array $map
     */
    private function bindPTKeys($options, string $prefix, array $map) : void
    {
        $prefix && $prefix .= '/';
        foreach ($map as $cKey => $pName) {
            if (is_array($pName)) {
                $this->bindPTKeys($options, $prefix . $cKey, $pName);
            } else {
                $this->bound[spl_object_hash($options)][] =
                    $this->watching(
                        $prefix . $cKey,
                        function ($value) use ($options, $pName) {
                            $this->syncPTValue($options, $pName, $value);
                        }
                    )
                ;
            }
        }
    }

    /**
     * @param object $options
     * @param string $name
     * @param mixed $value
     * @throws ReflectionException
     */
    private function syncPTValue($options, string $name, $value) : void
    {
        $oid = spl_object_hash($options);

        /**
         * @var ReflectionProperty $property
         */

        $property =
            $this->references[$oid][$name] ?? (
                $this->references[$oid][$name] = (
                    (new ReflectionClass(get_class($options)))->getProperty($name) ?: null
                )
            )
        ;

        if ($property) {
            $property->isPublic() || $property->setAccessible(true);
            is_null($value) || $property->setValue($options, $value);
        }
    }
}
