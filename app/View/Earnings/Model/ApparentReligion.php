<?php
App::uses('AppModel','Model');

class ApparentReligion extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Apparent Religion is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Apparent Religion already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
