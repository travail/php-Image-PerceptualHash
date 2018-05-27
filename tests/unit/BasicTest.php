<?php

namespace Image\Tests;

use Image\PerceptualHash;
use Image\PerceptualHash\Exception\FileNotFoundException;
use PHPUnit\Framework\TestCase;

class BasicTest extends TestCase
{
    private $path_non_existent_image;
    private $path_image1;
    private $path_image2;
    private $hex_image1;
    private $hex_image2;
    private $bin_image1;
    private $bin_image2;

    public function setUp()
    {
        $this->path_non_existent_image = __DIR__ . '/../images/non_existent_inuo.jpg';
        $this->path_image1 = __DIR__ . '/../images/inuo1.jpg';
        $this->path_image2 = __DIR__ . '/../images/inuo2.jpg';
        $this->hex_image1 = 'fdd7a9d1c383c1ff';
        $this->hex_image2 = 'f7f30200c3c3c3ff';
        $this->bin_image1 = '1111110111010111101010011101000111000011100000111100000111111111';
        $this->bin_image2 = '1111011111110011000000100000000011000011110000111100001111111111';
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
            new PerceptualHash($this->path_non_existent_image);
        } catch (FileNotFoundException $e) {
            $this->assertTrue($e instanceof FileNotFoundException);
        }
    }
}
