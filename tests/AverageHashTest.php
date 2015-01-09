<?php

use Image\PerceptualHash;

class AverageHashTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->path_inuo1 = __DIR__ . '/images/inuo1.jpg';
        $this->path_inuo2 = __DIR__ . '/images/inuo2.jpg';
        $this->hash_inuo1 = 'fdd7a9d1c383c1ff';
        $this->hash_inuo2 = 'f7f30200c3c3c3ff';
    }

    public function testHash()
    {
        $ph = new PerceptualHash($this->path_inuo1);
        $hash = $ph->hash();

        $this->assertEquals(16, strlen($hash));
        $this->assertEquals($this->hash_inuo1, $hash);
    }

    public function testCompareDifferentImage()
    {
        $ph = new PerceptualHash($this->path_inuo1);
        $diff = $ph->compare($this->path_inuo2);

        $this->assertEquals(9, $diff);

    }

    public function testCompareSameImage()
    {
        $ph = new PerceptualHash($this->path_inuo1);
        $diff = $ph->compare($this->path_inuo1);

        $this->assertEquals(0, $diff);
    }
}
