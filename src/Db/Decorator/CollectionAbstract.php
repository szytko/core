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
namespace Vegas\Db\Decorator;

use Phalcon\Mvc\Collection;
use Vegas\Db\Adapter\Mongo\DbRef;
use Vegas\Db\Adapter\Mongo\RefResolverTrait;
use Vegas\Db\Decorator\Helper\MappingHelperTrait;
use Vegas\Db\Decorator\Helper\ReadNestedAttributeTrait;
use Vegas\Db\Decorator\Helper\SlugTrait;
use Vegas\Db\Decorator\Helper\WriteAttributesTrait;
use Vegas\Db\Mapping\MapperInterface;
use Vegas\Db\MappingResolverTrait;

/**
 * Class CollectionAbstract
 * @package Vegas\Db\Decorator
 */
abstract class CollectionAbstract extends Collection implements MapperInterface
{
    use MappingResolverTrait;
    use MappingHelperTrait;
    use SlugTrait;
    use WriteAttributesTrait;
    use ReadNestedAttributeTrait;
    use RefResolverTrait;

    protected static $eagerLoading = true;

    protected static function disableEagerLoading()
    {
        self::$eagerLoading = false;
    }

    public static function enableEagerLoading()
    {
        self::$eagerLoading = true;
    }

    public static function isEagerLoading()
    {
        return self::$eagerLoading;
    }

    /**
     * Event fired when record is being created
     */
    public function beforeCreate()
    {
        $this->created_at = new \MongoInt32(time());
    }

    /**
     * Event fired when record is being updated
     */
    public function beforeUpdate()
    {
        $this->updated_at = new \MongoInt32(time());
    }

    public static function map($value)
    {
        if (DbRef::isRef($value)) {
            $value = $value['$id'];
        } else if ($value instanceof CollectionAbstract) {
            $value = $value->getId();
        }
        return self::findById($value);
    }

    public static function extract($value)
    {
        return DbRef::create($value);
    }

    public static function getMetadata()
    {
        $annotations = (new \Vegas\Db\Mapping\Driver\Annotation(static::class))->getAnnotations();
        return $annotations;
    }

    private static function mapField($className, $value)
    {
        $class = new \ReflectionClass($className);
        return $class->getMethod('map')->invoke(null, $value);
    }

    protected static function mapRow($metadata, &$row)
    {
        foreach ($metadata as $field => $mapperClassName) {
            if (isset($row->{$field})) {
                $row->{$field} = self::mapField($mapperClassName, $row->{$field});
            }
        }
    }

    public static function find(array $parameters = null)
    {
        $rows = parent::find($parameters);
        if (self::isEagerLoading()) {
            $metadata = self::getMetadata();
            foreach ($rows as &$row) {
                self::mapRow($metadata, $row);
            }
        }

        return $rows;
    }

    public static function findById($id)
    {
        $row = parent::findById($id);
        if (self::isEagerLoading()) {
            self::mapRow(self::getMetadata(), $row);
        }

        return $row;
    }

    public static function findFirst(array $parameters = null)
    {
        $row = parent::findFirst($parameters);
        if (self::isEagerLoading()) {
            self::mapRow(self::getMetadata(), $row);
        }

        return $row;
    }

    public function save()
    {
        $metadata = $this->getMetadata();
        foreach (get_object_vars($this) as $object => $value) {
            if (isset($metadata[$object])) {
                $reflectionClass = new \ReflectionClass($metadata[$object]);
                if ($reflectionClass->isSubclassOf(MapperInterface::class)) {
                    $this->{$object} = $reflectionClass->getMethod('extract')->invoke(null, $this->{$object});
                }
            }
        }
        return parent::save();
    }

}