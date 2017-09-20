# php-type-annotation

[![Build Status](https://travis-ci.org/Dgame/php-type-annotation.svg?branch=master)](https://travis-ci.org/Dgame/php-type-annotation)
[![BCH compliance](https://bettercodehub.com/edge/badge/Dgame/php-type-annotation?branch=master)](https://bettercodehub.com/)
[![StyleCI](https://styleci.io/repos/104107738/shield?branch=master)](https://styleci.io/repos/104107738)
[![codecov](https://codecov.io/gh/Dgame/php-type-annotation/branch/master/graph/badge.svg)](https://codecov.io/gh/Dgame/php-type-annotation)
[![Build Status](https://scrutinizer-ci.com/g/Dgame/php-type-annotation/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Dgame/php-type-annotation/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Dgame/php-type-annotation/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Dgame/php-type-annotation/?branch=master)
[![Dependency Status](https://gemnasium.com/badges/github.com/Dgame/php-type-annotation.svg)](https://gemnasium.com/github.com/Dgame/php-type-annotation)

## Parses your PHP-Doc-Annotations

### Parse single Doc-Comments
```php
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
```

### Or a whole Reflection
```php
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
```

## Compare the Doc-Type-Hints with actual PHP-Expressions

### Verify implicit matching...
```php
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
```

### ...or explicit matching
```php
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
```
