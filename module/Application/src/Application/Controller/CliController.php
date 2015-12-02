<?php

namespace Application\Controller;

use Zend\Console\Request as ConsoleRequest;
use Application\Model\Entity\Qr as QrEntity;

class CliController extends AbstractCliController
{
    public function generateQrsAction()
    {
        $request = $this->getRequest();

        // Make sure that we are running in a console and the user has not tricked our
        // application into running this action from a public web server.
        if (!$request instanceof ConsoleRequest){
            throw new \RuntimeException('You can only use this action from a console!');
        }

        $qty = (int) $request->getParam('qty');
        if (!$qty) {
            throw new \RuntimeException('qty is empty!');
        }
        /** @var \Application\Service\Qr $qrService  */
        $qrService = $this->getServiceLocator()->get('Application\Service\Qr');
        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        echo PHP_EOL . 'New codes:' . PHP_EOL . PHP_EOL;

        $QRs = [];
        for ($i = 0; $i < $qty; $i++) {
            $qrEntity = $qrService->generateQr(QrEntity::SOURCE_CARD);
            $QRs[] = $qrEntity;
            $entityManager->persist($qrEntity);
        }
        $entityManager->flush();

        foreach ($QRs as $qrEntity) {
            $code = $qrService->getHexCode($qrEntity);
            echo $code . PHP_EOL;
        }
        //
        echo PHP_EOL . 'Finished' . PHP_EOL . PHP_EOL;
    }
}
