<?php
/**
 * This file is part of Vegas package
 *
 * @author Slawomir Zytko <slawek@amsterdam-standard.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage http://vegas-cmf.github.io
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Test\Controllers\Backend;

use Vegas\Mvc\Controller\CrudAbstract;
use Vegas\Mvc\View;

class BrokenCrudController extends CrudAbstract
{
    public function initialize()
    {
        parent::initialize();
        $this->view->disableLevel(View::LEVEL_LAYOUT);
    }

    protected function redirectAfterSave()
    {
        return $this->scaffolding->getRecord()->getId();
    }

    protected function redirectAfterDelete()
    {
        return $this->redirectAfterSave();
    }
}