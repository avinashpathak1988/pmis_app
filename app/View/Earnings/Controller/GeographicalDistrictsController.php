<?php
App::uses('AppController', 'Controller');
class GeographicalDistrictsController extends AppController {
	public $layout='table';
	public function index() {
		$this->loadModel('State'); 
       // $this->loadModel('District');
        if(isset($this->data['GeographicalDistrictDelete']['id']) && (int)$this->data['GeographicalDistrictDelete']['id'] != 0){
        	if($this->GeographicalDistrict->exists($this->data['GeographicalDistrictDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();         		
                if($this->GeographicalDistrict->updateAll(array('GeographicalDistrict.is_trash'	=> 1), array('GeographicalDistrict.id'	=> $this->data['GeographicalDistrictDelete']['id']))){
                    if($this->auditLog('GeographicalDistrict', 'geographical_districts', $this->data['GeographicalDistrictDelete']['id'], 'Trash', json_encode(array('GeographicalDistrict.is_trash' => 1)))){
                        $db->commit();
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Delete Successfully !');
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Delete Failed !');
                    } 
        		}else{
                    $db->rollback();
					$this->Session->write('message_type','error');
                    $this->Session->write('message','Delete Failed !');
        		}
        	}else{
				$this->Session->write('message_type','error');
                $this->Session->write('message','Delete Failed !');
        	}
        }
        $stateList   = $this->State->find('list',array(
                'conditions'=>array(
                  'State.is_enable'=>1,
                  'State.is_trash'=>0,
                ),
                'order'=>array(
                  'State.name'
                )
				));
        $this->set(array(
            'stateList'         => $stateList,
        ));
    }
    public function indexAjax(){
      	//$this->loadModel('State'); 
        //$this->loadModel('GeographicalDistrict');
        $this->layout = 'ajax';
        $state_id  = '';
        $district_id = '';
        $geodistname  = '';
        $condition = array('GeographicalDistrict.is_trash'	=> 0);
        if(isset($this->params['named']['state_id']) && (int)$this->params['named']['state_id'] != 0){
            $state_id = $this->params['named']['state_id'];
            $condition += array('GeographicalDistrict.state_id' => $state_id );
        }
        if(isset($this->params['named']['district_id']) && (int)$this->params['named']['district_id'] != 0){
            $district_id = $this->params['named']['district_id'];
            $condition += array('GeographicalDistrict.district_id' => $district_id );
        }  
        if(isset($this->params['named']['geodistname']) && $this->params['named']['geodistname'] != ''){
            $geodistname = $this->params['named']['geodistname'];
            $condition += array("GeographicalDistrict.name LIKE '%$geodistname%'");
        } 
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'GeographicalDistrict.name'
            ),            
            'limit'         => 20,
        );
        $datas  = $this->paginate('GeographicalDistrict');
        $this->set(array(
            'state_id'          => $state_id,
            'district_id'          => $district_id,
            'geodistname'          => $geodistname,
            'datas'             => $datas,
        )); 
    }
	public function add() { 
		$this->loadModel("PrisonDistrict"); 
		$this->loadModel('State');
       
		if (isset($this->data['GeographicalDistrict']) && is_array($this->data['GeographicalDistrict']) && count($this->data['GeographicalDistrict'])>0){
    		$db = ConnectionManager::getDataSource('default');
            $db->begin(); 
			if ($this->GeographicalDistrict->save($this->request->data)) {
                if(isset($this->data['GeographicalDistrict']['id']) && (int)$this->data['GeographicalDistrict']['id'] != 0){
                    if($this->auditLog('GeographicalDistrict', 'geographical_districts', $this->data['GeographicalDistrict']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('GeographicalDistrict', 'geographical_districts', $this->GeographicalDistrict->id, 'Add', json_encode($this->data))){
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
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
			}
		}
        if(isset($this->data['GeographicalDistrictEdit']['id']) && (int)$this->data['GeographicalDistrictEdit']['id'] != 0){
            if($this->GeographicalDistrict->exists($this->data['GeographicalDistrictEdit']['id'])){
                $this->data = $this->GeographicalDistrict->findById($this->data['GeographicalDistrictEdit']['id']);
               
                $districtList = $this->PrisonDistrict->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'PrisonDistrict.id',
                        'PrisonDistrict.name',
                    ),
                    'conditions'    => array(
                        'PrisonDistrict.id'    => $this->data['GeographicalDistrict']['district_id'],
                    ),          
               
                ));
                $this->set(array(
                    'districtList'     => $districtList,
                ));   
            }
        }		
		$stateList = $this->State->find('list', array(
			'recursive'		=> -1,
			'fields'		=> array(
				'State.id',
				'State.name',
			),
			'conditions'	=> array(
				'State.is_trash'	=> 0,
				'State.is_enable'	=> 1,
			),			
			'order'			=> array(
				'State.name'
			),
		));
		$this->set(array(
			'stateList'		=> $stateList,
		));
	}

    public function getdistrictAjax()
    {
        $this->autoRender = false;
        $this->loadModel("PrisonDistrict"); 
       if(isset($this->params['named']['state_id']) && (int)$this->params['named']['state_id'] != 0){
            $condition = array('PrisonDistrict.state_id' => $this->params['named']['state_id'] );
            $district = $this->PrisonDistrict->find('list', array(
              'fields'          => array('id','name'),
              'conditions'      => $condition,  
            ));

          if(is_array($district) && count($district)>0){
                echo '<option value="">--Select District--</option>';
                foreach($district as $key=>$val){
                    echo '<option value="'.$key.'">'.$val.'</option>';
                }
            }else{
                echo '<option value="">--Select District--</option>';
            }
        }else
        {
            echo '<option value="">--Select District--</option>';
        }
        
    }
}
