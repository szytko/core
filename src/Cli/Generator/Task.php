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

use Vegas\Cli\Generator\Exception\ModuleNotExistsException;
use Vegas\Cli\Generator\Exception\TaskAlreadyExistsException;
use Vegas\Cli\Generator\Exception\TaskNotFoundException;
use Vegas\Cli\Generator\Exception\ModuleNameNotFoundException;
use Vegas\Cli\Generator\Exception\PathNotFoundException;


/**
 * Class Model
 * @package Vegas\Cli\Generator
 */
class Task
{

    const DEFAULT_ACTION_NAME = 'default';
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
    private $taskName;

    /**
     * @var
     */
    private $actionName;

    /**
     * @param $moduleName
     * @param $taskName
     * @throws ModuleNameNotFoundException
     * @throws TaskNotFoundException
     */
    public function __construct($moduleName, $taskName)
    {
        if($moduleName == null) {
            throw new ModuleNameNotFoundException();
        }

        if($taskName == null) {
            throw new TaskNotFoundException();
        }

        $this->moduleName = $moduleName;
        $this->taskName = $taskName;
    }

    /**
     * @param $actionName
     */
    public function addAction($actionName)
    {
        $this->actionName = $actionName;
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

        $modulePath = $this->path . '/' . $this->moduleName;
        if(!file_exists($modulePath)) {
            throw new ModuleNotExistsException();
        }

        $taskDirPath = $modulePath . '/tasks';
        if(!file_exists($taskDirPath)) {
            mkdir($taskDirPath);
        }

        $taskFileName = ucfirst($this->taskName) . 'Task';
        $taskPath = $taskDirPath . '/' . $taskFileName . '.php';

        if(file_exists($taskPath)) {
            throw new TaskAlreadyExistsException();
        }

        $arguments = [];
        $arguments['moduleName'] = $this->moduleName;
        $arguments['taskName'] = $this->taskName;
        if($this->actionName) {
            $actionName = $this->actionName;
        } else {
            $actionName = self::DEFAULT_ACTION_NAME;
        }
        $arguments['actionName'] = $actionName;

        StubCreator::create($arguments, $taskDirPath, 'Task', $taskFileName);
    }

}