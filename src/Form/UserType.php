<?php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
            ])
            ->add('roleChoice', ChoiceType::class, [
                'label'    => 'Rôle',
                'choices'  => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Enseignant'      => 'ROLE_ENSEIGNANT',
                ],
                'mapped'   => false,
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('plainPassword', PasswordType::class, [
                'label'    => 'Mot de passe',
                'mapped'   => false,
                'required' => $options['is_new'],
                'attr'     => ['placeholder' => $options['is_new'] ? '' : 'Laisser vide pour ne pas changer'],
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $user = $event->getData();
            $form = $event->getForm();
            if ($user && $user->getId()) {
                $form->get('roleChoice')->setData($user->getRoles()[0] ?? 'ROLE_ENSEIGNANT');
            }
        });

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $user = $event->getData();
            $form = $event->getForm();
            $user->setRoles([$form->get('roleChoice')->getData()]);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_new'     => true,
        ]);
    }
}