<?php

namespace Image\PerceptualHash\Algorithm;

use LengthException;
use Image\PerceptualHash\Algorithm;

class AverageHash implements Algorithm
{
    const SIZE = 8;

    /**
     * {@inheritDoc}
     */
    public function bin($resource)
    {
        // Resize
        $resized = imagecreatetruecolor(self::SIZE, self::SIZE);
        imagecopyresampled(
            $resized, $resource, 0, 0, 0, 0,
            self::SIZE, self::SIZE, imagesx($resource), imagesy($resource));

        // Create an array of gray-scaled pixel values.
        $pixels = array();
        for ($y = 0; $y < self::SIZE; $y++) {
            for ($x = 0; $x < self::SIZE; $x++) {
                $rgb = imagecolorsforindex($resized, imagecolorat($resized, $x, $y));
                $pixels[] = ($rgb['red'] + $rgb['green'] + $rgb['blue']) / 3;
            }
        }

        imagedestroy($resized);

        // Calculate a mean of gray-scaled pixel value.
        $mean = array_sum($pixels) / count($pixels);

        $bin = '';
        $one = 1;
        foreach ($pixels as $pixel) {
            $bin .= $pixel > $mean ? 1 : 0;
            $one = $one << 1;
        }

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
