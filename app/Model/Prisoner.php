<?php
App::uses('AppModel','Model');

class Prisoner extends AppModel{
    public $belongsTo = array(
        'Prison' => array(
            'className' 	=> 'Prison',
            'foreignKey' 	=> 'prison_id',
        ),
        'Gender' => array(
            'className'     => 'Gender',
            'foreignKey'    => 'gender_id',
        ),
        'Country' => array(
            'className' => 'Country',
            'foreignKey' => 'country_id',
        ),
        'State' => array(
            'className' => 'State',
            'foreignKey' => 'state_id',
        ),
        'District' => array(
            'className' => 'District',
            'foreignKey' => 'district_id',
        ),
        'Occupation' => array(
            'className' => 'Occupation',
            'foreignKey' => 'occupation_id',
        ), 
        'LevelOfEducation' => array(
            'className' => 'LevelOfEducation',
            'foreignKey' => 'level_of_education_id',
        ),
         'Skill' => array(
            'className' => 'Skill',
            'foreignKey' => 'skill_id',
        ),
         'Religion' => array(
            'className' => 'ApparentReligion',
            'foreignKey' => 'apparent_religion_id',
        ),
         'Build' => array(
            'className' => 'Build',
            'foreignKey' => 'build_id',
        ),
        'Face' => array(
            'className' => 'Face',
            'foreignKey' => 'face_id',
        ), 
        'Eye' => array(
            'className' => 'Eye',
            'foreignKey' => 'eyes_id',
        ),  
        'Mouth' => array(
            'className' => 'Mouth',
            'foreignKey' => 'mouth_id',
        ),  
        'Speech' => array(
            'className' => 'Speech',
            'foreignKey' => 'speech_id',
        ),  
        'Teeth' => array(
            'className' => 'Teeth',
            'foreignKey' => 'teeth_id',
        ), 
        'Lip' => array(
            'className' => 'Lip',
            'foreignKey' => 'lips_id',
        ),
        'Ear' => array(
            'className' => 'Ear',
            'foreignKey' => 'ears_id',
        ),
        'Hair' => array(
            'className' => 'Hair',
            'foreignKey' => 'hairs_id',
        ),
        'MaritalStatus' => array(
            'className' => 'MaritalStatus',
            'foreignKey' => 'marital_status_id',
        ),
        'Ward' => array(
            'className' => 'Ward',
            'foreignKey' => 'assigned_ward_id',
        ),
        'WardCell' => array(
            'className' => 'WardCell',
            'foreignKey' => 'assigned_ward_cell_id',
        ),
        'PlaceOfBirthDistrict' => array(
            'className' => 'District',
            'foreignKey' => 'birth_district_id',
        ),
        'PlaceOfBirthCounty' => array(
            'className' => 'County',
            'foreignKey' => 'county_id',
        ),
        'PlaceOfBirthSubCounty' => array(
            'className' => 'SubCounty',
            'foreignKey' => 'sub_county_id',
        ),
        'PlaceOfBirthParish' => array(
            'className' => 'Parish',
            'foreignKey' => 'parish_id',
        ),
        'PlaceOfBirthVillage' => array(
            'className' => 'Village',
            'foreignKey' => 'village_id',
        )
    );
    public $hasOne = array(
        'PrisonerAdmissionDetail' => array(
            'className'     => 'PrisonerAdmissionDetail',
            'foreignKey'    => 'prisoner_id',
        ),
        'PrisonerAdmission' => array(
            'className'     => 'PrisonerAdmission',
            'foreignKey'    => 'prisoner_id',
        )
    );
    public $hasMany = array(
        'PrisonerIdDetail' => array(
            'className'     => 'PrisonerIdDetail',
            'foreignKey'    => 'prisoner_id',
            'conditions' => array('is_trash' => 0)
        ),
        'PrisonerKinDetail' => array(
            'className'     => 'PrisonerKinDetail',
            'foreignKey'    => 'prisoner_id',
            'conditions' => array('is_trash' => 0)
        ),
        'PrisonerChildDetail' => array(
            'className'     => 'PrisonerChildDetail',
            'foreignKey'    => 'prisoner_id',
            'conditions' => array('is_trash' => 0)
        ),
        // 'PrisonerOffenceDetail' => array(
        //     'className'     => 'PrisonerOffenceDetail',
        //     'foreignKey'    => 'prisoner_id',
        //     'conditions' => array('is_trash' => 0)
        // ),
        // 'PrisonerOffenceCount' => array(
        //     'className'     => 'PrisonerOffenceCount',
        //     'foreignKey'    => 'prisoner_id',
        //     'conditions' => array('is_trash' => 0)
        // ),
        'PrisonerSpecialNeed' => array(
            'className'     => 'PrisonerSpecialNeed',
            'foreignKey'    => 'prisoner_id',
            'conditions' => array('is_trash' => 0)
        ),
        'PrisonerRecaptureDetail' => array(
            'className'     => 'PrisonerRecaptureDetail',
            'foreignKey'    => 'prisoner_id',
            'conditions' => array('is_trash' => 0)
        ),
        'PrisonerSentenceDetail' => array(
            'className'     => 'PrisonerSentenceDetail',
            'foreignKey'    => 'prisoner_id',
            'conditions' => array('is_trash' => 0)
        ),
        'MedicalCheckupRecord' => array(
            'className'     => 'MedicalCheckupRecord',
            'foreignKey'    => 'prisoner_id',
            'conditions' => array('is_trash' => 0)
        ),
        'MedicalDeathRecord' => array(
            'className'     => 'MedicalDeathRecord',
            'foreignKey'    => 'prisoner_id',
            'conditions' => array('is_trash' => 0)
        ),
        'MedicalSeriousIllRecord' => array(
            'className'     => 'MedicalSeriousIllRecord',
            'foreignKey'    => 'prisoner_id',
            'conditions' => array('is_trash' => 0)
        ),
        'MedicalSickRecord' => array(
            'className'     => 'MedicalSickRecord',
            'foreignKey'    => 'prisoner_id',
            'conditions' => array('is_trash' => 0)
        ),
        'StagePromotion' => array(
            'className'     => 'StagePromotion',
            'foreignKey'    => 'prisoner_id',
            'conditions' => array('is_trash' => 0)
        ),
        'StageDemotion' => array(
            'className'     => 'StageDemotion',
            'foreignKey'    => 'prisoner_id',
            'conditions' => array('is_trash' => 0)
        ),
        'StageReinstatement' => array(
            'className'     => 'StageReinstatement',
            'foreignKey'    => 'prisoner_id',
            'conditions' => array('is_trash' => 0)
        ),
        'DisciplinaryProceeding' => array(
            'className'     => 'DisciplinaryProceeding',
            'foreignKey'    => 'prisoner_id',
            'conditions' => array('is_trash' => 0)
        ),
        'InPrisonPunishment' => array(
            'className'     => 'InPrisonPunishment',
            'foreignKey'    => 'prisoner_id',
            'conditions' => array('is_trash' => 0)
        ),
        'Property' => array(
            'className'     => 'Property',
            'foreignKey'    => 'prisoner_id',
            'conditions' => array('is_trash' => 0)
        ),
        'PrisonerSentence' => array(
            'className'     => 'PrisonerSentence',
            'foreignKey'    => 'prisoner_id',
            'conditions' => array('is_trash' => 0)
        ),
        'PrisonerCaseFile' => array(
            'className'     => 'PrisonerCaseFile',
            'foreignKey'    => 'prisoner_id',
            'conditions' => array('is_trash' => 0)
        )
        // 'PrisonerOffence' => array(
        //     'className'     => 'PrisonerOffence',
        //     'foreignKey'    => 'prisoner_id',
        //     'conditions' => array('is_trash' => 0)
        // ),
    );
	public $virtualFields = array(
		'fullname' => 'CONCAT(Prisoner.first_name, " ", Prisoner.middle_name, " ", Prisoner.last_name)',
        'age' => 'TIMESTAMPDIFF(YEAR, Prisoner.date_of_birth, CURDATE())',
        'age_on_admission' => 'TIMESTAMPDIFF(YEAR, Prisoner.date_of_birth, Prisoner.doa)'
	);    
	public $validate = array(
		'first_name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'First Name is required !',
			),
		),	
		// 'last_name' => array(
		// 	'notBlank' => array(
		// 		'rule' => array('notBlank'),
		// 		'message' => 'surname is required !',
		// 	),
		// ),
		// 'father_name' => array(
		// 	'notBlank' => array(
		// 		'rule' => array('notBlank'),
		// 		'message' => 'Father name is required !',
		// 	),
		// ),
		'date_of_birth' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Date of birth is required !',
			),
		),
		// 'place_of_birth' => array(
		// 	'notBlank' => array(
		// 		'rule' => array('notBlank'),
		// 		'message' => 'Place of is required !',
		// 	),
		// ),		
		'gender_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Gender is required !',
			),
            'rule1' => array(
                'rule' => array('numeric'),
                'message' => 'Gender should be numeric !',
            ),            
		),
		'country_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Country is required !',
			),
            'rule1' => array(
                'rule' => array('numeric'),
                'message' => 'Country should be numeric !',
            ),            
		),
        'state_id' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Region is required !',
            ),
            'rule1' => array(
                'rule' => array('numeric'),
                'message' => 'Region should be numeric !',
            ),            
        ),
		// 'tribe_id' => array(
		// 	'notBlank' => array(
		// 		'rule' => array('notBlank'),
		// 		'message' => 'Tribe is required !',
		// 	),
  //           'rule1' => array(
  //               'rule' => array('numeric'),
  //               'message' => 'Tribe should be numeric !',
  //           ),            
		// ),	
        // 'classification_id' => array(
        //     'notBlank' => array(
        //         'rule' => array('notBlank'),
        //         'message' => '  Classification is required !',
        //     ),
        //     'rule1' => array(
        //         'rule' => array('numeric'),
        //         'message' => 'Classification should be numeric !',
        //     ),            
        // ),  													
        'photo'=>array(
            'rule1'=>array(
                'rule'    => 'validateEmptyPhoto',
                'message' => 'Please Upload Photo'
            ),        
            'rule2'=>array(
                'rule'    => 'validateExtPhoto',
                'message' => 'Please upload (jpg,jpeg,png,gif) type photo'
            ),
            'rule3'=>array(
                'rule'    => 'validateSizePhoto',
                'message' => 'Please upload valid photo'
            ),  
        ),	
        'id_name' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Id proof is required !',
            ),
            'rule1' => array(
                'rule' => array('numeric'),
                'message' => 'Id proof should be numeric !',
            ),            
        ),  
        'id_number' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Id proof number is required !',
            )         
        ),  										
	);
    
	public function beforeSave($options = Array()) {

       //echo '<pre>'; print_r($this->data); exit;

        if(isset($this->data['Prisoner']['photo']) && is_array($this->data['Prisoner']['photo']))
        { 
            if(isset($this->data['Prisoner']['photo']['tmp_name']) && $this->data['Prisoner']['photo']['tmp_name'] != '' && (int)$this->data['Prisoner']['photo']['size'] > 0){
                $ext        = $this->getExt($this->data['Prisoner']['photo']['name']);
                $softName       = 'profilephoto_'.rand().'_'.time().'.'.$ext;
                $pathName       = './files/prisnors/'.$softName;
                if(move_uploaded_file($this->data['Prisoner']['photo']['tmp_name'],$pathName)){
                    unset($this->data['Prisoner']['photo']);
                    $this->data['Prisoner']['photo'] = $softName;
                }else{
                    unset($this->data['Prisoner']['photo']);
                }
            }else{
                unset($this->data['Prisoner']['photo']);
            }
        }
        else 
        {
            if(isset($this->request->data['Prisoner']['transfer_id']) && $this->request->data['Prisoner']['transfer_id']!=''){

            }
            else if(isset($this->data['Prisoner']['is_ext']) && $this->data['Prisoner']['is_ext'] == 1){ 

            } 
            else 
            {
                unset($this->data['Prisoner']['photo']);
            }         
        }
        //save left photo 
        if(isset($this->data['Prisoner']['left_photo']) && is_array($this->data['Prisoner']['left_photo']))
        { 
            if(isset($this->data['Prisoner']['left_photo']['tmp_name']) && $this->data['Prisoner']['left_photo']['tmp_name'] != '' && (int)$this->data['Prisoner']['left_photo']['size'] > 0){
                $ext        = $this->getExt($this->data['Prisoner']['left_photo']['name']);
                $softName       = 'profile_left_photo_'.rand().'_'.time().'.'.$ext;
                $pathName       = './files/prisnors/'.$softName;
                if(move_uploaded_file($this->data['Prisoner']['left_photo']['tmp_name'],$pathName)){
                    unset($this->data['Prisoner']['left_photo']);
                    $this->data['Prisoner']['left_photo'] = $softName;
                }else{
                    unset($this->data['Prisoner']['left_photo']);
                }
            }else{
                unset($this->data['Prisoner']['left_photo']);
            }
        }
        else 
        {
            if(isset($this->request->data['Prisoner']['transfer_id']) && $this->request->data['Prisoner']['transfer_id']!=''){

            }
            else if(isset($this->data['Prisoner']['is_ext']) && $this->data['Prisoner']['is_ext'] == 1){ 

            } 
            else 
            {
                unset($this->data['Prisoner']['left_photo']);
            }         
        }

        //save right photo 
        if(isset($this->data['Prisoner']['right_photo']) && is_array($this->data['Prisoner']['right_photo']))
        { 
            if(isset($this->data['Prisoner']['right_photo']['tmp_name']) && $this->data['Prisoner']['right_photo']['tmp_name'] != '' && (int)$this->data['Prisoner']['right_photo']['size'] > 0){
                $ext        = $this->getExt($this->data['Prisoner']['right_photo']['name']);
                $softName       = 'profile_right_photo_'.rand().'_'.time().'.'.$ext;
                $pathName       = './files/prisnors/'.$softName;
                if(move_uploaded_file($this->data['Prisoner']['right_photo']['tmp_name'],$pathName)){
                    unset($this->data['Prisoner']['right_photo']);
                    $this->data['Prisoner']['right_photo'] = $softName;
                }else{
                    unset($this->data['Prisoner']['right_photo']);
                }
            }else{
                unset($this->data['Prisoner']['right_photo']);
            }
        }
        else 
        {
            if(isset($this->request->data['Prisoner']['transfer_id']) && $this->request->data['Prisoner']['transfer_id']!=''){

            }
            else if(isset($this->data['Prisoner']['is_ext']) && $this->data['Prisoner']['is_ext'] == 1){ 

            } 
            else 
            {
                unset($this->data['Prisoner']['right_photo']);
            }         
        }

        //save repatriation order
        if(isset($this->data['Prisoner']['repatriation_order']) && is_array($this->data['Prisoner']['repatriation_order']))
        { 
            if(isset($this->data['Prisoner']['repatriation_order']['tmp_name']) && $this->data['Prisoner']['repatriation_order']['tmp_name'] != '' && (int)$this->data['Prisoner']['repatriation_order']['size'] > 0){
                $ext        = $this->getExt($this->data['Prisoner']['repatriation_order']['name']);
                $softName       = 'repatriation_order_'.rand().'_'.time().'.'.$ext;
                $pathName       = './files/prisnors/'.$softName;
                if(move_uploaded_file($this->data['Prisoner']['repatriation_order']['tmp_name'],$pathName)){
                    unset($this->data['Prisoner']['repatriation_order']);
                    $this->data['Prisoner']['repatriation_order'] = $softName;
                }else{
                    unset($this->data['Prisoner']['repatriation_order']);
                }
            }else{
                unset($this->data['Prisoner']['repatriation_order']);
            }
        }
        else 
        {
            if(isset($this->request->data['Prisoner']['transfer_id']) && $this->request->data['Prisoner']['transfer_id']!=''){

            }
            else if(isset($this->data['Prisoner']['is_ext']) && $this->data['Prisoner']['is_ext'] == 1){ 

            } 
            else 
            {
                unset($this->data['Prisoner']['repatriation_order']);
            }         
        }
    }
    public function validateEmptyPhoto(){

        if(isset($this->data['Prisoner']['photo']) && is_string($this->data['Prisoner']['photo']))
        {
            return true;
        }
        if(isset($this->data['Prisoner']['photo']['tmp_name'])){
            if($this->data['Prisoner']['photo']['tmp_name'] == '')
                return false;
            else
                return true;
        }else{
            return true;
        }       
    } 
    public function validateExtPhoto(){ 
        
        if(isset($this->data['Prisoner']['photo']['tmp_name']) && $this->data['Prisoner']['photo']['tmp_name'] != '' && (int)$this->data['Prisoner']['photo']['size'] > 0){
            $fileExt            = $this->getExt($this->data['Prisoner']['photo']['name']);
            if(strtolower($fileExt) != 'jpg' && strtolower($fileExt) != 'jpeg' && strtolower($fileExt) != 'png' && strtolower($fileExt) != 'gif'){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }   
    }
    public function validateSizePhoto(){
        if(isset($this->data['Prisoner']['photo']['tmp_name']) && $this->data['Prisoner']['photo']['tmp_name'] != ''){
            $fileSize    = $this->data['Prisoner']['photo']['size'];
            if($fileSize == 0){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }       
    }  
    //
    // function afterSave($created) {
    //     if($created) {
    //          unset($this->request->data['Prisoner']);
    //     }
    // }  
}
?>