<?php

namespace App;

final class GildedRose {

    private $items = [];

    public function __construct($items) {
        $this->items = $items;
    }

    public function updateQuality() {
        foreach ($this->items as $item) {
            $this->applyQualityReductions($item);
            $this->applyQualityIncreases($item);
            $this->applySellReduction($item);

            // Item sell-by has not been reached, skip the rest
            if ($item->sell_in >= 0) {
                continue;
            }

            // Item "Aged Brie" quality continues to increase after sell-by
            if ($this->applyBrieAdjusment($item)) {
                continue;
            }

            // Item of specific type has quality drop to exactly 0 once past sell-by
            if ($this->neutralizeBackstagePassAfterExpiration($item)) {
                continue;
            }

            // Item quality continues to decrease after sell-by, to a minimum of 0
            $this->finalQualityDeduction($item);
        }
    }

    protected function applyQualityReductions($item) {
        if ($item->name === 'Aged Brie' or $item->name === 'Backstage passes to a TAFKAL80ETC concert') {
            return;
        }

        if ($item->quality <= 0 || $item->name === 'Sulfuras, Hand of Ragnaros') {
            return;
        }

        $item->quality = $item->quality - 1;
    }

    protected function applyQualityIncreases($item) {
        if ($item->name !== 'Aged Brie' and $item->name !== 'Backstage passes to a TAFKAL80ETC concert') {
            return;
        }

        if ($item->quality >= 50) {
            return;
        }

        $item->quality = $item->quality + 1;

        $this->applyExtraQualityIncreases($item);
    }

    protected function applyExtraQualityIncreases($item) {
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

    protected function applySellReduction($item) {
        if ($item->name != 'Sulfuras, Hand of Ragnaros') {
            $item->sell_in = $item->sell_in - 1;
        }
    }

    protected function applyBrieAdjusment($item): bool {
        if ($item->name !== 'Aged Brie') {
            return false;
        }

        if ($item->quality < 50) {
            $item->quality = $item->quality + 1;
        }

        return true;
    }

    protected function neutralizeBackstagePassAfterExpiration($item): bool {
        if ($item->name !== 'Backstage passes to a TAFKAL80ETC concert') {
            return false;
        }

        $item->quality = $item->quality - $item->quality;

        return true;
    }

    protected function finalQualityDeduction($item) {
        if ($item->quality > 0 && $item->name != 'Sulfuras, Hand of Ragnaros') {
            $item->quality = $item->quality - 1;
        }
    }
}

