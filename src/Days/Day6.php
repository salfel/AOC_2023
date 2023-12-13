<?php

namespace AOC\Days;

use AOC\Base;

class Day6 extends Base
{
    public function __construct()
    {
        parent::__construct('--- Day 6: Wait For It ---', 6);
    }

    protected function testData(int $part): void
    {
        $this->data = array_map(fn ($value) => trim($value), preg_split('/\n/',
            'Time:      7  15   30
            Distance:  9  40  200'
        ));
    }

    protected function part1(): string
    {
        $times = $this->parseNumbers($this->data[0]);
        $distances = $this->parseNumbers($this->data[1]);

        $won = [];

        for ($i = 0; $i < count($times); $i++) {
            $tries = [];
            $time = $times[$i];
            for ($j = 0; $j <= $time; $j++) {
                $tries[$j] = ($time - $j) * $j;
            }
            $won[] = count(array_filter($tries, fn (int $value) => $value > $distances[$i]));
        }

        $product = 1;
        foreach ($won as $win) {
            $product *= $win;
        }

        return (string) $product;
    }

    protected function part2(): string
    {
        $time = intval(str_replace(' ', '', explode(':', $this->data[0])[1]));
        $record = intval(str_replace(' ', '', explode(':', $this->data[1])[1]));

        $tries = null;
        for ($i = 0; $i <= $time; $i++) {
            $distance = ($time - $i) * $i;

            if ($distance > $record) {
                $tries = $time - ($i * 2) + 1;
                break;
            }
        }

        return (string) $tries;
    }

    private function parseNumbers(string $line): array
    {
        return [...array_filter(array_map(fn ($value) => trim($value), explode(' ', explode(':', $line)[1])))];
    }
}
