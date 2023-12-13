<?php

namespace AOC;

abstract class Base
{
    protected array $data;

    protected int $part;

    public function __construct(protected string $title, int $day)
    {
        $this->data = preg_split('/\n/', file_get_contents('./src/inputs/day'.$day.'.txt'));
        $this->part = 1;
    }

    public function run(bool $test = false): void
    {
        if ($test) {
            $this->testData(1);
        }
        echo $this->title.PHP_EOL.PHP_EOL;
        echo 'Part 1: ';
        echo $this->part1().PHP_EOL.PHP_EOL;

        if ($test) {
            $this->testData(2);
        }

        $this->part = 2;
        echo 'Part 2: ';
        echo $this->part2().PHP_EOL;
    }

    abstract protected function part1(): string;

    abstract protected function part2(): string;

    abstract protected function testData(int $part): void;
}
