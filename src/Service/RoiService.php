<?php
    namespace App\Service;

    use App\Document\Container;
    use App\Document\Item;
    use Doctrine\ODM\MongoDB\DocumentManager;

    class RoiService
    {
        private const WEAR_BOUNDS = [
            'Factory New' => [0, 0.07],
            'Minimal Wear' => [0.07, 0.15],
            'Field-Tested' => [0.15, 0.38],
            'Well-Worn' => [0.38, 0.45],
            'Battle-Scarred' => [0.45, 1],
        ];

        private const RARITY_CHANCES = [
            'yellow' => 0.004,
            'red' => 0.006,
            'pink' => 0.032,
            'purple' => 0.159,
            'blue' => 0.799,
        ];

        public function __construct(
            private DocumentManager $dm,
        ) {}

        public function calculate(string $container): float
        {
            $containers = $this->dm->getRepository(Container::class);
            $container = $containers->findOneBy(['command' => $container]);
            $containerItems = $container->getItems();

            $perRarityItems = [];
            $items = $this->dm->getRepository(Item::class);
            foreach ($containerItems as $containerItem) {
                $item = $items->findOneBy(['name' => $containerItem["name"]]);
                $perRarityItems[$containerItem["rarity"]][] = $this->calculateAveragePrice($item);
            }

            $returnSum = 0;
            foreach ($perRarityItems as $rarity => $items) {
                $itemsAveragePrice = array_sum($items) / count($items);
                $returnSum += self::RARITY_CHANCES[$rarity] * $itemsAveragePrice;
            }

            // 20% tax due to premium unactivated
            $returnSum *= 0.8;
            return $returnSum / $container->getPrice();
        }

        private function updateWearBounds(array $customWearBounds, float $minItemFloat, float $maxItemFloat): array
        {
            foreach ($customWearBounds as $condition => list(, $maxWearFloat)) {
                if ($minItemFloat < $maxWearFloat) {
                    $customWearBounds[$condition][0] = $minItemFloat;
                    break;
                } else {
                    unset($customWearBounds[$condition]);
                }
            }

            foreach(array_reverse($customWearBounds) as $condition => list($minWearFloat, )) {
                if ($maxItemFloat > $minWearFloat) {
                    $customWearBounds[$condition][1] = $maxItemFloat;
                    break;
                } else {
                    unset($customWearBounds[$condition]);
                }
            }

            return $customWearBounds;
        }

        private function calculateAveragePrice(Item $item): float
        {
            list($minItemFloat, $maxItemFloat) = $item->getFloatRange();
            $customWearBounds = $this->updateWearBounds(self::WEAR_BOUNDS, $minItemFloat, $maxItemFloat);

            $averageItemPrice = 0;
            foreach ($item->getWear() as $wear) {
                list($minWearFloat, $maxWearFloat) = $customWearBounds[$wear["condition"]];
                $wearChances = $maxWearFloat - $minWearFloat;
                $averageItemPrice += ($wearChances / ($maxItemFloat - $minItemFloat)) * $wear["price"];
            }

            return $averageItemPrice;
        }
    }
