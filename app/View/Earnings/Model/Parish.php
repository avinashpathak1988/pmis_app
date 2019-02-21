<?php
App::uses('AppModel','Model');

class Parish extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Parish Name is required !'
            ),
        ),
    );
}
