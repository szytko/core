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

namespace Vegas\Cli\Generator;

use Vegas\Cli\Generator\Exception\FileExistsException;


/**
 * Class StubCreator
 * @package Vegas\Cli\Generator
 */
class StubCreator
{
    /**
     * @param $arguments
     * @param $path
     * @param $templateName
     * @param $outputFileName
     * @throws Exception
     */
    public static function create($arguments, $path, $templateName, $outputFileName)
    {
        $content = file_get_contents(dirname(__FILE__) . '/Stub/' . $templateName . '.php.vegas');

        foreach($arguments as $key => $arg) {
            $content = preg_replace("/(%%$key%%)/", $arg, $content);
        }

        if(file_exists($path . '/' . $outputFileName . '.php')) {
            throw new FileExistsException;
        }

        file_put_contents($path . '/' . $outputFileName . '.php', $content);

    }

    /**
     * @param $actionName
     * @return mixed
     */
    public static function createAction($actionName)
    {
        $content = file_get_contents(dirname(__FILE__) . '/Stub/_method.php.vegas');
        return preg_replace("/(%%actionName%%)/", $actionName, $content);
    }

}