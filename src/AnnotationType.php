<?php

namespace Dgame\Annotation;

use Dgame\Type\Type;
use Dgame\Type\TypeFactory;
use Exception;

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
     * @var AnnotationType|null
     */
    private $next;

    /**
     * AnnotationType constructor.
     *
     * @param string              $type
     * @param AnnotationType|null $next
     *
     * @throws Exception
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
     * @throws Exception
     */
    public static function parse(string $type): self
    {
        if (preg_match_all('/(\[\s*\])/S', $type, $brackets)) {
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
     * @return AnnotationType|null
     */
    public function next(): ?self
    {
        return $this->next;
    }

    /**
     * @param mixed $expression
     *
     * @return bool
     * @throws Exception
     */
    public function isImplicit($expression): bool
    {
        $type = TypeFactory::expression($expression);
        if ($type->isArray() && $this->hasNext()) {
            return $this->compare($expression, function (self $type, $expr): bool {
                return $type->isImplicit($expr);
            });
        }

        return $type->isImplicitSame($this->type);
    }

    /**
     * @param mixed $expression
     *
     * @return bool
     * @throws Exception
     */
    public function isSame($expression): bool
    {
        $type = TypeFactory::expression($expression);
        if ($type->isArray() && $this->hasNext()) {
            return $this->compare($expression, function (self $type, $expr): bool {
                return $type->isSame($expr);
            });
        }

        return $type->isSame($this->type);
    }

    /**
     * @param array    $expression
     * @param callable $callback
     *
     * @return bool
     */
    private function compare(array $expression, callable $callback): bool
    {
        foreach ($expression as $expr) {
            if (!$callback($this->next(), $expr)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return int
     */
    public function getDimension(): int
    {
        $this->iterate($dimension);

        return $dimension;
    }

    /**
     * @return Type|null
     */
    public function getBaseType(): ?Type
    {
        $type = $this->iterate();

        return $type === null ? null : $type->getType();
    }

    /**
     * @param int|null $dimension
     *
     * @return AnnotationType|null
     */
    private function iterate(int &$dimension = null): ?AnnotationType
    {
        $dimension = 0;
        $type = $this;
        while ($type !== null && $type->hasNext()) {
            $type = $type->next();
            $dimension++;
        }

        return $type;
    }

    /**
     * @return string|null
     */
    public function export(): ?string
    {
        $type = $this->getBaseType();

        return $type === null ? null : $type->export() . str_repeat('[]', $this->getDimension());
    }
}
