<?php
require_once __DIR__ . '/../src/Cloudinary.php';

use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function test_config_nested_values()
    {
        \Cloudinary::config_from_url('cloudinary://key:secret@test123?foo[bar]=value');
        $config = \Cloudinary::config();
        $this->assertArrayHasKey('foo', $config);
        $this->assertArrayHasKey('bar', $config['foo']);
        $this->assertEquals('value', $config['foo']['bar']);
    }
}
