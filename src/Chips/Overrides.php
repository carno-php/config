<?php
/**
 * Config watch overrides (multi-key)
 * User: moyo
 * Date: 2018/8/20
 * Time: 5:55 PM
 */

namespace Carno\Config\Chips;

use Carno\Config\Features\Overrider;
use Closure;

trait Overrides
{
    /**
     * @param Closure $observer
     * @param string ...$keys
     * @return Overrider
     */
    public function overrides(Closure $observer, string ...$keys) : Overrider
    {
        return new Overrider($this, $observer, $keys);
    }
}
