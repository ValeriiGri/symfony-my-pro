<?php

namespace App\Form;

use App\Entity\Post;
//use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType

    /*В методе configureOptions() Symfony указывает, какая сущность будет источником данных для нашей формы.
     На данный момент нас интересует метод buildForm, который, собственно, и строит нашу форму из полей нашей сущности.
     Мы смело можем удалить поля slug и created_at, поскольку они будут генерироваться автоматически.
     Кроме того, нам доступны некоторые настройки для нашей формы: так, например, метод в add() мы можем указать,
     какой тип данных будет принимать то или иное поле - TextType или TextareaType, а также установить label,
     если он нужен*/

{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => ''
            ])
            ->add('body', TextareaType::class, [
                'label' => ' '
            ])
            //->add('slug')
            //->add('created_at')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
