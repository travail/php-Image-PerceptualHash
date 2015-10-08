<?php

namespace Image\PerceptualHash\Algorithm;

use LengthException;
use Image\PerceptualHash\Algorithm;

class PerceptionHash implements Algorithm
{
    const SIZE = 32;

    /**
     * {@inheritDoc}
     */
    public function bin($resource)
    {
        $resized = imagecreatetruecolor(static::SIZE, static::SIZE);
        imagecopyresampled($resized, $resource, 0, 0, 0, 0,
            static::SIZE, static::SIZE, imagesx($resource), imagesy($resource));
        // Get luma value (YCbCr) from RGB colors and calculate the DCT for each row.
        $matrix = array();
        $row = array();
        $rows = array();
        $col = array();
        for ($y = 0; $y < static::SIZE; $y++) {
            for ($x = 0; $x < static::SIZE; $x++) {
                $rgb = imagecolorsforindex($resized, imagecolorat($resized, $x, $y));
                $row[$x] = floor(($rgb['red'] * 0.299) + ($rgb['green'] * 0.587) + ($rgb['blue'] * 0.114));
            }
            $rows[$y] = $this->dct($row);
        }

        imagedestroy($resized);

        for ($x = 0; $x < static::SIZE; $x++) {
            for ($y = 0; $y < static::SIZE; $y++) {
                $col[$y] = $rows[$y][$x];
            }
            $matrix[$x] = $this->dct($col);
        }

        // Extract the top-left 8x8 pixels.
        $pixels = array();
        for ($y = 0; $y < 8; $y++) {
            for ($x = 0; $x < 8; $x++) {
                $pixels[] = $matrix[$y][$x];
            }
        }

        // @todo median or mean?
        // Calculate the median.
        $median = $this->median($pixels);
        // Calculate the binary hash.
        $bin = '';
        $one = 1;
        foreach ($pixels as $pixel) {
            $bin .= $pixel > $median ? 1 : 0;
            $one = $one << 1;
        }

        return (string) $bin;
    }

    /**
     * {@inheritDoc}
     */
    public function hex($bin)
    {
        if (strlen($bin) !== 64) {
            throw new LengthException('Binary length must be ' . 64);
        }

        $hex = '';
        foreach (str_split($bin, 4) as $bin) {
            $hex .= dechex(bindec($bin));
        }

        return (string) $hex;
    }

    protected function dct(array $pixels)
    {
        $transformed = array();
        $size = count($pixels);
        for ($i = 0; $i < $size; $i++) {
            $sum = 0;
            for ($j = 0; $j < $size; $j++) {
                $sum += $pixels[$j] * cos($i * pi() * ($j + 0.5) / ($size));
            }
            $sum *= sqrt(2 / $size);
            if ($i === 0) {
                $sum *= 1 / sqrt(2);
            }
            $transformed[$i] = $sum;
        }

        return $transformed;
    }

    protected function median(array $pixels)
    {
        sort($pixels, SORT_NUMERIC);
        $middle = floor(count($pixels) / 2);
        if (count($pixels) % 2) {
            $median = $pixels[$middle];
        } else {
            $low = $pixels[$middle];
            $high = $pixels[$middle + 1];
            $median = ($low + $high) / 2;
        }

        return $median;
    }
}
