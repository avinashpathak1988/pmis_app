
<?php
App::uses('AppController', 'Controller');
class GetbooksController extends AppController {
    public $layout='table';
	public $uses = array('Ward');
    
	public function getbookStageReport()
	{
		$this->loadModel('Prison');
        $this->loadModel('Gender');
        if($this->Session->read('Auth.User.prison_id')!=''){
            $prisonList = $this->Prison->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Prison.id',
                    'Prison.name',
                ),
                'conditions'    => array(
                    'Prison.is_enable'  => 1,
                    'Prison.is_trash'   => 0,
                    'Prison.id'=>$this->Session->read('Auth.User.prison_id'),
                ),
                'order'         => array(
                    'Prison.name'       => 'ASC',
                ),
            ));
        }else{
            $prisonList = $this->Prison->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Prison.id',
                    'Prison.name',
                ),
                'conditions'    => array(
                    'Prison.is_enable'  => 1,
                    'Prison.is_trash'   => 0,
                ),
                'order'         => array(
                    'Prison.name'       => 'ASC',
                ),
            ));
        }
        
        $this->set(array(
            'prisonList'    => $prisonList,
        ));
	}
	
	public function getbookStageAjax()
	{
		$this->layout = 'ajax';
		$this->loadModel('Prisoner');
		ini_set('memory_limit', '-1');
		$condition      = array( 'Prisoner.is_trash'=> 0,);

    if($this->Session->read('Auth.User.prison_id')!=''){
        $condition += array('Prisoner.prison_id' => $this->Session->read('Auth.User.prison_id') );
    }else{
        if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('Prisoner.prison_id' => $prison_id );
        }
    }
    
    if(isset($this->params['named']['prisoner_name']) && $this->params['named']['prisoner_name'] != ''){
        $prisoner_name = $this->params['named']['prisoner_name'];
        $condition += array("Prisoner.first_name like '%".$prisoner_name."%'");
    }
		
		if(isset($this->params['named']['epd_from']) && $this->params['named']['epd_from'] != ''){
          $epd_from = date('Y-m-d',strtotime($this->params['named']['epd_from']));
          $condition += array('Prisoner.epd >= ' => $epd_from );
      }
	  
		if(isset($this->params['named']['epd_to']) && $this->params['named']['epd_to'] != ''){
          $epd_to = date('Y-m-d',strtotime($this->params['named']['epd_to']));
          $condition += array('Prisoner.epd <= ' => $epd_to);
      }

      if(isset($this->params['named']['lpd_from']) && $this->params['named']['lpd_from'] != ''){
          $lpd_from = date('Y-m-d',strtotime($this->params['named']['lpd_from']));
          $condition += array('Prisoner.lpd >= ' => $lpd_from);
      }
	  
	  if(isset($this->params['named']['lpd_to']) && $this->params['named']['lpd_to'] != ''){
          $lpd_to = date('Y-m-d',strtotime($this->params['named']['lpd_to']));
          $condition += array('Prisoner.lpd >= ' => $lpd_to);
      }
    
      if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
          if($this->params['named']['reqType']=='XLS'){
              $this->layout='export_xls';
              $this->set('file_type','xls');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.xls');
          }else if($this->params['named']['reqType']=='DOC'){
              $this->layout='export_xls';
              $this->set('file_type','doc');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.doc');
          }else if($this->params['named']['reqType']=='PDF'){
              $this->layout='pdf';
              $this->set('file_type','pdf');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.pdf');
          }
			$this->set('is_excel','Y');
			$limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 
		
		$this->Prisoner->recursive = -1;
		$this->paginate = array(
    		'conditions'	=> array(
          'Prisoner.is_trash'         => 0,
          'Prisoner.prisoner_type_id'         => Configure::read('CONVICTED'),
          // 'Prisoner.prison_id'        => $this->Auth->user('prison_id'),
          'Prisoner.present_status'        => 1,
          'Prisoner.is_approve'        => 1,
          'Prisoner.transfer_status !='        => 'Approved'
        )+$condition,
    		'order'			=> array(
				'Prisoner.prison_id'	=> 'ASC',
    			'Prisoner.state_id'	=> 'ASC',
				'Prisoner.country_id' => 'ASC',
				'Prisoner.prisoner_type_id' => 'ASC',
				'Prisoner.prisoner_sub_type_id' => 'ASC',
    		),
			
    	)+$limit;
		  $datas = $this->paginate('Prisoner');
		
		  $this->set(array(
          'datas'          => $datas,
          
      ));
     
	}
   

}
