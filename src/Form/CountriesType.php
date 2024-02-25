<?php

namespace App\Form;

use App\Entity\Countries;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CountriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country', ChoiceType::class, [
                'choices' => $this->countryChoices($options['countries_data']),
                'label' => 'País',
                'placeholder' => 'Selecciona un país',
                'attr' => ['class' => 'country-select']
            ])
            ->add('currency')
            ->add('flag', HiddenType::class, [
                'attr' => ['class' => 'flag-input']
            ])
            ->add('capital')
            ->add('languages')
            ->add('timezones')
            ->add('notes');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Countries::class,
            'countries_data' => [],
        ]);
    }

    public function countryChoices(array $countriesData): array
    {
        usort($countriesData, function ($a, $b) {
            return strcmp($a['name']['common'], $b['name']['common']);
        });
        
        $choices = [];
        foreach ($countriesData as $country) {
            $choices[$country['name']['common']] = $country['name']['common'];
        }
        return $choices;
    }
}
