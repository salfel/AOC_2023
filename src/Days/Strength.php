<?php

namespace AOC\Days;

enum Strength: int
{
    case Five = 0;
    case Four = 1;
    case FullHouse = 2;
    case Three = 3;
    case DoublePair = 4;
    case Pair = 5;
    case High = 6;
}
