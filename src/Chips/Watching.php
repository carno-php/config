<?php
/**
 * Config watching
 * User: moyo
 * Date: 18/10/2017
 * Time: 3:30 PM
 */

namespace Carno\Config\Chips;

use Closure;
use Throwable;

trait Watching
{
    /**
     * @var array
     */
    private $observer = [];

    /**
     * @var array
     */
    private $replicator = [];

    /**
     * @var array
     */
    private $watching = [];

    /**
     * @param string $key
     * @param Closure $observer
     * @return string
     */
    public function watching(string $key, Closure $observer) : string
    {
        $wid = spl_object_id($observer);

        if ($key === '*') {
            $this->replicator[$wid] = $observer;
        } else {
            $this->observer[$key][$wid] = $observer;
            $this->watching[$wid][] = $key;
            // trigger notify immediate if value already exists
            if ($this->has($key)) {
                $this->cbPerforming($observer, $key, $this->get($key));
            }
        }

        return $wid;
    }

    /**
     * @param string $wid
     */
    public function unwatch(string $wid) : void
    {
        foreach ($this->watching[$wid] ?? [] as $key) {
            unset($this->observer[$key][$wid]);
        }
        unset($this->watching[$wid]);
        unset($this->replicator[$wid]);
    }

    /**
     * @param string $key
     * @param $new
     */
    protected function wkChanged(string $key, $new) : void
    {
        foreach ($this->observer[$key] ?? [] as $watcher) {
            $this->cbPerforming($watcher, $key, $new);
        }

        foreach ($this->replicator as $receiver) {
            $this->cbPerforming($receiver, $key, $new);
        }
    }

    /**
     * @param Closure $program
     * @param string $key
     * @param $value
     */
    private function cbPerforming(Closure $program, string $key, $value) : void
    {
        try {
            call_user_func_array($program, [$value, $key]);
        } catch (Throwable $e) {
            // do some thing
        }
    }
}
