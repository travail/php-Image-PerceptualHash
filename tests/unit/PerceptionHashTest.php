<?php

use Image\PerceptualHash;
use Image\PerceptualHash\Algorithm\PerceptionHash;

class PerceptionHashTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->path_inuo1 = __DIR__ . '/../images/inuo1.jpg';
        $this->path_inuo2 = __DIR__ . '/../images/inuo2.jpg';
        $this->hex_inuo1 = 'eacc911396406eb7';
        $this->hex_inuo2 = 'b9f8910e89f44e43';
        $this->bin_inuo1 = '1110101011001100100100010001001110010110010000000110111010110111';
        $this->bin_inuo2 = '1011100111111000100100010000111010001001111101000100111001000011';
    }

    public function testCalculateBinaryHash()
    {
        $algorithm = new PerceptionHash;
        $ph = new PerceptualHash($this->path_inuo1, $algorithm);
        $bin = $ph->bin();

        $this->assertEquals(64, strlen($bin));
        $this->assertEquals($this->bin_inuo1, $bin);
    }

    public function testCalculateHexHash()
    {
        $algorithm = new PerceptionHash;
        $ph = new PerceptualHash($this->path_inuo1, $algorithm);
        $hex = $ph->hex();

        $this->assertEquals(16, strlen($hex));
        $this->assertEquals($this->hex_inuo1, $hex);
    }

    public function testCompareDifferentImages()
    {
        $algorithm = new PerceptionHash;
        $ph = new PerceptualHash($this->path_inuo1, $algorithm);
        $diff = $ph->compare($this->path_inuo2);

        $this->assertEquals(26, $diff);
    }

    public function testCompareSameImages()
    {
        $algorithm = new PerceptionHash;
        $ph = new PerceptualHash($this->path_inuo1, $algorithm);
        $diff = $ph->compare($this->path_inuo1);

        $this->assertEquals(0, $diff);
    }
}
