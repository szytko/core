<?php
/**
 * @author Sławomir Żytko <slawek@amsterdam-standard.pl>
 * @homepage http://amsterdam-standard.pl
 */

namespace Vegas\Db\Mapping;

interface MapperInterface
{
    public static function map($value);

    public static function extract($value);
}