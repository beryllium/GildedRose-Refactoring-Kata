<?php

namespace App;

final class GildedRose {

    private $items = [];

    public function __construct($items) {
        $this->items = $items;
    }

    // Item quality decreases
    protected function reduceQuality($item) {
        if ($item->name === 'Aged Brie' or $item->name === 'Backstage passes to a TAFKAL80ETC concert') {
            return;
        }

        if ($item->quality <= 0 || $item->name === 'Sulfuras, Hand of Ragnaros') {
            return;
        }

        $item->quality = $item->quality - 1;
    }

    protected function increaseQuality($item) {
        if ($item->name !== 'Aged Brie' and $item->name !== 'Backstage passes to a TAFKAL80ETC concert') {
            return;
        }

        if ($item->quality >= 50) {
            return;
        }

        $item->quality = $item->quality + 1;

        $this->applyExtraIncreases($item);
    }

    protected function applyExtraIncreases($item) {
        // Item significantly increases in quality as sell-by date approaches
        if ($item->name != 'Backstage passes to a TAFKAL80ETC concert') {
            return;
        }

        if ($item->sell_in >= 11) {
            return;
        }

        if ($item->quality < 50) {
            $item->quality = $item->quality + 1;
        }

        if ($item->sell_in >= 6) {
            return;
        }

        if ($item->quality < 50) {
            $item->quality = $item->quality + 1;
        }
    }

    public function updateQuality() {
        foreach ($this->items as $item) {
            $this->reduceQuality($item);
            $this->increaseQuality($item);

            // Item sell-by date approaches, except in a special case
            if ($item->name != 'Sulfuras, Hand of Ragnaros') {
                $item->sell_in = $item->sell_in - 1;
            }

            // Item sell-by has not been reached, skip the rest
            if ($item->sell_in >= 0) {
                continue;
            }

            // Item quality continues to increase after sell-by
            if ($item->name === 'Aged Brie') {
                if ($item->quality < 50) {
                    $item->quality = $item->quality + 1;
                }

                continue;
            }

            // Item of specific type has quality drop to exactly 0 once past sell-by
            if ($item->name === 'Backstage passes to a TAFKAL80ETC concert') {
                $item->quality = $item->quality - $item->quality;

                continue;
            }

            // Item quality continues to decrease after sell-by, to a minimum of 0
            if ($item->quality > 0 && $item->name != 'Sulfuras, Hand of Ragnaros') {
                $item->quality = $item->quality - 1;
            }
        }
    }
}

