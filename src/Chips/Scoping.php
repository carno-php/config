<?php
/**
 * Config scopes assigning/getting
 * User: moyo
 * Date: 2018/8/20
 * Time: 4:43 PM
 */

namespace Carno\Config\Chips;

trait Scoping
{
    /**
     * @var string
     */
    private $scope = 'default';

    /**
     * @param string $scope
     * @return static
     */
    public function assigned(string $scope) : self
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * @return string
     */
    public function scoped() : string
    {
        return $this->scope;
    }
}
