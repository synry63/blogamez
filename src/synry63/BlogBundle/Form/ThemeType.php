<?php

namespace synry63\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ThemeType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nom')
        ;
    }

    public function getName()
    {
        return 'synry63_blogbundle_themetype';
    }
}
