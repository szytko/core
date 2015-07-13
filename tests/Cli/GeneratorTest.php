<?php
/**
 * This file is part of Vegas package
 *
 * @author Arkadiusz Ostrycharz <aostrycharz@amsterdam-standard.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage http://vegas-cmf.github.io
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vegas\Tests\Cli;


use Vegas\Cli\Generator\Controller;
use Vegas\Cli\Generator\Model;
use Vegas\Cli\Generator\Module;
use Vegas\Cli\Generator\StubCreator;
use Vegas\Cli\Generator\Task;

class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    private $path;

    public function setUp()
    {
        $this->path = TESTS_ROOT_DIR . '/fixtures/app/modules';

        @unlink($this->path . '/ModuleTest/Module.php');
        @rmdir($this->path . '/ModuleTest/controller');
        @rmdir($this->path . '/ModuleTest/forms');
        @rmdir($this->path . '/ModuleTest/tasks');
        @rmdir($this->path . '/ModuleTest/services');
        @rmdir($this->path . '/ModuleTest/views');
        @rmdir($this->path . '/ModuleTest/models');
        @rmdir($this->path . '/ModuleTest');

    }

    public function tearDown()
    {
        @unlink($this->path . '/ModuleTest/tasks/TestTask.php');
        @rmdir($this->path . '/ModuleTest/tasks');
        @rmdir($this->path . '/ModuleTest/controller');
        @rmdir($this->path . '/ModuleTest/services');
        @rmdir($this->path . '/ModuleTest/views');
        @rmdir($this->path . '/ModuleTest/forms');
        @rmdir($this->path . '/ModuleTest/models');
        @rmdir($this->path . '/ModuleTest');
    }

    public function testTaskGenerator()
    {

        $generator = new Module('ModuleTest');
        $generator->setPath($this->path);
        $generator->run();

        $taskGenerator = new Task('ModuleTest', 'test');
        $taskGenerator->setPath($this->path);
        $taskGenerator->run();

        $this->assertFileExists($this->path . '/ModuleTest/tasks');
        $this->assertFileExists($this->path . '/ModuleTest/tasks/TestTask.php');

    }

    public function testModuleGenerator()
    {
        $generator = new Module('ModuleTest');
        $generator->setPath($this->path);
        $generator->run();

        $this->assertFileExists($this->path . '/ModuleTest');
        $this->assertFileExists($this->path . '/ModuleTest/controller');
        $this->assertFileExists($this->path . '/ModuleTest/models');
        $this->assertFileExists($this->path . '/ModuleTest/forms');
        $this->assertFileExists($this->path . '/ModuleTest/services');
        $this->assertFileExists($this->path . '/ModuleTest/views');
        $this->assertFileExists($this->path . '/ModuleTest/Module.php');
    }

    public function testTaskAddAction()
    {
        $generator = new Module('ModuleTest');
        $generator->setPath($this->path);
        $generator->run();

        $taskGenerator = new Task('ModuleTest', 'test');
        $taskGenerator->setPath($this->path);
        $taskGenerator->addAction('test1');
        $taskGenerator->run();

        $content = file_get_contents($this->path . '/ModuleTest/tasks/TestTask.php');

        $result = preg_match_all("/public function test1Action/", $content);

        $this->assertEquals(1, $result);
    }

    /**
     * @expectedException \Vegas\Cli\Generator\Exception\PathNotFoundException
     */
    public function testTaskPathNotFoundException()
    {
        $generator = new Module('ModuleTest');
        $generator->setPath($this->path);
        $generator->run();

        $taskGenerator = new Task('ModuleTest', 'test');
        $taskGenerator->run();
    }

    /**
     * @expectedException \Vegas\Cli\Generator\Exception\ModuleNameNotFoundException
     */
    public function testTaskModuleNotFoundException()
    {
        $generator = new Module('ModuleTest');
        $generator->setPath($this->path);
        $generator->run();

        $taskGenerator = new Task(null, 'test');
    }

    /**
     * @expectedException \Vegas\Cli\Generator\Exception\ModuleNotExistsException
     */
    public function testTaskModuleNotExistsException()
    {
        $taskGenerator = new Task('ModuleTest', 'test');
        $taskGenerator->setPath($this->path);
        $taskGenerator->run();
    }

    /**
     * @expectedException \Vegas\Cli\Generator\Exception\TaskAlreadyExistsException
     */
    public function testTaskAlreadyExistsException()
    {
        $generator = new Module('ModuleTest');
        $generator->setPath($this->path);
        $generator->run();

        $taskGenerator = new Task('ModuleTest', 'test');
        $taskGenerator->setPath($this->path);
        $taskGenerator->run();

        $taskGenerator->run();
    }

    /**
     * @expectedException \Vegas\Cli\Generator\Exception\TaskNotFoundException
     */
    public function testTaskNotFoundException()
    {

        $generator = new Module('ModuleTest');
        $generator->setPath($this->path);
        $generator->run();

        $taskGenerator = new Task('ModuleTest', null);
        $taskGenerator->setPath($this->path);
        $taskGenerator->run();

    }

    /**
     * @expectedException \Vegas\Cli\Generator\Exception\ModuleNameNotFoundException
     */
    public function testModuleNameNotFoundException()
    {
        $path = TESTS_ROOT_DIR . '/fixtures/app/modules';
        @unlink($path . '/ModuleTest');

        $generator = new Module(null);
        $generator->setPath($path);
        $generator->run();
    }

    /**
     * @expectedException \Vegas\Cli\Generator\Exception\PathNotFoundException
     */
    public function testModulePathNotFoundException()
    {
        $path = TESTS_ROOT_DIR . '/fixtures/app/modules';
        @unlink($path . '/ModuleTest');

        $generator = new Module('ModuleTest');
        $generator->run();
    }

    /**
     * @expectedException \Vegas\Cli\Generator\Exception\ModuleAlreadyExistsException
     */
    public function testModuleExistsException()
    {
        $path = TESTS_ROOT_DIR . '/fixtures/app/modules';
        @unlink($path . '/ModuleTest');

        $generator = new Module('ModuleTest');
        $generator->setPath($path);
        $generator->run();

        $generator->run();
    }



    public function testControllerGenerator()
    {
        $path = TESTS_ROOT_DIR . '/fixtures/app/modules/FoobarModule/controller';
        $generator = new Controller('FoobarModule', 'Generator');
        $generator->setPath($path);
        $generator->addAction('new');
        $generator->addAction('index');
        $generator->addAction('edit');
        $generator->run();

        $this->assertFileExists($path . '/GeneratorController.php');

        @unlink($path . '/GeneratorController.php');
    }

    public function testCrudControllerGenerator()
    {
        $path = TESTS_ROOT_DIR . '/fixtures/app/modules/FoobarModule/controller';
        $generator = new Controller('FoobarModule', 'CrudGenerator', true);
        $generator->setPath($path);
        $generator->run();

        $this->assertFileExists($path . '/CrudGeneratorController.php');

        @unlink($path . '/CrudGeneratorController.php');
    }

    public function testModelGenerator()
    {
        $path = TESTS_ROOT_DIR . '/fixtures/app/modules/FoobarModule/models';
        $generator = new Model('FoobarModule', 'TestModel');
        $generator->setPath($path);
        $generator->run();

        $this->assertFileExists($path . '/TestModel.php');

        @unlink($path . '/TestModel.php');
    }

    /**
     * @expectedException \Vegas\Cli\Generator\Exception\FileExistsException
     */
    public function testStubCreatorException()
    {
        $path = TESTS_ROOT_DIR . '/fixtures/app/modules/FoobarModule/models';

        StubCreator::create([], $path, 'Model', 'Test');

        StubCreator::create([], $path, 'Model', 'Test');
    }

    /**
     * @expectedException \Vegas\Cli\Generator\Exception\ControllerNameNotFoundException
     */
    public function testControllerNameNotFound()
    {
        $generator = new Controller('Test', null);
    }

    /**
     * @expectedException \Vegas\Cli\Generator\Exception\ModuleNameNotFoundException
     */
    public function testModuleNameNotFound()
    {
        $generator = new Controller(null, 'Test');
    }

    /**
     * @expectedException \Vegas\Cli\Generator\Exception\PathNotFoundException
     */
    public function testControllerPathNotGiven()
    {
        $generator = new Controller('Test', 'Test');
        $generator->run();
    }

    /**
     * @expectedException \Vegas\Cli\Generator\Exception\ModelNameNotFoundException
     */
    public function testModelNameNotFound()
    {
        $generator = new Model('Test', null);
    }

    /**
     * @expectedException \Vegas\Cli\Generator\Exception\ModuleNameNotFoundException
     */
    public function testModelModuleNameNotFound()
    {
        $generator = new Model(null, 'Test');
    }

    /**
     * @expectedException \Vegas\Cli\Generator\Exception\PathNotFoundException
     */
    public function testModelPathNotGiven()
    {
        $generator = new Model('Test', 'Test');
        $generator->run();
    }
}