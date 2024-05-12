<?php
    namespace App\Controller;

    use App\Document\User;
    use Doctrine\ODM\MongoDB\DocumentManager;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class UserController extends AbstractController
    {
        #[Route('/user/{id}')]
        #[Route('/user/{id}/{inventoryType?}', requirements: ['inventoryType' => 'skins|stickers'])]
        public function user(DocumentManager $dm, $id, $inventoryType = null): Response
        {
            $users = $dm->getRepository(User::class);

            $user = $users->findOneBy(['userID' => $id]);
            if (!$user) {
                $user = $users->findOneBy(['username' => $id]);
            }

            if (!$inventoryType) {
                $inventoryType = 'skins';
            }

            return $this->render('user.html.twig', [
                'user' => $user,
                'id' => $id,
                'inventoryType' => $inventoryType,
            ]);
        }
    }
