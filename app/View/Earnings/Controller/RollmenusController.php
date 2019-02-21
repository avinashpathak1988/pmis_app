<?php 
App::uses('AppController','Controller');

class RollmenusController extends AppController{
	 public $components = array('Paginator', 'Flash','Session');
   public $layout='table';
   
   public function m_menu_list() {
     $this->loadmodel('MMenu');
     $this->layout='table';
     $menuList = $this->MMenu->find('all');
        $this->set('menuList', $menuList);
   }
	public function m_menu() {
    $this->layout='table';
        $this->loadmodel('MMenu');
        if (!empty($this->data['MMenu'])) {
            if ($this->data['MMenu']['id']) {
                $this->MMenu->id = $this->data['MMenu']['id'];
                if ($this->MMenu->save($this->data['MMenu'])) {
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','updated Successfully !');
                    $this->redirect('/Rollmenus/m_menu_list');
                }
            } else {
                if ($this->MMenu->save($this->data['MMenu'])) {
                   $this->Session->write('message_type','success');
                   $this->Session->write('message','Saved Successfully !');
                    $this->redirect('/Rollmenus/m_menu_list');
                }
            }
        }
        if (!empty($this->data['Menuedit'])) {
            $this->data = $this->MMenu->findById($this->data['Menuedit']['id']);
            $this->set('button', 'Update Menu');
        }
        if (!empty($this->data['Menudelete'])) {
            $this->MMenu->delete($this->data['Menudelete']['id']);
            $this->Session->setFlash('Menu deleted successfully','default', array('class' => 'success'));
            $this->redirect('/Rollmenus/m_menu');
        }
         $is_enable=array(
            '0'=>'In Active',
            '1'=>'Active'
        );
        $this->set(compact('is_enable'));
        
    }
    function m_sub_menu_list()
    {
      $this->layout='table';
      $this->loadmodel('MMenu');
      $this->loadmodel('MSubMenu');
      $submenuList = $this->MSubMenu->find('all',array(
                                                 'order' => array('MSubMenu.m_menu_id' => 'asc')
                                                 ));
        $this->set(array(
                        'submenuList' => $submenuList,
        ));
    }
    function m_sub_menu() {
        $this->loadmodel('MMenu');
        $this->loadmodel('MSubMenu');
        if (!empty($this->data['MSubMenu'])) {
            if ($this->data['MSubMenu']['id']) {
                $this->MSubMenu->id = $this->data['MSubMenu']['id'];
                //print_r($this->MasterSubMenu->id );exit();
                  $this->loadmodel('MRoleMenu');
                  $this->MRoleMenu->updateAll(array(
                                                      'MRoleMenu.m_menu_id' =>$this->data['MSubMenu']['m_menu_id'],
                                                      'MRoleMenu.modified' => "'".date("Y-m-d H:i:s")."'",
                                                      ),array(
                                                              'MRoleMenu.m_sub_menu_id' =>$this->data['MSubMenu']['m_sub_menu_id']
                                                             ) 
                                                 );
                if ($this->MSubMenu->save($this->data['MSubMenu'])) {
                    $this->Session->setFlash('Sub Menu updated successfully','default', array('class' => 'success'));
                    $this->redirect('/Rollmenus/m_sub_menu_list');
                }
            } else {
                if ($this->MSubMenu->save($this->data['MSubMenu'])) {
                    $this->Session->setFlash('Sub Menu added successfully','default', array('class' => 'success'));
                    $this->redirect('/Rollmenus/m_sub_menu_list');
                }
            }
        }
        if (!empty($this->data['SubMenuedit'])) {
            $this->data = $this->MSubMenu->findById($this->data['SubMenuedit']['id']);
            $this->set('button', 'Update SubMenu');
        }
        if (!empty($this->data['SubMenudelete'])) {
            $this->MSubMenu->delete($this->data['SubMenudelete']['id']);
            $this->Session->setFlash('Sub Menu deleted successfully','default', array('class' => 'success'));
            $this->redirect('/Rollmenus/m_sub_menu_list');
        }
        $menuList = $this->MMenu->find('list',array(
                                                 'conditions' => array('MMenu.is_enable' => 1,'MMenu.menu_url' => ''),         
                                                 'order'     => array('MMenu.name')
                                                 )); 
        $is_enable=array(
            '0'=>'In Active',
            '1'=>'Active'
        );
        $this->set(compact('is_enable'));
        $submenuList = $this->MSubMenu->find('all',array(
                                                 'order' => array('MSubMenu.m_menu_id' => 'asc')
                                                 ));
        $this->set(array(
                        'submenuList' => $submenuList,
                        'menuList'    => $menuList
                        ));
    }

	function m_role_menu(){
        $this->loadmodel('Designation');
        $this->loadmodel('MMenu');
        $this->loadmodel('MSubMenu');
        $this->loadmodel('MRoleMenu');
        if(isset($this->data['SearchMenu']['designation_id']) && $this->data['SearchMenu']['designation_id'] != ''){
            $designation_id = $this->data['SearchMenu']['designation_id'];
        }else if(isset($this->data['MRoleMenu']['designation_id']) && $this->data['MRoleMenu']['designation_id']){
            $designation_id = $this->data['MRoleMenu']['designation_id'];    
        }else{
            $designation_id = 0;
        }
        $error = 0;
        if(isset($this->data['MRoleMenu']['designation_id']) && $this->data['MRoleMenu']['designation_id']){
            if(empty($this->data['MRoleMenu']['MMenu']) && empty($this->data['MRoleMenu']['MSubMenu']) && empty($this->data['MRoleMenu']['MSubSubMenu'])){
                  $this->Session->setFlash('Please Select atlest one menu or submenu or sub sub menu','default', array('class' => 'success'));  
                  $error++;
            }
        }
        /*
         * Only Save Case
         */
        if(!empty($this->data['MRoleMenu']) && $error == 0){
            /*
             * Delete The existing Data For Existing Designation id in Edit case.
             */                                                  
            if(isset($this->data['MRoleMenu']['designation_id']) && $this->data['MRoleMenu']['designation_id'] && (isset($this->data['MRoleMenu']['MMenu']) && (count($this->data['MRoleMenu']['MMenu']) > 0) || (isset($this->data['MRoleMenu']['MSubMenu'])&&(count($this->data['MRoleMenu']['MSubMenu']) > 0)) || count($this->data['MRoleMenu']['MSubSubMenu']) > 0 )){
               $this->MRoleMenu->deleteAll(array('MRoleMenu.designation_id='.$this->data['MRoleMenu']['designation_id']));
            }
            if(isset($this->data['MRoleMenu']['MMenu'])){
              foreach($this->data['MRoleMenu']['MMenu'] AS $key =>$val){
                $master_menu = array(
                            'designation_id' => $this->data['MRoleMenu']['designation_id'],
                            'm_menu_id' => $val,
                            'm_sub_menu_id' => 0,
                            'm_sub_sub_menu_id' => 0,
                           );
                // $this->MRoleMenu->create();           
                $this->MRoleMenu->save($master_menu);
                }
            }
            if(isset($this->data['MRoleMenu']['MSubMenu'])){
                foreach($this->data['MRoleMenu']['MSubMenu'] AS $k =>$v){
                   
                    $menu =$this->MSubMenu->find('first',array(
                                                                  'conditions' => array(
                                                                                         'MSubMenu.id' => $v
                                                                                       ),
                                                                   'fields'     =>array('MSubMenu.m_menu_id')                    
                                                                     ));
                    $master_submenu=array(
                                            'designation_id' => $this->data['MRoleMenu']['designation_id'],
                                            'm_menu_id' => $menu['MSubMenu']['m_menu_id'],
                                            'm_sub_menu_id' => $v,
                                            'm_sub_sub_menu_id' => 0,
                   
                                         );
                    // $this->MRoleMenu->create();
                    $this->MRoleMenu->saveAll($master_submenu);                      
                    
                }
            }
            if(isset($this->data['MRoleMenu']['MSubSubMenu'])){
                foreach($this->data['MRoleMenu']['MSubSubMenu'] AS $k =>$v){
                    
                    $menu =$this->MSubSubMenu->find('first',array(
                                                                  'conditions' => array(
                                                                                         'MSubSubMenu.m_sub_sub_menu_id' => $v
                                                                                       ),
                                                                   'fields'     =>array('MSubSubMenu.m_menu_id','MSubSubMenu.m_sub_menu_id')                    
                                                                     ));
                    $master_sub_sub_menu=array(
                                            'designation_id' => $this->data['MRoleMenu']['designation_id'],
                                            'm_menu_id' => $menu['MSubSubMenu']['m_menu_id'],
                                            'm_sub_menu_id' => $menu['MSubSubMenu']['m_sub_menu_id'],
                                            'm_sub_sub_menu_id' => $v,
                                         );
                    // $this->MRoleMenu->create();
                    $this->MRoleMenu->save($master_sub_sub_menu);                      
                
                }
            } 
            $this->Session->setFlash('Role Menu inserted Successfully','default', array('class' => 'success'));
            $this->redirect('/Rollmenus/m_role_menu');
        }
        $editMenuList       = array('0');
        $editSubmenuList    = array('0');
        $editSubsubmenuList = array('0');
        
        if($designation_id != 0){
            
            $editMenuListFind = $this->MRoleMenu->find('all',array(
                                                                  'conditions' => array( 
                                                                          'MRoleMenu.designation_id' => $designation_id,
                                                                  ),
                                                  
                                                                  'fields'     =>array(
                                                                       'DISTINCT(MRoleMenu.m_menu_id) AS m_menu_id'
                                                                  ), 
                                                      ));
        
            if(!empty($editMenuListFind)){
                   foreach($editMenuListFind AS $editMenuListArray){
                       $editMenuList[] = $editMenuListArray["MRoleMenu"]['m_menu_id']; 
                   }
            }
            
            
            $editSubmenuListFind = $this->MRoleMenu->find('all',array(
                                                             'conditions' => array(
                                                                                     'MRoleMenu.designation_id' => $designation_id,
                                                                                   ),
                                                             'fields'     =>array(
                                                                                  'DISTINCT(MRoleMenu.m_sub_menu_id) AS m_sub_menu_id'
                                                                                  )
                                                         ));
            if(!empty($editSubmenuListFind)){
                   foreach($editSubmenuListFind AS $editSubmenuListArray){
                       $editSubmenuList[] = $editSubmenuListArray["MRoleMenu"]['m_sub_menu_id']; 
                   }
            }
            
            
            $editSubsubmenuListFind = $this->MRoleMenu->find('all',array(
                                                                'conditions' => array(
                                                                                        'MRoleMenu.designation_id' => $designation_id,
                                                                                      ),
                                                                'fields'     =>array(
                                                                                     'DISTINCT(MRoleMenu.m_sub_sub_menu_id) AS m_sub_sub_menu_id'
                                                                                     )
                                                            ));
            if(!empty($editSubsubmenuListFind)){
                   foreach($editSubsubmenuListFind AS $editSubsubmenuListArray){
                       $editSubsubmenuList[] = $editSubsubmenuListArray["MRoleMenu"]['m_sub_sub_menu_id']; 
                   }
            }
        }
        $designationList = $this->Designation->find('list',array(
                                        'conditions'    => array(
                                                                'Designation.is_enable'   => 1,
                                                            ),
                                        'fields'        => array(
                                                                'Designation.id','Designation.name',     
                                                            ),
                                        'order'         => array('Designation.name'),
                                    ));
        $menuList = $this->MMenu->find('all',array(
                                                'conditions'    => array('MMenu.is_enable'   => 1),
                                                'order' => array('MMenu.menu_order'),
                                                'recursive' => 2
                                                ));
        
        $submenuList = $this->MSubMenu->find('all',array(
                                                'order' => array('MSubMenu.sub_menu_order')
                                                 ));                                                                            
        $rolemenuList = $this->MRoleMenu->find('all');
        $this->set('designationList', $designationList);
        $this->set('menuList', $menuList);
        $this->set('rolemenuList', $rolemenuList);
        $this->set('designation_id', $designation_id);
        $this->set('editMenuList', $editMenuList);
        $this->set('editSubmenuList', $editSubmenuList);
        $this->set('editSubsubmenuList', $editSubsubmenuList);
    }
}
 ?>