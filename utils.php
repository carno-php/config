<?php
/**
 * Commands kit
 * User: moyo
 * Date: 13/10/2017
 * Time: 6:33 PM
 */

namespace Carno\Config;

/**
 * default config scope should be server name of detected
 * you can also assigned to others e.g. "global" "biz1"
 * @param string $scope
 * @return Config
 */
function conf(string $scope = '_') : Config
{
    static $sources = [];
    return $sources[$scope] ?? $sources[$scope] = (new Config())->assigned($scope);
}
