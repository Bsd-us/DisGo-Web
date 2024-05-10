<?php
    namespace App\Controller;

    use App\Document\User;
    use Doctrine\ODM\MongoDB\DocumentManager;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class UserController extends AbstractController
    {
        #[Route('/user')]
        public function user(DocumentManager $dm): Response
        {
            $users = $dm->getRepository(User::class);
            if (isset($_GET['ID'])) {
                $user = $users->findOneBy(['userID' => $_GET['ID']]);
            } elseif (isset($_GET['name'])) {
                $user = $users->findOneBy(['username' => $_GET['name']]);
            }

            return $this->render('user.html.twig', [
                'user' => $user,
            ]);
        }
    }
