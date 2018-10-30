<?php

/**
 * Marsble Favicons Proxy
 *
 * @author    Marsble Team
 * @copyright Marsble. MIT.
 */


/**
 * Settings
 */

define('MARSBLE_DEBUG', isset($_GET['debug']));

// <https://stackoverflow.com/q/68651/1163000>
// <https://stackoverflow.com/a/2183140/1163000>
// TODO: Allow underscores in domain name
$k = array_keys(isset($_GET) ? $_GET : []);
define('MARSBLE_URL_PATH', strtr(array_shift($k), '_', '.'));
define('MARSBLE_URL_QUERY', http_build_query($_GET));


/**
 * Check Home Page
 */

function is_home() {
    return !MARSBLE_URL_PATH;
}


error_reporting(MARSBLE_DEBUG ? E_ALL | E_STRICT : 0);

if (is_home()) {
    require __DIR__ . '/home.php';
} else {
    require __DIR__ . '/class-favicons.php';
    $favicon = new Favicons(MARSBLE_URL_PATH);
    // `http://127.0.0.1/example.com?cache=false`
    if (isset($_GET['cache']) && !$_GET['cache']) {
        $favicon->expires = 0;
    }
    $favicon->debugMode = MARSBLE_DEBUG;
    $favicon->draw();
}