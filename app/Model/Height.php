<?php
App::uses('AppModel','Model');

class Height extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Height is required !'
            ),
            // 'isUnique'=>array(
            //     'rule'=>'isUnique',
            //     'message'=>'Height already exists !',
            //     'on'=>'create',
            // ),
        ),
    );
}
 ?>
