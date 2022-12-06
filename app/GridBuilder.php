<?php

namespace App;

use App\Models\Pull;
use Illuminate\Support\Collection;

class GridBuilder
{
    public $grid;
    public array $cursor = [0, 0]; // [row, column]

    public static function make($items, $size)
    {
        return (new self($items, $size))->grid;
    }

    public function __construct(
        public Collection $items,
        public int $size
    ) {
        $this->createGrid();

        $items->each(function (Pull $pull) {
            // Defaults
            $pull->columns = 1;
            $pull->rows = 1;

            // Skip filled squares
            if ($this->grid[$this->cursor[0]][$this->cursor[1]] !== '__FILLED__') {
                // Fill the square(s)
                $this->grid[$this->cursor[0]][$this->cursor[1]] = $pull;

                // Portrait image
                if ($pull->gridSize->get('columns') < $pull->gridSize->get('rows')) {
                    $this->grid[$this->cursor[0] + 1][$this->cursor[1]] = '__FILLED__';
                    $pull->rows = 2;
                }

                // Landscape image
                if ($pull->gridSize->get('columns') > $pull->gridSize->get('rows')) {
                    // If the next space isn't FILLED or OOB, then we can place the image
                    if (! in_array($this->getNextSquare(), ['__FILLED__', '__OOB__'])) {
                        $this->setNextSquare('__FILLED__');
                        $pull->columns = 2;
                    }
                }
            }

            // Move the cursor
            $this->cursor[1] += $pull->columns;
            if ($this->cursor[1] >= $this->size) {
                $this->cursor[0] += 1;
                $this->cursor[1] = 0;
            }
        });

        $this->grid = collect($this->grid)->map(function ($row) {
            return collect($row)->map(function ($item) {
                return (in_array($item, ['__FILLED__', '__OOB__'])) ? null : $item;
            });
        })->flatten()->filter();
    }

    private function createGrid()
    {
        $this->grid = array_pad([],
            ceil($this->items->count() / $this->size) * 2,
            array_pad([], $this->size, null)
        );
    }

    private function getNextSquare()
    {
        if ($this->cursor[1] + 1 >= $this->size) {
            return '__OOB__';
        }

        return $this->grid[$this->cursor[0]][$this->cursor[1] + 1];
    }

    private function setNextSquare($value)
    {
        $this->grid[$this->cursor[0]][$this->cursor[1] + 1] = $value;
    }
}
