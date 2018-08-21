<?php
/**
 * Stores in memory
 * User: moyo
 * Date: 18/10/2017
 * Time: 3:32 PM
 */

namespace Carno\Config\Chips\Stores;

trait Memory
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @var array
     */
    private $protected = [];

    /**
     * @var array
     */
    private $replicated = [];

    /**
     * @param string $key
     * @return bool
     */
    protected function locked(string $key) : bool
    {
        return isset($this->protected[$key]);
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    protected function replica(string $key, $value) : void
    {
        if (is_null($value)) {
            unset($this->replicated[$key]);
        } else {
            $this->replicated[$key] = $value;
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param bool $replica
     */
    protected function reset(string $key, $value, bool $replica = false) : void
    {
        if (is_null($value)) {
            if (isset($this->replicated[$key])) {
                $this->data[$key] = $value = $this->replicated[$key];
            } else {
                unset($this->data[$key]);
            }
            unset($this->protected[$key]);
        } else {
            $this->data[$key] = $value;
            $replica || $this->protected[$key] = true;
        }

        $this->wkChanged($key, $value);
    }
}
