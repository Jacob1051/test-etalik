<?php

namespace App\Form;

use App\Entity\VehicleData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditVehicleDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('compteAffaire', TextType::class, [
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('compteEvenementVeh', TextType::class, [
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('compteDernierEvenementVeh', TextType::class, [
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('numeroDeFiche', NumberType::class, [
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('libelleCivilite', ChoiceType::class, array(
                'required' => false,
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-select'
                ],
                'choices'     => VehicleData::LIBELLECIVILITE_CHOICE,
            ))
            ->add('proprietaireActuelDuVehicule', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('nom', TextType::class, [
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('prenom', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('numeroEtNomDeLaVoie', TextType::class, [
                'required' => true,
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('complementAdresse1', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('codePostal', TextType::class, [
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('ville', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('telephoneDomicile', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('telephonePortable', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('telephoneJob', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('email', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('dateDeMiseEnCirculation', DateType::class, [
                'required' => false,
                'widget'   => 'single_text',
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('dateAchatDateDeLivraison', DateType::class, [
                'required' => false,
                'widget'   => 'single_text',
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('dateDernierEvenementVeh', DateType::class, [
                'widget'   => 'single_text',
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('libelleMarqueMrq', TextType::class, [
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('libelleModeleMod', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('version', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('vin', TextType::class, [
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('immatriculation', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('typeDeProspect', ChoiceType::class, array(
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-select'
                ],
                'choices'     => VehicleData::TYPEPROSPECT_CHOICE,
            ))
            ->add('kilometrage', NumberType::class, [
                'required' => false,
                'empty_data' => 0,
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('libelleEnergieEnerg', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('vendeurVN', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('vendeurVO', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('commentaireDeFacturationVeh', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('typeVNVO', ChoiceType::class, array(
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-select'
                ],
                'choices'     => VehicleData::TYPEVNVO_CHOICE,
            ))
            ->add('numeroDeDossierVNVO', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('intermediaireDeVenteVN', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('dateEvenementVeh', DateType::class, [
                'widget'   => 'single_text',
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('origineEvenementVeh', TextType::class, [
                'row_attr' => [
                    'class' => 'p-3'
                ],
                'attr'        => [
                    'class' => 'form-control'
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => VehicleData::class,
        ]);
    }
}
