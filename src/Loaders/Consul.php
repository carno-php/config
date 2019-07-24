<?php
/**
 * Loading values from consul-kv
 * User: moyo
 * Date: 16/10/2017
 * Time: 2:52 PM
 */

namespace Carno\Config\Loaders;

use Carno\Channel\Chan;
use Carno\Channel\Channel;
use Carno\Channel\Worker;
use Carno\Config\Config;
use Carno\Consul\KVStore;
use Carno\Consul\Types\Agent;
use Carno\Promise\Promise;
use Carno\Promise\Promised;

/**
 * @codeCoverageIgnore
 */
class Consul
{
    /**
     * @var string
     */
    private const KEYS = '*';

    /**
     * @var Chan
     */
    private $chan = null;

    /**
     * @var Agent
     */
    private $agent = null;

    /**
     * @var Config
     */
    private $config = null;

    /**
     * @var string
     */
    private $prefix = null;

    /**
     * Consul constructor.
     * @param Agent $agent
     * @param Config $source
     * @param string $prefix
     */
    public function __construct(Agent $agent, Config $source, string $prefix = 'conf')
    {
        $this->agent = $agent;
        $this->config = $source;
        $this->prefix = $prefix;
    }

    /**
     * @return Promised
     */
    public function connect() : Promised
    {
        $dir = $this->folder();

        (new KVStore($this->agent))->watching($dir, self::KEYS, $this->chan = new Channel());

        ($await = Promise::deferred())->then(static function () use ($dir) {
            logger('config')->info('Config watcher is connected', ['dir' => $dir]);
        });

        new Worker($this->chan, function (array $changes) use ($await) {
            $await->pended() && $await->resolve();
            foreach ($changes as $key => $value) {
                $this->config->set($key, $value);
            }
        });

        return $await;
    }

    /**
     * @return Promised
     */
    public function disconnect() : Promised
    {
        $dir = $this->folder();

        $this->chan->close();

        ($wait = $this->chan->closed())->then(static function () use ($dir) {
            logger('config')->info('Config watcher is closed', ['dir' => $dir]);
        });

        return $wait;
    }

    /**
     * @return string
     */
    private function folder() : string
    {
        if (empty($this->config->scoped())) {
            return $this->prefix;
        } else {
            return sprintf('%s/%s', $this->prefix, $this->config->scoped());
        }
    }
}
