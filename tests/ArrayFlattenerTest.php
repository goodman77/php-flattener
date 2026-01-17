<?php

declare(strict_types=1);

namespace App\Tests;

use App\ArrayFlattener;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class ArrayFlattenerTest extends TestCase
{
    public function testFlattenSimpleArray(): void
    {
        $flattener = new ArrayFlattener();
        $input = [1, [2, [3, 4]], 5];
        $expected = [1, 2, 3, 4, 5];

        $this->assertSame($expected, $flattener->flatten($input));
    }

    public function testFlattenSkipsNonIntegers(): void
    {
        $flattener = new ArrayFlattener();
        $input = [1, "foo", [2.5, [3, null]]];
        $expected = [1, 3];

        $this->assertSame($expected, $flattener->flatten($input));
    }

    public function testFlattenUnique(): void
    {
        $flattener = new ArrayFlattener(unique: true);
        $input = [1, 2, [3, 2, 1], 4];
        $expected = [1, 2, 3, 4];

        $this->assertSame($expected, $flattener->flatten($input));
    }

    public function testFlattenDeeplyNested(): void
    {
        $flattener = new ArrayFlattener();
        $input = [[[[[[1]]]]], 2, [[3]]];
        $expected = [1, 2, 3];

        $this->assertSame($expected, $flattener->flatten($input));
    }

    public function testFlattenEmptyArray(): void
    {
        $flattener = new ArrayFlattener();
        $input = [];
        $expected = [];

        $this->assertSame($expected, $flattener->flatten($input));
    }

    public function testFlattenAllNonIntegers(): void
    {
        $flattener = new ArrayFlattener();
        $input = ["a", null, [false, [3.14]]];
        $expected = [];

        $this->assertSame($expected, $flattener->flatten($input));
    }

    public function testExceedMaxDepthThrows(): void
    {
        // generate deep nested array
        $tooDeep = [];
        $temp = &$tooDeep;
        for ($i = 0; $i < 20; $i++) {
            $temp[] = [];
            $temp = &$temp[0];
        }

        $flattener = new ArrayFlattener(maxDepth: 10);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Array nesting exceeds maximum depth of 10");

        $flattener->flatten($tooDeep);
    }

    public function testMaxDepthExact(): void
    {
        $exactDepth = [];
        $temp = &$exactDepth;
        for ($i = 0; $i < 10; $i++) { // maxDepth = 10
            $temp[] = [];
            $temp = &$temp[0];
        }

        $flattener = new ArrayFlattener(maxDepth: 10);

        // Should not throw, returns empty array
        $this->assertSame([], $flattener->flatten($exactDepth));
    }
}
