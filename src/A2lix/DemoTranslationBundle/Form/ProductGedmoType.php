<?php

namespace A2lix\DemoTranslationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\HttpFoundation\Request;

class ProductGedmoType extends AbstractType
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', 'a2lix_translations_gedmo', array(
                'fields' => array(
                    'title' => array(
                        'locale_options' => array(
                            'de' => array(
                                'required' => true,
                                'constraints' => new NotBlank(),
                            ),
                            'en' => array(
                                'required' => true,
                                'constraints' => new NotBlank(),
                            ),
                        ),
                    ),
                    'description' => array(
                        'required' => false,
                    ),
                ),
            ))
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            if (null === $data) {
                return;
            }

            $form
                ->add('save', 'submit', array(
                    'label' => $data->getId() ? 'Edit' : 'Create',
                    'attr' => array(
                        'class' => 'btn btn-primary'
                    )
                ))
             ;
        });

        $currentLocale = $this->request->getLocale();
        $builder->addEventListener(FormEvents::SUBMIT, function(FormEvent $event) use ($currentLocale) {
            $form = $event->getForm();
            $data = $event->getData();

            if (null === $data) {
                return;
            }

            // checking the locale is not necessary when the workaround is used
//          if ('de' === $currentLocale) {
                $data->setTitle($form['translations']['de']['title']->getData());
                $data->setDescription($form['translations']['de']['description']->getData());
//          }

            $event->setData($data);
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'A2lix\DemoTranslationBundle\Entity\ProductGedmo',
        ));
    }

    public function getName()
    {
        return 'productGedmo';
    }
}
