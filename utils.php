<?php
/**
 * Commands kit
 * User: moyo
 * Date: 13/10/2017
 * Time: 6:33 PM
 */

/**
 * default config scope should be server name of detected
 * you can also assigned to others e.g. "global" "biz1"
 * @param string $scope
 * @return \Carno\Config\Config
 */
function config(string $scope = '_') : \Carno\Config\Config
{
    static $sources = [];
    return $sources[$scope] ?? $sources[$scope] = (new \Carno\Config\Config)->assigned($scope);
}
