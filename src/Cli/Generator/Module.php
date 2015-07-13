<?php
/**
 * This file is part of Vegas package
 *
 * @author Mateusz Aniołek <mateusz.aniolek@amsterdam-standard.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage http://vegas-cmf.github.io
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vegas\Cli\Generator;

use Vegas\Cli\Generator\Exception\ModuleAlreadyExistsException;
use Vegas\Cli\Generator\Exception\ModuleNameNotFoundException;
use Vegas\Cli\Generator\Exception\PathNotFoundException;


/**
 * Class Model
 * @package Vegas\Cli\Generator
 */
class Module extends GeneratorAbstract
{
    /**
     * @var
     */
    private $moduleName;

    /**
     * @param $moduleName
     * @throws ModuleNameNotFoundException
     */
    public function __construct($moduleName)
    {
        if($moduleName == null) {
            throw new ModuleNameNotFoundException();
        }

        $this->moduleName = $moduleName;
    }

    /**
     * @throws Exception
     */
    public function run()
    {
        if($this->path == null) {
            throw new PathNotFoundException();
        }

        $modulePath = $this->path . '/' . $this->moduleName;
        if(file_exists($modulePath)) {
            throw new ModuleAlreadyExistsException();
        }

        mkdir($modulePath);
        mkdir($modulePath . '/controller');
        mkdir($modulePath . '/models');
        mkdir($modulePath . '/forms');
        mkdir($modulePath . '/services');
        mkdir($modulePath . '/views');

        StubCreator::create(['moduleName' => $this->moduleName], $modulePath, 'Module', 'Module');
    }

}