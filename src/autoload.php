<?php
/**
 * Seaf Auto Load
 */
Seaf::di('autoLoader')->addNamespace(
    'Seaf\\Cli',
    null,
    dirname(__FILE__).'/Cli'
);

Seaf::register('cli', 'Seaf\Cli\FrontController');
