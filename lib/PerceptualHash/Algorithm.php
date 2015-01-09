<?php

namespace Image\PerceptualHash;

interface Algorithm
{
    /**
     * Calculate the hash
     *
     * @param resource $resource
     * @return string
     */
    public function calculate($resource);
}
