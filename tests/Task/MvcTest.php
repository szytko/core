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
namespace Vegas\Tests\Task;

use Vegas\Cli\Exception\ModelNameNotFoundException;
use Vegas\Cli\Generator\Exception\ConfigNotConfiguredException;
use Vegas\Task\MvcTask;
use Vegas\Tests\Cli\TestCase;

class MvcTest extends TestCase
{
    public function testCreateAction()
    {
        @unlink(TESTS_ROOT_DIR.'/fixtures/app/modules/FoobarModule/controller/TestController.php');
        @unlink(TESTS_ROOT_DIR.'/fixtures/app/modules/FoobarModule/models/Test.php');

        @rmdir(TESTS_ROOT_DIR.'/fixtures/app/modules/FoobarModule/controller');
        @rmdir(TESTS_ROOT_DIR.'/fixtures/app/modules/FoobarModule/forms');
        @rmdir(TESTS_ROOT_DIR.'/fixtures/app/modules/FoobarModule/models');
        @rmdir(TESTS_ROOT_DIR.'/fixtures/app/modules/FoobarModule/services');
        @rmdir(TESTS_ROOT_DIR.'/fixtures/app/modules/FoobarModule/views');
        @rmdir(TESTS_ROOT_DIR.'/fixtures/app/modules/FoobarModule');

        $result = $this->runCliAction('cli/cli.php vegas:module create -n FoobarModule');

        $this->assertFileExists(TESTS_ROOT_DIR.'/fixtures/app/modules/FoobarModule');

        $this->assertContains("Done.", $result);

        $result = $this->runCliAction('cli/cli.php vegas:module create -n FoobarModule');

        $this->assertContains("Done.", $result);

    }

    public function testCrudAction()
    {
        @unlink(TESTS_ROOT_DIR.'/fixtures/app/modules/FoobarModule/controller/TestController.php');
        $result = $this->runCliAction('cli/cli.php vegas:mvc crud -m FoobarModule -n Test');

        $this->assertContains("Done.", $result);
    }

    public function testControllerAction()
    {
        @unlink(TESTS_ROOT_DIR.'/fixtures/app/modules/FoobarModule/controller/TestController.php');
        $result = $this->runCliAction('cli/cli.php vegas:mvc controller -m FoobarModule -n Test -a test1,test2');

        $this->assertContains("Done.", $result);
    }

    public function testModelAction()
    {
        @unlink(TESTS_ROOT_DIR.'/fixtures/app/modules/FoobarModule/models/Test.php');
        $result = $this->runCliAction('cli/cli.php vegas:mvc model -m FoobarModule -n Test');

        $this->assertContains("Done.", $result);
    }

    /**
     * @expectedException \Vegas\Cli\Generator\Exception\DINotConfiguredException
     */
    public function testIsConfigured()
    {
        $mock = new MvcTask();
        $mock->setDI(new \Phalcon\DI\FactoryDefault());
        $mock->modelAction();
    }

}
