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
 
namespace Vegas\Cli\Generator\Exception;

use Vegas\Cli\Generator\Exception as Exception;

/**
 * Class TaskAlreadyExistsException
 * @package Vegas\Cli\Exception
 */
class TaskAlreadyExistsException extends Exception
{
    /**
     * Exception default message
     *
     * @var string
     */
    protected $message = 'Task already exists';
}
