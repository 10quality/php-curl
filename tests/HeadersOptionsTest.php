<?php

use PHPUnit\Framework\TestCase;
/**
 * Test headers and options.
 *
 * @link https://httpbin.org
 *
 * @author Cami M <info@10quality.com>
 * @copyright 10 Quality <info@10quality.com>
 * @package [php-curl]
 * @version 1.0.0
 */
class HeadersOptionsTest extends TestCase
{
    /**
     * Test request.
     * @since 1.0.0
     */
    public function testHeaders()
    {
        // Prepare and execute
        $response = curl_request(
            'https://httpbin.org/bearer',
            'GET',
            [],
            ['Authorization: test']
        );
        $json = json_decode($response);
        // Assert
        $this->assertIsString($response);
    }
    /**
     * Test request.
     * @since 1.0.0
     */
    public function testHeadersFailed()
    {
        // Prepare and execute
        $response = curl_request('https://httpbin.org/bearer');
        // Assert
        $this->assertIsString($response);
        $this->assertEmpty($response);
    }
    /**
     * Test request. TESTING ON LOCALMACHINE
     * @since 1.0.0
     */
    public function testOptionsOverride()
    {
        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('SSL certificate problem: unable to get local issuer certificate');
        // Run
        $response = curl_request(
            'https://httpbin.org/get',
            'GET',
            [],
            [],
            [
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_SSL_VERIFYPEER => 2,
            ]
        );
    }
}