<?php
App::uses('AppModel','Model');

class Ward extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Ward is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Ward already exists !',
                'on'=>'create',
            ),
        ),
    );
    public $hasMany = array(
        'WardCell' => array(
            'className'     => 'WardCell',
            'foreignKey'    => 'ward_id',
            'conditions' => array('is_trash' => 0)
        )
    );
}
 ?>
