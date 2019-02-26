<?php
App::uses('AppController', 'Controller');
class UserAccessControlsController extends AppController {
	public $layout='table';
	public $uses=array('UserAccessControl','Prison','Usertype', 'Menu');
	public function index() {
		
		$superadmin_usertype    = Configure::read('SUPERADMIN_USERTYPE');
		/*
		 *Code to save the user access control for modules
		*/					
		if(isset($this->data['UserAccessControl']) && is_array($this->data['UserAccessControl']) && count($this->data['UserAccessControl']) >0){
			
            $prison_id = $this->request->data['UserAccessControl']['prisonId'];
            $user_type = $this->request->data['UserAccessControl']['userType'];
            $module_id = $this->request->data['UserAccessControl']['module_id'];
           // $id = $this->request->data['UserAccessControl']['id'];
            unset($this->request->data['UserAccessControl']['prisonId']);
            unset($this->request->data['UserAccessControl']['userType']);
            unset($this->request->data['UserAccessControl']['module_id']);
            unset($this->request->data['UserAccessControl']['menu_id']);
            //unset($this->request->data['UserAccessControl']['id']);
            //unset($this->request->data['UserAccessControl']);	
            //echo '<pre>'; print_r($this->request->data); exit;
            //validate 
            if(count($this->data['UserAccessControl']) > 0)
            {
                $datas = $this->data['UserAccessControl'];
                $i = 0;
                foreach($datas as $data)
                {
                    if(($data['is_add'] == 0) && ($data['is_edit'] == 0) && ($data['is_delete'] == 0) && ($data['is_view'] == 0) && ($data['is_review'] == 0) && ($data['is_approve'] == 0)) 
                    {
                        unset($this->request->data['UserAccessControl'][$i]);
                    }
                    $i++;
                }
            }
             // echo '<pre>'; print_r($this->data); exit;
            //update the previous user access controls
            // $fields = array(
            // 	//'UserAccessControl.id'    => $id,
            //     'UserAccessControl.is_trash'    => 1,
            //     'UserAccessControl.prison_id'    => $prison_id,
            //     'UserAccessControl.user_type'    => $user_type,
            //     'UserAccessControl.module_id'    => $module_id,
            // );
            // $conds = array(
            //     'UserAccessControl.prison_id'    => $prison_id,
            //     'UserAccessControl.user_type'    => $user_type,
            //     'UserAccessControl.module_id'    => $module_id,
            // );

            //debug($this->data); exit;
            //$this->UserAccessControl->updateAll($fields, $conds);
            // debug($this->data['UserAccessControl']); exit;
			if($this->UserAccessControl->saveAll($this->data['UserAccessControl'])){
                $this->Session->write('message_type','success');
                $this->Session->write('message','User Access Control saved successfully !');
                //$this->redirect('/UserAccessControls');
			}else{
                //debug($this->UserAccessControl->validateErrors);
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
			}
		}	
		
		//get prison list
		$prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_trash'   => 0,
                'Prison.is_enable'  => 1,
            ),
            'order'         => array(
                'Prison.name'
            ),
        ));
        //get user type list
		$usertypeList = $this->Usertype->find('list', array(
            'recursive'     => -1,
            'conditions'    => array(
                'Usertype.is_enable'    => 1,
                'Usertype.is_trash'     => 0,
                "Usertype.id NOT IN ($superadmin_usertype)"
            ),
            'fields'        => array(
                'Usertype.id',
                'Usertype.name',
            ),
            'order'         => array(
                'Usertype.name' => 'ASC',
            ),
        ));
        $this->loadModel('Module');
        $moduleList = $this->Module->find('list', array(
          
            'conditions'    => array(
                'Module.status'    => 1,
            ),
            'fields'        => array(
                'Module.id',
                'Module.name',
            ),
        )); 
    
    
        //get all modules 
   //      $modules = array(
   //      	'prisoner_admission'	=>'prisoner admission',
			// 'sentence'				=>'sentence',
			// 'medical'				=>'medical',
			// 'properties'			=>'properties',
			// 'court_attendance'		=>'court attendance',
			// 'stages'				=>'stages',
			// 'discipline'			=>'discipline',
			// 'discharge'				=>'discharge',
			// 'discharge'				=>'discharge',
			// 'transfer'				=>'transfer',
   //          'earnings'              =>'earnings',
   //          'station'              =>'earnings',
   //      );
        
		$this->set(array(
			'prisonList'	=> $prisonList,
			'usertypeList'	=> $usertypeList,
			'moduleList'		=> $moduleList
		));
		
		
    }
    //save access control 
    function saveAccess()
    {
        if(isset($this->data['UserAccessControl']) && is_array($this->data['UserAccessControl']) && count($this->data['UserAccessControl']) >0){
            
            $prison_id = $this->request->data['UserAccessControl']['prisonId'];
            $user_type = $this->request->data['UserAccessControl']['userType'];
            $module_id = $this->request->data['UserAccessControl']['module_id'];
            //$id = $this->request->data['UserAccessControl']['id'];
            unset($this->request->data['UserAccessControl']['prisonId']);
            unset($this->request->data['UserAccessControl']['userType']);
            unset($this->request->data['UserAccessControl']['module_id']);
            //unset($this->request->data['UserAccessControl']); 
            //echo '<pre>'; print_r($this->request->data); exit;
            //validate 
            if(count($this->data['UserAccessControl']) > 0)
            {
                $datas = $this->data['UserAccessControl'];
                $i = 0;
                foreach($datas as $data)
                {
                    if(($data['is_add'] == 0) && ($data['is_edit'] == 0) && ($data['is_delete'] == 0) && ($data['is_view'] == 0) && ($data['is_review'] == 0) && ($data['is_approve'] == 0)) 
                    {
                        unset($this->request->data['UserAccessControl'][$i]);
                    }
                    $i++;
                }
            }
            echo '<pre>'; print_r($this->data); exit;
            //update the previous user access controls
            $fields = array(
                //'UserAccessControl.id'    => $id,
                'UserAccessControl.is_trash'    => 1,
                'UserAccessControl.prison_id'    => $prison_id,
                'UserAccessControl.user_type'    => $user_type,
                'UserAccessControl.module_id'    => $module_id,
            );
            $conds = array(
                'UserAccessControl.prison_id'    => $prison_id,
                'UserAccessControl.user_type'    => $user_type,
                'UserAccessControl.module_id'    => $module_id,
            );
            
            $this->UserAccessControl->updateAll($fields, $conds);
           // debug($this->data['UserAccessControl']); exit;
            if($this->UserAccessControl->saveAll($this->data['UserAccessControl'])){
                $this->Session->write('message_type','success');
                $this->Session->write('message','Saved Successfully !');
                //$this->redirect('/UserAccessControls');
            }else{
                //debug($this->UserAccessControl->validateErrors);
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
            }
        }   
    }
    public function indexAjax(){
		$this->layout 			= 'ajax';
        //echo '<pre>';print_r($this->data); exit; 
        $prison_id = 0;  
        $user_type = 0;
        $module_id = 0;
        $menu_id = 0;
        if(isset($this->data['prison_id']))
        {
            $prison_id = $this->data['prison_id'];
        }
        if(isset($this->data['user_type']))
        {
            $user_type = $this->data['user_type'];
        }
        if(isset($this->data['module_id']))
        {
            $module_id = $this->data['module_id'];
        }
        $data = $this->UserAccessControl->find('all', array(
            'recursive'     => -1,
            'conditions'    => array(
                'UserAccessControl.prison_id'    => $prison_id,
                'UserAccessControl.user_type'    => $user_type,
                'UserAccessControl.module_id'    => $module_id
            )
        ));
        $menu_data = $this->Menu->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Menu.id',
                'Menu.name'
            ),
            'conditions'    => array(
                'Menu.module_id'    => $module_id,
                'Menu.is_trash'                       => 0
            )
        ));
    	//get all modules 
        // $modules = array(
        //     'prisoner_admission'    =>'prisoner admission',
        //     'sentence'              =>'sentence',
        //     'medical'               =>'medical',
        //     'properties'            =>'properties',
        //     'court_attendance'      =>'court attendance',
        //     'stages'                =>'stages',
        //     'discipline'            =>'discipline',
        //     'discharge'             => 'discharge'
        // );
        $this->loadModel('Module');
        $modules = $this->Module->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Module.code',
                'Module.name',
            ),
            'conditions'    => array(
                'Module.status'  => 1
            ),
            'order'         => array(
                'Module.order' => 'ASC'
            ),
        ));
        $this->set(array(
            'prison_id'     => $prison_id,
            'user_type'     => $user_type,
            'module_id'     => $module_id,
            'menu_id'       => $menu_id,
            'modules'       => $modules,
            'data'          => $data,
            'module_ids'    => $menu_data
        ));
    }
}