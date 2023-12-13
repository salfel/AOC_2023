<?php

namespace AOC\Days;

use AOC\Base;

class Day7 extends Base
{
    private array $strengths;

    public function __construct()
    {
        parent::__construct('--- Day 7: Camel Cards ---', 7);

        $this->strengths = [
            [5],
            [4],
            [3, 2],
            [3],
            [2, 2],
            [2],
            [1],
        ];
    }

    protected function testData(int $part): void
    {
        $this->data = array_map(fn ($value) => trim($value), preg_split('/\n/',
            '32T3K 765
            T55J5 684
            KK677 28
            KTJJT 220
            QQQJA 483'
        ));
    }

    protected function part1(): string
    {
        $hands = [];
        foreach ($this->data as $line) {
            [$hand, $bid] = explode(' ', $line);
            $cards = array_count_values(str_split($hand));

            $strength = $this->getStrength($cards);

            $hands[] = [
                'strength' => $strength->value,
                'bid' => $bid,
                'cards' => str_split($hand),
            ];
        }

        usort($hands, [$this, 'sortHands']);

        $winnings = 0;
        foreach ($hands as $key => $hand) {
            $winnings += $hand['bid'] * ($key + 1);

            if ($this->part == 1) {
                continue;
            }
            echo implode('', $hand['cards']).' '.Strength::from($hand['strength'])->name.' '.PHP_EOL;
        }

        return (string) $winnings;
    }

    protected function part2(): string
    {
        return $this->part1();
    }

    private function getStrength(array $values): Strength
    {
        foreach ($this->strengths as $key => $strength) {
            $matches = 0;
            $newValues = $values;
            foreach ($strength as $card) {
                if ($this->getMaxFromValues($newValues) >= $card) {
                    $k = array_search(max($newValues), $newValues);
                    unset($newValues[$k]);

                    $matches++;
                }
            }
            if ($matches == count($strength)) {
                return Strength::from($key);
            }
        }

        return Strength::High;
    }

    private function sortHands(array $a, array $b): int
    {
        $cards = $this->getCards();
        $diff = $a['strength'] - $b['strength'];
        if ($diff != 0) {
            return -$diff;
        }

        for ($i = 0; $i < 5 && $diff == 0; $i++) {
            $diff = $cards[$a['cards'][$i]] - $cards[$b['cards'][$i]];

        }

        return -$diff;
    }

    private function getMaxFromValues(array &$values): int
    {
        if ($this->part == 1) {
            return max($values);
        }
        $add = 0;
        if (isset($values['J']) && ! isset($values['used'])) {
            $add = $values['J'];
        }
        $newValues = $values;
        unset($newValues['J']);
        if (empty($newValues)) {
            $newValues = $values;
        }
        $max = max($newValues) + $add;

        $values['used'] = true;

        return $max;
    }

    private function getCards(): array
    {
        if ($this->part == 1) {
            return [
                'A' => 0,
                'K' => 1,
                'Q' => 2,
                'J' => 3,
                'T' => 4,
                '9' => 5,
                '8' => 6,
                '7' => 7,
                '6' => 8,
                '5' => 9,
                '4' => 10,
                '3' => 11,
                '2' => 12,
            ];
        }

        return [
            'A' => 0,
            'K' => 1,
            'Q' => 2,
            'T' => 3,
            '9' => 4,
            '8' => 5,
            '7' => 6,
            '6' => 7,
            '5' => 8,
            '4' => 9,
            '3' => 10,
            '2' => 11,
            'J' => 12,
        ];
    }
}
