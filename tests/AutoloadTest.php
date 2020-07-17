<?php

use PHPUnit\Framework\TestCase;
/**
 * Test autoload of global functions.
 *
 * @author Cami M <info@10quality.com>
 * @copyright 10 Quality <info@10quality.com>
 * @package [php-curl]
 * @version 1.0.0
 */
class AutoloadTest extends TestCase
{
    /**
     * Test function definition.
     * @since 1.0.0
     */
    public function testCurlRequestDefinition()
    {
        $this->assertTrue(function_exists('curl_request'));
    }
    /**
     * Test function definition.
     * @since 1.0.0
     */
    public function testGetCurlContentsDefinition()
    {
        $this->assertTrue(function_exists('get_curl_contents'));
    }
    /**
     * Test class definition.
     * @since 1.0.0
     */
    public function testClassDefinition()
    {
        $this->assertEquals('Curl', Curl::class);
    }
}