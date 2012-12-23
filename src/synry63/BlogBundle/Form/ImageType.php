<?php

namespace synry63\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name','file')
            ->add('path','file')    
        ;
    }

    public function getName()
    {
        return 'synry63_blogbundle_imagetype';
    }
}
