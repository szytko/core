<?php
/**
 * This file is part of Vegas package
 *
 * @author Mateusz Aniołek <mateusz.aniolek@amsterdamstandard.com>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage http://vegas-cmf.github.io
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vegas\Task;

use Phalcon\DI;
use Vegas\Cli\Generator\Controller;
use Vegas\Cli\Generator\Exception\DINotConfiguredException;
use Vegas\Cli\Generator\Model;
use Vegas\Cli\Task\Action;
use Vegas\Cli\Task;
use Vegas\Cli\TaskAbstract;

/**
 * Class MvcTask
 * @package Vegas\Task
 */
class MvcTask extends TaskAbstract
{
    /**
     * Creates crud
     */
    public function crudAction()
    {
        $this->putText("Creating new controller...");

        if ($this->checkConfiguration()) {
            $config = $this->getDI()->get('config');

            $moduleDir = $config->application->moduleDir;
            $moduleName = $this->getOption('module');
            $controllerName = $this->getOption('name');

            $controllerDirPath = $moduleDir . $moduleName . '/controller';

            $controllerGenerator = new Controller($moduleName, $controllerName, true);
            $controllerGenerator->setPath($controllerDirPath);
            $controllerGenerator->run();

            $this->putSuccess("Done.");
        }
    }

    /**
     * Creates crud
     */
    public function controllerAction()
    {
        $this->putText("Creating new controller...");

        if ($this->checkConfiguration()) {
            $config = $this->getDI()->get('config');

            $moduleDir = $config->application->moduleDir;
            $moduleName = $this->getOption('module');
            $controllerName = $this->getOption('name');
            $actions = explode(',', $this->getOption('action'));

            $controllerDirPath = $moduleDir . $moduleName . '/controller';

            $controllerGenerator = new Controller($moduleName, $controllerName);
            $controllerGenerator->setPath($controllerDirPath);
            foreach($actions as $action) {
                $controllerGenerator->addAction($action);
            }
            $controllerGenerator->run();

            $this->putSuccess("Done.");
        }
    }

    /**
     * Creates model
     */
    public function modelAction()
    {
        $this->putText("Creating new controller...");

        if ($this->checkConfiguration()) {
            $config = $this->getDI()->get('config');

            $moduleDir = $config->application->moduleDir;
            $moduleName = $this->getOption('module');
            $modelName = $this->getOption('name');

            $controllerDirPath = $moduleDir . $moduleName . '/models';

            $controllerGenerator = new Model($moduleName, $modelName);
            $controllerGenerator->setPath($controllerDirPath);
            $controllerGenerator->run();

            $this->putSuccess("Done.");
        }
    }

    /**
     * @return bool
     * @throws DINotConfiguredException
     */
    private function checkConfiguration()
    {
        if (!$this->getDI()->has('config')) {
            throw new DINotConfiguredException();
        }
        $config = $this->getDI()->get('config');
        return !empty($config->application)
            && !empty($config->application->moduleDir)
            && !empty($config->application->serviceDir)
            && !empty($config->application->configDir);
    }

    /**
     * Task's available options
     *
     * @return mixed
     */
    public function setupOptions()
    {
        $action = new Action('controller', 'Controller creator');

        $option = new Task\Option('module', 'm', 'Module name');
        $option->setRequired(true);
        $action->addOption($option);

        $option = new Task\Option('name', 'n', 'Controller name');
        $option->setRequired(true);
        $action->addOption($option);

        $option = new Task\Option('action', 'a', 'Actions list, separated by a comma');
        $action->addOption($option);

        $this->addTaskAction($action);

        $action = new Action('crud', 'Crud creator');

        $option = new Task\Option('module', 'm', 'Module name');
        $option->setRequired(true);
        $action->addOption($option);

        $option = new Task\Option('name', 'n', 'Controller name');
        $option->setRequired(true);
        $action->addOption($option);

        $this->addTaskAction($action);

        $action = new Action('model', 'Model creator');

        $option = new Task\Option('module', 'm', 'Module name');
        $option->setRequired(true);
        $action->addOption($option);

        $option = new Task\Option('name', 'n', 'Model name');
        $option->setRequired(true);
        $action->addOption($option);

        $this->addTaskAction($action);

    }

}
