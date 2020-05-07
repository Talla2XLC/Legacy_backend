<?php

namespace App;

use Core\Application;
use Core\Model;
use Core\S3Libs;

use function PHPSTORM_META\type;

class CreateImg
{
    public function setImg()
    {
        //print_r($_FILES);
        if(!empty($_FILES)){
            $image = $_FILES['image']['name'];
            $type = $_FILES['image']['type'];
            $tmp = $_FILES['image']['tmp_name'];
            $imageDir = 'temp/'.$image;
            
            switch($type){
                case 'image/png':
                    $type = true;
                break;
                case 'image/jpeg':
                    $type = true;
                break;
                default:
                $type = false;
            }
                
            if($type){
                move_uploaded_file($tmp,$imageDir);
                if(exif_imagetype($imageDir) == IMAGETYPE_JPEG){
                $s3Libs = new S3Libs();
                
                $s3Libs->uploadCloud($image,$imageDir,'Ivan');
                //$model = new Model();

                unlink($imageDir);
                }else{
                    unlink($imageDir);
                    
                }
            }else{
                unlink($imageDir);
            }
            
        }
        
        
    }
}