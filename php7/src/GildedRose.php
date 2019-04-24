<?php

namespace App;

final class GildedRose {

    private $items = [];

    public function __construct($items) {
        $this->items = $items;
    }

    public function updateQuality() {
        foreach ($this->items as $item) {
            if ($this->applySpecialLogic($item)) {
                continue;
            }

            $item->sell_in = $item->sell_in - 1;

            if ($item->quality > 0) {
                $item->quality = $item->quality - 1;
            }

            if ($item->quality > 0 && $item->sell_in < 0) {
                $item->quality = $item->quality - 1;
            }

            continue;
        }
    }

    protected function applySpecialLogic($item): bool {
        if ($item->name === 'Sulfuras, Hand of Ragnaros') {
            $item->quality = 80;

            return true;
        }

        if ($item->name === 'Aged Brie') {
            if ($item->quality < 50) {
                $item->quality = $item->quality + 1;
            }

            $item->sell_in = $item->sell_in - 1;

            if ($item->sell_in >= 0) {
                return true;
            }

            if ($item->quality < 50) {
                $item->quality = $item->quality + 1;
            }

            return true;
        }

        if ($item->name === 'Backstage passes to a TAFKAL80ETC concert') {
            if ($item->quality < 50) {
                $item->quality = $item->quality + 1;

                if ($item->sell_in < 11) {
                    if ($item->quality < 50) {
                        $item->quality = $item->quality + 1;
                    }
                }

                if ($item->sell_in < 6) {
                    if ($item->quality < 50) {
                        $item->quality = $item->quality + 1;
                    }
                }
            }

            $item->sell_in = $item->sell_in - 1;

            if ($item->sell_in >= 0) {
                return true;
            }

            $item->quality = $item->quality - $item->quality;

            return true;
        }

        if (0 === strpos($item->name, 'Conjured')) {
            $item->sell_in = $item->sell_in - 1;

            if ($item->quality > 0) {
                $item->quality = $item->quality - 2;
            }

            if ($item->quality > 0 && $item->sell_in < 0) {
                $item->quality = $item->quality - 2;
            }

            if ($item->quality < 0) {
                $item->quality = 0;
            }

            return true;
        }

        return false;
    }
}

