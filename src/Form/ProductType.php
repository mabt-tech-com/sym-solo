<?php
namespace App\Form;

use App\Entity\Product;
use App\Entity\Store;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du produit',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Prix',
            ])
            ->add('image', FileType::class, [
                'label' => 'Image (fichier JPG ou PNG)',
                'mapped' => false, // Ne pas mapper directement à l'entité
                'required' => false,
            ])
            ->add('store', EntityType::class, [
                'class' => Store::class,
                'choice_label' => 'name', // Afficher le nom du magasin
                'label' => 'Magasin',
                'placeholder' => 'Sélectionnez un magasin', // Optionnel
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}