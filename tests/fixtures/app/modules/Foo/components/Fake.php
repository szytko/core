<?php
/**
 * This file is part of Vegas package
 *
 * @author Arkadiusz Ostrycharz <arkadiusz.ostrycharz@gmail.com>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage http://vegas-cmf.github.io
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Foo\Components;

use Vegas\DI\InjectionAwareTrait;
use Vegas\DI\Service\ComponentAbstract;

class Fake extends ComponentAbstract
{
    protected function setUp($params = array())
    {
        return array('test' => $params);
    }
}