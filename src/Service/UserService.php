<?php
    namespace App\Service;

    use App\Document\User;
    use App\Document\Mission;
    use App\Document\Watcher;
    use Doctrine\ODM\MongoDB\DocumentManager;

    class UserService
    {
        public function __construct(
            private DocumentManager $dm,
        ) {}

        public function getFormattedUser($userID, $inventoryType, $inventorySort, $inventoryOrder)
        {
            $users = $this->dm->getRepository(User::class);
            $watchers = $this->dm->getRepository(Watcher::class);

            // Finding user by userID or username
            $user = $users->findOneBy(['userID' => $userID]);
            $watcher = null;
            if (!$user) {
                $user = $users->findOneBy(['username' => $userID]);
                if (!$user) {
                    // Username can be different in Watcher so we might find it there
                    $watcher = $watchers->findOneBy(['username' => $userID]);
                    if (!$watcher) {
                        throw new \Exception('User not found');
                    }
                    $user = $users->findOneBy(['userID' => $watcher->getUserID()]);
                }
            }
            if (!$watcher) {
                $watcher = $watchers->findOneBy(['userID' => $user->getUserID()]);
            }

            // Figuring user inventory type to sort
            $inventory = $user->getInventory();
            if ($inventoryType === 'stickers') {
                $inventory = $user->getItemInventory();
            }
            if ($inventorySort !== 'default') {
                $inventory = $this->sortInventory(
                    $inventory, $inventorySort, $inventoryOrder
                );
            } elseif ($inventoryOrder === 'desc') {
                $inventory = array_reverse($inventory);
            }

            // Finding and temporarily adding his current missions
            $missions = $this->dm->getRepository(Mission::class);
            $missions = $missions->findAll();
            $quests = $watcher->getOperationQuests();
            $user->currentMissions = [$missions[$quests[0]], $missions[$quests[1]]];

            // Temporarily sorting user inventory for display purposes
            if ($inventoryType === 'stickers') {
                $user->setItemInventory($inventory);
            } else {
                $user->setInventory($inventory);
            }

            return $user;
        }

        private function sortInventory($array, $sortBy, $order): array
        {
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
