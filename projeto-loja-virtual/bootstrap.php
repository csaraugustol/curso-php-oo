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
 * Configuração banco
 */
define('DB_NAME', '');
define('DB_HOST', '');
define('DB_USER', '');
define('DB_PASSWORD', '');
define('DB_CHARSET', 'UTF8');

/**
 * PagSeguro Environment
 */
putenv('PAGSEGURO_ENV=');
putenv('PAGSEGURO_EMAIL=');
putenv('PAGSEGURO_TOKEN_SANDBOX=');
putenv('PAGSEGURO_CHARSET=UTF-8');

\PagSeguro\Library::initialize();
\PagSeguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
\PagSeguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");
