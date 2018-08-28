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
    private const PREFIX = 'service/conf';

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
     * Consul constructor.
     * @param Agent $agent
     * @param Config $source
     */
    public function __construct(Agent $agent, Config $source)
    {
        $this->agent = $agent;
        $this->config = $source;
    }

    /**
     * @return Promised
     */
    public function connect() : Promised
    {
        (new KVStore($this->agent))
            ->watching(
                sprintf('%s/%s', self::PREFIX, $this->config->scoped()),
                self::KEYS,
                $this->chan = new Channel
            )
        ;

        ($await = Promise::deferred())->then(function () {
            logger('config')->info('Config watcher is connected', ['dir' => $this->config->scoped()]);
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
        $this->chan->close();

        ($wait = $this->chan->closed())->then(function () {
            logger('config')->info('Config watcher is closed', ['dir' => $this->config->scoped()]);
        });

        return $wait;
    }
}
