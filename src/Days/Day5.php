<?php

namespace AOC\Days;

use AOC\Base;

class Day5 extends Base
{
    public function __construct()
    {
        parent::__construct('--- Day 5: If You Give A Seed A Fertilizer ---', 5);
    }

    protected function testData(int $part): void
    {
        $this->data = array_map(fn ($value) => trim($value), preg_split('/\n/',
            'seeds: 79 14 55 13
                
                seed-to-soil map:
                50 98 2
                52 50 48

                soil-to-fertilizer map:
                0 15 37
                37 52 2
                39 0 15

                fertilizer-to-water map:
                49 53 8
                0 11 42
                42 0 7
                57 7 4

                water-to-light map:
                88 18 7
                18 25 70

                light-to-temperature map:
                45 77 23
                81 45 19
                68 64 13

                temperature-to-humidity map:
                0 69 1
                1 0 69

                humidity-to-location map:
                60 56 37
                56 93 4'
        ));
    }

    protected function part1(): string
    {
        $data = explode(' ', explode(': ', $this->data[0])[1]);
        $min = null;

        for ($i = 2; $i < count($this->data); $i++) {
            $line = $this->data[$i];
            if (trim($line) != '' && ! preg_match('/[0-9]/', $line)) {
                $almanac = $this->getAlmanac($i);

                foreach ($data as $key => $value) {
                    foreach ($almanac as $alma) {
                        $val = $this->getNumberFromRange($value, $alma);
                        if (is_int($val)) {
                            $data[$key] = $val;
                        }
                        if ($min == null) {
                            $min = $data[$key];
                        } elseif ($data[$key] < $min) {
                            $min = $data[$key];
                        }
                    }
                }
            }
        }

        return (string) $min;
    }

    protected function part2(): string
    {
        $data = $newData = $new = [];
        $seeds = explode(' ', explode(': ', $this->data[0])[1]);
        for ($i = 0; $i < count($seeds); $i += 2) {
            $data[] = [
                'start' => $seeds[$i],
                'end' => intval($seeds[$i]) + intval($seeds[$i + 1]) - 1,
            ];
        }

        $almas = [];
        for ($i = 2; $i < count($this->data); $i++) {
            $line = $this->data[$i];
            if (trim($line) != '' && ! preg_match('/[0-9]/', $line)) {
                $almas[] = $this->getAlmanac($i);
            }
        }

        foreach ($almas as $almanacs) {
            $newData = [];
            foreach ($data as $value) {
                $found = false;
                foreach ($almanacs as $almanac) {
                    $val = $this->mapRange($almanac, $value);
                    if ($val) {
                        [$newRange, $range] = $val;
                        $newData[] = $newRange;
                        $data = array_merge($data, $range);
                        $found = true;
                    }
                }
                if (! $found) {
                    $newData[] = $value;
                }
            }
            $data = $newData;
        }

        $min = -1;
        foreach ($data as $value) {
            if ($min == -1) {
                $min = $value['start'];
            }
            $min = min($min, $value['start']);
        }

        return (string) $min;
    }

    private function getNumberFromRange(int $number, array $almanac): int|false
    {
        if ($this->inRange($number, $almanac['source'], $almanac['source'] + $almanac['range'])) {
            return $almanac['destination'] + $number - $almanac['source'];
        }

        return false;
    }

    private function mapRange(array $almanac, array $range): array|false
    {
        $ranges = [];
        $newRange = [];

        if ($this->inRange($almanac['source'], $range['start'], $range['end'])) {
            if (($almanac['source'] + $almanac['range'] - 1) < $range['end']) {
                $newRange = [
                    'start' => $almanac['destination'],
                    'end' => $almanac['destination'] + $almanac['range'] - 1,
                ];

                $ranges[] = [
                    'start' => $range['start'],
                    'end' => $almanac['source'] - 1,
                ];
                $ranges[] = [
                    'start' => $almanac['source'] + $almanac['range'],
                    'end' => $range['end'],
                ];
            } else {
                $newRange = [
                    'start' => $almanac['destination'],
                    'end' => $almanac['destination'] + ($range['end'] - $almanac['source'] - 1),
                ];

                $range[] = [
                    'start' => $range['start'],
                    'end' => $almanac['source'] - 1,
                ];
            }
        } elseif ($this->inRange($range['start'], $almanac['source'], $almanac['source'] + $almanac['range'] - 1)) {
            if (($almanac['source'] + $almanac['range'] - 1) < $range['end']) {
                $newRange = [
                    'start' => $almanac['destination'] + ($range['start'] - $almanac['source']),
                    'end' => $almanac['destination'] + $almanac['range'] - 1,
                ];

                $ranges[] = [
                    'start' => $almanac['source'] + $almanac['range'] - 1,
                    'end' => $range['end'],
                ];
            } else {
                $newRange = [
                    'start' => $almanac['destination'] + ($range['start'] - $almanac['source']),
                    'end' => $almanac['destination'] + ($range['end'] - $almanac['source']),
                ];
            }
        } else {
            return false;
        }

        return [$newRange, $ranges];
    }

    private function getAlmanac(int &$i): array
    {
        $almanac = [];
        for ($i++; $i < count($this->data) && trim($this->data[$i]) != ''; $i++) {
            $line = $this->data[$i];
            [$destination, $source, $range] = explode(' ', $line);

            $almanac[] = [
                'destination' => $destination,
                'source' => $source,
                'range' => $range,
            ];
        }

        return $almanac;
    }

    private function inRange(int $number, int $start, int $end): bool
    {
        return $number >= $start && $number <= $end;
    }
}
