<?php
namespace VMBDataExport\Controller;

use VMBDataExport\Service\MainService;
use VMBDataExport\Traits\VerifyPostData;
use Zend\Mvc\Controller\AbstractActionController;

class DataExportController extends AbstractActionController
{
    use VerifyPostData;

    /**
     * @var string
     */
    private $redirect_to;
    /**
     * @var MainService
     */
    private $service;

    /**
     * DataExportController constructor.
     * @param MainService $service
     */
    public function __construct(MainService $service)
    {
        $this->service = $service;
    }

    /**
     * @return \Zend\Http\Response
     * @throws \Exception
     */
    public function exportAction()
    {

        $request = $this->getRequest();

        if ($request->isPost()) {

            $data = $request->getPost()->toArray();

            if ($this->validate($data)) {
                $this->redirect_to = $data['redirect_to'];
                $filePath = $this->service->strategy($data['type'], $data);
                return $this->redirect()->toUrl($filePath);
            }
        }
        return $this->redirect()->toUrl($this->redirect_to);
    }
}
