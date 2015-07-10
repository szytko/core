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
use Vegas\Cli\Generator\StubCreator;

class GeneratorTest extends \PHPUnit_Framework_TestCase
{
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