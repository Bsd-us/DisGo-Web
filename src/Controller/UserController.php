<?php
    namespace App\Controller;

    use App\Service\UserService;
    use Doctrine\ODM\MongoDB\DocumentManager;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class UserController extends AbstractController
    {
        private const VALID_SKINS_SORTS = [null, 'price', 'float', 'rarity'];
        private const VALID_STICKERS_SORTS = [null, 'price', 'rarity'];
        private const VALID_ORDERS = [null, 'asc', 'desc'];

        public function __construct(
            private DocumentManager $dm,
            private UserService $us,
        ) {}

        #[Route('/user/{userID}')]
        #[Route('/user/{userID}/{inventoryType?}', requirements: ['inventoryType' => 'skins|stickers'])]
        public function user($userID, $inventoryType='skins'): Response
        {
            if (!empty($_GET)) {
                $validSorts = $inventoryType === 'skins' ? self::VALID_SKINS_SORTS : self::VALID_STICKERS_SORTS;
                if (isset($_GET["sort"]) && !in_array($_GET["sort"], $validSorts, true)) {
                    throw new \Exception('Invalid sort');
                }
                if (isset($_GET["order"]) && !in_array($_GET["order"], self::VALID_ORDERS, true)) {
                    throw new \Exception('Invalid order');
                }
            }

            $sort = $_GET["sort"] ?? 'default';
            $order = $_GET["order"] ?? 'asc';

            $user = $this->us->getFormattedUser($userID, $inventoryType, $sort, $order);

            return $this->render('user.html.twig', [
                'inventoryType' => $inventoryType,
                'order' => $order,
                'sort' => $sort,
                'user' => $user,
                'userID' => $userID,
            ]);
        }
    }
