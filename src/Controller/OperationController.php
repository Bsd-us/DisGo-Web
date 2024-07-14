<?php
    namespace App\Controller;

    use App\Util\Roi;
    use App\Util\WeaponMission;
    use Doctrine\ODM\MongoDB\DocumentManager;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class OperationController extends AbstractController
    {
        public function __construct(
            private DocumentManager $dm,
            private Roi $roi,
            private WeaponMission $wm,
        ) {}

        #[Route('/operation')]
        public function test(): void
        {
            //$this->testBestCasesForWeapon($dm, $wm, 'SG');
            //$this->testBestCasesFor2Weapons($dm, $wm, 'MP9', 'R8');
            //dd($wm->getBestCaseAndCostsForWeapon($dm, $roi, 'AK', 46));
            dd($this->wm->getCostForCase($this->dm, $this->roi, 'glove', 254));
        }

        private function testBestCasesForWeapon(string $weapon): void
        {
            $bestCases = $this->wm->getBestCasesForWeapon($this->dm, $weapon);
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
            $bestCases = $this->wm->getBestCasesFor2Weapons($this->dm, $weapon1, $weapon2);
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
