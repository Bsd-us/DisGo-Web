<?php
    namespace App\Service;

    use App\Document\MarketItem;
    use Doctrine\ODM\MongoDB\DocumentManager;

    class MarketService
    {
        public function __construct(
            private DocumentManager $dm,
        ) {}

        public function getFormattedMarketItems($itemSort, $itemOrder): array
        {
            $marketItems = $this->dm->getRepository(MarketItem::class)->findAll();

            if ($itemSort !== 'default') {
                $marketItems = $this->sortMarket(
                    $marketItems, $itemSort, $itemOrder
                );
            } elseif ($itemOrder === 'desc') {
                $marketItems = array_reverse($marketItems);
            }

            return $marketItems;
        }

        private function sortMarket($array, $sortBy, $order): array
        {
            usort($array, function($a, $b) use ($sortBy, $order) {
                if ($sortBy === 'price') {
                    if ($order === 'asc') {
                        return $a->getSellPrice() <=> $b->getSellPrice();
                    } else {
                        return $b->getSellPrice() <=> $a->getSellPrice();
                    }
                } elseif ($order === 'asc') {
                    return $a->getItem()[$sortBy] <=> $b->getItem()[$sortBy];
                } else {
                    return $b->getItem()[$sortBy] <=> $a->getItem()[$sortBy];
                }
            });
            return $array;
        }
    }
