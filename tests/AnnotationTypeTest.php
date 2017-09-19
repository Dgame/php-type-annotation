<?php

namespace Dgame\Annotation\Test;

use Dgame\Annotation\AnnotationType;
use PHPUnit\Framework\TestCase;

/**
 * Class AnnotationTypeTest
 * @package Dgame\Annotation\Test
 */
final class AnnotationTypeTest extends TestCase
{
    public function testImplicit()
    {
        $this->assertTrue(AnnotationType::parse('string[]')->isImplicit(['a', 'b']));
        $this->assertTrue(AnnotationType::parse('string[]')->isImplicit([]));
        $this->assertTrue(AnnotationType::parse('string[]')->isImplicit([4, 2]));
        $this->assertTrue(AnnotationType::parse('string[]')->isImplicit([4.1, 2.3]));
        $this->assertFalse(AnnotationType::parse('string[]')->isImplicit(42));
        $this->assertFalse(AnnotationType::parse('string[]')->isImplicit('a'));

        $this->assertFalse(AnnotationType::parse('int[]')->isImplicit('a'));
        $this->assertFalse(AnnotationType::parse('int[]')->isImplicit(['a', 'b']));
        $this->assertFalse(AnnotationType::parse('int[]')->isImplicit(42));
        $this->assertTrue(AnnotationType::parse('int[]')->isImplicit([4, 2]));
        $this->assertTrue(AnnotationType::parse('int[]')->isImplicit([4.1, 2.3]));

        $this->assertTrue(AnnotationType::parse('int[][]')->isImplicit([[4, 2]]));
    }

    public function testSame()
    {
        $this->assertTrue(AnnotationType::parse('string[]')->isSame(['a', 'b']));
        $this->assertTrue(AnnotationType::parse('string[]')->isSame([]));
        $this->assertFalse(AnnotationType::parse('string[]')->isSame([4, 2]));
        $this->assertFalse(AnnotationType::parse('string[]')->isSame([4.1, 2.3]));
        $this->assertFalse(AnnotationType::parse('string[]')->isSame(42));
        $this->assertFalse(AnnotationType::parse('string[]')->isSame('a'));

        $this->assertFalse(AnnotationType::parse('int[]')->isSame('a'));
        $this->assertFalse(AnnotationType::parse('int[]')->isSame(['a', 'b']));
        $this->assertFalse(AnnotationType::parse('int[]')->isSame(42));
        $this->assertTrue(AnnotationType::parse('int[]')->isSame([4, 2]));
        $this->assertFalse(AnnotationType::parse('int[]')->isSame([4.1, 2.3]));

        $this->assertTrue(AnnotationType::parse('int[][]')->isSame([[4, 2]]));
    }
}