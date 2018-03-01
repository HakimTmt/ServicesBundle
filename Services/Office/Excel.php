<?php

namespace Tmt\ServicesBundle\Services\Office;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use PHPExcel_IOFactory;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Excel
 *
 * @author tmt
 */
class Excel {

    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    public function create_excel_file($data, $file_name) {

        $alphabet = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("liuggio")
                ->setLastModifiedBy("Giulio De Donato")
                ->setTitle("Office 2005 XLSX Test Document")
                ->setSubject("Office 2005 XLSX Test Document")
                ->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
                ->setKeywords("office 2005 openxml php")
                ->setCategory("Test result file");


        foreach ($data as $key => $values) {

            $i = 0;
            foreach ($values as $key2 => $val) {
                $phpExcelObject->setActiveSheetIndex(0)
                        ->setCellValue($alphabet[$i] . ($key + 1), (is_array($val)) ? implode(',', $val) : $val);

                $i++;
            }
        }

        $phpExcelObject->getActiveSheet()->setTitle('Simple');
        $writer = $this->container->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->container->get('phpexcel')->createStreamedResponse($writer);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=' . $file_name . '.csv');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0



        return $response;
    }

    public function readExcel($inputFileName) {
//  Read your Excel workbook
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
            $data = array();
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
        }
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        for ($row = 1; $row <= $highestRow; $row++) {
            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
            foreach ($rowData as $val) {
                array_push($data, $val);
            }
        }

        return $data;
    }

    public function read_excel_file($file_path) {

        $objPHPExcel = $this->container->get('phpexcel')->createPHPExcelObject($file_path);
        $ligne = array();
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {


            foreach ($worksheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                $lignes = array();

                foreach ($cellIterator as $cell) {
                    array_push($lignes, $cell->getValue());
                }
                array_push($ligne, $lignes);
            }
        }

        return $ligne;
    }

}
