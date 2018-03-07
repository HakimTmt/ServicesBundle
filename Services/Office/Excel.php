<?php

namespace Tmt\ServicesBundle\Services\Office;

use PHPExcel_IOFactory;
use Liuggio\ExcelBundle\Factory as LiuggioFactory;

/**
 * Services to read and write Excel file
 *
 * @author adouiri@techmyteam.com
 * @author aaboulhaj@techmyteam.com
 */
class Excel {


    public function __construct(LiuggioFactory $phpexcel,$root_dir) {
        $this->phpexcel = $phpexcel;
        $this->root_dir = $root_dir;
    }

    /**
     * @param array $data
     * @param string $file_name
     * @return StreamedResponse 
     */
    public function create_excel_file($data) {

        $alphabet = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $phpExcelObject = $this->createPhpExcelObject();
        foreach ($data as $key => $values) {
            $i = 0;
            foreach ($values as  $val) {
                $phpExcelObject->setActiveSheetIndex(0)
                        ->setCellValue($alphabet[$i++] . ($key + 1), (is_array($val)) ? implode(',', $val) : $val);
            }
        }
        $phpExcelObject->getActiveSheet()->setTitle('Simple');
        return  $this->phpexcel->createWriter($phpExcelObject, 'Excel5');
        
    }
    
    /**
     * @return string file_path 
     */
    public function save_excel_file($data,$file_name,$dir=null) {
        $writer = $this->create_excel_file($data);
        if(null === $dir){
            $dir = $this->root_dir . '/../web/uploads/';
             if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                    chmod($dir, 0777);
                }
        }
        $filename = $dir .$file_name.'.xls';
        // create filename
        $writer->save($filename);
        return $filename;
    }
    /**
     * @return PHPExcelObject 
     */
    public function reponse_excel_file($data,$file_name) {
        $writer = $this->create_excel_file($data);
        $response = $this->phpexcel->createStreamedResponse($writer);
        
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
    /**
     * @return PHPExcelObject 
     */
    private function createPhpExcelObject() {
        $phpExcelObject = $this->phpexcel->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("liuggio")
                ->setLastModifiedBy("Giulio De Donato")
                ->setTitle("Office 2005 XLSX Test Document")
                ->setSubject("Office 2005 XLSX Test Document")
                ->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
                ->setKeywords("office 2005 openxml php")
                ->setCategory("Test result file");
        return $phpExcelObject;
    }

    /**
     * @param file $inputFileName
     * @return array 
     */
    public function readExcel($inputFileName) {
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
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
            foreach ($rowData as $val) {
                array_push($data, $val);
            }
        }
        return $data;
    }

}
