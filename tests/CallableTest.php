<?php

use PHPUnit\Framework\TestCase;
/**
 * Test callable parameter.
 *
 * @link https://httpbin.org
 *
 * @author Cami M <info@10quality.com>
 * @copyright 10 Quality <info@10quality.com>
 * @package [php-curl]
 * @version 1.0.0
 */
class CallableTest extends TestCase
{
    /**
     * Test request.
     * @since 1.0.0
     */
    public function testPatch()
    {
        // Prepare and execute
        $response = curl_request(
            'http://httpbin.org/bearer',
            'PATCH',
            ['code' => 200],
            ['Content-Type: application/json'],
            [],
            function($curl, $data) {
                $json = json_encode($data);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
                curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
                // Rewrite headers
                curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Length: '.strlen($json)]);
                return $curl;
            }
        );
        $json = json_decode($response);
        // Assert
        $this->assertInternalType('string', $response);
    }
}