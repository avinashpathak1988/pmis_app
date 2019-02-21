<?php
App::uses('AppModel','Model');

class Disability extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Disability Name is required !'
            ),
        )
    );
}
