<?php

namespace Image\PerceptualHash\Algorithm;

use LengthException;
use Image\PerceptualHash\Algorithm;

class DifferenceHash implements Algorithm
{
    const SIZE = 8;

    /**
     * {@inheritDoc}
     */
    public function bin($resource)
    {
        $width = self::SIZE + 1;
        $heigth = self::SIZE;

        // Resize the image.
        $resized = imagecreatetruecolor($width, $heigth);
        imagecopyresampled($resized, $resource, 0, 0, 0, 0,
            $width, $heigth, imagesx($resource), imagesy($resource));

        $bin = '';
        $one = 1;
        for ($y = 0; $y < $heigth; $y++) {
            // Get the pixel value for the leftmost pixel.
            $rgb = imagecolorsforindex($resized, imagecolorat($resized, 0, $y));
            $left = floor(($rgb['red'] + $rgb['green'] + $rgb['blue']) / 3);
            for ($x = 1; $x < $width; $x++) {
                // Get the pixel value for each pixel starting from position 1.
                $rgb = imagecolorsforindex($resized, imagecolorat($resized, $x, $y));
                $right = floor(($rgb['red'] + $rgb['green'] + $rgb['blue']) / 3);
                // Each hash bit is set based on whether the left pixel is brighter than the right pixel.
                // http://www.hackerfactor.com/blog/index.php?/archives/529-Kind-of-Like-That.html
                $bin .= $left > $right ? 1 : 0;
                $left = $right;
                $one = $one << 1;
            }
        }

        imagedestroy($resized);

        return (string) $bin;
    }

    /**
     * {@inheritDoc}
     */
    public function hex($bin)
    {
        if (strlen($bin) !== self::SIZE * self::SIZE) {
            throw new LengthException('Binary length must be ' . self::SIZE * self::SIZE);
        }

        $hex = '';
        foreach (str_split($bin, 4) as $bin) {
            $hex .= dechex(bindec($bin));
        }

        return (string) $hex;
    }
}
