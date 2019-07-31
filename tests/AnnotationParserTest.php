<?php

namespace Dgame\Annotation\Test;

use Dgame\Annotation\AnnotationParser;
use PHPUnit\Framework\TestCase;

/**
 * Class AnnotationParserTest
 * @package Dgame\Annotation\Test
 */
final class AnnotationParserTest extends TestCase
{
    public function testParseProperty(): void
    {
        $parser     = new AnnotationParser();
        $annotation = $parser->parsePropertyAnnotations('@property string[] $ids');

        $this->assertNotEmpty($annotation);
        $this->assertCount(1, $annotation);
        $this->assertTrue($annotation[0]->getType()->hasNext());
        $this->assertEquals(1, $annotation[0]->getType()->getDimension());
        $this->assertEquals('string', $annotation[0]->getType()->getBaseType()->export());
        $this->assertEquals('string[]', $annotation[0]->getType()->export());
        $this->assertTrue($annotation[0]->hasId());
        $this->assertEquals('$ids', $annotation[0]->getId());
        $this->assertEquals('ids', $annotation[0]->getName());

        $annotation = $parser->parseVariableAnnotations('@var string[]');

        $this->assertNotEmpty($annotation);
        $this->assertCount(1, $annotation);
        $this->assertTrue($annotation[0]->getType()->hasNext());
        $this->assertEquals(1, $annotation[0]->getType()->getDimension());
        $this->assertEquals('string', $annotation[0]->getType()->getBaseType()->export());
        $this->assertEquals('string[]', $annotation[0]->getType()->export());
        $this->assertFalse($annotation[0]->hasId());
        $this->assertEmpty($annotation[0]->getId());
        $this->assertEmpty($annotation[0]->getName());
    }
}
