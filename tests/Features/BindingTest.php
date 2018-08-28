<?php
/**
 * Binding test
 * User: moyo
 * Date: 22/03/2018
 * Time: 11:11 AM
 */

namespace Carno\Config\Tests\Features;

use Carno\Config\Config;
use Carno\Config\Tests\Chips\GCAssert;
use Carno\Config\Tests\Options\OptA;
use Carno\Config\Tests\Options\OptB;
use PHPUnit\Framework\TestCase;

class BindingTest extends TestCase
{
    use GCAssert;

    public function testOptionsSync()
    {
        $conf = new Config;

        $optA = new OptA;
        $optB = new OptB;

        $conf->bind($optA, ['opts' => ['set.f.1' => 'f1', 'set.f.2' => 'f2']]);
        $conf->bind($optB, ['opts' => ['set.f.1' => 'f3', 'set.f.2' => 'f4']]);

        $this->assertNull($optA->f1);
        $this->assertNull($optA->f2);
        $this->assertNull($optB->f3);
        $this->assertNull($optB->f4);

        $conf->set('opts/set.f.1', 111)->set('opts/set.f.2', 222);

        $this->assertEquals(111, $optA->f1);
        $this->assertEquals(222, $optA->f2);
        $this->assertEquals(111, $optB->f3);
        $this->assertEquals(222, $optB->f4);

        $conf->bind($optA, ['opts' => ['a' => ['b' => ['c' => ['d' => ['f.2.test' => 'f2']]]]]]);

        $conf->set('opts/a/b/c/d/f.2.test', 333)->set('opts/set.f.1', 444);

        $this->assertEquals(111, $optA->f1);
        $this->assertEquals(333, $optA->f2);
        $this->assertEquals(444, $optB->f3);
        $this->assertEquals(222, $optB->f4);

        $conf->unbind($optA);
        $conf->unbind($optB);

        $this->assertNoGC();
    }
}
