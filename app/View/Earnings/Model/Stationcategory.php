<?php
App::uses('AppModel','Model');

class Stationcategory extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Station Name is required !'
            ),
        ),
        
    );
}
?>