# Curl Wrapper (PHP)

[![Latest Stable Version](https://poser.pugx.org/10quality/php-curl/v/stable)](https://packagist.org/packages/10quality/php-curl)
[![Total Downloads](https://poser.pugx.org/10quality/php-curl/downloads)](https://packagist.org/packages/10quality/php-curl)
[![License](https://poser.pugx.org/10quality/php-curl/license)](https://packagist.org/packages/10quality/php-curl)

Library (wrapper) that provides functionality for when doing requests using Curl. Perfect for when developing custom API connectivity or creating generic requests.

## Install

```bash
composer require 10quality/php-curl
```

## Usage

### Request call options

You can make your request call using the following options:
```php
// With global `curl_request()`
$response = curl_request('http://my-api.com');

// With global `get_curl_contents()`
// --- Which is an alias of `curl_request()`
$response = get_curl_contents('http://my-api.com');

// With class `Curl`
$response = Curl::request('http://my-api.com');
```

### Parameters

Parameters sample:
```php
$response = curl_request($url, $method, $data, $headers, $options);
```

| Parameter | Type | Description |
| --------- | ---- | ----------- |
| `$url` | `string` | Request URL. |
| `$method` | `string` | Request method (`GET` by default). Available options: **GET**, **POST**, **JPOST**, **JPUT**, **JGET**, **JDELETE**, **JUPDATE**, **JPATCH**. Json request is sent in those with the *J* prefix. |
| `$data` | `array` | The request data to send via query string, request body or both (Empty by default). |
| `$headers` | `array` | The list of request headers to add (Empty by default). |
| `$options` | `array` | The list of curl options to add (Empty by default). |
| `$callable` | `mixed` | Callable for when an unknown method is requested (null by default). |

#### Query string and post body

GET sample:
```php
$response = curl_request(
    'http://my-api.com',
    'GET',
    [
        'action'    => 'get-countries',
        'format'    => 'xml',
    ]
);
```

POST sample:
```php
$response = curl_request(
    'http://my-api.com',
    'POST',
    [
        'action'    => 'get-countries',
        'format'    => 'xml',
        'search'    => 'united',
        'limit'     => 5,
    ]
);
```

Using both:

Define `$data` array keys *query_string* and *request_body* to send some parameters as query string or request body. In the following example `action` and `format` will send as query string and `search` and `limit` as post body:
```php
$response = curl_request(
    'http://my-api.com',
    'POST',
    [
        'query_string' => [
            'action'    => 'get-countries',
            'format'    => 'xml',
        ],
        'request_body' => [
            'search'    => 'united',
            'limit'     => 5,
        ],
    ]
);
```

The example below will generate the following request:
```bash
url:        http://my-api.com?action=get-countries&format=xml
post body:  search=united&limit=5
```

#### Headers

The following example shows how to send headers:
```php
$response = curl_request(
    'http://my-api.com',
    'GET',
    ['action' => 'get-countries'],
    [
        'Authorization: Bearer -token-',
        'Custom: Value',
    ]
);
```

#### Curl options

The following example shows how to add curl options or override existing:
```php
$response = curl_request(
    'http://my-api.com',
    'GET',
    ['action' => 'get-countries'],
    [],
    [
        CURLOPT_TIMEOUT         => 60,
        CURLOPT_RETURNTRANSFER  => 0,
        CURLOPT_USERAGENT       => 'Custom/Agent 007',
    ]
);
```

#### Custom request method using callables

If you need to make a special request to a different method from the ones supported, you can use the the `$callable` parameter as shown in the following sample:
```php
$response = curl_request(
    'http://my-api.com',
    'XPOST',
    [],
    ['temperature' => 60, 'uptime' => 4564612131],
    [],// headers
    [],// options
    function($curl, $data) {
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'XPOST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        return $curl;
    }
);
```

**NOTE:** The callable will receive `$curl` and `$data` as parameters and it is expecting to return the variable `$curl` back.

## Guidelines

PSR-2 coding standards.

## Copyright and License

MIT License - (C) 2018 [10 Quality](https://www.10quality.com/).