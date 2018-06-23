<?php

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
class HeadersOptionsTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test request.
     * @since 1.0.0
     */
    public function testHeaders()
    {
        // Prepare and execute
        $response = curl_request(
            'http://httpbin.org/bearer',
            'GET',
            [],
            ['Authorization: test']
        );
        $json = json_decode($response);
        // Assert
        $this->assertInternalType('string', $response);
        $this->assertTrue($json->authenticated);
        $this->assertEquals('test', $json->token);
    }
    /**
     * Test request.
     * @since 1.0.0
     */
    public function testHeadersFailed()
    {
        // Prepare and execute
        $response = curl_request('http://httpbin.org/bearer');
        // Assert
        $this->assertInternalType('string', $response);
        $this->assertEmpty($response);
    }
    /**
     * Test request. TESTING ON LOCALMACHINE
     * @since 1.0.0
     *
     * @expectedException        Exception
     * @expectedExceptionMessage SSL certificate problem: unable to get local issuer certificate
     */
    public function testOptionsOverride()
    {
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