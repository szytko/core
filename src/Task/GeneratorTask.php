<?php
/**
 * This file is part of Vegas package
 *
 * @author Radosław Fąfara <radek@amsterdam-standard.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage http://vegas-cmf.github.io
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vegas\Task;

use Phalcon\DI;
use Vegas\Cli\Generator\Task;
use Vegas\Cli\Task\Action;
use Vegas\Cli\Task\Option;
use Vegas\Cli\TaskAbstract;

/**
 * Class GeneratorTask
 * @package Vegas\Task
 */
class GeneratorTask extends TaskAbstract
{

    /**
     * Creates new task
     */
    public function taskAction()
    {
        $this->putText("Creating new task...");

        if ($this->isConfigured()) {
            $config = $this->getDI()->get('config');

            $moduleDir = $config->application->moduleDir;
            $moduleName = $this->getOption('module-name');
            $taskName = $this->getOption('name');
            $actions = $this->getOption('action');

            try {

                $generator = new Task($moduleName, $taskName);
                if($actions != null) {
                    $generator->addAction($actions);
                }
                $generator->setPath($moduleDir);
                $generator->run();

                $this->putSuccess("Done.");

            } catch(\Vegas\Cli\Exception $ex) {
                $this->putError($ex->getMessage());
            }
        }
    }

    /**
     * @return bool
     */
    private function isConfigured()
    {
        if (!$this->getDI()->has('config')) {
            return false;
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
        $action = new Action('task', 'Creates new module');

        $option = new Option('module-name', 'm', 'Module name');
        $option->setRequired(true);
        $action->addOption($option);

        $option = new Option('name', 'n', 'Task name');
        $option->setRequired(true);
        $action->addOption($option);

        $option = new Option('action', 'a', 'Action name');
        $action->addOption($option);

        $this->addTaskAction($action);
    }
}
