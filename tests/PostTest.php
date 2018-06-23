<?php

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
class PostTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test request.
     * @since 1.0.0
     */
    public function testPost()
    {
        // Prepare and execute
        $response = curl_request(
            'http://archive.org/services/loans/beta/loan/index.php',
            'POST',
            [
                'action' => 'availability',
                'validate' => 1,
                'identifiers' => 'adventuresofoli00dick,alchemist00jons_2',
            ]
        );
        $json = json_decode($response);
        // Assert
        $this->assertInternalType('string', $response);
        $this->assertTrue(strlen($response)>0);
        $this->assertTrue($json->success);
        $this->assertTrue(isset($json->responses->adventuresofoli00dick));
    }
    /**
     * Test request.
     * @since 1.0.0
     */
    public function testGetPost()
    {
        // Prepare and execute
        $response = curl_request(
            'http://archive.org/services/loans/beta/loan/index.php',
            'POST',
            [
                'query_string' => [
                    'identifiers' => 'adventuresofoli00dick,alchemist00jons_2',
                ],
                'request_body' => [
                    'action' => 'availability',
                    'validate' => 1,
                ],
            ]
        );
        $json = json_decode($response);
        // Assert
        $this->assertInternalType('string', $response);
        $this->assertTrue(strlen($response)>0);
        $this->assertTrue($json->success);
        $this->assertTrue(isset($json->responses->adventuresofoli00dick));
    }
}