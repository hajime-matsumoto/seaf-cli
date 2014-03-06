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

namespace Seaf\Cli;

use Seaf;
use Seaf\Core\Environment\Environment;
use Seaf\FrameWork as FW;

/**
 * FramwWork Front Controller
 */
class FrontController extends FW\FrontController
{
    public function isRoot ( )
    {
        return getenv('USER') === 'root';
    }

    public function out ($str)
    {
        echo $str."\n";
    }

    public function in ($str, $default = null)
    {
        $prop = $str;
        if ($default != null) $prop.='['.$default.']';
        echo $prop." : ";
        $res =  trim(fgets(STDIN,1024));
        if (empty($res) && $default) return $default;
        return $res;
    }

    public function templateWrite( $template, $params, $dist = false )
    {
        $data = preg_replace_callback(
            '/\{\{(.+)}\}/U',
            function($m) use($params){
                return $params[$m[1]];
            },
                file_get_contents($template)
            );

        if ($dist === false) {
            return $data;
        }else{
            file_put_contents($dist, $data);
        }
    }



    public function exec ($cmd, $input = null)
    {
        echo "$cmd\n";
        $desc = array(
            0=>array("pipe","r"),
            1=>array("pipe","w"),
            2=>array("pipe","w")
        );

        $proc = proc_open($cmd, $desc, $pipes);

        if (is_resource($proc)) {
            if ($input = null) {
                fclose($pipes[0]);
            }
        }

        while ($line = fgets($pipes[1])){
            echo $line;
        }

        fclose($pipes[1]);

        $stde = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        $return_value = proc_close($proc);

        echo $stde;

        return strlen($stde) > 0 ? false: true;
    }

    public function mkdir ($dir)
    {
        $new_dir = strtok($dir,'/');
        if ($dir{0} === '/') {
            $new_dir = '/'.$new_dir;
        }
        while ($token = strtok('/')) {
            $new_dir = $new_dir.'/'.$token;

            if (!is_dir($new_dir)) {
                mkdir($new_dir);
            }
        }
    }
}

/* vim: set expandtab ts=4 sw=4 sts=4: et*/
