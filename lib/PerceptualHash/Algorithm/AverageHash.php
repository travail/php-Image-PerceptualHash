<?php

namespace Image\PerceptualHash\Algorithm;

use Image\PerceptualHash\Algorithm;

class AverageHash implements Algorithm
{
    const SIZE = 8;

    /**
     * {@inheritDoc}
     */
    public function calculate($resource)
    {
        // Resize
        $resized = imagecreatetruecolor(static::SIZE, static::SIZE);
        imagecopyresampled(
            $resized, $resource, 0, 0, 0, 0,
            static::SIZE, static::SIZE, imagesx($resource), imagesy($resource));

        // Create an array of gray-scale pixel value
        $pixels = array();
        for ($y = 0; $y < static::SIZE; $y++) {
            for ($x = 0; $x < static::SIZE; $x++) {
                $rgb = imagecolorsforindex($resized, imagecolorat($resized, $x, $y));
                $pixels[] = floor(($rgb['red'] + $rgb['green'] + $rgb['blue']) / 3);
            }
        }

        imagedestroy($resized);

        // Calculate the average pixel value
        $average = floor(array_sum($pixels) / count($pixels));

        $binary = '';
        $one = 1;
        foreach ($pixels as $pixel) {
            $binary .= $pixel > $average ? 1 : 0;
            $one = $one << 1;
        }

        $hex = '';
        foreach (str_split($binary, 4) as $binary) {
            $hex .= dechex(bindec($binary));
        }

        return $hex;
    }
}
