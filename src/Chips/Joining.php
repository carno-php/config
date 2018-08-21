<?php
/**
 * Config joining (merged from upstream)
 * User: moyo
 * Date: 19/10/2017
 * Time: 4:06 PM
 */

namespace Carno\Config\Chips;

use Carno\Config\Config;
use Closure;

trait Joining
{
    /**
     * @param Config $upstream
     */
    public function joining(Config $upstream) : void
    {
        $upstream->jFollow(function (...$args) {
            $this->jWatched(...$args);
        });
    }

    /**
     * @param Closure $observer
     */
    private function jFollow(Closure $observer) : void
    {
        $this->watching('*', $observer);
    }

    /**
     * @param mixed $value
     * @param string $key
     */
    private function jWatched($value, string $key) : void
    {
        // REPLICA log
        $this->replica($key, $value);
        // NEVER reset existed value
        $this->locked($key) || $this->reset($key, $value, true);
    }
}
