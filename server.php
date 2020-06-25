<?php

define('VALET_HOME_PATH', __DIR__ . '/.config');

@mkdir(VALET_HOME_PATH, 0777, true);
@mkdir('tmp', 0777, true);

if (! file_exists(VALET_HOME_PATH . '/config.json')) {
    file_put_contents(
        VALET_HOME_PATH . '/config.json',
        json_encode([
            'tld' => 'test',
            'paths' => [
                realpath(__DIR__ . '/..'),
            ],
        ])
    );
}

if (
    ! file_exists('tmp/server-patched.php') or
    filemtime('valet/server.php') > filemtime('tmp/server-patched.php')
) {
    file_put_contents(
        'tmp/server-patched.php',
        str_replace(
            "define('VALET_HOME_PATH'",
            "// define('VALET_HOME_PATH'",
            file_get_contents('valet/server.php')
        )
    );
}

include 'valet/server.php';
