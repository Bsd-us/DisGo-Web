<?php
    namespace App\Util;

    use App\Util\Roi;
    use App\Document\Container;
    use App\Document\Item;
    use Doctrine\ODM\MongoDB\DocumentManager;

    class WeaponMission
    {
        private const RARITY_CHANCES = [
            'yellow' => 0.004,
            'red' => 0.006,
            'pink' => 0.032,
            'purple' => 0.159,
            'blue' => 0.799,
        ];

        public function getBestCasesForWeapon(DocumentManager $dm, string $weapon): array
        {
            $containers = $dm->getRepository(Container::class)->findBy([
                'active' => true,
                'type' => 'case',
                'currType' => 'money',
            ]);

            $bestCases = [];
            foreach ($containers as $container) {
                $weaponItems = $container->getItemsByPrefix($weapon);
                if (!empty($weaponItems)) {
                    $sumChances = 0;
                    $minIndexRarity = count(self::RARITY_CHANCES);
                    foreach ($weaponItems as $weaponItem) {
                        $chancesItemAnyRarity = 1 / $container->getNumberItemsInRarity($weaponItem["rarity"]);
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

        public function getBestCasesFor2Weapons(DocumentManager $dm, string $weapon1, string $weapon2): array
        {
            $res1 = $this->getBestCasesForWeapon($dm, $weapon1);
            $res2 = $this->getBestCasesForWeapon($dm, $weapon2);

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

        public function getBestCaseAndCostsForWeapon(DocumentManager $dm, ROI $roi, string $weapon, int $nbWeapon): array
        {
            $res = $this->getBestCasesForWeapon($dm, $weapon);
            $minPriceChances = $res[0]["priceChances"];
            $res = array_filter($res, fn($row) => $row["priceChances"] === $minPriceChances);

            if (count($res) === 1) {
                $res = array_values($res)[0];
            } else {
                $bestRoi = 0;
                foreach ($res as $row) {
                    $roiValue = $roi->calculate($dm, $row["caseName"]);
                    if ($roiValue > $bestRoi) {
                        $bestRoi = $roiValue;
                        $res = $row;
                    }
                }
            }

            $concernedCase = $dm->getRepository(Container::class)->findOneBy(['command' => $res["caseName"]]);
            $nbCases = $nbWeapon / $res["chances"];
            $baseCost = $nbCases * $concernedCase->getPrice();
            $roi = $roi->calculate($dm, $res["caseName"]);

            return [
                'caseName' => $res["caseName"],
                'nbCases' => $nbCases,
                'cost' => $baseCost * (1 - $roi),
            ];
        }

        public function getCostForCase(DocumentManager $dm, ROI $roi, string $case, int $nbCases): float
        {
            $concernedCase = $dm->getRepository(Container::class)->findOneBy(['command' => $case]);
            $roi = $roi->calculate($dm, $case);
            return $concernedCase->getPrice() * $nbCases * (1 - $roi);
        }
    }