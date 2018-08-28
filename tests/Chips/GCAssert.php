<?php
/**
 * GC assert
 * User: moyo
 * Date: 2018/8/28
 * Time: 3:15 PM
 */

namespace Carno\Config\Tests\Chips;

trait GCAssert
{
    protected function assertNoGC()
    {
        if (!(extension_loaded('xdebug') && xdebug_code_coverage_started())) {
            $this->assertEquals(0, gc_collect_cycles());
        }
    }
}
