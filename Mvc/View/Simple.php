<?php
/**
 * This file is part of Vegas package
 *
 * @author Jaroslaw Macko <jarek@amsterdam-standard.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage https://bitbucket.org/amsdard/vegas-phalcon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vegas\Mvc\View;

use Phalcon\Mvc\View as PhalconView;
use Phalcon\Mvc\View\Simple as PhalconSimpleView;

class Simple extends PhalconSimpleView
{

    public function __construct($options=null) {
        parent::__construct($options);

        $config = require APP_CONFIG.'/config.php';

        $this->registerEngines(array(
            '.volt' => function ($this, $di) use ($config) {
                    $volt = new PhalconView\Engine\Volt($this, $di);
                    $volt->setOptions(array(
                        'compiledPath' => $config->application->cacheDir,
                        'compiledSeparator' => '_'
                    ));
                    return $volt;
                },
            '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
        ));
    }
}
