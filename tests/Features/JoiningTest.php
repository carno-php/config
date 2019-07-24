<?php
/**
 * Joining test
 * User: moyo
 * Date: 24/03/2018
 * Time: 3:37 PM
 */

namespace Carno\Config\Tests\Features;

use Carno\Config\Config;
use Carno\Config\Tests\Chips\GCAssert;
use PHPUnit\Framework\TestCase;

class JoiningTest extends TestCase
{
    use GCAssert;

    public function testFollow()
    {
        $master = new Config();
        $slaver = new Config();

        $slaver->joining($master);

        $master->set('t1', 222);

        $this->assertEquals(222, $master->get('t1'));
        $this->assertEquals(222, $slaver->get('t1'));

        $master->set('t1', 333);

        $this->assertEquals(333, $slaver->get('t1'));

        $slaver->set('t2', 111);

        $this->assertEquals(111, $slaver->get('t2'));
        $this->assertNull($master->get('t2'));

        $slaver->set('t1', 555);
        $master->set('t1', 666);

        $this->assertEquals(555, $slaver->get('t1'));
        $this->assertEquals(666, $master->get('t1'));

        $slaver->set('t1', null);

        // inherit from master
        $this->assertEquals(666, $slaver->get('t1'));

        $slaver->set('t1', 777);

        $this->assertEquals(777, $slaver->get('t1'));

        // reconfigure slaver following master
        $slaver->set('t1', null);
        $master->set('t1', 888);
        $this->assertEquals(888, $slaver->get('t1'));
        $this->assertEquals(888, $master->get('t1'));

        $master->set('t1', null);

        // syncing from master to salver
        $this->assertNull($slaver->get('t1'));
        $this->assertNull($master->get('t1'));

        // gc check
        $this->assertNoGC();
    }
}
