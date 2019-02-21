<?php
App::uses('AppModel','Model');

class SubCounty extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Sub County Name is required !'
            ),
        ),
    );
}
