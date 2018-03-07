<?php

namespace Tmt\ServicesBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * test services controller.
 */
class TestServicesController extends Controller {

    /**
     * test asyncService
     *
     * @Route("/test/async_service", name="test_async_service" )
     * @Method("GET")
     */
    public function AsyncServiceAction() {
        $response = new JsonResponse();
        $async_service = $this->get('async_service');
        $arg = 'arg1';
        $args[] = 'array';
        $service = 'async_service';
        $method = 'test';
        $async_service->AsyncServiceCall($service, $method, array($arg, $args));
        return $response->setData('ok');
    }

    /**
     * test asyncService
     *
     * @Route("/test/twig/extension", name="test_twig_extenion" )
     * @Method("GET")
     */
    public function twigExtentionAction() {
        $date = new \DateTime();
        sleep(10);
        return $this->render('@TmtServices/test.html.twig', array(
                    'dai' => 'http://dai.ly/x2epiza',
                    'dailymotion' => 'http://www.dailymotion.com/video/x2epiza_ubisoft-jeux-video-les-lapins-cretins-et-la-fin-du-monde-decembre-2012-petage-de-plomb_creation',
                    'youtu' => 'http://youtu.be/sOML64y5dfQ',
                    'vimeo' => 'https://vimeo.com/184065357',
                    'date' => $date,
        ));
    }

    /**
     * test asyncService
     *
     * @Route("/test/office/excel", name="test_office_service" )
     * @Method("GET")
     */
    public function excelAction() {
        $office_service = $this->get('office_service');
        $data = [];
        for ($i = 1; $i <= 20; $i++) {
            $data[][] = "v$i";
        }
        $file_path = $office_service->save_excel_file($data, 'test');
        $res = $office_service->readExcel($file_path);
        return $office_service->reponse_excel_file($data, 'test');
    }

    /**
     * test asyncService
     *
     * @Route("/test/pagination", name="test_pagination_service" )
     * @Method("GET")
     */
    public function paginationAction() {
        $response = new JsonResponse();
        $pagination_service = $this->get('pagination_service');
        $totalPages = $pagination_service->totalPages(60, 5);
        $data['links'] = $pagination_service->paginationLink(6, $totalPages);
        return $response->setData($data);
    }

}
