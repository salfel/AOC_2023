<?php

namespace AOC\Days;

use AOC\Base;

class Day4 extends Base
{
    public function __construct()
    {
        parent::__construct('--- Day 4: Scratchcards ---', 4);
    }

    protected function testData(int $part): void
    {
        if ($part == 1) {
            $this->data = [
                'Card 1: 41 48 83 86 17 | 83 86  6 31 17  9 48 53',
                'Card 2: 13 32 20 16 61 | 61 30 68 82 17 32 24 19',
                'Card 3:  1 21 53 59 44 | 69 82 63 72 16 21 14  1',
                'Card 4: 41 92 73 84 69 | 59 84 76 51 58  5 54 83',
                'Card 5: 87 83 26 28 32 | 88 30 70 12 93 22 82 36',
                'Card 6: 31 18 13 56 72 | 74 77 10 23 35 67 36 11',
            ];
        }
        if ($part == 2) {
            $this->data = [
                'Card 1: 41 48 83 86 17 | 83 86  6 31 17  9 48 53',
                'Card 2: 13 32 20 16 61 | 61 30 68 82 17 32 24 19',
                'Card 3:  1 21 53 59 44 | 69 82 63 72 16 21 14  1',
                'Card 4: 41 92 73 84 69 | 59 84 76 51 58  5 54 83',
                'Card 5: 87 83 26 28 32 | 88 30 70 12 93 22 82 36',
                'Card 6: 31 18 13 56 72 | 74 77 10 23 35 67 36 11',
            ];
        }
    }

    protected function part1(): string
    {
        $sum = 0;
        foreach ($this->data as $card) {
            $count = 0;
            for ($i = 1; $i <= $this->getWinners($card); $i++) {
                $count = $i == 1 ? 1 : $count * 2;
            }

            $sum += $count;
        }

        return (string) $sum;
    }

    protected function part2(): string
    {
        $winnerCards = array_fill(1, count($this->data), 1);

        foreach ($this->data as $key => $card) {
            $count = $this->getWinners($card);

            for ($i = 2; $i <= $count + 1; $i++) {
                if (! isset($winnerCards[$key + $i])) {
                    $winnerCards[$key + $i] = 1;
                }
                $winnerCards[$key + $i] += $winnerCards[$key + 1];
            }
        }

        return (string) array_sum($winnerCards);
    }

    private function getWinners(string $card): int
    {
        [$winnersString, $havingString] = explode('|', explode(':', $card)[1]);
        $count = 0;

        foreach (explode(' ', $winnersString) as $winner) {
            $having = explode(' ', $havingString);
            if (in_array($winner, $having) && trim($winner) != '') {
                $count++;
            }
        }

        return $count;
    }
}
