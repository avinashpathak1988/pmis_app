<?php
App::uses('Controller', 'Controller');

class StationsController extends AppController{
    public $components = array('Paginator', 'Flash','Session');
    /**
     * Index Function
     */
    public function index(){
        $this->layout='table';
          $datas=$this->Station->find('all',array(
            'conditions'=>array(
                  'Station.is_trash'=>0,
              ), 
              'order'=>array(
                  'Station.name'
              )
          ));
          $this->set(compact('datas'));
    }
    /**
     * Add Function
     */
    public function add(){
        $this->layout='table';
        if($this->request->is(array('post','put'))){
            $this->request->data['Station']['date_of_opening']=date('Y-m-d',strtotime($this->request->data['Station']['date_of_opening']));
            if($this->Station->save($this->request->data)){
                $this->Session->write('message_type','success');
                $this->Session->write('message','Saved Successfully !');
                $this->redirect(array('action'=>'index'));
            }else{
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
            }
        }
        $is_enable=array(
            '0'=>'In Active',
            '1'=>'Active'
        );
          $this->loadModel('Security');
          $security_id=$this->Security->find('list',array(
                'conditions'=>array(
                  'Security.is_enable'=>1,
                  'Security.is_trash'=>0,
                ),
                'order'=>array(
                  'Security.name'
                )
          ));
          $this->loadModel('Stationcategory');
          $stationcategory_id=$this->Stationcategory->find('list',array(
                'conditions'=>array(
                  'Stationcategory.is_enable'=>1,
                  'Stationcategory.is_trash'=>0,
                ),
                'order'=>array(
                  'Stationcategory.name'
                )
          ));
        $this->set(compact('is_enable','security_id','stationcategory_id'));
    }
    /**
     * Edit Function
     */
    public function edit($id){
        $this->layout='table';
        if($this->request->is(array('post','put'))){
            if($this->Station->save($this->request->data)){
                $this->Session->write('message_type','success');
                $this->Session->write('message','Saved Successfully !');
                $this->redirect(array('action'=>'index'));
            }else{
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
            }
        }
        $is_enable=array(
            '0'=>'In Active',
            '1'=>'Active'
        );
        $this->loadModel('Security');
          $security_id=$this->Security->find('list',array(
                'conditions'=>array(
                  'Security.is_enable'=>1,
                  'Security.is_trash'=>0,
                ),
                'order'=>array(
                  'Security.name'
                )
          ));
          $this->loadModel('Stationcategory');
          $stationcategory_id=$this->Stationcategory->find('list',array(
                'conditions'=>array(
                  'Stationcategory.is_enable'=>1,
                  'Stationcategory.is_trash'=>0,
                ),
                'order'=>array(
                  'Stationcategory.name'
                )
          ));
        $this->set(compact('is_enable','security_id','stationcategory_id'));
        $this->request->data=$this->Station->findById($id);
    }
    /**
     * Delete Function
     */
    public function delete($id){
        $this->Station->delete($id);
        $this->Session->write('message_type','success');
        $this->Session->write('message','Deleted Successfully !');
        $this->redirect(array('action'=>'index'));
    }
    /////////////////////
    public function disable($id){
        $this->Station->id=$id;
        $this->Station->saveField('is_enable',0);
        $this->Session->write('message_type','success');
        $this->Session->write('message','Disabled Successfully !');
        $this->redirect(array('action'=>'index'));
    }
    /////////////////////////
    public function enable($id){
        $this->Station->id=$id;
        $this->Station->saveField('is_enable',1);
        $this->Session->write('message_type','success');
        $this->Session->write('message','Enabled Successfully !');
        $this->redirect(array('action'=>'index'));
    }
    public function trash($id){
      $this->Station->id=$id;
      $this->Station->updateAll(
            array('Station.is_trash' => 1),
            array('Station.id' => $id)
        );
      //$this->Document->saveField('is_trash',1);
      $this->Session->write("message_type",'success');
      $this->Session->write('message','Trashed Successfully !');
      $this->redirect(array(
        'controller'=>'stations',
        'action'=>'index'
      ));
    }
}
