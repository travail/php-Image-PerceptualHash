<?php

use Image\PerceptualHash;
use Image\PerceptualHash\Algorithm\PerceptionHash;

class PerceptionHashTest extends PHPUnit_Framework_TestCase
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
        $this->hex_image1 = 'eacc911396406eb7';
        $this->hex_image2 = 'b9f8910e89f44e43';
        $this->bin_image1 = '1110101011001100100100010001001110010110010000000110111010110111';
        $this->bin_image2 = '1011100111111000100100010000111010001001111101000100111001000011';
    }

    public function testCalculateBinaryHash()
    {
        $algorithm = new PerceptionHash;
        $ph = new PerceptualHash($this->path_image1, $algorithm);
        $bin = $ph->bin();

        $this->assertEquals(64, strlen($bin));
        $this->assertEquals($this->bin_image1, $bin);
    }

    public function testCalculateHexHash()
    {
        $algorithm = new PerceptionHash;
        $ph = new PerceptualHash($this->path_image1, $algorithm);
        $hex = $ph->hex();

        $this->assertEquals(16, strlen($hex));
        $this->assertEquals($this->hex_image1, $hex);
    }

    public function testCompareDifferentImages()
    {
        $algorithm = new PerceptionHash;
        $ph = new PerceptualHash($this->path_image1, $algorithm);
        $diff = $ph->compare($this->path_image2);

        $this->assertEquals(26, $diff);
    }

    public function testCompareSameImages()
    {
        $algorithm = new PerceptionHash;
        $ph = new PerceptualHash($this->path_image1, $algorithm);
        $diff = $ph->compare($this->path_image1);

        $this->assertEquals(0, $diff);
    }
}
