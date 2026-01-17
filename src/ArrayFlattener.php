<?php

declare(strict_types=1);

namespace App;
use InvalidArgumentException;

class ArrayFlattener
{
    private bool $unique;
    private int $maxDepth;  


    public function __construct(bool $unique = false, int $maxDepth = 1000) 
    {
        $this->unique = $unique;
        $this->maxDepth = $maxDepth;
    }

    /**
     * Flatten an arbitrarily nested array of integers.
     * Features:
     *  - Recursively flattens nested arrays
     *  - Skips non-integer values
     *  - Optional uniqueness
     *  - Maximum nesting depth check
     *
     * @param array<mixed> $input
     * @return array<int>
     */
    public function flatten(array $input): array
    {
        $result = [];
        $seen = [];

        $this->flattenRecursive($input, $result, $seen, 0);

        return $this->unique ? array_keys($seen) : $result;
    }

    /**
     * Recursive flatter array helper.
     *
    * @param array<mixed> $array
     * @param array<int> $result
     * @param array<int,bool> $seen
     * @param int $depth
     * @throws InvalidArgumentException if depth exceeds maxDepth
     */
    private function flattenRecursive(array $array, array &$result, array &$seen, int $depth): void
    {
        if ($depth > $this->maxDepth) {
            throw new InvalidArgumentException(
                "Array nesting exceeds maximum depth of {$this->maxDepth}"
            );
        }

        foreach ($array as $item) {
            if (is_array($item)) {
                $this->flattenRecursive($item, $result, $seen, $depth + 1);
            } elseif (is_int($item)) {
                if ($this->unique) {
                    if (!isset($seen[$item])) {
                        $seen[$item] = true;
                    }
                } else {
                    $result[] = $item;
                }
            }
            // Non-integers are skipped
        }

        if ($this->unique) {
            $result = array_keys($seen);
        }
    }
}
