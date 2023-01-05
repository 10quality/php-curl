<?php

use PHPUnit\Framework\TestCase;
/**
 * Test request.
 *
 * @author Cami M <info@10quality.com>
 * @copyright 10 Quality <info@10quality.com>
 * @package [php-curl]
 * @version 1.0.0
 */
class GetTest extends TestCase
{
    /**
     * Test request.
     * @since 1.0.0
     */
    public function testGet()
    {
        // Prepare and execute
        $response = curl_request('http://httpbin.org/get');
        // Assert
        $this->assertIsString($response);
        $this->assertTrue(strlen($response)>0);
    }
    /**
     * Test request.
     * @since 1.0.0
     */
    public function testGet404()
    {
        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Could not resolve host: unknow.page.for.test');
        // Prepare and execute
        $response = curl_request('http://unknow.page.for.test/');
    }
    /**
     * Test request.
     * @since 1.0.0
     */
    public function testQueryStringBuilder()
    {
        // Prepare and execute
        $response = curl_request(
            'https://api.jikan.moe/v4/anime',
            'GET',
            [
                'q' => 'One Piece',
            ]
        );
        $json = json_decode($response);
        // Assert
        $this->assertIsString($response);
        $this->assertTrue(strlen($response)>0);
        $this->assertNotEmpty($json->data);
    }
}