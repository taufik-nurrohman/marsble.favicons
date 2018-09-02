<?php
/**
 * Favicons
 * Proxying favicon from a domain name
 *
 * This is Class for Favicons, this script is allowing you
 * to proxying favicon from a domain name and set default
 * favicon if no one exist.
 *
 * @author    Marsble Team
 * @license   MIT
 * @copyright 2018 Marsble
 */

// Debuging
error_reporting(0);

class Favicons
{
    public $url;
    public $default = 'favicon_default.ico'; // Set default favicon
    public $expires = 300; // 5 minutes
    public $version = '1.1';
    public $type = 'x-icon';
    public $userAgent = "MarsbleFavicons";

    function __construct()
    {
        // Set the content type
        header("Content-Type: image/$this->type");

        // Set cache expires
        header("Cache-Control: public, max-age=$this->expires");
    }

    /**
     * Add HTTP
     */
    public function addhttp($url)
    {
        if (!preg_match('~^(?:f|ht)tps?://~i', $url) ) {
            $url = 'http://' . $url;
        }
        return $url;
    }

    /**
     * Proxy with cURL
     */
    public function getFavicon($url)
    {
        $options = array(
        CURLOPT_FAILONERROR     => true,
        CURLOPT_FOLLOWLOCATION  => true,
        CURLOPT_MAXREDIRS       => 2,
        CURLOPT_USERAGENT       => $this->userAgent . '/' . $this->version,
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_TIMEOUT         => 15
        );

        $url = parse_url($this->addhttp($url), PHP_URL_HOST);

        $ch = curl_init($url . '/favicon.ico');
        curl_setopt_array($ch, $options);
        $url = curl_exec($ch);
        curl_close($ch);

        // If get nothing, display the default favicon
        if ($url == false ) {
            $default = file_get_contents(__DIR__ . '/' . $this->default);

            header('Cache-Control: public, max-age=0');

            echo $default;

            return;
        }

        return $url;
    }
}
