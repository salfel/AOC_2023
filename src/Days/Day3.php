<?php

namespace AOC\Days;

use AOC\Base;

class Day3 extends Base
{
    public function __construct()
    {
        parent::__construct('--- Day 3: Gear Ratios ---', 3);
    }

    protected function testData(int $part): void
    {
        $this->data = [
            '467..114..',
            '...*......',
            '..35..633.',
            '......#...',
            '617*......',
            '.....+.58.',
            '..592.....',
            '......755.',
            '...$.*....',
            '.664.598..',
        ];
    }

    protected function part1(): string
    {
        [$symbols, $numbers] = $this->getSymbolsAndNumbers();

        $sum = 0;
        foreach ($numbers as $number) {
            $_symbols = $this->getSymbols($symbols, $number);
            if (count($_symbols)) {
                $sum += $number['number'];
            }
        }

        return (string) $sum;
    }

    protected function part2(): string
    {
        [$symbols, $numbers] = $this->getSymbolsAndNumbers();

        $gears = [];

        $sum = 0;
        foreach ($numbers as $number) {
            $_symbols = $this->getSymbols($symbols, $number);
            array_filter($_symbols, function (array $symbol) use (&$gears, $number) {
                if ($symbol['char'] == '*') {
                    $found = false;
                    foreach ($gears as $key => $gear) {
                        if ($gear['row'] == $symbol['row'] && $gear['column'] == $symbol['column']) {
                            $gears[$key]['numbers'][] = $number;
                            $found = true;
                        }
                    }
                    if (! $found) {
                        $gears[] = [
                            'row' => $symbol['row'],
                            'column' => $symbol['column'],
                            'numbers' => [$number],
                        ];
                    }
                }
            });
        }
        foreach ($gears as $gear) {
            if (count($gear['numbers']) == 2) {
                $sum += $gear['numbers'][0]['number'] * $gear['numbers'][1]['number'];
            }
        }

        return (string) $sum;
    }

    private function isInRange($number, $lowerLimit, $upperLimit, $print = false): bool
    {
        return $number >= $lowerLimit && $number <= $upperLimit;
    }

    private function getSymbolsAndNumbers(): array
    {
        $symbols = [];
        $numbers = [];
        foreach ($this->data as $i => $line) {
            for ($j = strlen($line) - 1; $j > 0; $j--) {
                $sum = 0;
                $count = 0;
                while (is_numeric($line[$j])) {
                    $sum += pow(10, $count) * intval($line[$j]);
                    $count++;
                    $j--;
                }
                if ($count != 0) {
                    $numbers[] = [
                        'number' => $sum,
                        'row' => $i,
                        'start' => $j + 1,
                        'end' => $j + $count,
                    ];
                    $j++;
                } elseif ($line[$j] != '.') {
                    $symbols[] = [
                        'char' => $line[$j],
                        'row' => $i,
                        'column' => $j,
                    ];
                }
            }
        }

        return [$symbols, $numbers];
    }

    private function getSymbols(array $symbols, array $number): array
    {
        return array_filter($symbols, function (array $symbol) use ($number) {
            return $this->isInRange($symbol['row'], $number['row'] - 1, $number['row'] + 1, $number['number'] == 954)
                && $this->isInRange($symbol['column'], $number['start'] - 1, $number['end'] + 1, $number['number'] == 954);
        });
    }
}
