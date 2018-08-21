<?php
/**
 * Config setter (locally affected)
 * User: moyo
 * Date: 22/03/2018
 * Time: 11:16 AM
 */

namespace Carno\Config\Chips;

trait Setter
{
    /**
     * @param string $key
     * @param mixed $val
     * @return static
     */
    public function set(string $key, $val) : self
    {
        $this->reset($key, $val);
        return $this;
    }
}
