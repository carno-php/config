<?php
/**
 * Watching test
 * User: moyo
 * Date: 2018/8/13
 * Time: 11:54 AM
 */

namespace Carno\Config\Tests\Features;

use Carno\Config\Config;
use PHPUnit\Framework\TestCase;

class WatchingTest extends TestCase
{
    public function testWatching()
    {
        $conf = new Config;

        $val = null;

        $w = $conf->watching('key', function ($set) use (&$val) {
            $val = $set;
        });

        $this->assertNull($val);

        $conf->set('key', 123);
        $this->assertEquals(123, $val);

        $conf->set('key', 'abc');
        $this->assertEquals('abc', $val);

        $conf->set('key', true);
        $this->assertTrue($val);

        $conf->set('key', false);
        $this->assertFalse($val);

        $conf->set('key', 'final');
        $this->assertEquals('final', $val);

        $conf->unwatch($w);

        $conf->set('key', 'change');
        $this->assertEquals('final', $val);
        $this->assertEquals('change', $conf->get('key'));
    }
}
