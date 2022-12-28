# Advent of Code 2022
http://adventofcode.com/2022

## Goals

* write **as little code as possible**
* while maintaining **readability**
* try maximizing **performance**
* **no dependencies**, just a PHP8 environment

## Usage

Each day's solution resides in a numbered directory containing a text file `input.txt` as input data and `solve.php` for processing.

Run using php-cli, e.g.:

`php 01/solve.php`

To profile execution time and memory usage, use (for day 1 for example):

`php profile.php 1`

## Specials

### SVG renderings

For the following days you can generate SVGs (`output_1.svg` resp. `output_2.svg` in the day's folder). Just include `visualize.php` and uncomment the `createSVG()` calls.

* Day 12: height map with the found path
* Day 14: the rock structure and sand piles