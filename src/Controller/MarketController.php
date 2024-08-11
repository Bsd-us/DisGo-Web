<?php
    namespace App\Controller;

    use App\Service\MarketService;
    use Doctrine\ODM\MongoDB\DocumentManager;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class MarketController extends AbstractController
    {
        private const VALID_SORTS = [null, 'price', 'float', 'rarity'];
        private const VALID_ORDERS = [null, 'asc', 'desc'];

        public function __construct(
            private DocumentManager $dm,
            private MarketService $ms,
        ) {}

        #[Route('/market')]
        public function market(): Response
        {
            if (!empty($_GET)) {
                if (isset($_GET["sort"]) && !in_array($_GET["sort"], self::VALID_SORTS, true)) {
                    throw new \Exception('Invalid sort');
                }
                if (isset($_GET["order"]) && !in_array($_GET["order"], self::VALID_ORDERS, true)) {
                    throw new \Exception('Invalid order');
                }
            }

            $sort = $_GET["sort"] ?? 'default';
            $order = $_GET["order"] ?? 'asc';

            $marketItems = $this->ms->getFormattedMarketItems($sort, $order);

            return $this->render('market.html.twig', [
                'marketItems' => $marketItems,
                'order' => $order,
                'sort' => $sort,
            ]);
        }
    }
