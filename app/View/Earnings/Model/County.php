<?php
App::uses('AppModel','Model');

class County extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'County Name is required !'
            ),
        ),
    );
}
