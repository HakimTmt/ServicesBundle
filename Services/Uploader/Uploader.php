<?php

namespace Tmt\ServicesBundle\Services\Uploader;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use SplFileInfo;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Uploader Service
 *
 * @author TMT
 */
class Uploader {

    private $container;
    private $errors;

    public function __construct(Container $container) {
        $this->container = $container;
        $this->errors = array();
    }

    public function upload_file($folder, $csvUploaded, $type, $name = null, $destination = null, $max_upload_size = 5000) {
        $sec_path = 'uploads/' . $folder;
        $path = __DIR__ . '/../../../../../app/Resources/' . $sec_path;

        if ($destination == 'web') {
            $path = __DIR__ . '/../../../../../web/' . $sec_path;
        }

        if ($csvUploaded === null) {
            $this->errors[] = 'file is null';
            return array('error' => true, 'errors' => $this->errors);
        } else {
            switch ($type) {
                case 'zip':
                    $preg = '/\.(zip)$/';
                    break;
                case 'pdf':
                    $preg = '/\.(pdf)$/';
                    break;
                case 'word':
                    $preg = '/\.(doc|docx)$/';
                    break;
                case 'excel':
                    $preg = '/\.(csv|xlsx|xls|xlsb|xlsm)$/';
                    break;
                case 'ppt':
                    $preg = '/\.(ppt|pptx)$/';
                    break;
                case 'video':
                    $preg = '/\.(mp4|mpeg4|3gp|)$/';
                    break;
                case 'picture':
                    $preg = '/\.(png|jpg|jpeg|gif)$/';
                    break;
                case 'audio':
                    $preg = '/\.(mp3)$/';
                    break;
                case 'text':
                    $preg = '/\.(txt)$/';
                    break;
                default: $this->errors[] = 'undefined or no type is selected';
                    return array('error' => true, 'errors' => $this->errors);
            }



            if (filesize($csvUploaded) < ($max_upload_size * 1024)) {
                if (preg_match($preg, strtolower($csvUploaded->getClientOriginalName()))) {
                    try {
                        $date_md5 = new \DateTime('now');
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

                $this->errors[] = 'La taille d\'image maximum est = '.$max_upload_size;
                return array('error' => true, 'errors' => $this->errors);
            }
        }



        return array(
            'error' => false,
            'filename' => $namefile,
            'path' => $path . '/' . $namefile,
            'size' => filesize($path . '/' . $namefile),
            'simple_path' => $sec_path . '/' . $namefile,
            'destination' => $destination,
            'original_name' => $csvUploaded->getClientOriginalName(),
        );
    }

}
