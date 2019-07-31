<?php

namespace Dgame\Annotation;

use Exception;

/**
 * Class AnnotationParser
 * @package Dgame\Annotation
 */
final class AnnotationParser
{
    private const REGEX = '\s+(.*?)\s*(\$.+?)?\s*\z';

    /**
     * @param string $comment
     *
     * @return VariableAnnotation[]
     * @throws Exception
     */
    public function parseParameterAnnotations(string $comment): array
    {
        return $this->parseAnnotations('param', $comment);
    }

    /**
     * @param string $comment
     *
     * @return VariableAnnotation[]
     * @throws Exception
     */
    public function parseVariableAnnotations(string $comment): array
    {
        return $this->parseAnnotations('var', $comment);
    }

    /**
     * @param string $comment
     *
     * @return VariableAnnotation[]
     * @throws Exception
     */
    public function parsePropertyAnnotations(string $comment): array
    {
        return $this->parseAnnotations('property', $comment);
    }

    /**
     * @param string $annotation
     * @param string $comment
     *
     * @return VariableAnnotation[]
     * @throws Exception
     */
    private function parseAnnotations(string $annotation, string $comment): array
    {
        $output = [];
        $lines  = preg_split('/\R/m', $comment);
        $lines  = $lines === false ? [] : $lines;
        foreach ($lines as $line) {
            $result = $this->parseAnnotation($annotation, $line);
            if ($result !== null) {
                $output[] = $result;
            }
        }

        return $output;
    }

    /**
     * @param string $annotation
     * @param string $line
     *
     * @return VariableAnnotation|null
     * @throws Exception
     */
    private function parseAnnotation(string $annotation, string $line): ?VariableAnnotation
    {
        $regex = sprintf('/@%s%s/i', $annotation, self::REGEX);
        if (preg_match($regex, $line, $matches)) {
            [$type, $var] = $this->parseAnnotationMatch($matches);

            return new VariableAnnotation($type, $var);
        }

        return null;
    }

    /**
     * @param array $matches
     *
     * @return array
     */
    private function parseAnnotationMatch(array $matches): array
    {
        array_shift($matches);
        if (!array_key_exists(0, $matches)) {
            $matches[0] = 'mixed';
        }

        if (!array_key_exists(1, $matches)) {
            $matches[1] = '';
        }

        return $matches;
    }
}
