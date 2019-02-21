<?php
App::uses('AppModel','Model');

class ModuleStage extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Non Formal Program Module name is required !'
            ),
            
        ),
    );
}
 ?>