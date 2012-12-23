<?php
namespace synry63\BlogBundle\Controller;

interface PHPInterface {
     // get path first image
    public function getImageRandPath($n);
    //get general path ico
    public function getGeneralPathIco();
    // get path root image
    public function getGeneralPath();
    // get name rand image name : name exemple = 56,57,101
    public function getRandImageName();
}

?>
