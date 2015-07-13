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
 
namespace Vegas\Cli\Generator\Exception;

use Vegas\Cli\Generator\Exception;

/**
 * Class ModuleNotExistsException
 * @package Vegas\Cli\Exception
 */
class ModuleNotExistsException extends Exception
{
    /**
     * Exception default message
     *
     * @var string
     */
    protected $message = 'Module not exists';
}
