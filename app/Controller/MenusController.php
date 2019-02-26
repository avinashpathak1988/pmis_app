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
class MenusController extends AppController {
    public $layout='table';
/**
 * Components
 *
 * @var array
 */
	//public $components = array('Paginator', 'Flash', 'Session');
	//public $helpers = array('Html', 'Form');
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->loadModel('Menu');
		$this->Menu->recursive = 0;
		$Parent = $this->Menu->find('list',array(
	         'fields' => 'Menu.name',
	         'conditions' => array(
	         'parent_id' => 0
	         	)
         	)
			);

		// $name = $this->Menu->find('list',array(
	 //         'fields' => 'Menu.name',
	 //         'conditions' => array(
	 //         'Not' => array('parent_id' => 0)
	 //         	)
  //        	)
		// 	);

        // $menuList = $this->Menu->find('all',array(
        //     'recursive'     => -1,
        //     'joins'         => array(
        //         array(
        //             'table'         => 'menus',
        //             'alias'         => 'MainMenu',
        //             'foreignKey'    => false,
        //             'type'          => 'left',
        //             'conditions'    =>array('Menu.parent_id = MainMenu.id')
        //         ),                
        //     ),
        //     'fields'        => array(
        //         'MainMenu.name as parentname',
        //         'Menu.id',
        //         'Menu.name',
        //         'Menu.url',
        //         'Menu.order',
        //         'Menu.is_enable',
        //     ),
        //     'maxLimit'  => 250,
        //     'limit'     => 250,
        // )); //debug($menuList);
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
		$this->set(array(
            'Parent'         => $Parent,    
            'moduleList'         => $moduleList,	
            //'name'           => $name,	
        ));
	}
	public function indexAjax(){
		$this->layout = 'ajax';
		$this->loadModel('Menu');
		$parent_id = '';
		$name = '';
		$condition = array();
		$this->Menu->recursive = 0;
    if(isset($this->params['named']['parent_id']) && $this->params['named']['parent_id'] != '' ){
        $parent_id = $this->params['named']['parent_id'];
        if($parent_id !=0){
            $condition += array('Menu.parent_id' => $parent_id);
        }
     }
     if(isset($this->params['named']['name']) && $this->params['named']['name'] != ''){
        $name = $this->params['named']['name'];
        $condition += array("Menu.name LIKE '%$name%'");
     }
      if(isset($this->params['named']['module_id']) && $this->params['named']['module_id'] != ''){
        $module = $this->params['named']['module_id'];
        $condition += array('Menu.module_id' => $module);
     }

		$this->paginate = array(
			'conditions' => $condition,
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
          'Menu.module_id',
          'Menu.is_enable',
      ),
			'order'=>array(

                'Menu.id'=>'DESC',
				'BankBranch.name'=>'asc'
				),
			'limit'=>20,
			);
		$datas = $this->paginate('Menu');
    $this->set(array(
        'datas'          => $datas,
        'parent_id'      => $parent_id,
        'name'           => $name,
    ));
    //debug($condition);
	}
/**
 * add , edit, delete method
 *
 * @return void
 */

	public function addMenu() {
		$this->loadModel('Menu');
		/* for add menu */
        if(isset($this->data['Menu']) && is_array($this->data['Menu']) && count($this->data['Menu'])>0){
            if(isset($this->request->data['Menu']['parent_id']) && $this->request->data['Menu']['parent_id'] == ''){
                $this->request->data['Menu']['parent_id'] = 0;
            }
            $db = ConnectionManager::getDataSource('default');
            $db->begin(); 
            $countMenu = $this->Menu->find('count', array(
            'recursive'     => -1,
            'conditions'    => array(
                'Menu.parent_id'    =>  $this->request->data['Menu']['parent_id'],
                'Menu.order'    =>  $this->request->data['Menu']['order'],

            )
        ));  
            //debug($countMenu);
            if($countMenu > 0  && $this->request->data['Menu']['id']==''){
                $this->Flash->error(__('The menu could not be saved. Please, try different ordering.'));
                //return $this->redirect(array('action' => 'index'));                        

            }else{
                    if($this->Menu->save($this->request->data)){
                        if(isset($this->data['Menu']['id']) && (int)$this->data['Menu']['id'] != 0){
                            if($this->auditLog('Menu', 'menus', $this->data['Menu']['id'], 'Update', json_encode($this->data))){
                                $db->commit(); 
                                $this->Flash->success(__('The Menu has been saved.'));
                                return $this->redirect(array('action' => 'index'));                        
                            }else{
                                $db->rollback();
                                $this->Flash->error(__('The menu could not be saved. Please, try again.'));
                            }
                        }else{
                            if($this->auditLog('Menu', 'menus', $this->Menu->id, 'Add', json_encode($this->data))){
                                $db->commit(); 
                                $this->Flash->success(__('The Menu has been saved.'));
                                return $this->redirect(array('action' => 'index'));                        
                            }else{
                                $db->rollback();
                                $this->Flash->error(__('The menu could not be saved. Please, try again.'));
                            }
                        }
                    }else{
                        $db->rollback();
                        $this->Flash->error(__('The menu could not be saved. Please, try again.'));
                    }  
            }
                      
        }
        /* for edit menu */
        if (isset($this->data['MenuEdit']) && is_array($this->data['MenuEdit']) && count($this->data['MenuEdit'])>0) {
            if($this->Menu->exists($this->data['MenuEdit']['id'])){
                $this->data = $this->Menu->findById($this->data['MenuEdit']['id']);
            }
        }

        /* for delete menu */
        if (isset($this->data['MenuDelete']) && is_array($this->data['MenuDelete']) && count($this->data['MenuDelete'])>0) {
            if($this->Menu->exists($this->data['MenuDelete']['id'])){ 
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                             
                if($this->Menu->delete($this->data['MenuDelete']['id'])){
                    if($this->auditLog('Menu', 'menus', $this->data['MenuDelete']['id'], 'Delete', json_encode($this->data))){
                        $db->commit();
                    }else{
                        $db->rollback();
                    }
                }else{
                    $db->rollback();
                }
                $this->redirect(array('action'=>'index'));
            }
        }

        $datas = $this->Menu->find('all',array(
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
            ),
        )); 
        $parentList = $this->Menu->find('list', array(
            'recursive'     => -1,
            'conditions'    => array(
                'Menu.parent_id'    => 0,
            ),
            'fields'        => array(
                'Menu.id',
                'Menu.name',
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
        // debug($moduleList);
        $this->set(array(
            'title'         => 'Add Menu',
            'datas'         => $datas,
            'parentList'    => $parentList,
            'moduleList'    => $moduleList
        ));

	}
}
