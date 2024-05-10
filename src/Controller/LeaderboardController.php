<?php
    namespace App\Controller;

    use App\Document\User;
    use Doctrine\ODM\MongoDB\DocumentManager;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class LeaderboardController extends AbstractController
    {
        private const EXCLUDED_USERS = [
            'm.y.t.h.o.l.o.g.y',
            'ItsSeb',
        ];

        #[Route('/leaderboard')]
        public function user(DocumentManager $dm): Response
        {
            $qb = $dm->createQueryBuilder(User::class);
            $qb->field('username')->notIn(self::EXCLUDED_USERS);
            $qb->field('banType')->exists(false);
            $qb->sort('inventoryValue', 'DESC');
            $qb->limit(10);
            $bestUsers = $qb->getQuery()->execute();

            return $this->render('leaderboard.html.twig', [
                'bestUsers' => $bestUsers,
            ]);
        }
    }
