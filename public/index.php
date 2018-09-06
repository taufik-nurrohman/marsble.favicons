<?php
/**
 * Marsble Favicons Proxy
 *
 * @author    Marsble Team
 * @copyright Marsble. MIT.
 */

function is_home() {
    return $_SERVER['REQUEST_URI'] == '/';
}

/**
 * Remove HTTP
 */
function removehttp($url)
{
    if (preg_replace('#^https?://#', '', $url) ) {
        $url = preg_replace('#^https?://#', '', $url);
    }

    return $url;
}

if ( is_home() ) {

    require dirname( __DIR__ ) . '/home.php';

} else {

    require dirname(  __DIR__ ) . '/class-favicons.php';

    $favicon = new Favicons;
    $url = removehttp($_SERVER['REQUEST_URI']);

    echo $favicon->getFavicon($url);

}
