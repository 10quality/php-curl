<?php

/**
 * Global functions.
 *
 * @author Cami M <info@10quality.com>
 * @copyright 10 Quality <info@10quality.com>
 * @package [php-curl]
 * @version 1.0.0
 */

if (!function_exists('curl_request')) {
    /**
     * Performes a HTTP request.
     * @since 1.0.0
     *
     * @see Curl::request()
     *
     * @param string $url      Request URL.
     * @param string $method   Request method.
     * @param array  $data     POST body parameters, GET query string or a combination of both.
     * @param array  $headers  Headers to use.
     * @param array  $options  Curl options to use.
     * @param mixed  $callable Callable to run method is unknown.
     *
     * @return string|bool Response string or false if request failed.
     */
    function curl_request($url, $method = 'GET', $data = [], $headers = [], $options = [], $callable = null)
    {
        return Curl::request($url, $method, $data, $headers, $options, $callable);
    }
}

if (!function_exists('get_curl_contents')) {
    /**
     * Performes a HTTP request.
     * @since 1.0.0
     *
     * @see curl_request()
     *
     * @param string $url      Request URL.
     * @param string $method   Request method.
     * @param array  $data     POST body parameters, GET query string or a combination of both.
     * @param array  $headers  Headers to use.
     * @param array  $options  Curl options to use.
     * @param mixed  $callable Callable to run method is unknown.
     *
     * @return string|bool Response string or false if request failed.
     */
    function get_curl_contents($url, $method = 'GET', $data = [], $headers = [], $options = [], $callable = null)
    {
        return curl_request($url, $method, $data, $headers, $options, $callable);
    }
}