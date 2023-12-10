<?php

namespace AOC\Days;

use AOC\Base;

class Day2 extends Base
{
    private array $colors = [
        'red' => 12,
        'green' => 13,
        'blue' => 14,
    ];

    public function __construct()
    {
        parent::__construct('--- Day 2: Cube Conundrum ---', 2);
    }

    public function testData(int $part): void
    {
        $this->data = [
            'Game 1: 3 blue, 4 red; 1 red, 2 green, 6 blue; 2 green',
            'Game 2: 1 blue, 2 green; 3 green, 4 blue, 1 red; 1 green, 1 blue',
            'Game 3: 8 green, 6 blue, 20 red; 5 blue, 4 red, 13 green; 5 green, 1 red',
            'Game 4: 1 green, 3 red, 6 blue; 3 green, 6 red; 3 green, 15 blue, 14 red',
            'Game 5: 6 red, 1 blue, 3 green; 2 blue, 1 red, 2 green',
        ];
    }

    public function part1(): string
    {
        $sum = 0;
        foreach ($this->data as $game) {
            $possible = true;
            [$gameId, $cubes] = explode(':', $game);
            $sets = explode(';', $cubes);
            foreach ($sets as $set) {
                $colors = explode(',', trim($set));
                foreach ($colors as $color) {
                    [$amount, $color] = explode(' ', trim($color));
                    if ($this->colors[$color] < $amount) {
                        $possible = false;
                    }
                }
            }
            if ($possible) {
                $sum += intval(explode(' ', $gameId)[1]);
            }
        }

        return (string) $sum;
    }

    public function part2(): string
    {
        $sum = 0;
        foreach ($this->data as $game) {
            $cubes = explode(':', $game);
            $sets = explode(';', $cubes[1]);
            $minimumCubes = [
                'red' => 0,
                'green' => 0,
                'blue' => 0,
            ];
            foreach ($sets as $set) {
                $colors = explode(',', trim($set));
                foreach ($colors as $color) {
                    [$amount, $color] = explode(' ', trim($color));
                    if ($amount > $minimumCubes[$color]) {
                        $minimumCubes[$color] = intval($amount);
                    }
                }
            }
            $power = 1;
            foreach ($minimumCubes as $cube) {
                $power *= $cube;
            }
            $sum += $power;
        }

        return (string) $sum;
    }
}
