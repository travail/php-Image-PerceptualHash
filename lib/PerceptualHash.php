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

    /**
     * @var string Calculated binary hash
     */
    protected $bin;

    /**
     * @var string Calculated hex hash
     */
    protected $hex;

    /**
     * @param mixed $file
     * @param Algorithm $algorithm
     */
    public function __construct($file, Algorithm $algorithm = null)
    {
        $resource = is_resource($file) ? $file : $this->load($file);
        $this->algorithm = $algorithm ?: new AverageHash;
        $this->bin = $this->algorithm->bin($resource);
        $this->hex = $this->algorithm->hex($this->bin);
    }

    public function bin()
    {
        return $this->bin;
    }

    public function hex()
    {
        return $this->hex;
    }

    /**
     * @param mixed $file
     * @return integer The distance to $file
     */
    public function compare($file)
    {
        $algorithm_class = get_class($this->algorithm);
        $objective = new self($file, new $algorithm_class);

        return self::distance($this->bin, $objective->bin);
    }

    /**
     * Calculate the Hamming distance between two hashes
     *
     * @param string $hash1
     * @param string $hash2
     * @return integer $diff
     * @throws
     */
    public static function distance($hash1, $hash2)
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

        return (int)$diff;
    }

    public function similarity($file)
    {
        return 1 - ($this->compare($file) / strlen($this->bin));
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
