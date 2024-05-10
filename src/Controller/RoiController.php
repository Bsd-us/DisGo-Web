<?php
    namespace App\Controller;

    use App\Util\Roi;
    use Doctrine\ODM\MongoDB\DocumentManager;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;

    class RoiController extends AbstractController
    {
        #[Route('/roi')]
        public function test(DocumentManager $dm, ROI $roi): void
        {
            dd($roi->calculate($dm, "winter2013"));
        }
    }
