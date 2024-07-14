<?php
    namespace App\Controller;

    use App\Util\Roi;
    use Doctrine\ODM\MongoDB\DocumentManager;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;

    class RoiController extends AbstractController
    {
        public function __construct(
            private DocumentManager $dm,
            private Roi $roi,
        ) {}

        #[Route('/roi')]
        public function test(): void
        {
            dd($this->roi->calculate($this->dm, "winter2013"));
        }
    }
