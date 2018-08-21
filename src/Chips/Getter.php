<?php
/**
 * Config getter
 * User: moyo
 * Date: 18/10/2017
 * Time: 2:28 PM
 */

namespace Carno\Config\Chips;

trait Getter
{
    use Types;

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key) : bool
    {
        return isset($this->data[$key]);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->data[$key] ?? null;
    }
}
