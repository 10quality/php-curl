<?php

use PHPUnit\Framework\TestCase;
/**
 * Test request.
 *
 * @link https://archive.readme.io/docs/availability-borrowing
 *
 * @author Cami M <info@10quality.com>
 * @copyright 10 Quality <info@10quality.com>
 * @package [php-curl]
 * @version 1.0.0
 */
class PostTest extends TestCase
{
    /**
     * Test request.
     * @since 1.0.0
     * @group post
     */
    public function testPost()
    {
        // Prepare and execute
        $response = curl_request(
            'https://httpbin.org/anything',
            'POST',
            [
                'test' => '123',
            ]
        );
        $json = json_decode($response);
        // Assert
        $this->assertIsString($response);
        $this->assertTrue(strlen($response)>0);
        $this->assertEquals(123, $json->form->test);
    }
    /**
     * Test request.
     * @since 1.0.0
     * @group post
     */
    public function testGetPost()
    {
        // Prepare and execute
        $response = curl_request(
            'https://httpbin.org/anything',
            'POST',
            [
                'query_string' => [
                    'q' => 'abc',
                ],
                'request_body' => [
                    'test' => '123',
                ],
            ]
        );
        $json = json_decode($response);
        // Assert
        $this->assertIsString($response);
        $this->assertTrue(strlen($response)>0);
        $this->assertEquals('abc', $json->args->q);
        $this->assertEquals(123, $json->form->test);
    }
}