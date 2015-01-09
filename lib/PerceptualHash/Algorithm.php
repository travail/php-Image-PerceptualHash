<?php

namespace Image\PerceptualHash;

interface Algorithm
{
    /**
     * Calculate the binary hash
     *
     * @param resource $resource
     * @return string Binary string
     */
    public function bin($resource);

    /**
     * Calculate the hex hash
     *
     * @param resource $resource
     * @return string Hex string
     */
    public function hex($binary);
}
