<?php
namespace Application\Form;

use Zend\Form\Form,
    Zend\Form\Element,
    Zend\InputFilter\Factory as InputFactory,
    Zend\InputFilter\InputFilter;

class Login extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('login');
        $this->setAttribute('method', 'post');
        $email = new Element\Text('eMail');
        $this->add($email);
        $password = new Element\Password('password');
        $this->add($password);
        $submit = new Element\Submit('submit');
        $this->add($submit);
        // set InputFilter
        $inputFilter = new InputFilter();
        $factory     = new InputFactory();
        $inputFilter->add($factory->createInput(array(
            'name'       => 'eMail',
            'required'   => true,
            'filters'    => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'min' => 5
                    )
                )
            )
        )));
        $inputFilter->add($factory->createInput(array(
            'name'       => 'password',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'min' => 3
                    )
                )
            )
        )));
        $this->setInputFilter($inputFilter);
    }
}