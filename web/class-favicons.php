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

class Favicons {

    const version = '1.2';

    public $domain = null;
    public $favicon = 'favicon_default.ico'; // Set default favicon
    public $expires = 86400; // 1 Day
    public $userAgent = 'MarsbleFavicons';
    public $debugMode = false;

    protected $contentType = 'text/plain';
    protected $faviconURL = null;
    protected $blob = null;

    public function __construct($domain) {
        $this->domain = $domain;
    }

    protected function cURL($url) {
        $c = curl_init($url);
        curl_setopt_array($c, [
            CURLOPT_FAILONERROR => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 2,
            CURLOPT_USERAGENT => $this->userAgent . '/' . self::version, // Custom User Agent
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPGET => true,
            CURLOPT_TIMEOUT => 15
        ]);
        $result = curl_exec($c);
        curl_close($c);
        return $result;
    }

    protected function removeProtocols($domain) {
        if (strpos($domain, '://') !== false) {
            return preg_replace('#^(?:f|ht)tps?://#', "", $domain);
        }
        return $domain;
    }

    protected function useSSL() {
        return !empty($_GET['ssl']);
    }

    public function process() {

        $domain = $this->removeProtocols($this->domain);

        if ($this->useSSL()) {
            $prefix = 'https://';
            $suffix = '?ssl=1';
        } else {
            $prefix = 'http://';
            $suffix = "";
        }

        // Get favicon from URL
        if ($result = $this->cURL($faviconURL = $prefix . $domain . '/favicon.ico' . $suffix)) {
            $this->contentType = 'image/x-icon';
            $this->faviconURL = $faviconURL;
            $this->blob = $result;
        // Get favicon from HTML `<link>`
        } else if ($result = file_get_contents($prefix . $domain)) {
            if (
                stripos($result, '<link ') !== false &&
                stripos($result, ' href=') !== false &&
                stripos($result, ' rel=') !== false &&
                preg_match_all('#<link(?:\s[^<>]+?)?\/?>#i', $result, $m)
            ) {
                foreach ($m[0] as $html) {
                    // Check for `rel="shortcut icon"` and `rel="icon"` string
                    if (preg_match('#\srel=([\'"]?)(?:apple-touch-icon|msapplication-TileImage|(?:shortcut\s+)?icon)\1#', $html)) {
                        // Check for `href` attribute
                        if (preg_match('#\shref=([\'"]?)(.*?)\1#', $html, $m)) {
                            $faviconURL = $m[2];
                            // Maybe relative protocol
                            if (strpos($faviconURL, '//') === 0) {
                                $faviconURL = $prefix . substr($faviconURL, 2);
                            // Maybe relative path
                            } else if (strpos($faviconURL, '/') === 0) {
                                $faviconURL = $prefix . $domain . $faviconURL;
                            }
                            $this->faviconURL = $faviconURL;
                            if ($result = $this->cURL($faviconURL)) {
                                // Check for `type` attribute
                                if (preg_match('#\stype=([\'"]?)(.*?)\1#', $html, $m)) {
                                    // Set custom favicon type
                                    $this->contentType = $m[2];
                                // Else ...
                                } else {
                                    $type = pathinfo($favicon, PATHINFO_EXTENSION);
                                    switch ($type) {
                                        case 'ico':
                                            $type = 'x-icon';
                                        break;
                                        case 'jpg':
                                            // <https://stackoverflow.com/a/37266399/1163000>
                                            $type = 'jpeg';
                                        break;
                                    }
                                    // ... gues it by the file extension
                                    $this->contentType = 'image/' . $type;
                                }
                                $this->blob = $result;
                            }
                            break;
                        }
                    }
                }
            }
        }

        // Last check, return the default favicon!
        if (!$this->blob) {
            $this->contentType = 'image/x-icon';
            $this->faviconURL = null;
            $this->blob = file_get_contents(__DIR__ . '/' . $this->favicon);
        }

    }

    public function draw() {
        $this->process();
        if (!$this->debugMode) {
            if ($this->expires) {
                header('Cache-Control: public, max-age=' . $this->expires);
            } else {
                header('Cache-Control: no-cache');
            }
            if (isset($_GET['raw'])) {
                header('Content-Type: text/plain');
                echo $this->blob;
            } else if (isset($_GET['base64'])) {
                header('Content-Type: text/plain');
                echo 'data:' . $this->contentType . ';base64,' . base64_encode($this->blob);
            } else {
                header('Content-Type: ' . $this->contentType);
                echo $this->blob;
            }
        } else {
            var_dump(
                $this->contentType,
                $this->faviconURL,
                $this->blob
            );
        }
        exit;
    }

}