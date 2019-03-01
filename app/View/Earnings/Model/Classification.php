<?php
App::uses('AppModel','Model');

class Classification extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Classification Name is required !'
            ),
        )
    );
}
