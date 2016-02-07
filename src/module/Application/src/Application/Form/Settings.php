<?php
namespace Application\Form;

use Zend\Form\Form,
    Zend\Form\Element,
    Zend\InputFilter\Factory as InputFactory,
    Zend\InputFilter\InputFilter;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator,
    Doctrine\ORM\EntityManager;

class Settings extends AbstractForm
{
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct('settings');
        $hydrator = new DoctrineHydrator($entityManager, '\Application\Model\Entity\Customer');
        $this->setHydrator($hydrator);
        $this->setAttribute('method', 'post');
        $id = new Element\Hidden('id');
        $this->add($id);
        $name = new Element\Text('name');
        $this->add($name);
        $email = new Element\Text('eMail');
        $this->add($email);
        $isSubscribed = new Element\Checkbox('isSubscribed');
        $this->add($isSubscribed);
        $password = new Element\Text('password-new');
        $this->add($password);
        $password = new Element\Text('password-rep');
        $this->add($password);
        $password = new Element\Text('password-old');
        $this->add($password);

        $phoneNumber = new Element\Text('phoneNumber');
        $this->add($phoneNumber);
        $gender = new Element\Text('gender');
        $this->add($gender);
        $birthday = new Element\Text('birthday');
        $this->add($birthday);
        $bday = new Element\Text('day');
        $this->add($bday);
        $bmonth = new Element\Text('month');
        $this->add($bmonth);
        $byear = new Element\Text('year');
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
                        'min'      => 2,
                        'max'      => self::MAX_WORDS,
                    ),
                ),
            )
        );
        $inputFilter->add($factory->createInput($nameFilter));
        $emailFilter = array(
            'name'       => 'eMail',
            'required'   => true,
            'filters'    => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
        );
        $inputFilter->add($factory->createInput($emailFilter));
/*        $passwordFilter = array(
            'name'       => 'password',
            'required'   => false,
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
                        'max'      => self::MAX_WORDS,
                    ),
                ),
            )
        );
        $inputFilter->add($factory->createInput($passwordFilter));*/
        $this->setInputFilter($inputFilter);
    }
}