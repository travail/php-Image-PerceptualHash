<?php

namespace Image;

use Exception;
use InvalidArgumentException;
use LengthException;
use Image\PerceptualHash\Algorithm;
use Image\PerceptualHash\Algorithm\AverageHash;
use Image\PerceptualHash\Exception\FileNotFoundException;

class PerceptualHash {
    /**
     * @var Algorithm Instance of Algorithm
     */
    protected $algorithm;

    /**
     * @var string Calculated binary hash
     */
    protected $bin;

    /**
     * @var string Calculated hexadecimal hash
     */
    protected $hex;

    /**
     * Creates an instance and calculate hashes.
     *
     * @param string|resource $file Path to file or file handle
     * @param Algorithm $algorithm Instance of Algorithm class
     */
    public function __construct($file, Algorithm $algorithm = null)
    {
        $resource = is_resource($file) ? $file : $this->load($file);
        $this->algorithm = $algorithm ?: new AverageHash;
        $this->bin = $this->algorithm->bin($resource);
        $this->hex = $this->algorithm->hex($this->bin);
    }

    /**
     * Returns binary hash.
     *
     * @return string Binary hash
     */
    public function bin()
    {
        return (string) $this->bin;
    }

    /**
     * Returns hexadecimal hash.
     *
     * @return string Hexadecimal hash
     */
    public function hex()
    {
        return (string) $this->hex;
    }

    /**
     * Compares with another image and returns a distance to that.
     *
     * @param string|resource $file Path to file or file handle
     * @return integer The distance to $file
     */
    public function compare($file)
    {
        $algorithm_class = get_class($this->algorithm);
        $objective = new self($file, new $algorithm_class);

        return self::distance($this->bin, $objective->bin);
    }

    /**
     * Calculates Hamming distance between two hashes
     *
     * @param string $hash1
     * @param string $hash2
     * @return integer Hamming distance between two hashes
     * @throws InvalidArgumentException
     * @throws LengthException
     */
    public static function distance($hash1, $hash2)
    {
        if (!is_string($hash1) || !is_string($hash2)) {
            throw new InvalidArgumentException('Given hashes are not string');
        }

        if (strlen($hash1) !== strlen($hash2)) {
            throw new LengthException('Length of given hashes is not accordant');
        }

        $diff = 0;
        $split_hash1 = str_split($hash1);
        $split_hash2 = str_split($hash2);
        for ($i = 0; $i < count($split_hash1); $i++) {
            if ($split_hash1[$i] !== $split_hash2[$i]) {
                $diff++;
            }
        }

        return (int) $diff;
    }

    /**
     * Returns a similarity to the $file.
     *
     * @param string|resource $file Path to file or file handle
     * @return double Similarity represented between 0 and 1
     */
    public function similarity($file)
    {
        return (double) 1 - ($this->compare($file) / strlen($this->bin));
    }

    /**
     * @param string $file Path to file
     * @return resource
     * @throws Image\PerceptualHash\Exception\FileNotFoundException
     * @throws Exception
     */
    protected function load($file)
    {
        if (!file_exists($file)) {
            throw new FileNotFoundException("No such a file $file");
        }

        try {
            return imagecreatefromstring(file_get_contents($file));
        } catch (Exception $e) {
            throw $e;
        }
    }
}
