<?php
namespace Application\Form;
use Zend\Form\Form,
    Zend\Form\Element;

class AbstractForm extends Form
{
    const MAX_NUMERIC10 = 99999999;
    const MAX_WORDS     = 250;
    const MAX_FEEDBACK     = 5250;
}