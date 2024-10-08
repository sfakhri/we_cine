<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SearchType extends AbstractType
{
    private $router;
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->router = $urlGenerator;

    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('search_suggestions', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control me-2',
                    'placeholder' => 'Recherche ...',
                    'data-filter' => $this->router->generate('app_search_autocomplete').'?term=#QUERY#'
//                    'data-filter' => 'https://127.0.0.1:8002/autocomplete?term=#QUERY#'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }

    public function getBlockPrefix()
    {
        return ''; // return an empty string here
    }

}
