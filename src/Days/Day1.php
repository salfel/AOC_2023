<?php

namespace AOC\Days;

use AOC\Base;

class Day1 extends Base
{
    private array $numbers = [
        1 => 'one',
        2 => 'two',
        3 => 'three',
        4 => 'four',
        5 => 'five',
        6 => 'six',
        7 => 'seven',
        8 => 'eight',
        9 => 'nine',
    ];

    public function __construct()
    {
        parent::__construct('--- Day 1: Trebuchet?! ---', 1);
    }

    public function testData(int $part): void
    {
        if ($part == 1) {
            $this->data = [
                '1abc2',
                'pqr3stu8vwx',
                'a1b2c3d4e5f',
                'treb7uchet',
            ];
        } elseif ($part == 2) {
            $this->data = [
                'two1nine',
                'eightwothree',
                'abcone2threexyz',
                'xtwone3four',
                '4nineeightseven2',
                'zoneight234',
                '7pqrstsixteen',
            ];
        }
    }

    protected function part1(): string
    {
        $sum = 0;
        foreach ($this->data as $line) {
            $leftmost = -1;
            $rightmost = -1;
            if (strlen($line) == 0) {
                continue;
            }
            foreach (str_split($line) as $char) {
                if (is_numeric($char) && $leftmost == -1) {
                    $leftmost = intval($char);
                } elseif (is_numeric($char)) {
                    $rightmost = intval($char);
                }
            }
            if ($rightmost == -1) {
                $rightmost = $leftmost;
            }

            $sum += $leftmost * 10 + $rightmost;
        }

        return (string) $sum;
    }

    protected function part2(): string
    {
        $sum = 0;
        foreach ($this->data as $line) {
            $validString = '';
            $leftmost = null;
            $rightmost = null;
            if (strlen($line) == 0) {
                continue;
            }
            foreach (str_split($line) as $char) {
                if (is_numeric($char)) {
                    if ($leftmost == null) {
                        $leftmost = intval($char);
                    }
                    $rightmost = intval($char);
                    $validString = '';
                } else {
                    $validString .= $char;
                    $number = $this->getNumber($validString);
                    if ($number) {
                        $leftmost ??= array_search($number, $this->numbers);
                        $rightmost = array_search($number, $this->numbers);
                        $validString = substr($validString, -(strlen($number) - 1));
                    }
                }
            }

            $sum += $leftmost * 10 + $rightmost;
        }

        return (string) $sum;
    }

    private function getNumber(string $value): string|false
    {
        foreach ($this->numbers as $number) {
            if (str_contains($value, $number)) {
                return $number;
            }
        }

        return false;
    }
}
