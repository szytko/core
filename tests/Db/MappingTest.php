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
 
namespace Vegas\Tests\Db;

use Phalcon\DI;
use Vegas\Db\Decorator\CollectionAbstract;
use Vegas\Db\Decorator\ModelAbstract;
use Vegas\Db\Mapping\MapperInterface;

class CustomMapper implements MapperInterface
{
    public function __construct($value)
    {
        $this->value = $value;
    }

    protected $value;

    public function getValue()
    {
        return $this->value;
    }

    public static function map($value)
    {
        return new self($value);
    }

    public static function extract($value)
    {
        return $value->getValue();
    }
}

class FakeParentParent extends CollectionAbstract
{
    public function getSource()
    {
        return 'fake_fake_parent';
    }

    public $parentTest;

}

class FakeParent extends CollectionAbstract
{
    public function getSource()
    {
        return 'fake_parent';
    }


    /**
     * @var \Vegas\Tests\Db\FakeParentParent
     * @Mapper
     */
    public $parent;
    public $test;
    public $test2;
    public $test3;
}

class Fake extends CollectionAbstract
{
    public function getSource()
    {
        return 'fake';
    }

    /**
     * @var \Vegas\Tests\Db\FakeParent
     * @Mapper
     */
    public $parent;

    /**
     * @var \Vegas\Tests\Db\CustomMapper
     * @Mapper
     */
    public $custom;

}

class EagerLoader
{
    public function __construct($object)
    {
        $this->object = $object;
    }

    public function __get($name)
    {
        $metadata = $this->getMetadata();
        $value = $this->object->{$name};
        if (isset($metadata[$name])) {
            $reflectionClass = new \ReflectionClass($metadata[$name]);
            if ($reflectionClass->isSubclassOf(MapperInterface::class)) {
                $instance = $reflectionClass->newInstance();
                $value = call_user_func_array([$instance, 'factory'], $value);
            }
        }

        return $value;
    }

    public function __call($funcName, $args)
    {
        return call_user_func_array([$this->object, $funcName], $args);
    }
}

class FakeModel extends ModelAbstract
{
    public function getSource()
    {
        return 'fake_table';
    }

    protected $mappings = [
        'somedata'  =>  'json',
        'somecamel' =>  'camelize',
        'encoded'   =>  'blob'
    ];
}

class FakeDate extends CollectionAbstract
{
    public function getSource()
    {
        return 'fake_date';
    }

    protected $mappings = [
        'createdAt' => 'dateTime'
    ];
}

class FakeMultiple extends CollectionAbstract
{
    public function getSource()
    {
        return 'fake_multiple';
    }

    protected $mappings = [
        'jsonlower' => ['json', 'lowercase']
    ];
}

class FakeClass
{
    public $param;

    public function __construct($param)
    {
        $this->param = $param;
    }
}

class MappingTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        $di = \Phalcon\DI::getDefault();
        $di->get('mongo')->fake_parent->drop();
        $di->get('mongo')->fake_parent_parent->drop();
        $di->get('mongo')->fake->drop();
        $di->get('db')->execute('DROP TABLE IF EXISTS fake_table ');
        $di->get('db')->execute(
            'CREATE TABLE fake_table(
            id int not null primary key auto_increment,
            somedata varchar(250) null,
            somecamel varchar(250) null
            )'
        );
    }

    public static function tearDownAfterClass()
    {
        $di = \Phalcon\DI::getDefault();

        foreach (Fake::find() as $fake) {
            $fake->delete();
        }

        $di->get('db')->execute('DROP TABLE IF EXISTS fake_table ');
    }

    public function testShouldAddMapperToMappingManager()
    {
        $parentParent = new FakeParentParent();
        $parentParent->parentTest = 'teststse';
        $parentParent->save();

        $parent = new FakeParent();
        $parent->parent = $parentParent;
        $parent->test = uniqid();
        $parent->test2 = uniqid();
        $parent->test3 = uniqid();
        $parent->save();

        $customMapper = new CustomMapper(1234);
        $fake = new Fake();
        $fake->parent = $parent;
        $fake->custom = $customMapper;
        $fake->save();

        /** @var Fake $fake */
        $fake = Fake::findFirst();
    }

} 