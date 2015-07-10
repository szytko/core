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

use Vegas\Cli\Generator\Exception\ControllerNameNotFoundException;
use Vegas\Cli\Generator\Exception\ModuleNameNotFoundException;
use Vegas\Cli\Generator\Exception\PathNotFoundException;


/**
 * Class Controller
 * @package Vegas\Cli\Generator
 */
class Controller
{

    /**
     * @var array
     */
    private $actionsContainer = [];

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
    private $controllerName;

    /**
     * @var bool
     */
    private $isCrud = false;

    /**
     * @var array
     */
    private $crudArguments = [];

    /**
     * @param $moduleName
     * @param $controllerName
     * @param bool|false $isCrud
     * @throws Exception
     */
    public function __construct($moduleName, $controllerName, $isCrud = false)
    {
        if($moduleName == null) {
            throw new ModuleNameNotFoundException();
        }
        if($controllerName == null) {
            throw new ControllerNameNotFoundException();
        }

        $this->isCrud = $isCrud;
        $this->controllerName = $controllerName;
        $this->moduleName = $moduleName;
        if($this->isCrud) {
            $this->crudArguments = [
                'modelName' => $moduleName . '\Models\\' . $controllerName,
                'formName' => $moduleName . '\Forms\\' . $controllerName
            ];
        }

    }

    /**
     * @param $name
     */
    public function addAction($name)
    {
        $this->actionsContainer[] = $name;
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
        $arguments['namespace'] = $this->moduleName . '\Controllers';
        $arguments['controller'] = $this->controllerName;
        $arguments['module'] = $this->moduleName;

        if($this->isCrud) {
            $arguments['modelName'] = $this->crudArguments['modelName'];
            $arguments['formName'] = $this->crudArguments['formName'];

            StubCreator::create($arguments, $this->path, 'Crud', $this->controllerName . 'Controller');

        } else {
            $actionBlock = [];
            foreach($this->actionsContainer as $action) {
                $actionBlock[] = StubCreator::createAction($action);
            }
            $arguments['actionsBlock'] = implode("\n", $actionBlock);
            StubCreator::create($arguments, $this->path, 'Controller', $this->controllerName . 'Controller');
        }

    }

}