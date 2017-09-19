<?php

namespace Dgame\Annotation;

use Dgame\Type\Type;
use Dgame\Type\TypeFactory;

/**
 * Class AnnotationType
 * @package Dgame\Annotation
 */
final class AnnotationType
{
    /**
     * @var Type
     */
    private $type;
    /**
     * @var AnnotationType
     */
    private $next;

    /**
     * AnnotationType constructor.
     *
     * @param string              $type
     * @param AnnotationType|null $next
     */
    private function __construct(string $type, self $next = null)
    {
        $this->type = Type::import($type);
        $this->next = $next;
    }

    /**
     * @param string $type
     *
     * @return AnnotationType
     */
    public static function parse(string $type): self
    {
        if (preg_match_all('/(\[\s*\])/i', $type, $brackets)) {
            $base = str_replace(['[', ']'], '', $type);
            $type = new self('array', new self($base));
            for ($i = 1, $c = count($brackets[1]); $i < $c; $i++) {
                $type = new self('array', $type);
            }

            return $type;
        }

        return new self($type);
    }

    /**
     * @return Type
     */
    public function getType(): Type
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function hasNext(): bool
    {
        return $this->next !== null;
    }

    /**
     * @return AnnotationType
     */
    public function next(): self
    {
        return $this->next;
    }

    /**
     * @param $expression
     *
     * @return bool
     */
    public function isImplicit($expression): bool
    {
        $type = TypeFactory::expression($expression);
        if ($type->isArray() && $this->hasNext()) {
            foreach ($expression as $expr) {
                if (!$this->next()->isImplicit($expr)) {
                    return false;
                }
            }

            return true;
        }

        return $type->isImplicitSame($this->type);
    }

    /**
     * @param $expression
     *
     * @return bool
     */
    public function isSame($expression): bool
    {
        $type = TypeFactory::expression($expression);
        if ($type->isArray() && $this->hasNext()) {
            foreach ($expression as $expr) {
                if (!$this->next()->isSame($expr)) {
                    return false;
                }
            }

            return true;
        }

        return $type->isSame($this->type);
    }

    /**
     * @return int
     */
    public function getDimension(): int
    {
        $brackets = 0;
        $type     = $this;
        while ($type->hasNext()) {
            $type = $type->next();
            $brackets++;
        }

        return $brackets;
    }

    /**
     * @return Type
     */
    public function getBaseType(): Type
    {
        $type = $this;
        while ($type->hasNext()) {
            $type = $type->next();
        }

        return $type->getType();
    }

    /**
     * @return string
     */
    public function export(): string
    {
        return $this->getBaseType()->export() . str_repeat('[]', $this->getDimension());
    }
}