<?php
/**
 * @author Sławomir Żytko <slawek@amsterdam-standard.pl>
 * @homepage http://amsterdam-standard.pl
 */

namespace Vegas\Db\Mapping\Driver\Exception;

/**
 * Class AnnotationNotFoundException
 * @package Vegas\Db\Mapping\Driver\Exception
 */
class AnnotationNotFoundException extends \Vegas\Exception
{
    /**
     * @var string
     */
    protected $message = 'Annotation not found';
}