<?php
/**
 * Config master
 * User: moyo
 * Date: 16/10/2017
 * Time: 2:50 PM
 */

namespace Carno\Config;

use Carno\Config\Chips\Binding;
use Carno\Config\Chips\Getter;
use Carno\Config\Chips\Joining;
use Carno\Config\Chips\Overrides;
use Carno\Config\Chips\Scoping;
use Carno\Config\Chips\Setter;
use Carno\Config\Chips\Stores\Memory;
use Carno\Config\Chips\Watching;

class Config
{
    use Memory, Scoping, Getter, Setter, Joining, Binding, Watching, Overrides;
}
