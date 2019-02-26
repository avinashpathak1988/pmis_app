<?php
App::uses('AppController', 'Controller');
class WardCellsController extends AppController {
	public $layout='table';
	public function index() {
		$this->loadModel('Ward'); 
        $this->loadModel('WardCell');
        if(isset($this->data['WardCellDelete']['id']) && (int)$this->data['WardCellDelete']['id'] != 0){
        	if($this->WardCell->exists($this->data['WardCellDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();         		
                if($this->WardCell->updateAll(array('WardCell.is_trash'	=> 1), array('WardCell.id'	=> $this->data['WardCellDelete']['id']))){
                    if($this->auditLog('WardCell', 'ward_cells', $this->data['WardCellDelete']['id'], 'Trash', json_encode(array('WardCell.is_trash' => 1)))){
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
         $wardList   = $this->Ward->find('list');
        $prisonlist = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'      => 1,
                'Prison.is_trash'       => 0
            ),
            'order'         => array(
                'Prison.name'
            ),
        ));
        //$countryList = '';
        // if(isset($this->data["WardCell"]["station_id"]) && (int)$this->data["WardCell"]["station_id"] != 0){
        //     $prisonList = $this->Ward->find('list', array(
        //         'recursive'     => -1,
        //         'fields'        => array(
        //             'Ward.id',
        //             'Ward.name',
        //         ),
        //         'conditions'    => array(
        //             'Ward.prison'     => $this->data["Ward"]["station_id"],
        //             'Ward.is_enable'      => 1,
        //             'Ward.is_trash'       => 0,
        //         ),
        //         'order'         => array(
        //             'Ward.name'
        //         ),
        //     ));    
        // }
        
        $this->set(array(
             'wardList'         => $wardList,
            'prisonlist'       => $prisonlist,
        ));
    }
    public function indexAjax(){
      	$this->loadModel('Ward'); 
        $this->loadModel('WardCell');
        $this->layout = 'ajax';
        $ward_id  = '';
        $cell_name  = '';
        $prison_id ='';
        $condition = array('WardCell.is_trash'	=> 0);
        if(isset($this->params['named']['ward_id']) && (int)$this->params['named']['ward_id'] != 0){
            $prison_id = $this->params['named']['ward_id'];
            $condition += array('WardCell.ward_id' => $prison_id );
        } 
         if(isset($this->params['named']['prison_id']) && (int)$this->params['named']['prison_id'] != 0){
            $ward_id = $this->params['named']['prison_id'];
            $condition += array('WardCell.prison_id' => $ward_id );
        } 
        if(isset($this->params['named']['cell_name']) && $this->params['named']['cell_name'] != ''){
            $cell_name = $this->params['named']['cell_name'];
            $condition += array("WardCell.cell_name LIKE '%$cell_name%'");
        } 
        $this->paginate = array(
            'recursive'=> -1,
            'joins' => array(
                array(
                    'table' => 'ward_cells',
                    'alias' => 'WardCell',
                    'type' => 'inner',
                    'conditions'=> array('WardCell.prison_id = Prison.id')
                ),
                array(
                    'table' => 'wards',
                    'alias' => 'Ward',
                    'type' => 'inner',
                    'conditions'=> array('WardCell.ward_id = Ward.id')
                ),
            ), 
            //'conditions'    => $condition,
            // 'order'         =>array(
               
            //     'WardCell.id'  => 'DESC'
              
            // ),  
            // 'fields'  => array(
            //      'WardCell.ward_id',                   
            //      'WardCell.cell_name',
            //      'WardCell.cell_no' 
            // ),        
            'group'=>array(
               'Ward.id'

            ),
            'limit'         => 20,
        );

        $datas  = $this->paginate('Prison');
        debug($datas); exit;
        $this->set(array(
            'ward_id'          => $ward_id,
            'cell_name'        => $cell_name,
            'prison_id'        => $prison_id,
            'datas'             => $datas,
        )); 
    }


    function getCellDetails($ward_id=''){
        $this->loadModel('WardCell');
        $fullname = '';
        $condition = array(
            'WardCell.id'    => $ward_id
        );
        $cellData = $this->WardCell->find('all', array(
            'recursive'     => -1,
            'conditions'    => $condition
        ));
        
         return $cellData;
    }


	public function add() { 
		$this->loadModel("WardCell"); 
		$this->loadModel('Ward');
		if (isset($this->data['WardCell']) && is_array($this->data['WardCell']) && count($this->data['WardCell'])>0){
            $fibalarray = array();
            foreach ($this->data['WardCell']['cell_name'] as $key => $value) {
                $fibalarray[$key]['WardCell']['prison_id'] = $this->data['WardCell']['prison_id'];
                $fibalarray[$key]['WardCell']['ward_id'] = $this->data['WardCell']['ward_id'];
                $fibalarray[$key]['WardCell']['cell_name'] = $value;
                $fibalarray[$key]['WardCell']['cell_no'] = $this->data['WardCell']['cell_no'][$key];
            }
            // debug($fibalarray);exit;
    		$db = ConnectionManager::getDataSource('default');
            $db->begin(); 
			if ($this->WardCell->saveMany($fibalarray)) {
                if(isset($this->data['WardCell']['id']) && (int)$this->data['WardCell']['id'] != 0){
                    if($this->auditLog('WardCell', 'ward_cells', $this->data['WardCell']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('WardCell', 'ward_cells', $this->WardCell->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['WardCellEdit']['id']) && (int)$this->data['WardCellEdit']['id'] != 0){
            if($this->WardCell->exists($this->data['WardCellEdit']['id'])){
                $this->data = $this->WardCell->findById($this->data['WardCellEdit']['id']);
            }
        }		
		$wardList = $this->Ward->find('list', array(
			'recursive'		=> -1,
			'fields'		=> array(
				'Ward.id',
				'Ward.name',
			),
			'conditions'	=> array(
				'Ward.is_trash'	=> 0,
				'Ward.is_enable'	=> 1,
			),			
			'order'			=> array(
				'Ward.name'
			),
		));
          $this->loadModel('Prison');
        $prisonlist = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'      => 1,
                'Prison.is_trash'       => 0
            ),
            'order'         => array(
                'Prison.name'
            ),
        ));
		$this->set(array(
			'wardList'		=> $wardList,
            'prisonlist'    => $prisonlist,
		));
	}
    function stationList()
    {
        $this->autoRender = false;
        $station_id = $this->request->data['prison_id'];
        $countryHtml = '<option value="0">-- Select Wards --</option>';
        if(isset($station_id) && (int)$station_id != 0)
        {
            $this->loadModel('Ward');
            $WardList = $this->Ward->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Ward.id',
                    'Ward.name',
                ),
                'conditions'    => array(
                    'Ward.prison'     => $station_id,
                    'Ward.is_enable'      => 1,
                    'Ward.is_trash'       => 0,
                ),
                'order'         => array(
                    'Ward.name'
                ),
            ));    
            //$stateHtml = '';
            foreach($WardList as $countryKey=>$stationVal)
            {
                $countryHtml .= '<option value="'.$countryKey.'">'.$stationVal.'</option>';
            }
        }
        $countryHtml .= '<option value="other">Other</option>';
        echo $countryHtml;  
    }
   
}
