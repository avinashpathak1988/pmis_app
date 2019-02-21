<?php
App::uses('AppController','Controller');
class HeightsController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('Height');
        if(isset($this->data['HeightDelete']['id']) && (int)$this->data['HeightDelete']['id'] != 0){
            if($this->Height->exists($this->data['HeightDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->Height->updateAll(array('Height.is_trash' => 1), array('Height.id'  => $this->data['HeightDelete']['id']))){
                    if($this->auditLog('Height', 'Heights', $this->data['HeightDelete']['id'], 'Trash', json_encode(array('Height.is_trash' => 1)))){
                        $db->commit();
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Deleted Successfully !');
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Deleted Failed !');
                    }
                }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Deleted Failed !');
                }
            }else{
                $this->Session->write('message_type','error');
                $this->Session->write('message','Deleted Failed !');                
            }
        }   
        $datas=$this->Height->find('all',array(
            'conditions'    => array(
                'Height.is_trash' => 0
            ),
            'order'         => array(
                'Height.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function indexAjax(){
        $this->layout = 'ajax';
        $height_type = '';
        $condition = array();
        $this->Height->recursive = 0;
        if(isset($this->params['named']['height_type']) && $this->params['named']['height_type'] != '0'){
            $height_type = $this->params['named']['height_type'];
            $condition += array('Height.height_type' => $height_type);
        }
        //print_r($condition); exit;
        $this->paginate = array(
            'conditions'    => array(
                'Height.is_trash' => 0
            ),
            'order'         => array(
                'Height.id'
            ),
            'limit'=>20,
        );

        // $this->paginate = array(
        //     'conditions' => $condition,
        //     'recursive'     => -1,
        //     'order'=>array(
        //         'Height.name'=>'asc'
        //         ),
        //     'limit'=>20,
        //     );
        $datas = $this->paginate('Height');
        $this->set(array(
            'datas'          => $datas,
            'height_type'      => $height_type
        ));
        //debug($condition);
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['Height']) && is_array($this->data['Height']) && count($this->data['Height']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();  
            $datas_height=$this->Height->find('all',array(
                'conditions'    => array(
                    'Height.is_trash' => 0,
                    'Height.name'=>$this->request->data['Height']['name'],
                    'Height.height_type'=>$this->request->data['Height']['height_type'],
                ),
                'order'         => array(
                    'Height.name'
                ),
            ));
            if(count($datas_height)>0){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
            }
            else{           
                if($this->Height->save($this->request->data)){
                    if(isset($this->data['Height']['id']) && (int)$this->data['Height']['id'] != 0){
                        if($this->auditLog('Height', 'Height', $this->data['Height']['id'], 'Update', json_encode($this->data))){
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
                        if($this->auditLog('Height', 'Height', $this->Height->id, 'Add', json_encode($this->data))){
                            $db->commit(); 
                            $this->Session->write('message_type','success');
                            $this->Session->write('message','Saved Successfully !');
                            $this->redirect(array('action'=>'index'));                      
                        }else{
                            $db->rollback();
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','Saving Failed !');
                        }
                    }
                }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Saving Failed !');
                }
            }
        }
        if(isset($this->data['HeightEdit']['id']) && (int)$this->data['HeightEdit']['id'] != 0){
            if($this->Height->exists($this->data['HeightEdit']['id'])){
                $this->data = $this->Height->findById($this->data['HeightEdit']['id']);
            }
        }
        $rparents=$this->Height->find('list',array(
            'conditions'=>array(
                'Height.is_enable'=>1,
            ),
            'order'=>array(
                'Height.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
