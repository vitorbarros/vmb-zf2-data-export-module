<?php
namespace VMBDataExport\Form;

use Zend\Form\Form;

;

class Export extends Form
{

    public function __construct($name = null)
    {

        parent::__construct('dataExport');

        $this->setAttribute('method', 'post');

        //field de email
        $this->add(array(
            'name' => 'entity',
            'attributes' => array(
                'id' => 'entity',
            ),
        ));

        $this->add(array(
            'name' => 'criteria',
            'attributes' => array(
                'id' => 'criteria',
            ),
        ));

        $this->add(array(
            'name' => 'type',
            'attributes' => array(
                'id' => 'type',
            ),
        ));

        $this->add(array(
            'name' => 'redirect_to',
            'attributes' => array(
                'id' => 'redirect_to',
            ),
        ));

        $this->add(array(
            'name' => 'headers',
            'attributes' => array(
                'id' => 'headers',
            ),
        ));

        $this->add(array(
            'name' => 'custom',
            'attributes' => array(
                'id' => 'custom'
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'value' => 'Exportar',
                'class' => 'btn btn-success custom-btn'
            )
        ));

    }

}