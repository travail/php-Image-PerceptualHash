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
     * Calculate hexadecimal hash.
     *
     * @param resource $resource
     * @return string Hexadecimal hash
     */
    public function hex($binary);
}
