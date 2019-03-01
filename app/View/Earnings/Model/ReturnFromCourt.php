<?php
App::uses('AppModel', 'Model');
class ReturnFromCourt extends AppModel {
public $belongsTo = array(
        'CaseType' => array(
            'className'     => 'OffenceCategory',
            'foreignKey' => 'case_type'
        ),
        'Offence' => array(
            'className'     => 'Offence',
            'foreignKey' => 'offence_id'
        ),

    );
	
	public function beforeSave($options = Array()) {

      // echo '<pre>'; print_r($this->data); exit;

        if(isset($this->data['ReturnFromCourt']['nolle_prosque_doc']) && is_array($this->data['ReturnFromCourt']['nolle_prosque_doc']))
        { 
            if(isset($this->data['ReturnFromCourt']['nolle_prosque_doc']['tmp_name']) && $this->data['ReturnFromCourt']['nolle_prosque_doc']['tmp_name'] != '' && (int)$this->data['ReturnFromCourt']['nolle_prosque_doc']['size'] > 0){
                $ext        = $this->getExt($this->data['ReturnFromCourt']['nolle_prosque_doc']['name']);
                $softName       = 'nolle_prosque_doc_'.rand().'_'.time().'.'.$ext;
                $pathName       = './files/applicationtocourt/'.$softName;
                if(move_uploaded_file($this->data['ReturnFromCourt']['nolle_prosque_doc']['tmp_name'],$pathName)){
                    unset($this->data['ReturnFromCourt']['nolle_prosque_doc']);
                    $this->data['ReturnFromCourt']['nolle_prosque_doc'] = $softName;
                }else{
                    unset($this->data['ReturnFromCourt']['nolle_prosque_doc']);
                }
            }else{
                unset($this->data['ReturnFromCourt']['nolle_prosque_doc']);
            }
        }
        else 
        {
            
            unset($this->data['ReturnFromCourt']['nolle_prosque_doc']);
                 
        }
		
		/* for release bond file */
		 if(isset($this->data['ReturnFromCourt']['release_bond_doc']) && is_array($this->data['ReturnFromCourt']['release_bond_doc']))
        { 
            if(isset($this->data['ReturnFromCourt']['release_bond_doc']['tmp_name']) && $this->data['ReturnFromCourt']['release_bond_doc']['tmp_name'] != '' && (int)$this->data['ReturnFromCourt']['release_bond_doc']['size'] > 0){
                $ext        = $this->getExt($this->data['ReturnFromCourt']['release_bond_doc']['name']);
                $softName       = 'release_bond_doc_'.rand().'_'.time().'.'.$ext;
                $pathName       = './files/applicationtocourt/'.$softName;
                if(move_uploaded_file($this->data['ReturnFromCourt']['release_bond_doc']['tmp_name'],$pathName)){
                    unset($this->data['ReturnFromCourt']['release_bond_doc']);
                    $this->data['ReturnFromCourt']['release_bond_doc'] = $softName;
                }else{
                    unset($this->data['ReturnFromCourt']['release_bond_doc']);
                }
            }else{
                unset($this->data['ReturnFromCourt']['release_bond_doc']);
            }
        }
        else 
        {
            
            unset($this->data['ReturnFromCourt']['release_bond_doc']);
                 
        }
		
		/* for case amend file */
		 if(isset($this->data['ReturnFromCourt']['case_amend_doc']) && is_array($this->data['ReturnFromCourt']['case_amend_doc']))
        { 
            if(isset($this->data['ReturnFromCourt']['case_amend_doc']['tmp_name']) && $this->data['ReturnFromCourt']['case_amend_doc']['tmp_name'] != '' && (int)$this->data['ReturnFromCourt']['case_amend_doc']['size'] > 0){
                $ext        = $this->getExt($this->data['ReturnFromCourt']['case_amend_doc']['name']);
                $softName       = 'case_amend_doc_'.rand().'_'.time().'.'.$ext;
                $pathName       = './files/applicationtocourt/'.$softName;
                if(move_uploaded_file($this->data['ReturnFromCourt']['case_amend_doc']['tmp_name'],$pathName)){
                    unset($this->data['ReturnFromCourt']['case_amend_doc']);
                    $this->data['ReturnFromCourt']['case_amend_doc'] = $softName;
                }else{
                    unset($this->data['ReturnFromCourt']['case_amend_doc']);
                }
            }else{
                unset($this->data['ReturnFromCourt']['case_amend_doc']);
            }
        }
        else 
        {
            
            unset($this->data['ReturnFromCourt']['case_amend_doc']);
                 
        }
		
		/* for case amend file */
		 if(isset($this->data['ReturnFromCourt']['bail_file']) && is_array($this->data['ReturnFromCourt']['bail_file']))
        { 
			$bail_file_name = '';
			foreach($this->data['ReturnFromCourt']['bail_file'] as $fileval)
			{
				 if(isset($fileval['tmp_name']) && $fileval['tmp_name'] != '' && (int)$fileval['size'] > 0){
					$ext        = $this->getExt($fileval['name']);
					$softName       = 'bail_file_'.rand().'_'.time().'.'.$ext;
					$pathName       = './files/applicationtocourt/'.$softName;
					if(move_uploaded_file($fileval['tmp_name'],$pathName)){
						unset($fileval);
						$bail_file_name .= $softName.',';
					}else{
						unset($fileval);
					}
				}else{
					unset($fileval);
				}
				
			}
           $this->data['ReturnFromCourt']['bail_file'] = rtrim($bail_file_name,',');
        }
        else 
        {
            
            unset($this->data['ReturnFromCourt']['bail_file']);
                 
        }
		
    }
}
