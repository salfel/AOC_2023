<?php

namespace AOC\Days;

use AOC\Base;

class Day8 extends Base
{
    public function __construct()
    {
        parent::__construct('--- Day 8: Haunted Wasteland ---', 8);
    }

    protected function testData(int $part): void
    {
        if ($part == 1) {
            $this->data = preg_split('/\n/',
                'LLR

                AAA = (BBB, BBB)
                BBB = (AAA, ZZZ)
                ZZZ = (ZZZ, ZZZ)'
            );

            return;
        }

        $this->data = preg_split('/\n/',
            'LR

            11A = (11B, XXX)
            11B = (XXX, 11Z)
            11Z = (11B, XXX)
            22A = (22B, XXX)
            22B = (22C, 22C)
            22C = (22Z, 22Z)
            22Z = (22B, 22B)
            XXX = (XXX, XXX)'
        );
    }

    protected function part1(): string
    {
        $sequence = str_split($this->data[0]);
        $instructions = $this->getInstructions();

        $node = $instructions['AAA'];
        for ($i = 0; $node->id != 'ZZZ'; $i++) {
            $dir = strtolower($sequence[$i % count($sequence)]);

            $node = $instructions[$node->{$dir}];
        }

        return (string) $i;
    }

    protected function part2(): string
    {
        $sequence = str_split($this->data[0]);
        $instructions = $this->getInstructions();

        $nodes = array_map(fn (Node $node) => ['iterations' => 0, 'node' => $node, 'occurrences' => []], array_filter($instructions, fn (Node $node) => str_ends_with($node->id, 'A')));
        $newNodes = [];

        for ($i = 0; count($nodes); $i++) {
            foreach ($nodes as $key => $node) {
                $dir = strtolower($sequence[$i % count($sequence)]);

                $nodes[$key]['node'] = $instructions[$node['node']->{$dir}];
                if (str_ends_with($nodes[$key]['node']->id, 'Z')) {
                    $lastFound = count($nodes[$key]['occurrences']) == 0 ? 0 : $node['occurrences'][count($node['occurrences']) - 1]['i'];
                    $nodes[$key]['occurrences'][] = [
                        'i' => $i,
                        'pos' => $i % count($sequence),
                        'id' => $nodes[$key]['node']->id,
                        'lastFound' => $lastFound,
                    ];

                    $node = $nodes[$key];
                    $occurrences = $nodes[$key]['occurrences'];
                    $occurrenceCount = count($occurrences) - 1;

                    if ($occurrenceCount > 2) {
                        $node['iteration'] = $occurrences[$occurrenceCount]['i'] - $occurrences[$occurrenceCount - 1]['i'];
                        $newNodes[] = $node;
                        unset($nodes[$key]);
                    }
                }
            }
        }

        $iterations = array_map(fn (array $node) => $node['iteration'], $newNodes);

        return (string) $this->lcm($iterations);
    }

    private function getInstructions(): array
    {
        $instructions = [];
        for ($i = 2; $i < count($this->data); $i++) {
            [$key, $lr] = explode(' = ', $this->data[$i]);

            [$l, $r] = explode(', ', str_replace(['(', ')'], '', $lr));

            $instructions[trim($key)] = new Node(trim($key), $l, $r);
        }

        return $instructions;
    }

    private function gcd(int $a, int $b): int
    {
        if ($b == 0) {
            return $a;
        }

        return $this->gcd($b, $a % $b);
    }

    private function lcm($numbers): int
    {
        $result = 1;
        foreach ($numbers as $number) {
            $result = ($result * $number) / $this->gcd($result, $number);
        }

        return $result;
    }
}
