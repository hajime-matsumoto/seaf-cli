<?php
/**
 * Seaf: Simple Easy Acceptable micro-framework.
 *
 * クラスを定義する
 *
 * @author HAjime MATSUMOTO <mail@hazime.org>
 * @copyright Copyright (c) 2014, Seaf
 * @license   MIT, http://seaf.hazime.org
 */

namespace Seaf\Cli\Component;

use Seaf;
use Seaf\Core\Environment\Environment;
use Seaf\FrameWork as FW;

/**
 * リクエストクラス
 */
class Request extends FW\Component\Request
{
    public function init( )
    {
        $this->setUri(Seaf::util()->arrayGet($GLOBALS['argv'],1,'/'));
    }
}

/* vim: set expandtab ts=4 sw=4 sts=4: et*/
