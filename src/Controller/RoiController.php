<?php
    namespace App\Controller;

    use App\Service\RoiService;
    use Doctrine\ODM\MongoDB\DocumentManager;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;

    class RoiController extends AbstractController
    {
        public function __construct(
            private DocumentManager $dm,
            private RoiService $roi,
        ) {}

        #[Route('/roi')]
        public function test(): void
        {
            dd($this->roi->calculate("winter2013"));
        }
    }
