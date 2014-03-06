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

/**
 * コマンド
 */
class Command
{
    private $cmd, $opt;
    private $desc = array(
        0=>array("pipe","r"),
        1=>array("pipe","w"),
        2=>array("pipe","w")
    );
    private $pipes;
    private $input_file_list = array();
    private $input_string = '';
    private $error = '';
    private $proc;


    public function __construct ($cmd, $opt = null)
    {
        $this->cmd = $cmd;
        $this->opt = $opt;
    }

    public function inputFile ($file)
    {
        $this->input_file_list[] = $file;
        return $this;
    }

    public function inputString ($string)
    {
        $this->input_string = $string;
        return $this;
    }

    public function buildCommand ()
    {
        return $this->cmd.' '.$this->opt;
    }

    public function execute( )
    {
        $this->proc = proc_open($this->buildCommand(), $this->desc, $this->pipes);

        if (!is_resource($this->proc)) {
            throw new CommandCantExecut($this->buildCommand());
        }

        foreach ($this->input_file_list as $file) {
            fwrite($this->pipes[0], file_get_contents($file));
        }
        if(!empty($this->input_string)) {
            fwrite($this->pipes[0], $this->input_string);
        }
        fclose($this->pipes[0]);

        return $this;
    }

    public function getStdError (&$stderr)
    {
        $stderr = $this->pipes[2];
        return $this;
    }

    public function getStdOut (&$stdout)
    {
        $stdout = $this->pipes[1];
        return $this;
    }

    public function __destruct( )
    {
        if (is_resource($this->pipes[1])) {
            fclose($this->pipes[1]);
        }
        if (is_resource($this->pipes[2])) {
            fclose($this->pipes[2]);
        }
        if (is_resource($this->proc)) {
            $return_value = proc_close($this->proc);
        }
    }
}

/* vim: set expandtab ts=4 sw=4 sts=4: et*/
