<?php
/**
 * Config getting as types
 * User: moyo
 * Date: 2018/8/13
 * Time: 11:44 AM
 */

namespace Carno\Config\Chips;

trait Types
{
    /**
     * @param string $key
     * @return int
     */
    public function int(string $key) : int
    {
        return (int) $this->get($key);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function bool(string $key) : bool
    {
        return filter_var($this->get($key), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @param string $key
     * @return string
     */
    public function string(string $key) : string
    {
        return (string) $this->get($key);
    }
}
