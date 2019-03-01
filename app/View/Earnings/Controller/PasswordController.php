 <?php
App::uses('AppController','Controller');
class PasswordController extends AppController{

    public $layout='table';
    public $uses=array('User','Prisoner');

	public function change(){
		

		$this->set(array(
            
            'here' => 'here'
            
        ));

    }
    public function submitChangePassword(){

		 $old_password = $this->params['data']['ChangePassword']['old_pass'];
		 $new_password = $this->params['data']['ChangePassword']['new_pass'];
		 $confirm_password = $this->params['data']['ChangePassword']['confirm_pass'];
    	$password = $this->Session->read('Auth.User.password');

    	$saltingOriginalPwd = md5($old_password);
    	$result = 'false';

    	if($saltingOriginalPwd == $password){
    		if($new_password == $confirm_password){
    			$saltingNewPwd = md5($new_password);
    			//$user = $this->User->findByUsername( $this->Session->read('Auth.User.username'));
    			//$user->password = $saltingNewPwd;

                    $fields = array(
                    'User.password'    => "'".$saltingNewPwd."'",
                    );
                    $conds = array(
                        'User.id'    => $this->Session->read('Auth.User.id'),
                    );
                if($this->User->updateAll($fields, $conds)){
                    $this->Session->write('Auth.User.password',$saltingNewPwd);
                    $result = 'true';
                 }
    		}
    		
    	}
		echo $result; 
		exit;

    }
}