<?php
App::uses('Controller', 'Controller');
class PrisonersController extends AppController{
    public $layout='table';
    public $uses=array('User', 'Department', 'Designation', 'Usertype', 'State', 'District', 'Prison', 'Gender', 'Tribe', 'Country','Prisoner','Iddetail','PrisonerIdDetail');
    public function idProofAjax(){
        $this->layout   = 'ajax';
        $prisoner_id      = '';
        $condition      = array(
            'PrisonerIdDetail.is_trash'         => 0,
        );
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array('PrisonerIdDetail.prisoner_id' => $prisoner_id );
        }
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
            'conditions'    => $condition,
            'order'         => array(
                'PrisonerIdDetail.modified',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('PrisonerIdDetail');
        $this->set(array(
            'datas'         => $datas,  
            'prisoner_id'=>$prisoner_id    
        ));
    }
    public function index(){

    }
    public function indexAjax(){
        $this->layout   = 'ajax';
        $prisoner_no    = '';
        $prisoner_name  = '';
        $usertype_id    = $this->Auth->user('usertype_id');
        $condition      = array(
            'Prisoner.is_trash'         => 0,
            'Prisoner.prison_id'        => $this->Auth->user('prison_id')
        );
        if($usertype_id == Configure::read('OFFICERINCHARGE_USERTYPE')){
            $condition      += array(
                'Prisoner.is_final_save'    => 1,
            );            
        }else if($usertype_id == Configure::read('PRINCIPALOFFICER_USERTYPE')){
            $condition      += array(
                'Prisoner.is_verify'    => 1,
            );            
        } 
        if(isset($this->params['named']['prisoner_no']) && $this->params['named']['prisoner_no'] != ''){
            $prisoner_no = $this->params['named']['prisoner_no'];
            $condition      += array(
                1 => "Prisoner.prisoner_no LIKE '%$prisoner_no%'"
            );
        }
        if(isset($this->params['named']['prisoner_name']) && $this->params['named']['prisoner_name'] != ''){
            $prisoner_name = $this->params['named']['prisoner_name'];
            $condition      += array(
                2 => "Prisoner.fullname LIKE '%$prisoner_name%'"
            );            
        }                
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
            'conditions'    => $condition,
            'order'         => array(
                'Prisoner.modified',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('Prisoner');
        $this->set(array(
            'datas'         => $datas,     
            'usertype_id'   => $usertype_id,
            'prisoner_no'   => $prisoner_no,
            'prisoner_name' => $prisoner_name,
        ));
    }
    public function getExt($filename){
        $ext = substr(strtolower(strrchr($filename, '.')), 1);
        return $ext;
    }
    public function getNationName()
    {
      $this->autoRender = false;
      $country_id = $this->request->data['country_id'];
       $country=$this->Country->find('first',array(
                'conditions'=>array(
                  'Country.id'=>$country_id,
                  'Country.is_enable'=>1,
                  'Country.is_trash'=>0,
                ),
        ));
       $nationality_name=$country["Country"]["nationality_name"];
       
       echo json_encode(array("nationality_name"=>$nationality_name));
    }

    //Get Region list as per selected country START
    public function regionList()
    {
        $this->layout = 'ajax';
        //if($this->RequestHandler->isAjax()) 
        //{
            
            $this->layout = 'ajax';
            $countryid=$this->request->data('country_id');
            $selectstate = $this->State->find('list', array('fields' => array('State.name'), 'conditions' => array('State.country_id' => $countryid)));
            $this->set('selectbox',$selectstate); 
        //}
    }
    //Get Region list as per selected country END
    
    public function prisnorsIdInfo(){
        if($this->request->is(array('post','put'))){
            $uuid = $this->PrisonerIdDetail->query("select uuid() as code");
            $uuid = $uuid[0][0]['code'];
            $this->request->data['PrisonerIdDetail']['uuid'] = $uuid;
            $prisoner_id=$this->request->data['PrisonerIdDetail']['prisoner_id'];
            $id_name=$this->request->data['PrisonerIdDetail']['id_name'];
            $id_number=$this->request->data['PrisonerIdDetail']['id_number'];

            $id_name_validate=$this->PrisonerIdDetail->find('first',array(
                'conditions'=>array(
                  'PrisonerIdDetail.prisoner_id'=>$prisoner_id,
                  'PrisonerIdDetail.id_name'=>$id_name,
                ),
            ));
            $id_number_validate=$this->PrisonerIdDetail->find('first',array(
                'conditions'=>array(
                  'PrisonerIdDetail.prisoner_id'=>$prisoner_id,
                  'PrisonerIdDetail.id_number'=>$id_number,
                ),
            ));
            if(count($id_name_validate)>0){
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Saving Failed ! Id name already exist');
                    $this->redirect(array('action'=>'edit/'.$prisoner_id.'#id_proof_details'));
            }
            else{
                if(count($id_number_validate)>0){
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Saving Failed ! Id number already exist'); 
                    $this->redirect(array('action'=>'edit/'.$prisoner_id.'#id_proof_details'));
                }
                else{
                    if($this->PrisonerIdDetail->save($this->request->data)){
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Saved Successfully !');
                        $this->redirect(array('action'=>'edit/'.$prisoner_id.'#id_proof_details'));
                    }
                    else{
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Saving Failed !'); 
                    }
                }
            }
        }
    }
    public function prisnoriddetailedit(){
      $this->autoRender = false;
      $prisonerDetailId = $this->request->data['prisonerDetailId'];
       $prisoner_id_details=$this->PrisonerIdDetail->find('first',array(
                'conditions'=>array(
                  'PrisonerIdDetail.id'=>$prisonerDetailId,
                ),
        ));
       $id_name=$prisoner_id_details["PrisonerIdDetail"]["id_name"];
       $id=$prisoner_id_details["PrisonerIdDetail"]["id"];
       $id_number=$prisoner_id_details["PrisonerIdDetail"]["id_number"];
       
       echo json_encode(array("id_name"=>$id_name,"id"=>$id,"id_number"=>$id_number));

    }
    public function add(){
        if(isset($this->data['Prisoner']) && is_array($this->data['Prisoner']) && count($this->data['Prisoner'])>0){
            $uuid = $this->Prisoner->query("select uuid() as code");
            $uuid = $uuid[0][0]['code'];
            $this->request->data['Prisoner']['uuid']                = $uuid;
            $this->request->data['Prisoner']['prisoner_unique_no']  = $uuid.time().rand();
            $this->request->data["Prisoner"]["prison_id"]           = $this->Auth->user('prison_id');
            if(isset($this->data['Prisoner']['date_of_birth']) && $this->data['Prisoner']['date_of_birth'] != ''){
                $this->request->data['Prisoner']['date_of_birth']=date('Y-m-d',strtotime($this->data['Prisoner']['date_of_birth']));
            }     
            /*
             *Query for get the prison name for generate prisoner no.
             */
            $prisonData = $this->Prison->find('first', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Prison.name',
                ),
                'conditions'    => array(
                    'Prison.id' => $this->data["Prisoner"]["prison_id"],
                ),
            ));
            if(isset($prisonData['Prison']['name']) && $prisonData['Prison']['name'] != ''){
                $prisonName = $prisonData['Prison']['name'];
            }else{
                $prisonName = 'DEFAULT';
            }
            $db = ConnectionManager::getDataSource('default');
            $db->begin();            
            if($this->Prisoner->save($this->data)){
                $prisoner_id    = $this->Prisoner->id;
                $prisoner_no    = strtoupper(substr($prisonName, 0, 3)).'/'.str_pad($prisoner_id,6,'0',STR_PAD_LEFT) .'/'.date('Y');
                $fields = array(
                    'Prisoner.prisoner_no'  => "'$prisoner_no'",
                );
                $conds = array(
                    'Prisoner.id'       => $prisoner_id,
                );
                if($this->Prisoner->updateAll($fields, $conds)){
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
        $districtList = $this->District->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'District.id',
                'District.name',
            ),
            'conditions'    => array(
                'District.is_enable'      => 1,
                'District.is_trash'       => 0,
            ),
            'order'         => array(
                'District.name'
            ),
        ));
        $genderList = $this->Gender->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Gender.id',
                'Gender.name',
            ),
            'conditions'    => array(
                'Gender.is_enable'      => 1,
                'Gender.is_trash'       => 0,
            ),
            'order'         => array(
                'Gender.name'
            ),
        ));
        $countryList = $this->Country->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Country.id',
                'Country.name',
            ),
            'conditions'    => array(
                'Country.is_enable'      => 1,
                'Country.is_trash'       => 0,
            ),
            'order'         => array(
                'Country.name'
            ),
        ));
        $tribeList      = $this->Tribe->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Tribe.id',
                'Tribe.name',
            ),
            'conditions'    => array(
                'Tribe.is_enable'      => 1,
                'Tribe.is_trash'       => 0,
            ),
            'order'         => array(
                'Tribe.name'
            ),
        ));
        $this->set(array(
            'genderList'    => $genderList,
            'countryList'   => $countryList,
            'tribeList'     => $tribeList,
            'districtList'  => $districtList,
        ));
    }
    public function edit($id){


        if($this->request->is(array('post','put'))){
            $uuid = $this->Prisoner->query("select uuid() as code");
            $uuid = $uuid[0][0]['code'];
            $this->request->data['Prisoner']['uuid'] = $uuid;
            
            $first_name=$this->request->data['Prisoner']['first_name'];
             $this->request->data["Prisoner"]["prison_id"]=$this->Session->read('Auth.User.prison_id');
            $txt = strtoupper(substr($first_name, 0, 3));

            $this->request->data['Prisoner']['date_of_birth']=date('Y-m-d',strtotime($this->request->data['Prisoner']['date_of_birth']));

            if(isset($this->request->data['Prisoner']["photo"]['tmp_name']) && $this->request->data['Prisoner']["photo"]['tmp_name'] != '' && (int)$this->request->data['Prisoner']["photo"]['size'] > 0){
                  $ext        = $this->getExt($this->request->data['Prisoner']["photo"]['name']);
                  $softName       = 'profilephoto'.rand().'_'.time().'.'.$ext;
                  $pathName       = './files/prisnors/'.$softName;
                  if(move_uploaded_file($this->request->data['Prisoner']["photo"]['tmp_name'],$pathName)){  
                        $this->request->data['Prisoner']['photo'] = $softName;
                        
                            if($this->Prisoner->save($this->request->data)){
                               

                                $this->Session->write('message_type','success');
                                $this->Session->write('message','Saved Successfully !');
                                 $this->redirect(array('action'=>'edit/'.$id.'#personal_info'));
                            }
                            else{
                                $this->Session->write('message_type','error');
                                $this->Session->write('message','Saving Failed !');
                            }


                  }
              }
              else{
                $prisonerListData = $this->Prisoner->find('first', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.photo',
                    ),
                    'conditions'    => array(
                        'Prisoner.id'      => $id,
                       
                    ),
                    
                ));
                $this->request->data['Prisoner']['photo'] = $prisonerListData["Prisoner"]["photo"];
                if($this->Prisoner->save($this->request->data)){
                    

                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Saved Successfully !');
                    $this->redirect(array('action'=>'edit/'.$id.'#personal_info'));
                }
                else{
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Saving Failed !');
                }
              }
        }


        $district_id = $this->District->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'District.id',
                'District.name',
            ),
            'conditions'    => array(
                'District.is_enable'      => 1,
                'District.is_trash'       => 0,
            ),
            'order'         => array(
                'District.name'
            ),
        ));
        $genderList = $this->Gender->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Gender.id',
                'Gender.name',
            ),
            'conditions'    => array(
                'Gender.is_enable'      => 1,
                'Gender.is_trash'       => 0,
            ),
            'order'         => array(
                'Gender.name'
            ),
        ));
        $countryList = $this->Country->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Country.id',
                'Country.name',
            ),
            'conditions'    => array(
                'Country.is_enable'      => 1,
                'Country.is_trash'       => 0,
            ),
            'order'         => array(
                'Country.name'
            ),
        ));
        $tribeList      = $this->Tribe->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Tribe.id',
                'Tribe.name',
            ),
            'conditions'    => array(
                'Tribe.is_enable'      => 1,
                'Tribe.is_trash'       => 0,
            ),
            'order'         => array(
                'Tribe.name'
            ),
        ));
        $id_name      = $this->Iddetail->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Iddetail.id',
                'Iddetail.name',
            ),
            'conditions'    => array(
                'Iddetail.is_enable'      => 1,
                'Iddetail.is_trash'       => 0,
            ),
            'order'         => array(
                'Iddetail.name'
            ),
        ));
        
        $this->set(array(
            'genderList'    => $genderList,
            'countryList'   => $countryList,
            'tribeList'     => $tribeList,
            'district_id'   => $district_id,
            'id_name'       => $id_name
        ));
         $this->request->data=$this->Prisoner->findById($id);
    }
    public function verifyPrisoner(){
        $this->autoRender = false;
        if(isset($this->data['priosner_id']) && (int)$this->data['priosner_id'] != 0){
            $curDate = date('Y-m-d H:i:s');
            $fields  = array(
                'Prisoner.is_verify'        => 1,
                'Prisoner.verify_date'      => "'$curDate'",
                'Prisoner.verify_by'        => $this->Auth->user('prison_id'),
            );
            $conds   = array(
                'Prisoner.id'               => $this->data['priosner_id'],
            );
            if($this->Prisoner->updateAll($fields, $conds)){
                echo 'SUCC';
            }else{
                echo 'FAIL';
            }
        }else{
            echo 'FAIL';
        }
    }
    public function approvePrisoner(){
        $this->autoRender = false;
        if(isset($this->data['priosner_id']) && (int)$this->data['priosner_id'] != 0){
            $curDate = date('Y-m-d H:i:s');
            $fields  = array(
                'Prisoner.is_approve'        => 1,
                'Prisoner.approve_date'      => "'$curDate'",
                'Prisoner.approve_by'        => $this->Auth->user('prison_id'),
            );
            $conds   = array(
                'Prisoner.id'               => $this->data['priosner_id'],
            );
            if($this->Prisoner->updateAll($fields, $conds)){
                echo 'SUCC';
            }else{
                echo 'FAIL';
            }
        }else{
            echo 'FAIL';
        }
    }    
}