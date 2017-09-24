<?php

use Image\PerceptualHash;

class AverageHashTest extends PHPUnit_Framework_TestCase
{
    private $path_image1;
    private $path_image2;
    private $hex_image1;
    private $hex_image2;
    private $bin_image1;
    private $bin_image2;

    public function setUp()
    {
        $this->path_image1 = __DIR__ . '/../images/inuo1.jpg';
        $this->path_image2 = __DIR__ . '/../images/inuo2.jpg';
        $this->hex_image1 = 'fdd7a9d1c383c1ff';
        $this->hex_image2 = 'f7f30200c3c3c3ff';
        $this->bin_image1 = '1111110111010111101010011101000111000011100000111100000111111111';
        $this->bin_image2 = '1111011111110011000000100000000011000011110000111100001111111111';
    }

    public function testCalculateBinaryHash()
    {
        $ph = new PerceptualHash($this->path_image1);
        $bin = $ph->bin();

        $this->assertEquals(64, strlen($bin));
        $this->assertEquals($this->bin_image2, $bin);
    }

    public function testCalculateHexHash()
    {
        $ph = new PerceptualHash($this->path_image1);
        $hex = $ph->hex();

        $this->assertEquals(16, strlen($hex));
        $this->assertEquals($this->hex_image2, $hex);
    }

    public function testCompareDifferentImages()
    {
        $ph = new PerceptualHash($this->path_image1);
        $diff = $ph->compare($this->path_image2);

        $this->assertEquals(15, $diff);
    }

    public function testCompareSameImages()
    {
        $ph = new PerceptualHash($this->path_image1);
        $diff = $ph->compare($this->path_image2);

        $this->assertEquals(0, $diff);
    }
}
