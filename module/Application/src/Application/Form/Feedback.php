<?php
namespace Application\Form;
use Zend\Form\Form,
    Zend\Form\Element,
    Zend\InputFilter\Factory as InputFactory,
    Zend\InputFilter\InputFilter;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator,
    Doctrine\ORM\EntityManager;
class Feedback extends AbstractForm
{
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct('feedback');
        $hydrator = new DoctrineHydrator($entityManager, '\Application\Model\Entity\Feedback');
        $this->setHydrator($hydrator);
        $this->setAttribute('method', 'post');
        $id = new Element\Hidden('id');
        $this->add($id);
        $name = new Element\Text('name');
        $this->add($name);
        $email = new Element\Text('email');
        $this->add($email);
        $msg = new Element\Text('msg');
        $this->add($msg);
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
                        'min'      => 2,
                        'max'      => self::MAX_WORDS,
                    ),
                ),
            )
        );
        $inputFilter->add($factory->createInput($nameFilter));
        $emailFilter = array(
            'name'       => 'email',
            'required'   => true,
            'filters'    => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
        );
        $inputFilter->add($factory->createInput($emailFilter));
        $msgFilter = array(
            'name'       => 'msg',
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
                        'min'      => 3,
                        'max'      => self::MAX_FEEDBACK,
                    ),
                ),
            )
        );
        $inputFilter->add($factory->createInput($msgFilter));
        $this->setInputFilter($inputFilter);
    }
}