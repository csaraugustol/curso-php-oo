<?php

require __DIR__ . '/vendor/autoload.php';

/**
 * Configução views
 */
define('HOME', 'http://localhost:3000');
define('VIEWS_PATH', __DIR__ . '/views/');
define('APP_DEBUG', true);
define('UPLOAD_PATH', __DIR__ . '/public/uploads/');

/**
 * Configução banco
 */
define('DB_NAME', 'loja_virtual_db');
define('DB_HOST', 'localhost');
define('DB_USER', 'indb');
define('DB_PASSWORD', '230700');
define('DB_CHARSET', 'UTF8');

/**
 * PagSeguro Environment
 */

 putenv('PAGSEGURO_ENV=sandbox');
 putenv('PAGSEGURO_EMAIL=cesar.leitejm@hotmail.com');
 putenv('PAGSEGURO_TOKEN_SANDBOX=838E600E8D96489083EF3A51F3ACBDF3');
 putenv('PAGSEGURO_CHARSET=UTF-8');

\PagSeguro\Library::initialize();
\PagSeguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
\PagSeguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");
