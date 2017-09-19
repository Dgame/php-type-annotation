<?php

namespace Dgame\Annotation;

/**
 * Class VariableAnnotation
 * @package Dgame\Annotation
 */
final class VariableAnnotation
{
    /**
     * @var AnnotationType
     */
    private $type;
    /**
     * @var string
     */
    private $id;

    /**
     * VariableAnnotation constructor.
     *
     * @param string $type
     * @param string $id
     */
    public function __construct(string $type, string $id)
    {
        $this->type = AnnotationType::parse($type);
        $this->id   = $id;
    }

    /**
     * @return AnnotationType
     */
    public function getType(): AnnotationType
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function hasId(): bool
    {
        return !empty($this->id);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return substr($this->id, 1);
    }
}