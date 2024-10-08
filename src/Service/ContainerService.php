<?php
    namespace App\Service;

    use App\Document\Container;
    use App\Service\RoiService;
    use Doctrine\ODM\MongoDB\DocumentManager;

    class ContainerService
    {
        private const RARITY_CHANCES = [
            'yellow' => 0.004,
            'red' => 0.006,
            'pink' => 0.032,
            'purple' => 0.159,
            'blue' => 0.799,
        ];

        public function __construct(
            private DocumentManager $dm,
            private RoiService $roi,
        ) {}

        public function getBestCasesForWeapon(string $weapon): array
        {
            $containers = $this->dm->getRepository(Container::class)->findBy([
                'active' => true,
                'type' => 'case',
                'currType' => 'money',
            ]);

            $bestCases = [];
            foreach ($containers as $container) {
                $weaponItems = $this->getItemsByPrefix($container->getItems(), $weapon);
                if (!empty($weaponItems)) {
                    $sumChances = 0;
                    $minIndexRarity = count(self::RARITY_CHANCES);
                    foreach ($weaponItems as $weaponItem) {
                        $chancesItemAnyRarity = 1 / $this->getNumberItemsInRarity($container->getItems(), $weaponItem["rarity"]);
                        $sumChances += $chancesItemAnyRarity * self::RARITY_CHANCES[$weaponItem["rarity"]];
                        $minIndexRarity = min($minIndexRarity, array_search($weaponItem["rarity"], array_keys(self::RARITY_CHANCES)));
                    }
                    $bestCases[] = [
                        'caseName' => $container->getCommand(),
                        'minConcernedRarity' => array_keys(self::RARITY_CHANCES)[$minIndexRarity],
                        'priceChances' => $container->getPrice() / $sumChances,
                        'chances' => $sumChances,
                    ];
                }
            }

            usort($bestCases, fn($a, $b) => $a["priceChances"] <=> $b["priceChances"]);
            return $bestCases;
        }

        public function getBestCasesFor2Weapons(string $weapon1, string $weapon2): array
        {
            $res1 = $this->getBestCasesForWeapon($weapon1);
            $res2 = $this->getBestCasesForWeapon($weapon2);

            $bestCases = [];
            foreach ($res1 as $row1) {
                foreach ($res2 as $row2) {
                    if ($row1["caseName"] === $row2["caseName"]) {
                        $bestCases[] = [
                            'caseName' => $row1["caseName"],
                            'minConcernedRarity1' => $row1["minConcernedRarity"],
                            'minConcernedRarity2' => $row2["minConcernedRarity"],
                            'meanPriceChances' => ($row1["priceChances"] + $row2["priceChances"]) / 2,
                        ];
                    }
                }
            }
            usort($bestCases, fn($a, $b) => $a["meanPriceChances"] <=> $b["meanPriceChances"]);
            return $bestCases;
        }

        public function getBestCaseAndCostsForWeapon(string $weapon, int $nbWeapon): array
        {
            $res = $this->getBestCasesForWeapon($weapon);
            $minPriceChances = $res[0]["priceChances"];
            $res = array_filter($res, fn($row) => $row["priceChances"] === $minPriceChances);

            if (count($res) === 1) {
                $res = array_values($res)[0];
            } else {
                $bestRoi = 0;
                foreach ($res as $row) {
                    $roiValue = $this->roi->calculate($row["caseName"]);
                    if ($roiValue > $bestRoi) {
                        $bestRoi = $roiValue;
                        $res = $row;
                    }
                }
            }

            $concernedCase = $this->dm->getRepository(Container::class)->findOneBy(['command' => $res["caseName"]]);
            $nbCases = $nbWeapon / $res["chances"];
            $baseCost = $nbCases * $concernedCase->getPrice();
            $roiValue = $this->roi->calculate($res["caseName"]);

            return [
                'caseName' => $res["caseName"],
                'nbCases' => $nbCases,
                'cost' => $baseCost * (1 - $roiValue),
            ];
        }

        public function getCostForCase(string $case, int $nbCases): float
        {
            $concernedCase = $this->dm->getRepository(Container::class)->findOneBy(['command' => $case]);
            $roiValue = $this->roi->calculate($case);
            return $concernedCase->getPrice() * $nbCases * (1 - $roiValue);
        }

        private function getItemsByPrefix(array $items, string $itemPrefix): array
        {
            $resItems = [];
            foreach ($items as $item) {
                if (strpos($item["name"], $itemPrefix) === 0) {
                    $resItems[] = $item;
                }
            }
            return $resItems;
        }

        private function getNumberItemsInRarity(array $items, string $rarity): int
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
