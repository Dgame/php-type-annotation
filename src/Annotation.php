<?php

namespace Dgame\Annotation;

use Exception;
use ReflectionClass;

/**
 * Class Annotation
 * @package Dgame\Annotation
 */
final class Annotation
{
    /**
     * @var VariableAnnotation[]
     */
    private $properties = [];
    /**
     * @var VariableAnnotation[][]
     */
    private $parameters = [];

    /**
     * Annotation constructor.
     *
     * @param ReflectionClass $class
     *
     * @throws Exception
     */
    public function __construct(ReflectionClass $class)
    {
        $parser = new AnnotationParser();

        $this->collectPropertyAnnotations($parser, $class);
        $this->collectMethodParameterAnnotations($parser, $class);
    }

    /**
     * @param AnnotationParser $parser
     * @param ReflectionClass  $class
     *
     * @throws Exception
     */
    private function collectPropertyAnnotations(AnnotationParser $parser, ReflectionClass $class): void
    {
        $comment = $class->getDocComment();
        $comment = $comment === false ? '' : $comment;
        foreach ($parser->parsePropertyAnnotations($comment) as $annotation) {
            $this->properties[$annotation->getName()] = $annotation;
        }

        foreach ($class->getProperties() as $property) {
            $comment     = $property->getDocComment();
            $comment     = $comment === false ? '' : $comment;
            $annotations = $parser->parseVariableAnnotations($comment);
            $annotation  = array_pop($annotations);
            if ($annotation !== null) {
                $this->properties[$property->name] = $annotation;
            }
        }
    }

    /**
     * @param AnnotationParser $parser
     * @param ReflectionClass  $class
     *
     * @throws Exception
     */
    private function collectMethodParameterAnnotations(AnnotationParser $parser, ReflectionClass $class): void
    {
        foreach ($class->getMethods() as $method) {
            $name    = $method->getName();
            $comment = $method->getDocComment();
            $comment = $comment === false ? '' : $comment;

            $this->parameters[$name] = [];
            foreach ($parser->parseParameterAnnotations($comment) as $annotation) {
                $this->parameters[$name][$annotation->getName()] = $annotation;
            }
        }
    }

    /**
     * @return VariableAnnotation[]
     */
    public function getAllProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param string $property
     *
     * @return VariableAnnotation
     */
    public function getProperty(string $property): VariableAnnotation
    {
        return $this->properties[$property];
    }

    /**
     * @param string $property
     *
     * @return bool
     */
    public function hasProperty(string $property): bool
    {
        return array_key_exists($property, $this->properties);
    }

    /**
     * @return VariableAnnotation[][]
     */
    public function getAllMethodsParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param string $method
     *
     * @return VariableAnnotation[]
     */
    public function getMethodParameters(string $method): array
    {
        return $this->parameters[$method];
    }

    /**
     * @param string $method
     * @param string $param
     *
     * @return VariableAnnotation
     */
    public function getMethodParameter(string $method, string $param): VariableAnnotation
    {
        return $this->parameters[$method][$param];
    }

    /**
     * @param string      $method
     * @param string|null $param
     *
     * @return bool
     */
    public function hasMethodParameter(string $method, string $param = null): bool
    {
        return array_key_exists($method, $this->parameters) && ($param === null ? true : array_key_exists($param, $this->parameters[$method]));
    }
}
