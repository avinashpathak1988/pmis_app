<?php
App::uses('AppController', 'Controller');
class HolidaysController extends AppController {
	public $layout='table';
	public function index() {
		   if(isset($this->data['HolidayDelete']['id']) && (int)$this->data['HolidayDelete']['id'] != 0){
        	if($this->Holiday->exists($this->data['HolidayDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                  
        		if($this->Holiday->updateAll(array('Holiday.is_trash'	=> 1), array('Holiday.id'	=> $this->data['HolidayDelete']['id']))){
                    if($this->auditLog('Holiday', 'holidays', $this->data['HolidayDelete']['id'], 'Trash', json_encode(array('Holiday.is_trash' => 1)))){
                        $db->commit();
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Delete Successfully !');
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Delete Failed !');
                    } 
        		}else{
					$this->Session->write('message_type','error');
                    $this->Session->write('message','Delete Failed !');
        		}
        	}else{
				$this->Session->write('message_type','error');
                $this->Session->write('message','Delete Failed !');
        	}
        }
		$year = array();
		$beforeyr =  date('Y',strtotime( '- 5 years')); 
		$afteryr =  date('Y',strtotime( '+ 1 years')); 
		for($i = $beforeyr; $i <= $afteryr; $i++)
		{
			$year[$i] = $i;
		}
		$this->set('year',$year);
		
		
    }
    public function indexAjax(){
      	$this->layout = 'ajax';
        $description  = '';
        $condition = array('Holiday.is_trash'	=> 0);
       /* if(isset($this->params['named']['description']) && $this->params['named']['description'] != ''){
            $description = $this->params['named']['description'];
            $condition += array("Holiday.description LIKE '%$description%'");
        } */
		if(isset($this->params['named']['year']) && $this->params['named']['year'] != ''){
            $year = $this->params['named']['year'];
            $condition += array("DATE_FORMAT(Holiday.holiday_date,'%Y') = $year");
        }
		//debug($condition); exit;
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'Holiday.description'
            ),            
            'limit'         => 20,
        );
        $datas  = $this->paginate('Holiday');
        $this->set(array(
            'year'         => @$year,
            'datas'             => $datas,
        )); 
    }
	public function add() { 
		//$this->loadModel('Holiday');
		if (isset($this->data['Holiday']) && is_array($this->data['Holiday']) && count($this->data['Holiday'])>0){	
            $db = ConnectionManager::getDataSource('default');
            $db->begin();      
				$holiday['holiday_date'] = date('Y-m-d',strtotime($this->data['Holiday']['holiday_date']));
				$holiday['description']= $this->data['Holiday']['description'];
				$holiday['id']= $this->data['Holiday']['id'];
				if ($this->Holiday->save($holiday)) {
                if(isset($this->data['Holiday']['id']) && (int)$this->data['Holiday']['id'] != 0){
                    if($this->auditLog('Holiday', 'holidays', $this->data['Holiday']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('Holiday', 'holidays', $this->Holiday->id, 'Add', json_encode($this->data))){
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
				$this->Flash->error(__('The State could not be saved. Please, try again.'));
			}
		}
        if(isset($this->data['HolidayEdit']['id']) && (int)$this->data['HolidayEdit']['id'] != 0){
            if($this->Holiday->exists($this->data['HolidayEdit']['id'])){
                $this->data = $this->Holiday->findById($this->data['HolidayEdit']['id']);
            }
        }
	}
}