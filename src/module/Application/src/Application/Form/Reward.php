<?php
namespace Application\Form;

use Zend\Form\Form,
    Zend\Form\Element,
    Zend\InputFilter\Factory as InputFactory,
    Zend\InputFilter\InputFilter;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator,
    Doctrine\ORM\EntityManager;

class Reward extends AbstractForm
{
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct('settings');
        $hydrator = new DoctrineHydrator($entityManager, '\Application\Model\Entity\Reward');
        $this->setHydrator($hydrator);
        $this->setAttribute('method', 'post');
        $id = new Element\Hidden('id');
        $this->add($id);
        $name = new Element\Text('name');
        $this->add($name);
        $byear = new Element\Text('points');
        $this->add($byear);

        // set InputFilter
        $inputFilter = new InputFilter();
        $factory     = new InputFactory();
        $nameFilter = array(
            'name'       => 'name',
            'required'   => true,
            'filters'    => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min'      => 4,
                        'max'      => self::MAX_WORDS,
                    ),
                ),
            )
        );
        $inputFilter->add($factory->createInput($nameFilter));
        $emailFilter = array(
            'name'       => 'points',
            'required'   => true,
            'filters'    => array(
                array('name' => 'Int'),
            ),
            'validators' => array(
                array(
                    'name' => 'Between',
                    'options' => array(
                        'min' => 1,
                        'max' => 1000,
                        'inclusive' => true
                    ),
                ),
            )
        );
        $inputFilter->add($factory->createInput($emailFilter));
        $this->setInputFilter($inputFilter);
    }
}