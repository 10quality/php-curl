<?php

/**
 * Curl wrapper.
 *
 * @author Cami M <info@10quality.com>
 * @copyright 10 Quality <info@10quality.com>
 * @package [php-curl]
 * @version 1.0.1
 */
class Curl
{
    /**
     * Class instance for singletone.
     * @since 1.0.0
     *
     * @var Curl|object
     */
    static protected $instance;
    /**
     * Curl instantiated object.
     * @since 1.0.0
     *
     * @var object
     */
    protected $curl;
    /**
     * Latest request headers used.
     * @since 1.0.0
     *
     * @var array
     */
    protected $headers = [];
    /**
     * Latest curl options used.
     * @since 1.0.0
     *
     * @var array
     */
    protected $options = [];

    /**
     * Performes a HTTP request.
     * Static call.
     * @since 1.0.0
     *
     * @param string $url     Request URL.
     * @param string $method  Request method.
     * @param array  $data    POST body parameters, GET query string or a combination of both.
     * @param array  $headers Headers to use.
     * @param array  $options Curl options to use.
     *
     * @return string|bool Response string or false if request failed.
     */
    public static function request($url, $method = 'GET', $data = [], $headers = [], $options = [])
    {
        if (!isset(static::$instance))
            static::$instance = new self;
        return static::$instance->__request($url, $method, $data, $headers, $options);
    }

    /**
     * Performes a HTTP request.
     * @since 1.0.0
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
    public function __request($url, $method = 'GET', $data = [], $headers = [], $options = [], $callable = null)
    {
        $this->headers = $headers;
        $this->options = $options;
        if (!is_array( $this->headers))
            $this->headers = [];
        if (!is_array( $this->options))
            $this->options = [];
        $error = null;
        if (!is_array($data))
            throw new Exception('Data parameter must be an array.');
        // Set querystring
        $qs = isset($data['query_string'])
            ? $data['query_string']
            : ($method === 'GET'
                ? $data
                : null
            );
        if ($qs)
            $url .= (strpos($url, '?') !== false ? '&' : '?').http_build_query($qs);
        // Set data
        if (isset($data['request_body']))
            $data = $data['request_body'];
        // Begin
        $this->__setCurl(preg_match('/https/', $url));
        // Prepare request
        curl_setopt($this->curl, CURLOPT_URL, $url);
        // Set method
        switch ($method) {
            case 'GET':
                curl_setopt($this->curl, CURLOPT_POST, 0);
                break;
            case 'POST':
                curl_setopt($this->curl, CURLOPT_POST, 1);
                if (count($data) > 0)
                    curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($data));
                break;
            case 'JPOST':
            case 'JPUT':
            case 'JGET':
            case 'JDELETE':
            case 'JUPDATE':
                $json = json_encode($data);
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, str_replace('J', '', $method));
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, $json);
                // Rewrite headers
                curl_setopt($this->curl, CURLOPT_HTTPHEADER, array_merge(array(
                    'Content-Type: application/json',
                    'Content-Length: '.strlen($json),
                ), $this->headers));
                break;
            default:
                if ($callable)
                    $this->curl = call_user_func_array($callable, [$this->curl, $data]);
                break;
        }
        // Get response
        $response = curl_exec($this->curl);
        if ($response === false)
            $error = curl_error($this->curl);
        curl_close($this->curl);
        if ($error)
            throw new Exception($error);
        return $response;
    }
    /**
     * Sets curl properties and settings.
     * @since 1.0.0
     *
     * @see http://us3.php.net/manual/en/book.curl.php
     *
     * @param bool $is_https Flag that indictes if endpoint is secured.
     */
    private function __setCurl($is_https = false)
    {
        // Init
        $this->curl = curl_init();
        // Sets basic parameters
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, isset($this->options[CURLOPT_RETURNTRANSFER]) ? $this->options[CURLOPT_RETURNTRANSFER] : 1);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, isset($this->options[CURLOPT_TIMEOUT]) ? $this->options[CURLOPT_TIMEOUT] : 100);
        // Set parameters to maintain cookies across sessions
        curl_setopt($this->curl, CURLOPT_COOKIESESSION, isset($this->options[CURLOPT_COOKIESESSION]) ? $this->options[CURLOPT_COOKIESESSION] : true);
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, isset($this->options[CURLOPT_COOKIEFILE]) ? $this->options[CURLOPT_COOKIEFILE] : '/tmp/cookies_file');
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, isset($this->options[CURLOPT_COOKIEJAR]) ? $this->options[CURLOPT_COOKIEJAR] : '/tmp/cookies_file');
        curl_setopt(
            $this->curl, CURLOPT_USERAGENT,
            isset($this->options[CURLOPT_USERAGENT]) 
                ? $this->options[CURLOPT_USERAGENT]
                : 'Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7'
        );
        // Set SSL
        if ($is_https) {
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, isset($this->options[CURLOPT_SSL_VERIFYHOST]) ? $this->options[CURLOPT_SSL_VERIFYHOST] : 0);
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, isset($this->options[CURLOPT_SSL_VERIFYPEER]) ? $this->options[CURLOPT_SSL_VERIFYPEER] : 0);
        }
        // Set headers
        if (!empty($this->headers))
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
        // Add additional options
        if (!empty($this->options)) {
            unset($this->options[CURLOPT_RETURNTRANSFER]);
            unset($this->options[CURLOPT_TIMEOUT]);
            unset($this->options[CURLOPT_COOKIESESSION]);
            unset($this->options[CURLOPT_COOKIEFILE]);
            unset($this->options[CURLOPT_COOKIEJAR]);
            unset($this->options[CURLOPT_USERAGENT]);
            unset($this->options[CURLOPT_SSL_VERIFYHOST]);
            unset($this->options[CURLOPT_SSL_VERIFYPEER]);
            unset($this->options[CURLOPT_HTTPHEADER]);
            unset($this->options[CURLOPT_URL]);
            unset($this->options[CURLOPT_POST]);
            unset($this->options[CURLOPT_POSTFIELDS]);
            unset($this->options[CURLOPT_CUSTOMREQUEST]);
            foreach ($this->options as $key => $option) {
                curl_setopt($this->curl, $key, $option);
            }
        }
    }
}