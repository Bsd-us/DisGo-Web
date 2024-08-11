<?php
    namespace App\Controller;

    use App\Service\ContainerService;
    use App\Service\RoiService;
    use Doctrine\ODM\MongoDB\DocumentManager;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class OperationController extends AbstractController
    {
        public function __construct(
            private ContainerService $cs,
            private DocumentManager $dm,
            private RoiService $roi,
        ) {}

        // #[Route('/operation')]
        public function test(): void
        {
            //$this->testBestCasesForWeapon('SG');
            //$this->testBestCasesFor2Weapons('MP9', 'R8');
            //dd($this->cs->getBestCaseAndCostsForWeapon('AK', 46));
            dd($this->cs->getCostForCase('glove', 254));
        }

        private function testBestCasesForWeapon(string $weapon): void
        {
            $bestCases = $this->cs->getBestCasesForWeapon($weapon);
            echo '<pre>';
            foreach ($bestCases as $row) {
                echo str_pad($row["caseName"], 15);
                echo str_pad($row["minConcernedRarity"], 10);
                echo str_pad($row["priceChances"], 20);
                echo $row["chances"] . "<br>";
            }
            echo '</pre>';
        }

        private function testBestCasesFor2Weapons(string $weapon1, string $weapon2): void
        {
            $bestCases = $this->cs->getBestCasesFor2Weapons($weapon1, $weapon2);
            echo '<pre>';
            foreach ($bestCases as $row) {
                echo str_pad($row["caseName"], 15);
                echo str_pad($row["minConcernedRarity1"], 10);
                echo str_pad($row["minConcernedRarity2"], 10);
                echo $row["meanPriceChances"] . "<br>";
            }
            echo '</pre>';
        }
    }
