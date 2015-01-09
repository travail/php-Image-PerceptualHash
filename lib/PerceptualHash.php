<?php

namespace Image;

use Exception;
use Image\PerceptualHash\Algorithm;
use Image\PerceptualHash\Algorithm\AverageHash;

class PerceptualHash {
    /**
       @var Algorithm The hasing algorithm
     */
    protected $algorithm;
    protected $data;

    public function __construct($file, Algorithm $algorithm = null)
    {
        $this->algorithm = $algorithm ?: new AverageHash;
        $this->data = is_resource($file) ? $file : $this->load($file);
    }

    public function hash()
    {
        return $this->algorithm->calculate($this->data);
    }

    public function compare($file)
    {
        $algorithm_class = get_class($this->algorithm);
        $objective = new self($file, new $algorithm_class);

        return self::hammingDistance($this->hash(), $objective->hash());
    }

    public static function hammingDistance($hash1, $hash2)
    {
        if (!is_string($hash1) || !is_string($hash2)) {
            throw new Exception();
        }

        if (strlen($hash1) !== strlen($hash2)) {
            throw new Exception();
        }

        $diff = 0;
        $split_hash1 = str_split($hash1);
        $split_hash2 = str_split($hash2);
        for ($i = 0; $i < count($split_hash1); $i++) {
            if ($split_hash1[$i] !== $split_hash2[$i]) {
                $diff++;
            }
        }

        return $diff;
    }

    protected function load($file)
    {
        if (!file_exists($file)) {
            throw new Exception("No such a file $file");
        }

        try {
            return imagecreatefromstring(file_get_contents($file));
        } catch (Exception $e) {
            throw $e;
        }
    }
}
