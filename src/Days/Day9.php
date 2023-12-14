<?php

namespace AOC\Days;

use AOC\Base;

class Day9 extends Base
{
    public function __construct()
    {
        parent::__construct('--- Day 9: Mirage Maintenance ---', 9);
    }

    protected function testData(int $part): void
    {
        $this->data = array_map(fn ($value) => trim($value), preg_split('/\n/',
            '0 3 6 9 12 15
            1 3 6 10 15 21
            10 13 16 21 30 45'
        ));
    }

    protected function part1(): string
    {
        $data = $this->getNumbers();

        $numbers = [];
        foreach ($data as $value) {
            $number = 0;
            for ($i = count($value) - 2; $i >= 0; $i--) {
                $number = $value[$i][count($value[$i]) - 1] + $number;
            }
            $numbers[] = $number;
        }

        return (string) array_sum($numbers);
    }

    protected function part2(): string
    {
        $data = $this->getNumbers();
        $numbers = [];
        foreach ($data as $value) {
            $number = 0;
            for ($i = count($value) - 2; $i >= 0; $i--) {
                $number = $value[$i][0] - $number;
            }
            $numbers[] = $number;
        }

        return (string) array_sum($numbers);
    }

    private function checkNumbers(array $numbers): bool
    {
        foreach ($numbers as $number) {
            if ($number != 0) {
                return false;
            }
        }

        return true;
    }

    private function getNumbers(): array
    {
        $data = [];
        foreach ($this->data as $key => $line) {
            $data[$key] = [];
            $numbers = explode(' ', $line);
            $data[$key][] = $numbers;
            while (! $this->checkNumbers($numbers)) {
                $newNumbers = [];
                for ($i = 1; $i < count($numbers); $i++) {
                    $newNumbers[] = $numbers[$i] - $numbers[$i - 1];
                }
                $numbers = $newNumbers;
                $data[$key][] = $numbers;
            }
        }

        return $data;
    }
}
