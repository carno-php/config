<?php
/**
 * Overrider test
 * User: moyo
 * Date: 24/03/2018
 * Time: 10:55 AM
 */

namespace Carno\Config\Tests\Features;

use Carno\Config\Config;
use Carno\Config\Tests\Chips\GCAssert;
use PHPUnit\Framework\TestCase;

class OverriderTest extends TestCase
{
    use GCAssert;

    public function testWatching()
    {
        $conf = new Config;

        $last = 0;

        $overrider = $conf->overrides(function (string $v) use (&$last) {
            $last = $v;
        }, 'a', 'a.b', 'a.b.c', 'a.b.c.d');

        $this->assertEquals(0, $last);

        $conf->set('a', 1);
        $this->assertEquals(1, $last);

        $conf->set('a.b.c', 3);
        $this->assertEquals(3, $last);

        $conf->set('a.b', 2);
        $this->assertEquals(3, $last);

        $conf->set('a.b.c.d', 4);
        $this->assertEquals(4, $last);

        $conf->set('a', 1);
        $this->assertEquals(4, $last);

        $overrider->unwatch();

        $this->assertNoGC();
    }
}
