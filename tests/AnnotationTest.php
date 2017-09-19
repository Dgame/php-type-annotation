<?php

namespace Dgame\Annotation\Test;

use Dgame\Annotation\Annotation;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Class AnnotationTest
 * @package Dgame\Annotation\Test
 */
final class AnnotationTest extends TestCase
{
    public function testAnnoation()
    {
        $annotation = new Annotation(new ReflectionClass(Test::class));
        $this->assertCount(3, $annotation->getAllProperties());
        $this->assertCount(3, $annotation->getAllMethodsParameters());

        $this->assertTrue($annotation->hasProperty('streets'));
        $this->assertEquals('string[]', $annotation->getProperty('streets')->getType()->export());

        $this->assertTrue($annotation->hasProperty('nummer'));
        $this->assertEquals('float', $annotation->getProperty('nummer')->getType()->export());

        $this->assertTrue($annotation->hasProperty('einwohner'));
        $this->assertEquals('int', $annotation->getProperty('einwohner')->getType()->export());

        $this->assertTrue($annotation->hasMethodParameter('setStreets', 'streets'));
        $this->assertEquals('string[]', $annotation->getMethodParameter('setStreets', 'streets')->getType()->export());

        $this->assertTrue($annotation->hasMethodParameter('setNummer', 'nr'));
        $this->assertEquals('float', $annotation->getMethodParameter('setNummer', 'nr')->getType()->export());

        $this->assertTrue($annotation->hasMethodParameter('setEinwohner', 'einwohner'));
        $this->assertEquals('int', $annotation->getMethodParameter('setEinwohner', 'einwohner')->getType()->export());
    }
}