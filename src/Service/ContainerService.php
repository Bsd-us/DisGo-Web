<?php
    namespace App\Service;

    use App\Document\Container;

    class ContainerService {
        public function getItemsByPrefix(array $items, string $itemPrefix): array
        {
            $resItems = [];
            foreach ($items as $item) {
                if (strpos($item["name"], $itemPrefix) === 0) {
                    $resItems[] = $item;
                }
            }
            return $resItems;
        }

        public function getNumberItemsInRarity(array $items, string $rarity): int
        {
            $count = 0;
            foreach ($items as $item) {
                if ($item["rarity"] === $rarity) {
                    $count++;
                }
            }
            return $count;
        }
    }
