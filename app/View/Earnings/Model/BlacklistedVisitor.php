<?php
App::uses('AppModel','Model');

class BlacklistedVisitor extends AppModel{
    public $belongsTo = array(
        'Iddetail' => array(
            'className'     => 'Iddetail',
            'foreignKey'    => 'visitor_id_type',
        ),
        'Prison' => array(
            'className'     => 'Prison',
            'foreignKey'    => 'prison_id',
        ),

        
    );
}
 ?>
