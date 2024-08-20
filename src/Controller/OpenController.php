<?php
    namespace App\Controller;

    use App\Document\Container;
    use App\Document\Item;
    use Doctrine\ODM\MongoDB\DocumentManager;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class OpenController extends AbstractController
    {
        private const RARITY_CHANCES = [
            'yellow' => 0.004,  // 0.000 + 0.004
            'red' => 0.010,     // 0.004 + 0.006
            'pink' => 0.042,    // 0.010 + 0.032
            'purple' => 0.201,  // 0.042 + 0.159
            'blue' => 1.0,      // 0.201 + 0.799
        ];

        public function __construct(
            private DocumentManager $dm,
        ) {}

        #[Route('/open')]
        public function home(): Response
        {
            $items = $this->dm->getRepository(Container::class)->findOneBy(['command' => "chroma"])->getItems();
            $orderedByRarity = [
                'yellow' => [],
                'red' => [],
                'pink' => [],
                'purple' => [],
                'blue' => [],
            ];

            foreach ($items as &$item) {
                $itemDetails = $this->dm->getRepository(Item::class)->findOneBy(['name' => $item['name']]);
                $item['picture'] = $itemDetails->getWear()[0]['picture'];
                $item['float_range'] = $itemDetails->getFloatRange();
                $orderedByRarity[$item['rarity']][] = $item;
            }

            $selectedItems = [];
            for ($i = 0; $i < 100; $i++) {
                $random = mt_rand() / mt_getrandmax();
                foreach (self::RARITY_CHANCES as $rarity => $threshold) {
                    if ($random < $threshold) {
                        $selectedItems[] = $orderedByRarity[$rarity][
                            array_rand($orderedByRarity[$rarity])
                        ];
                        break;
                    }
                }
            }

            return $this->render('open.html.twig', [
                'items' => $selectedItems,
            ]);
        }
    }
