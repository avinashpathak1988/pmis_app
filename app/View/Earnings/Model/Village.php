<?php
App::uses('AppModel','Model');

class Village extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Village Name is required !'
            ),
        ),
    );
}
