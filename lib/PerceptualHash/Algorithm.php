<?php

namespace Image\PerceptualHash;

interface Algorithm
{
    /**
     * Calculate binary hash.
     *
     * @param resource $resource
     * @return string Binary hash
     */
    public function bin($resource);

    /**
     * Calculate hexadecimal hash by binary_hash.
     *
     * @param string $bin Binary hash
     * @return string Hexadecimal hash
     */
    public function hex($bin);
}
