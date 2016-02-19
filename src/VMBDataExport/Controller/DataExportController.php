<?php
namespace VMBDataExport\Controller;

use VMBDataExport\Traits\VerifyPostData;
use Zend\Mvc\Controller\AbstractActionController;

class DataExportController extends AbstractActionController
{

    use VerifyPostData;

    private $redirect_to;

    public function exportAction() {

        $request = $this->getRequest();

        if($request->isPost()) {

            $data = $request->getPost()->toArray();

            if($this->validate($data)) {

                $this->redirect_to = $data['redirect_to'];

                $mainService = $this->getServiceLocator()->get('VMBDataExport\Service\MainService');
                $filePath = $mainService->strategy($data['type'],$data);

                return $this->redirect()->toUrl($filePath);

            }
        }

        return $this->redirect()->toUrl($this->redirect_to);

    }

}