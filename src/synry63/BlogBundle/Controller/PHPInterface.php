<?php
namespace synry63\BlogBundle\Controller;

interface PHPInterface {
     // get path first image
    public function getImageRandPath($n);
    // get path root image
    public function getGeneralPathImage();
    // get name rand image name : name exemple = 56,57,101
    public function getRandImageName();
}

?>
