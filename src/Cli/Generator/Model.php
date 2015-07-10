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

use Vegas\Cli\Generator\Exception\ModelNameNotFoundException;
use Vegas\Cli\Generator\Exception\ModuleNameNotFoundException;
use Vegas\Cli\Generator\Exception\PathNotFoundException;


/**
 * Class Model
 * @package Vegas\Cli\Generator
 */
class Model
{
    /**
     * @var null
     */
    private $path = null;

    /**
     * @var
     */
    private $moduleName;

    /**
     * @var
     */
    private $modelName;

    /**
     * @param $moduleName
     * @param $modelName
     * @throws ModelNameNotFoundException
     * @throws ModuleNameNotFoundException
     */
    public function __construct($moduleName, $modelName)
    {
        if($moduleName == null) {
            throw new ModuleNameNotFoundException();
        }
        if($modelName == null) {
            throw new ModelNameNotFoundException();
        }

        $this->modelName = $modelName;
        $this->moduleName = $moduleName;
    }

    /**
     * @param $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @throws Exception
     */
    public function run()
    {
        if($this->path == null) {
            throw new PathNotFoundException();
        }

        $arguments = [];
        $arguments['module'] = $this->moduleName;
        $arguments['model'] = $this->modelName;

        StubCreator::create($arguments, $this->path, 'Model', $this->modelName);
    }

}