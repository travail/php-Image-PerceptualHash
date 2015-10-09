<?php

use Image\PerceptualHash;
use Image\PerceptualHash\Exception\FileNotFoundException;

class BasicTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->path_non_existent_inuo = __DIR__ . '/../images/non_existent_inuo.jpg';
        $this->path_inuo1 = __DIR__ . '/../images/inuo1.jpg';
        $this->path_inuo2 = __DIR__ . '/../images/inuo2.jpg';
        $this->hex_inuo1 = 'fdd7a9d1c383c1ff';
        $this->hex_inuo2 = 'f7f30200c3c3c3ff';
        $this->bin_inuo1 = '1111110111010111101010011101000111000011100000111100000111111111';
        $this->bin_inuo2 = '1111011111110011000000100000000011000011110000111100001111111111';
    }

    public function testVersion()
    {
        $version = PerceptualHash::version();
        $this->assertTrue(isset($version));
        $this->assertTrue(is_string($version));
        $this->assertSame(PerceptualHash::VERSION, $version);
    }

    public function testLoad()
    {
        try {
            $ph = new PerceptualHash($this->path_non_existent_inuo);
        } catch (FileNotFoundException $e) {
            $this->assertTrue($e instanceof FileNotFoundException);
        }
    }
}
