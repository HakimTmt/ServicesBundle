<?php

namespace Tmt\ServicesBundle\Services\Uploader;

use DateTime;
use SplFileInfo;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * Uploader Service
 *
 * @author adouiri@techmyteam.com
 * @author aaboulhaj@techmyteam.com
 */
class Uploader {

    private $errors;

    /**
     * @param ContainerInterface $container
     */
    public function __construct() {
        $this->errors = array();
    }

    /**
     * @param int $pg
     * @param int $totalPages
     * @return array 
     */
    public function upload_file($folder, $csvUploaded, $type, $name = null, $destination = null, $max_upload_size = 5000) {
        $sec_path = 'uploads/' . $folder;
        $path = __DIR__ . '/../../../../../app/Resources/' . $sec_path;

        if ($destination == 'web') {
            $path = __DIR__ . '/../../../../../web/' . $sec_path;
        }

        if (null === $csvUploaded) {
            $this->errors[] = 'file is null';
            return array('error' => true, 'errors' => $this->errors);
        } else {
            if (false === $preg = getPreg($type)) {
                $this->errors[] = 'undefined or no type is selected';
                return array('error' => true, 'errors' => $this->errors);
            }
            if (filesize($csvUploaded) < ($max_upload_size * 1024)) {
                if (preg_match($preg, strtolower($csvUploaded->getClientOriginalName()))) {
                    try {
                        $date_md5 = new DateTime('now');
                        $namefile = $name? : md5($csvUploaded->getClientOriginalName() . $date_md5->format('d/m/Y H:i:s'));
                        $info = new SplFileInfo($csvUploaded->getClientOriginalName());
                        $namefile = $namefile . '.' . $info->getExtension();

                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                            chmod($path, 0777);
                        }
                        $csvUploaded->move($path, $namefile);
                    } catch (FileException $ex) {
                        $this->errors[] = $ex->getMessage();
                        return array('error' => true, 'errors' => $this->errors);
                    }
                } else {
                    $this->errors[] = 'File extension is not compatible with type.';
                    return array('error' => true, 'errors' => $this->errors);
                }
            } else {

                $this->errors[] = 'La taille d\'image maximum est = ' . $max_upload_size;
                return array('error' => true, 'errors' => $this->errors);
            }
        }
        return array('error' => false,'filename' => $namefile, 'path' => $path . '/' . $namefile,'size' => filesize($path . '/' . $namefile), 'simple_path' => $sec_path . '/' . $namefile,'destination' => $destination,'original_name' => $csvUploaded->getClientOriginalName(),
        );
    }

    /**
     * @param string $type
     * @return string|bool 
     */
    private function getPreg($type) {
        switch ($type) {
            case 'zip':
                return '/\.(zip)$/';
            case 'pdf':
                return'/\.(pdf)$/';
            case 'word':
                return '/\.(doc|docx)$/';
            case 'excel':
                return'/\.(csv|xlsx|xls|xlsb|xlsm)$/';
            case 'ppt':
                return '/\.(ppt|pptx)$/';
            case 'video':
                return '/\.(mp4|mpeg4|3gp|)$/';
            case 'picture':
                return '/\.(png|jpg|jpeg|gif)$/';
            case 'audio':
                return'/\.(mp3)$/';
            case 'text':
                return '/\.(txt)$/';
        }
        return false;
    }

}
