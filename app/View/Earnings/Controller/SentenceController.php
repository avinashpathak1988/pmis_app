<?php
App::uses('AppController', 'Controller');
class SentenceController extends AppController {
    public $layout='table';
    public $uses=array('PrisonerSentenceDetail','Prisoner', 'PrisonerSentence', 'PrisonerSentenceCount');

    public function index($puuid = '') {

        $condition = array('Prisoner.is_trash'  => 0, 
            'Prisoner.present_status' => 1,
            'Prisoner.sentence_length !=' => '',
            'Prisoner.prisoner_type_id' => Configure::read('CONVICTED')
        );
        $condition += array('Prisoner.uuid' => $puuid );
        $data = array();
        $prisoner_uuid = '';
        $condition = array('Prisoner.is_trash'  => 0, 
                    'Prisoner.present_status' => 1,
                    'Prisoner.uuid' => $puuid,
                    //'Prisoner.sentence_length !=' => '',
                    'Prisoner.prisoner_type_id' => Configure::read('CONVICTED')
        );
        $data = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => $condition,
                'order'         => array(
                    'Prisoner.id'  => 'DESC'
                )
            ));
        $this->set(array(
            'puuid' => $puuid,
            'data'  => $data
        )); 
    }
    public function indexAjax(){ 
        $this->layout = 'ajax';
        $prisoner_uuid = '';
        $condition = array('Prisoner.is_trash'  => 0, 
                    'PrisonerSentence.is_trash' => 0,
                    //'PrisonerSentence.status' => Configure::read('Approved')
        );
        if(isset($this->params['named']['puuid']) && $this->params['named']['puuid'] != ''){
            $puuid = $this->params['named']['puuid'];
            $condition += array('Prisoner.uuid' => $puuid );
        }
        //export record list
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){

            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 
        $this->paginate = array(
            //'recursive'     => -1,
                    // 'joins' => array(
                    //     array(
                    //         'table' => 'prisoner_sentence_appeals',
                    //         'alias' => 'PrisonerSentenceAppeal',
                    //         'type' => 'left',
                    //         'conditions'=> array(
                    //             'PrisonerSentenceAppeal.case_file_id = PrisonerSentence.case_id',
                    //             'PrisonerSentenceAppeal.offence_id = PrisonerSentence.offence_id'

                    //         )
                    //     ),
                    // ), 
            'conditions'    => $condition,
            // 'fields'=> array(
            //     'PrisonerSentenceAppeal.case_file_id'
            // ),
            'order'         =>array(
                'PrisonerSentence.id' => 'ASC'
            ),            
            'limit'         => 20,
        );

        $datas  = $this->paginate('PrisonerSentence');
         // debug($priosnerSentanceAppl); 
        $this->set(array(
            'datas'             => $datas,
           
        )); 
    }

    public function getPrisonerSentence($prisoner_id,$case_file_id, $offence_id) {

         return $this->PrisonerSentenceAppeal->find('all',array(
            'fields'=> array(
                'PrisonerSentenceAppeal.case_file_id',
                'PrisonerSentenceAppeal.offence_id',
                'PrisonerSentenceAppeal.court_id',
                'PrisonerSentenceAppeal.type_of_appeallant',
                'PrisonerSentenceAppeal.courtlevel_id',
                'PrisonerSentenceAppeal.appeal_date',
                'PrisonerSentenceAppeal.appeal_status',
                'PrisonerSentenceAppeal.created',
            ),

            'conditions'=>array(
                'PrisonerSentenceAppeal.prisoner_id'=> $prisoner_id,
                'PrisonerSentenceAppeal.case_file_id'=> $case_file_id,
                'PrisonerSentenceAppeal.offence_id'=> $offence_id,
                'PrisonerSentenceAppeal.is_trash'=> 0,
            )
        ));
    }

    public function checkAppealData($case_file_id, $offence_id) {

         return $this->PrisonerSentenceAppeal->find('count',array(
            'conditions'=>array(
                'PrisonerSentenceAppeal.case_file_id'=> $case_file_id,
                'PrisonerSentenceAppeal.offence_id'=> $offence_id,
            )
        ));
    }
}