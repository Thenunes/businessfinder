<?php

namespace App\Form;

use App\Entity\Business;
use App\Entity\State;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BusinessType extends AbstractType
{	
	public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array('attr' => array('class' => 'form-control')))
			->add('phone', TextType::class, array('attr' => array('class' => 'form-control')))
			->add('address', TextType::class, array('attr' => array('class' => 'form-control')))
			->add('zipcode', TextType::class, array('attr' => array('class' => 'form-control')))
			->add('description', TextareaType::class, array('attr' => array('class' => 'form-control')))
            ->add('categories', EntityType::class, [
                'class'     => 'App:Category',
                'choice_label' => 'name',
                'query_builder' => function ( $repo) {
                    return $repo->createQueryBuilder('C');
                },
                'label'     => 'Categories:',
                'expanded'  => false,
                'multiple'  => true,
                'attr' => array('class' => 'form-control'),
            ]);

			$builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        	$builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
        ;
    }

    protected function addElements(FormInterface $form, State $state = null) {
        // 4. Add the province element
        $form->add('id_state', EntityType::class, array(
        	'label' => 'State',
            'required' => true,
            'mapped' => false,
            'data' => $state,
            'placeholder' => 'Select a State...',
            'class' => 'App:State',
            'attr' => array('class' => 'form-control')
        ));
        
        $cities = array();
        if ($state) {
            $repoCity = $this->em->getRepository('App:City');
            
            $cities = $repoCity->createQueryBuilder("C")
                ->where("C.id_state = :stateid")
                ->setParameter("stateid", $state->getId())
                ->getQuery()
                ->getResult();
        }
        
        $form->add('id_city', EntityType::class, array(
        	'label' => 'City',
            'required' => true,
            'placeholder' => 'Select a State first ...',
            'class' => 'App:City',
            'choices' => $cities,
            'attr' => array('class' => 'form-control')
        ));
    }
    
    function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();
        
        $state = $this->em->getRepository('App:State')->find($data['id_state']);
        
        $this->addElements($form, $state);
    }

    function onPreSetData(FormEvent $event) {
        $business = $event->getData();
        $form = $event->getForm();

        $city = $business->getIdCity() ? $business->getIdCity() : null;
        $state = null;

        $this->addElements($form, $state);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Business::class,
        ));
    }
}