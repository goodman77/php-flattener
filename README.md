# PHP Array Flattener

A simple PHP library to flatten arbitrarily nested arrays of integers.  

Features:
- Recursively flattens nested arrays
- Skips non-integer values
- Optional uniqueness
- Maximum nesting depth check

## Installation

```bash
composer require app/php-flattener

## Usage
<?php

require 'vendor/autoload.php';

use App\ArrayFlattener;

// Normal flatten
$flattener = new ArrayFlattener();
$input = [[1, 2, [3]], 4, "foo"];
$output = $flattener->flatten($input);

print_r($output);
// Output: [1, 2, 3, 4]

// Flatten with uniqueness
$flattenerUnique = new ArrayFlattener(unique: true);
$input = [1, 2, [3, 2, 1], 4];
$outputUnique = $flattenerUnique->flatten($input);

print_r($outputUnique);
// Output: [1, 2, 3, 4]

// Handling maximum depth
$flattenerDepth = new ArrayFlattener(maxDepth: 10);
try {
    $output = $flattenerDepth->flatten($input);
} catch (\InvalidArgumentException $e) {
    echo $e->getMessage();
}

## Testing
vendor/bin/phpunit

### 2️⃣ Initialize git (if not done yet)

From your project root:

```bash
git init
