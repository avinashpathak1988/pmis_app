<?php
App::uses('AppModel','Model');

class SocialTheme extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Social Theme is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Social Theme already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
