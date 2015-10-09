Image\PerceptualHash
========

## NAME

Image\PerceptualHash - Generate comparable hash from images

## SYNOPSIS

```php
<?php

use Image\PerceptualHash;
use Image\PerceptualHash\Algorithm\DifferenceHash;
use Image\PerceptualHash\Algorithm\PerceptionHash;

// Create an instance of Image\PerceptualHash, at the same time calculate hashes
// using hashing algorithm Image\PerceptualHash\Algorithm\AverageHash by default
$ph = new PerceptualHash('/path/to/foo.jpg');

// Get binary hash
$binary_hash = $ph->bin();

// Get hexadecimal hash
$hex_hash = $ph->hex();

// Compare with another image, return a Hamming distance
$distance = $ph->compare('/path/to/bar.jpg');

// Calculate a similarity
$similarity = $ph->similarity('/path/to/baz.jpg');

// Calculate by other hashing algorithms
$ph = new PerceptualHash('/path/to/foo.jpg', new DifferenceHash());
// or
$ph = new PerceptualHash('/path/to/foo.jpg', new PerceptionHash());
```

## DESCRIPTION

Image\PerceptualHash generates distinct, but not unique fingerprint with three hashing algorithms. Unlike cryptographic hashing, these fingerprints from similar images will be also similar.

## INSTALLATION

To install this package into your project via composer, add the following snippet to your `composer.json`. Then run `composer install`.

```
"require": {
    "travail/image-perceptualhash": "dev-master"
}
```

or

```
"repositories": {
    {
        "type": "vcs",
        "url": "git@github.com:travail/php-Image-PerceptualHash.git"
    }
}
```

## DEPENDENCIES

* ext-gd

## METHODS

### __construct

```php
Image\PerceptualHash __construct($file, Algorithm $algorithm)
```

Creates a new instance of Image\PerceptualHash and calculates hashes.

#### $file

Path to a file or a resource of that.

#### Algorithm $algorithm

Hashing algorithm, currently the following algorithm are available:

* AverageHash
* DifferenceHash
* PerceptionHash

### bin

```php
string bin()
```

Returns calculated binary hash.

### hex

```
string hex()
```

Returns calculated hexadecimal hash.

### compare

```php
int compare(string|resource $file)
```

Compares with another image and returns the Hamming distance to that.

#### $file

Path to a file or a resource of that.

### distance

```php
int distance(string $hash1, string $hash2)
```

### similarity

```php
double similarity(string|resource $file)
```

Calcuates the similarity to another image.

#### $file

Path to a file or a resource of that.

## AUTHOR

travail

### LICENSE

This library is free software. You can redistribute it and/or modify it under the same terms as PHP itself.
