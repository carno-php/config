<?php
/**
 * Base tests
 * User: moyo
 * Date: 2018/8/13
 * Time: 11:40 AM
 */

namespace Carno\Config\Tests;

use Carno\Config\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testUtils()
    {
        $c1 = config('s1');
        $c2 = config('s2');

        $this->assertInstanceOf(Config::class, $c1);
        $this->assertInstanceOf(Config::class, $c2);

        $this->assertNotEquals(spl_object_id($c1), spl_object_id($c2));
    }

    public function testBases()
    {
        $conf = new Config;

        $this->assertFalse($conf->has('key1'));
        $this->assertNull($conf->get('key1'));

        $conf->set('key1', 'val1');

        $this->assertTrue($conf->has('key1'));
        $this->assertEquals('val1', $conf->get('key1'));

        $conf->set('key1', null);

        $this->assertFalse($conf->has('key1'));
    }

    public function testTypes()
    {
        $conf = new Config;

        $conf->set('key', '1');

        $this->assertTrue(is_numeric($conf->get('key')));
        $this->assertTrue(is_string($conf->get('key')));

        $this->assertTrue(is_integer($conf->int('key')));
        $this->assertTrue(is_string($conf->string('key')));
        $this->assertTrue(is_bool($conf->bool('key')) && $conf->bool('key'));

        $conf->set('key2', 'a');

        $this->assertEquals(0, $conf->int('key2'));
        $this->assertEquals('a', $conf->string('key2'));
        $this->assertFalse($conf->bool('key2'));

        $conf->set('key3', 'yes');
        $conf->set('key4', 'no');

        $this->assertTrue($conf->bool('key3'));
        $this->assertFalse($conf->bool('key4'));
    }
}
