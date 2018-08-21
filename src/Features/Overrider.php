<?php
/**
 * Overrider of multi-key watching
 * User: moyo
 * Date: 2018/8/20
 * Time: 5:27 PM
 */

namespace Carno\Config\Features;

use Carno\Config\Config;
use Closure;

class Overrider
{
    /**
     * @var array
     */
    private $ordered = [];

    /**
     * @var array
     */
    private $watches = [];

    /**
     * @var int
     */
    private $priority = 0;

    /**
     * @var Closure
     */
    private $observer = null;

    /**
     * @var Config
     */
    private $source = null;

    /**
     * Overrider constructor.
     * @param Config $source
     * @param Closure $observer
     * @param array $keys
     */
    public function __construct(Config $source, Closure $observer, array $keys)
    {
        $this->source = $source;

        $this->observer = function ($value, string $key) use ($observer) {
            $priority = $this->ordered[$key];
            if ($priority >= $this->priority) {
                $this->priority = $priority;
                $observer($value, $key);
            }
        };

        foreach ($keys as $priority => $key) {
            $this->ordered[$key] = $priority;
            $this->watches[$priority] = $this->source->watching($key, $this->observer);
        }
    }

    /**
     */
    public function unwatch() : void
    {
        foreach ($this->watches as $wid) {
            $this->source->unwatch($wid);
        }
        unset($this->source);
        unset($this->observer);
    }
}
