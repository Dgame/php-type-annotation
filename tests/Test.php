<?php

namespace Dgame\Annotation\Test;

/**
 * Class AdressAggregator
 * @package Demv\Makler\Aggregator
 *
 * @property string[] $streets
 * @property float    $nummer
 */
final class Test
{
    /**
     * @var int
     */
    private $einwohner;

    /**
     * @param string[] $streets
     */
    public function setStreets(array $streets): void
    {
    }

    /**
     * @param float $nr
     */
    public function setNummer(float $nr): void
    {
    }

    /**
     * @param int $einwohner
     */
    public function setEinwohner(int $einwohner): void
    {
    }
}