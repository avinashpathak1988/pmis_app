<?php
App::uses('AppController', 'Controller');
/**
 * Districts Controller
 *
 * @property District $District
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class RoleMenusController extends AppController {
    public $layout='table';
/**
 * Components
 *
 * @var array
 */
	//public $components = array('Paginator', 'Flash', 'Session');
	//public $helpers = array('Html', 'Form');
/**
 * roleMenu index method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */

	public function index(){
        $this->loadModel('Usertype');
        $this->loadModel('RoleMenu');
        if(isset($this->data['RoleMenu']) && is_array($this->data['RoleMenu']) && count($this->data['RoleMenu'])>0){

            $data = array();
            if(isset($this->data['RoleMenu']['menu']) && is_array($this->data['RoleMenu']['menu']) && count($this->data['RoleMenu']['menu'])>0){
                foreach($this->data['RoleMenu']['menu'] as $menuKey=>$menuVal){
                    $menuArr = explode('-',$menuVal); //debug($menuArr); exit;
                    $data[$menuKey]['usertype_id']     = $this->data['RoleMenu']['usertype_id'];
                    $data[$menuKey]['menu_id']          = $menuArr[0];
                    $data[$menuKey]['submenu_id']      = $menuArr[1];
                }
            }
            if(is_array($data) && count($data)>0){ 
                $this->RoleMenu->deleteAll(array('RoleMenu.usertype_id'    => $this->data['RoleMenu']['usertype_id']));
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->RoleMenu->saveAll($data)){
                    if($this->auditLog('RoleMenu', 'role_menus', 0, 'Add', json_encode($data))){ 
                        $db->commit();                    
                        $this->Flash->success(__('Role Menu added successfully !'));
    					return $this->redirect(array('action' => 'index'));
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Invalid request, please try again !');                        
                    }
                }else{
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Invalid request, please try again !');  
                }
            }else{                
                $this->Flash->error(__('Invalid request, please try again !'));
            }
        }
        $usertypeList = $this->Usertype->find('list', array(
            'recursive'     => -1,
            'conditions'    => array(
                'Usertype.is_enable'    => 1,
                'Usertype.is_trash'    => 0,
            ),
            'fields'        => array(
                'Usertype.id',
                'Usertype.name',
            ),
        ));
        $this->set(array(            
            'usertypeList'      => $usertypeList,
        ));
    } 

/**
 * indexAjax method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */

    public function indexAjax(){
        $this->layout = 'ajax';
        $this->loadModel('Menu');
        $this->loadModel('RoleMenu');
        $menu       = array();
        $menuArr    = array();
        $subMenuArr = array(); 

        if(isset($this->data['user_type_id']) && (int)$this->data['user_type_id'] != 0){
            $menuData = $this->Menu->find('all', array(
                'recursive'     => -1,
                'joins'         => array(
                    array(
                        'table'         => 'menus',
                        'alias'         => 'MainMenu',
                        'foreignKey'    => false,
                        'type'          => 'left',
                        'conditions'    =>array('Menu.parent_id = MainMenu.id')
                    ),                
                ),
                'fields'        => array(
                    'MainMenu.name as parentname',
                    'Menu.id',
                    'Menu.name',
                    'Menu.url',
                    'Menu.order',
                    'Menu.is_enable',
                    'Menu.parent_id',
                ), 
                'conditions'            => array(
                    'Menu.is_enable'    => 1
                ),
                'order'                 => array(
                    'Menu.order'        => 'ASC',
                    'MainMenu.order'    => 'ASC',
                ),           
            ));
            // debug($menuData);
            if(is_array($menuData) && count($menuData)>0){
                foreach($menuData as $menuVal){
                    if((int)$menuVal['Menu']['parent_id'] == 0){
                        $menu[$menuVal['Menu']['id']]['id']      = $menuVal['Menu']['id'];
                        $menu[$menuVal['Menu']['id']]['name']    = $menuVal['Menu']['name'];
                        $menu[$menuVal['Menu']['id']]['url']     = $menuVal['Menu']['url'];
                        $menu[$menuVal['Menu']['id']]['order']   = $menuVal['Menu']['order'];
                    }else{
                        $menu[$menuVal['Menu']['parent_id']]['child'][$menuVal['Menu']['order']]['id']      = $menuVal['Menu']['id'];
                        $menu[$menuVal['Menu']['parent_id']]['child'][$menuVal['Menu']['order']]['name']    = $menuVal['Menu']['name'];
                        $menu[$menuVal['Menu']['parent_id']]['child'][$menuVal['Menu']['order']]['url']     = $menuVal['Menu']['url'];
                        $menu[$menuVal['Menu']['parent_id']]['child'][$menuVal['Menu']['order']]['order']   = $menuVal['Menu']['order'];
                    }
                }
            }

            // debug($menu);
            
            $role = $this->RoleMenu->find('all', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'RoleMenu.usertype_id' => $this->data['user_type_id'],
                ),
            )); 
            
            if(is_array($role) && count($role)>0){
                foreach($role as $roleKey=>$roleVal){
                    $menuArr[$roleVal['RoleMenu']['menu_id']]          = $roleVal['RoleMenu']['menu_id'];
                    $subMenuArr[$roleVal['RoleMenu']['submenu_id']]    = $roleVal['RoleMenu']['submenu_id'];
                }
            }
        }
        $this->set(array(
            'menu'          => $menu,
            'menuArr'       => $menuArr,
            'subMenuArr'    => $subMenuArr,
        ));
    }
}
