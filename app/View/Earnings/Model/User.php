<?php
App::uses('AppModel','Model');
class User extends AppModel{
    public $belongsTo=array('Department','Designation', 'Usertype', 'Prison');
    public $validate=array(
        'first_name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'First name is required !'
            ),
        ),
        'last_name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Last name is required !'
            ),
        ),
        'usertype_id'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'User type is required !'
            ),
        ),
        'designation_id'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Designation is required !'
            ),
        ),
        'prison_id'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Prison is required !'
            ),
        ),                                  
        'mail_id'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Mail ID is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Mail ID already exists !'
            )
        ),
        'username'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'User name is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'User name already exists !'
            )
        ),
        'password'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Password is required !'
            )
        ),
        'force_number'=>array(
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Force number already exists !'
            )
        )
        
    );
    public function beforeSave($options = Array()) {
        if(isset($this->data['User']['password']) && $this->data['User']['password'] != ''){
            $this->data['User']['password'] = md5($this->data['User']['password']);
            $this->data['User']['reset_key'] = Security::hash(mt_rand(),'md5',true);
            return true;
        }else{
            return true;

        }   
      
            
    }     
}
