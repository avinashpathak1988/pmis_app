<?php
App::uses('AppModel','Model');

class PrisonerIdDetail extends AppModel{
	public $belongsTo = array(
        'Iddetail' => array(
            'className' => 'Iddetail',
            'foreignKey' => 'id_name',
            'conditions' => '',
            'fields' => array("Iddetail.name"),
            'order' => ''
        ),  
    );
    public $validate=array(
        'id_name'=>array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Id Name is required !',
            ),
                       
        ),  
        'id_number'=>array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Id Number is required !',
            ),     
        ),  
    );
}
