<?php
    namespace App\Controller;

    use App\Document\Mission;
    use App\Document\User;
    use App\Document\Watcher;
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

        #[Route('/user/{id}')]
        #[Route('/user/{id}/{inventoryType?}', requirements: ['inventoryType' => 'skins|stickers'])]
        public function user($id, $inventoryType='skins'): Response
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

            $users = $this->dm->getRepository(User::class);
            $user = $users->findOneBy(['userID' => $id]);
            $watchers = $this->dm->getRepository(Watcher::class);
            $watcher = $watchers->findOneBy(['userID' => $id]);
            if (!$user) {
                $user = $users->findOneBy(['username' => $id]);
                $watcher = $watchers->findOneBy(['username' => $id]);
            }

            $inventory = $user->getInventory();
            if ($inventoryType === 'stickers') {
                $inventory = $user->getItemInventory();
            }
            if (isset($_GET["sort"])) {
                $inventory = $this->us->getSortedInventory(
                    $inventory, $_GET["sort"], $_GET["order"] ?? 'asc'
                );
            } else if (isset($_GET["order"]) && $_GET["order"] === 'desc') {
                $inventory = array_reverse($inventory);
            }

            // $missions = $dm->getRepository(Mission::class);
            // $missions = $missions->findAll();

            // $quests = $watcher->getOperationQuests();

            // dd($quests, $missions);

            return $this->render('user.html.twig', [
                'id' => $id,
                'inventory' => $inventory,
                'inventoryType' => $inventoryType,
                'order' => $_GET["order"] ?? 'default',
                'sort' => $_GET["sort"] ?? 'default',
                'user' => $user,
            ]);
        }
    }
