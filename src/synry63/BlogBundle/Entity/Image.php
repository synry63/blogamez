<?php

namespace synry63\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * synry63\BlogBundle\Entity\Image
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="synry63\BlogBundle\Entity\ImageRepository")
 */
class Image
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string $path
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    public function preUpload($file)
    {
        if (null !== $file) {
            $filename = sha1(uniqid(mt_rand(), true));
            $this->name = $filename;
            $this->path = $filename.'.'.$file->guessExtension();
        }
    }
    public function removeFile(){
        
        return unlink($this->getPath());
    }
    
    public function upload($file){
           if (null !== $file) {
                $file->move($this->getUploadDir(),$this->path);
                //$t = $this->getPath();
                //$test =  getimagesize($t);
           }
    }
    public function validationTypeImagePerso(){
        $infoFile = getimagesize($this->getPath());
        if(!$infoFile) return -1;
        else if($infoFile[0]<$infoFile[1]) return -2;
        else return 1;
    }
    
    public function getPath()
    {
          return  $this->getUploadDir().'/'.$this->path;
    }
    
    private function getUploadDir()
    {
        return 'images/images_article';
    }
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set path
     *
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }
}