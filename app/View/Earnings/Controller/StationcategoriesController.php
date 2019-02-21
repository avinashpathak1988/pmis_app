<?php
App::uses('Controller', 'Controller');

class StationcategoriesController extends AppController{
    public $layout='table';
    public $uses=array('Stationcategory');
    /**
     * Index Function
     */
    public function index(){
      // $this->loadModel('Stationcategory');
      //   $this->layout='table';
      //     $datas=$this->Stationcategory->find('all',array(
      //       'conditions'=>array(
      //             'Stationcategory.is_trash'=>0,
      //         ), 
      //         'order'=>array(
      //             'Stationcategory.name'
      //         )
      //     ));
      //     $this->set(compact('datas'));
    }
    public function indexAjax(){
        $this->layout   = 'ajax';
        $name      = '';
        $condition      = array(
            'Stationcategory.is_trash'         => 0,
        );
        if(isset($this->params['named']['name']) && $this->params['named']['name'] != ''){
            $name = $this->params['named']['name'];
            $condition += array('Stationcategory.name' => $name );
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','station_category_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','station_category_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
				$this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','station_category_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }               
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'User.modified',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('Stationcategory');
        $this->set(array(
            'datas'         => $datas,
            'name'     => $name,         
        ));
    }
    /**
     * Add Function
     */
    public function add(){
        $this->layout='table';
        if($this->request->is(array('post','put'))){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->Stationcategory->save($this->request->data)){
                if($this->auditLog('Stationcategory', 'stationcategories', $this->Stationcategory->id, 'Add', json_encode($this->data))){
                    $db->commit(); 
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Saved Successfully !');
                    $this->redirect(array('action'=>'index'));
                }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Saving Failed !');                    
                }
            }else{
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
            }
        }
        $is_enable=array(
            '0'=>'In Active',
            '1'=>'Active'
        );
          
        $this->set(compact('is_enable'));
    }
    /**
     * Edit Function
     */
    public function edit($id){
        $this->layout='table';
        if($this->request->is(array('post','put'))){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();            
            if($this->Stationcategory->save($this->request->data)){
                if($this->auditLog('Stationcategory', 'stationcategories', $this->data['Stationcategory']['id'], 'Update', json_encode($this->data))){
                    $db->commit();
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Saved Successfully !');
                    $this->redirect(array('action'=>'index'));
                }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Saving Failed !');
                }
            }else{
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
            }
        }
        $is_enable=array(
            '0'=>'In Active',
            '1'=>'Active'
        );
        
        $this->set(compact('is_enable'));
        $this->request->data=$this->Stationcategory->findById($id);
    }
    /**
     * Delete Function
     */
    public function delete($id){
        $this->Stationcategory->delete($id);
        $this->Session->write('message_type','success');
        $this->Session->write('message','Deleted Successfully !');
        $this->redirect(array('action'=>'index'));
    }
    /////////////////////
    public function disable($id){
        $db = ConnectionManager::getDataSource('default');
        $db->begin();         
        if($this->Stationcategory->updateAll(array('Stationcategory.is_enable'  => 0), array('Stationcategory.id' => $id))){
            if($this->auditLog('Stationcategory', 'stationcategories', $id, 'Disable', json_encode(array('Stationcategory.is_enable'  => 0)))){
                $db->commit();
                $this->Session->write('message_type','success');
                $this->Session->write('message','Disabled Successfully !');
            }else{
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Invalid request !');
            }
        }else{
            $db->rollback();
            $this->Session->write('message_type','success');
            $this->Session->write('message','Invalid request !');
        }
        $this->redirect(array('action'=>'index'));
    }
    /////////////////////////
    public function enable($id){
        $db = ConnectionManager::getDataSource('default');
        $db->begin();         
        if($this->Stationcategory->updateAll(array('Stationcategory.is_enable'  => 1), array('Stationcategory.id' => $id))){
            if($this->auditLog('Stationcategory', 'stationcategories', $id, 'Enable', json_encode(array('Stationcategory.is_enable'  => 0)))){
                $db->commit();
                $this->Session->write('message_type','success');
                $this->Session->write('message','Enabled Successfully !');
            }else{
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Invalid request !');
            }
        }else{
            $db->rollback();
            $this->Session->write('message_type','success');
            $this->Session->write('message','Invalid request !');
        }        
        $this->redirect(array('action'=>'index'));
    }
    public function trash($id){
        $db = ConnectionManager::getDataSource('default');
        $db->begin();         
        if($this->Stationcategory->updateAll(array('Stationcategory.is_trash'  => 1), array('Stationcategory.id' => $id))){
            if($this->auditLog('Stationcategory', 'stationcategories', $id, 'Trash', json_encode(array('Stationcategory.is_trash'  => 1)))){
                $db->commit();
                $this->Session->write('message_type','success');
                $this->Session->write('message','Trashed Successfully !');
            }else{
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Invalid request !');
            }
        }else{
            $db->rollback();
            $this->Session->write('message_type','success');
            $this->Session->write('message','Invalid request !');
        } 
        $this->redirect(array('controller'=>'stationcategories','action'=>'index'));
    }
}
