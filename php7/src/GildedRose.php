<?php

namespace App;

final class GildedRose {

    private $items = [];

    public function __construct($items) {
        $this->items = $items;
    }

    public function updateQuality() {
        foreach ($this->items as $item) {
            // Item quality decreases
            if ($item->name != 'Aged Brie' and $item->name != 'Backstage passes to a TAFKAL80ETC concert') {
                if ($item->quality > 0 && $item->name != 'Sulfuras, Hand of Ragnaros') {
                    $item->quality = $item->quality - 1;
                }
            }

            // Item quality increases to a maximum of 50
            if ($item->name === 'Aged Brie' or $item->name === 'Backstage passes to a TAFKAL80ETC concert') {
                if ($item->quality < 50) {
                    $item->quality = $item->quality + 1;

                    // Item significantly increases in quality as sell-by date approaches
                    if ($item->name == 'Backstage passes to a TAFKAL80ETC concert') {
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
                }
            }

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

