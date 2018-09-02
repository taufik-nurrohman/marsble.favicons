<?php
/**
 * Marsble Favicons Proxy
 *
 * @author    Marsble Team
 * @copyright Marsble. MIT.
 */

if ($_SERVER['REQUEST_URI'] == '/') {
    include __DIR__ . '/home.php';
} else {
    require __DIR__ . '/class-favicons.php';

    $favicon = new Favicons;

    header('Access-Control-Allow-Origin: *');
    echo $favicon->getFavicon($_SERVER['REQUEST_URI']);
}
