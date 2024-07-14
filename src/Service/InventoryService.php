<?php
    namespace App\Service;

    use App\Document\User;

    class InventoryService {
        public function getSortedInventory($array, $sortBy, $order): array {
            usort($array, function($a, $b) use ($sortBy, $order) {
                if ($order === 'asc') {
                    return $a[$sortBy] <=> $b[$sortBy];
                } else {
                    return $b[$sortBy] <=> $a[$sortBy];
                }
            });
            return $array;
        }
    }
