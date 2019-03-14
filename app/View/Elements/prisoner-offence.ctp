<?php 
$prisoner_type_id =$this->data['Prisoner']['prisoner_type_id'];
$caseFiles = array(); 
$judicialOfficerLevel = 'Presiding Judicial Officer';
//$isAdd = $funcall->isAccess('prisoner_admission','is_add'); 
$isAdd = 1;
if(isset($this->data['PrisonerAdmission']['id']) && !empty($this->data['PrisonerAdmission']['id']))
{
    $caseFiles = $funcall->getPrisonerCaseFiles($this->data['PrisonerAdmission']['id']);
}
//debug($caseFiles);
?>
    <!-- Court details -->
    <div class="row-fluid secondDiv widget-box" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;">
        <div class="widget-title">
            <h5>Court Details</h5>
        </div>
        <div class="widget-content">
            <div class="row-fluid secondDiv" id="case_list">
                <?php if(isset($caseFiles) && count($caseFiles) > 0)
                {
                    $c = 0; 
                    foreach($caseFiles as $caseData)
                    {
                        //debug($caseData['PrisonerCaseFile']);
                        $disableFieldClass = '';
                        if($caseData['PrisonerCaseFile']['status'] != 'Draft')
                        {
                            if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
                            {
                                if(($caseData['PrisonerCaseFile']['status'] == 'Approved') || ($caseData['PrisonerCaseFile']['login_user_id'] != $this->Session->read('Auth.User.id')))
                                    $disableFieldClass = 'field_disable';
                            }
                            else 
                            {
                                $disableFieldClass = 'field_disable';
                            }
                        }
                        $judicialOfficerLevel = $funcall->getJudicialOfficerLevel($caseData['PrisonerCaseFile']['courtlevel_id']);?>
                        <div class="span12 case_list <?php echo $disableFieldClass;?>" id="<?php echo $c;?>_case_file" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px; position:relative; padding-bottom: 10px;">
                            <?php //if($caseData['PrisonerCaseFile']['status'] == 'Draft')
                            if($disableFieldClass == '')
                            {?>
                                <button class="btn btn-add btn-remove btn-danger <?php if($i==0){echo 'hidden';}?> offence-remove" type="button" style="padding: 8px 8px;float: right;position: absolute;right: -2px;" onclick="removeCase('<?php echo $c;?>');"><span class="icon icon-minus"></span></button> 
                            <?php }
                            echo $this->Form->input('PrisonerCaseFile.'.$c.'.login_user_id',array('div'=>false,'label'=>false,'type'=>'hidden','required'=>false, 'value'=> $caseData['PrisonerCaseFile']['login_user_id'], 'id'=>$c.'_login_user_id'));
                            echo $this->Form->input('PrisonerCaseFile.'.$c.'.file_no',array('div'=>false,'label'=>false,'type'=>'hidden','required'=>false, 'value'=> $caseData['PrisonerCaseFile']['file_no'], 'id'=>$c.'_file_no'));
                            
                            echo $this->Form->input('PrisonerCaseFile.'.$c.'.id',array('div'=>false,'label'=>false,'type'=>'hidden','required'=>false, 'value'=> $caseData['PrisonerCaseFile']['id'], 'id'=>$c.'_case_id'));
                            echo $this->Form->input('PrisonerCaseFile.'.$c.'.status',array('div'=>false,'label'=>false,'type'=>'hidden','required'=>false, 'value'=> $caseData['PrisonerCaseFile']['status']));
                            ?>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Court File No<span id="<?php echo $c;?>_court_file_no_reqd"><?php echo $req;?></span> :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('PrisonerCaseFile.'.$c.'.court_file_no',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp nospace','type'=>'text','placeholder'=>"Enter Court File No", 'maxlength'=>'30', 'id'=>$c.'_court_file_no', 'value'=> $caseData['PrisonerCaseFile']['court_file_no'], 'title'=>'Court File No is required.'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Case File No. <?php echo $req; ?>:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->text('PrisonerCaseFile.'.$c.'.case_file_no',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp nospace','type'=>'text', 'placeholder'=>'CASE FILE No.','required','maxlength'=>'30','title'=>'Please enter case file no.','id'=>$c.'_case_file_no', 'value'=> $caseData['PrisonerCaseFile']['case_file_no'], 'title'=>'Case File No is required.'));?>
                                    </div>
                                </div>
                            </div> 
                            <div class="clearfix"></div>  
                            <div class="span6">
                                <div class="control-group">
                                    <?php 
                                    $court_category = $caseData['PrisonerCaseFile']['courtlevel_id'];
                                    $isValidCRB = false;
                                    if($court_category == 5 || $court_category == 6 || $court_category == 7)
                                    {
                                        $isValidCRB = true;
                                    }
                                    ?>
                                    <label class="control-label">C.R.B No<span id="<?php echo $c;?>_crb_no_reqd" <?php if($isValidCRB == false){echo 'class="hidden"';}?>><?php echo $req;?></span>:</label>
                                    <div class="controls">
                                        <?php 
                                        echo $this->Form->input('PrisonerCaseFile.'.$c.'.crb_no',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp nospace','type'=>'text', 'placeholder'=>'Enter C.R.B No.','required'=>$isValidCRB,'id'=>$c.'_crb_no', 'maxlength'=>'30', 'value'=> $caseData['PrisonerCaseFile']['crb_no'], 'title'=>'C.R.B No is required.'));?> 
                                    </div>
                                </div>
                            </div> 
                            <?php 
                            if(isset($this->data['Prisoner']['prisoner_type_id']) && $this->data['Prisoner']['prisoner_type_id'] != Configure::read('DEBTOR'))
                            {
                                $date_of_warrant = '';
                                if(isset($this->data['PrisonerCaseFile'][$c]['date_of_warrant']) && ($this->data['PrisonerCaseFile'][$c]['date_of_warrant'] != '0000-00-00'))
                                {
                                    $date_of_warrant = date('d-m-Y', strtotime($this->data['PrisonerCaseFile'][$c]['date_of_warrant']));
                                }?>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Date Of Warrant <?php echo $req; ?>:</label>
                                        <div class="controls">
                                            <?php echo $this->Form->text('PrisonerCaseFile.'.$c.'.date_of_warrant',array('div'=>false,'label'=>false,'class'=>'form-control maxCurrentDate','type'=>'text', 'placeholder'=>'Date Of Warrant','required','id'=>$c.'_date_of_warrant','readonly','value'=>$date_of_warrant, 'title'=>'Select date of warrant'));?>
                                        </div>
                                    </div>
                                </div> 
                            <?php }?>
                             
                            <div class="clearfix"></div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Court Category<?php echo $req; ?>:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('PrisonerCaseFile.'.$c.'.courtlevel_id',array('div'=>false,'label'=>false,'onChange'=>'getCourtList(this.value,'.$c.')','class'=>'form-control span11 pmis_select courtlevel_id','type'=>'select','options'=>$courtLevelList, 'empty'=>'','required','id'=>$c.'_courtlevel_id', 'selected'=> $caseData['PrisonerCaseFile']['courtlevel_id'], 'title'=>'Select Court Category'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Court Name<?php echo $req; ?>:</label>
                                    <div class="controls">
                                        <?php 
                                        $courtList = $funcall->getCourtList($caseData['PrisonerCaseFile']['courtlevel_id']);
                                        echo $this->Form->input('PrisonerCaseFile.'.$c.'.court_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select court_id','type'=>'select','options'=>$courtList, 'onChange'=>'getCourtDetails(this.value)','empty'=>'','required','id'=>$c.'_court_id', 'selected'=> $caseData['PrisonerCaseFile']['court_id'], 'title'=>'Select court name'));?>
                                    </div>
                                </div>
                            </div>
                            <?php 
                            //getCourtDetails
                            // $magisterial_id = 0;
                            // $court_detail = $funcall->getCourtData($caseData['PrisonerCaseFile']['court_id']);
                            // if(isset($court_detail))
                            // {
                            //     $court_detail = json_decode($court_detail);
                            //     if(isset($court_detail['magisterial_id']))
                            //         $magisterial_id = $court_detail['magisterial_id'];
                            // }
                            ?>
                            <div class="clearfix"></div>
                            <div class="span6">
                                <div class="control-group">
                                    <?php if(isset($this->data['Prisoner']['prisoner_type_id']) && $this->data['Prisoner']['prisoner_type_id'] == Configure::read('REMAND'))
                                    {?>
                                        <label class="control-label">Jurisdiction area<?php echo $req;?>:</label>
                                        <div class="controls">
                                            <?php
                                                echo $this->Form->input(
                                                    'PrisonerCaseFile.'.$c.'.magisterial_id',array(
                                                'div'=>false,
                                                'label'=>false,
                                                'type'=>'select',
                                                'class'=>'pmis_select magisterial_id',
                                                'options'=>$magisterialList, 'empty'=>'',
                                                'required','title'=>"Please select Jurisdiction area","id"=>$c."_magisterial_id",
                                                'selected'=> $caseData['PrisonerCaseFile']['magisterial_id']
                                                ));
                                             ?>
                                        </div>
                                    <?php }
                                    else {?>
                                        <label class="control-label">Jurisdiction area.:</label>
                                        <div class="controls">
                                            <?php
                                                echo $this->Form->input(
                                                    'PrisonerCaseFile.'.$c.'.magisterial_id',array(
                                                    'div'=>false,
                                                    'label'=>false,
                                                    'type'=>'select',
                                                    'class'=>'pmis_select',
                                                    'options'=>$magisterialList, 'empty'=>'',
                                                    'required'=>false,'title'=>"Please select Jurisdiction area","id"=>$c."_magisterial_id",
                                                    'selected'=> $caseData['PrisonerCaseFile']['magisterial_id']
                                                ));
                                             ?>
                                        </div>
                                    <?php }?>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <?php if(isset($this->data['Prisoner']['prisoner_type_id']) && $this->data['Prisoner']['prisoner_type_id'] == Configure::read('REMAND'))
                                    {?>
                                        <label class="control-label" id="<?php echo $c;?>_magistrate_level"><?php echo $judicialOfficerLevel;?><?php echo $req;?>:</label>
                                        <div class="controls" id="<?php echo $c;?>_judges">
                                            <?php 
                                            $judicial_officers = explode(',',$caseData['PrisonerCaseFile']['judicial_officer']);
                                            if(count($judicial_officers) > 0)
                                            {
                                                $j = 0; $judicial_officer_style = 'margin-top:0px';
                                                foreach($judicial_officers as $judicial_officer)
                                                {
                                                    if($j > 0)
                                                    {
                                                        $judicial_officer_style = 'margin-top:5px';
                                                    }
                                                    $j++;
                                                    echo $this->Form->text('PrisonerCaseFile.'.$c.'.judicial_officer.',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp judicial_officer','type'=>'text', 'placeholder'=>$judicialOfficerLevel,'id'=>$c.'_judicial_officer','required', 'value'=> $judicial_officer,'title'=>$judicialOfficerLevel.' is required.','style'=>$judicial_officer_style));
                                                }
                                            }
                                            ?>
                                        </div>
                                <?php }
                                    else {?>
                                        <label class="control-label" id="<?php echo $c;?>_magistrate_level"><?php echo $judicialOfficerLevel;?>:</label>
                                        <div class="controls" id="<?php echo $c;?>_judges">
                                            <?php 
                                            $judicial_officers = explode(',',$caseData['PrisonerCaseFile']['judicial_officer']);
                                            if(count($judicial_officers) > 0)
                                            {
                                                $j = 0; $judicial_officer_style = 'margin-top:0px';
                                                foreach($judicial_officers as $judicial_officer)
                                                {
                                                    if($j > 0)
                                                    {
                                                        $judicial_officer_style = 'margin-top:5px';
                                                    }
                                                    $j++;
                                                    echo $this->Form->text('PrisonerCaseFile.'.$c.'.judicial_officer.',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp judicial_officer','type'=>'text', 'placeholder'=>$judicialOfficerLevel,'id'=>$c.'_judicial_officer','required'=>false, 'value'=> $judicial_officer,'style'=>$judicial_officer_style));
                                                }
                                            }
                                            ?>
                                        </div>
                                <?php }?>
                                    <!-- Add panel of justices START -->
                                    <?php 
                                    $isRemoveJOfficer = 'hidden';
                                    if(count($judicial_officers) > 1)
                                    {
                                        $isRemoveJOfficer = '';
                                    }
                                    $isAddJOfficer = 'hidden';
                                    if(in_array($caseData['PrisonerCaseFile']['courtlevel_id'],array(9,10)))
                                    {
                                        $isAddJOfficer = '';
                                    }
                                    ?>
                                    <button class="btn btn-success <?php echo $isAddJOfficer;?> btn-add judges_btn" type="button" style="padding: 8px 8px; float:right;" id="<?php echo $c;?>_judges_btn" onclick="addJudge('<?php echo $c;?>');">
                                        <span class="icon icon-plus"></span>
                                    </button>
                                    <button class="btn btn-danger <?php echo $isRemoveJOfficer;?> btn-add judges_btn" type="button" style="padding: 8px 8px; float:right;" id="<?php echo $c;?>_judges_remove_btn" onclick="removeJudge('<?php echo $c;?>');">
                                        <span class="icon icon-minus"></span>
                                    </button>
                                    <!-- Add panel of justices END -->
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <?php 
                            $isValidHighCourtNo = false;
                            if($court_category ==8)
                            {
                                $isValidHighCourtNo = true;
                            }?>
                            <div class="span6 <?php if($isValidHighCourtNo == false){echo 'hidden';}?>" id="<?php echo $c;?>_highcourt_file_no_reqd">
                                <div class="control-group">
                                    <label class="control-label">High Court File No<?php echo $req;?>:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('PrisonerCaseFile.'.$c.'.highcourt_file_no',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp nospace','type'=>'text', 'placeholder'=>'Enter High Court File No.','required'=>$isValidHighCourtNo,'id'=>$c.'_highcourt_file_no', 'maxlength'=>'30','value'=> $caseData['PrisonerCaseFile']['highcourt_file_no'], 'title'=>' High Court File No is required'));?> 
                                    </div>
                                </div>
                            </div>
                            <!-- Offence detail START -->
                            <div class="row-fluid secondDiv widget-box" style="margin: 0;padding-bottom: 0;     border-bottom: 0;">
                                <div class="widget-title">
                                    <h5>Offence Details</h5>
                                </div>
                                <div class="widget-content" style="    padding-bottom: 0;">
                                    <!-- Multiple offence start -->
                                    <div class="row-fluid secondDiv" id="<?php echo $c;?>_offence_list">
                                        <?php 
                                        //$offencedata = $funcall->getPrisonerOffence($caseData['id']);
                                        $offencedata = $caseData['PrisonerOffence'];
                                        if(count($offencedata) > 0)
                                        {
                                            $i = 0; //debug($offencedata);
                                            foreach($offencedata as $offenceDetailData)
                                            {   
                                                $selected_sol = explode(',',$offenceDetailData['section_of_law']);
                                                $is_amended = $funcall->isAmendedOffence($offenceDetailData['id']);
                                                $is_amended_class = '';
                                                if($is_amended > 0)
                                                {
                                                    $is_amended_class = 'amended';
                                                    echo $this->Form->input('PrisonerCaseFile.'.$c.'.PrisonerOffence.'.$i.'.is_amended',array('div'=>false,'label'=>false,'type'=>'hidden','required'=>false, 'value'=> 1));
                                                }
                                                ?>
                                                <div class="span12 offence_list <?php echo $is_amended_class;?>" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px; position:relative; padding-bottom: 10px;" id="<?php echo $c;?>_<?php echo $i;?>_offence_list">
                                                    <?php //if($offenceDetailData['status'] == 'Draft')
                                                    //{?>
                                                        <button class="btn btn-add btn-remove btn-danger <?php if($i==0){echo 'hidden';}?> offence-remove" type="button" style="padding: 8px 8px;float: right;position: absolute;right: -2px;" onclick="removeOffence('<?php echo $c;?>','<?php echo $i;?>');"><span class="icon icon-minus"></span></button> 
                                                    <?php //}

                                                    echo $this->Form->input('PrisonerCaseFile.'.$c.'.PrisonerOffence.'.$i.'.id',array('div'=>false,'label'=>false,'type'=>'hidden','required'=>false, 'value'=> $offenceDetailData['id'], 'id'=>$c.'_'.$i.'_id'));

                                                    echo $this->Form->input('PrisonerCaseFile.'.$c.'.PrisonerOffence.'.$i.'.prisoner_case_file_id',array('div'=>false,'label'=>false,'type'=>'hidden','required'=>false, 'value'=> $offenceDetailData['prisoner_case_file_id']));
                                                    ?>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">
                                                                <?php if(count($offencedata) > 1)
                                                                {
                                                                    $ii = $i+1;
                                                                    echo '<span class="countno" id="'.$c.'-count-'.$i.'">Count-'.$ii.'</span>';
                                                                }
                                                                else 
                                                                {
                                                                    echo '<span class="countno" id="'.$c.'-count-'.$i.'">Offence</span>';
                                                                }?>
                                                                <?php echo $req;?>:
                                                            </label>
                                                            <div class="controls">
                                                                <?php 
                                                                echo $this->Form->input('PrisonerCaseFile.'.$c.'.PrisonerOffence.'.$i.'.offence',array('div'=>false,'label'=>false,'onChange'=>'getSOLaws(this.value,'.$c.','.$i.')','class'=>'form-control span11 pmis_select offence','type'=>'select','options'=>$offenceList, 'empty'=>'','required','id'=>$i.'_offence_id', 'selected'=>$offenceDetailData['offence'], 'title'=>'Please select Offence.'));?>
                                                            </div>
                                                        </div>
                                                    </div>  
                                                    <div class="span6">
                                                        <?php 
                                                        $sectionOfLawList = $funcall->getSectionOfLaw($offenceDetailData['offence']);
                                                        ?>
                                                        <div class="control-group">
                                                            <label class="control-label">Section Of Law
                                                                <span id="<?php echo $c.'_'.$i;?>_section_of_law_id_div" <?php if(empty($sectionOfLawList)){echo 'class="hidden"';}?>><?php echo $req; ?></span> :</label>
                                                            <div class="controls">
                                                                <?php
                                                                if(!empty($sectionOfLawList))
                                                                {
                                                                    echo $this->Form->input('PrisonerCaseFile.'.$c.'.PrisonerOffence.'.$i.'.section_of_law',array('div'=>false,'label'=>false,'multiple'=>true,'class'=>'form-control span11 pmis_select section_of_law','type'=>'select','options'=>$sectionOfLawList, 'required','id'=>$c.'_'.$i.'_section_of_law_id', 'selected'=>$selected_sol, 'title'=>'Please select section of law.'));
                                                                }
                                                                else 
                                                                {
                                                                    echo $this->Form->input('PrisonerCaseFile.'.$c.'.PrisonerOffence.'.$i.'.section_of_law',array('div'=>false,'label'=>false,'multiple'=>true,'class'=>'form-control span11 pmis_select section_of_law','type'=>'select','options'=>$sectionOfLawList, 'required'=>false, 'id'=>$c.'_'.$i.'_section_of_law_id', 'selected'=>$selected_sol, 'title'=>'Please select section of law.'));
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">Offence Category<?php echo $req; ?> :</label>
                                                            <div class="controls">
                                                                <?php 
                                                                $idname = "'admission'";
                                                                echo $this->Form->input('PrisonerCaseFile.'.$c.'.PrisonerOffence.'.$i.'.offence_category_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select offence_category','type'=>'select','options'=>$offenceCategoryList, 'empty'=>'','required','id'=>'admission_offence_category_id', 'selected'=>$offenceDetailData['offence_category_id'], 'title'=>'Please select Offence Category.'));?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">Place of Offence:</label>
                                                            <div class="controls">
                                                                <?php echo $this->Form->input('PrisonerCaseFile.'.$c.'.PrisonerOffence.'.$i.'.place_of_offence',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>"Enter Place of Offence",'required'=>false,'id'=>'place_of_offence', 'maxlength'=>'30', 'value'=>$offenceDetailData['place_of_offence']));?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="span6">
                                                       <div class="control-group">
                                                            <label class="control-label">District of offence:</label>
                                                            <div class="controls">
                                                                
                                                                <?php echo $this->Form->input('PrisonerCaseFile.'.$c.'.PrisonerOffence.'.$i.'.district_id',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select span11','type'=>'select','options'=>$allDistrictList, 'empty'=>'','required'=>false,'id'=>'district_id', 'selected'=>$offenceDetailData['district_id']));?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php $i++;
                                            }
                                        }
                                        else 
                                        {?>
                                            <div class="span12 offence_list" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px; position:relative; padding-bottom: 10px;">
                                                <button class="btn btn-add btn-remove btn-danger hidden offence-remove" type="button" style="padding: 8px 8px;float: right;position: absolute;right: -2px;"><span class="icon icon-minus"></span></button>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label"><span class="countno" id="0-count-0">Offence</span><?php echo $req;?>:</label>
                                                        <div class="controls">
                                                            <?php 
                                                            echo $this->Form->input('PrisonerCaseFile.'.$c.'.PrisonerOffence.0.offence',array('div'=>false,'label'=>false,'onChange'=>'getSOLaws(this.value,0,0)','class'=>'form-control span11 pmis_select','type'=>'select','options'=>$offenceList, 'empty'=>'','required','id'=>'0_offence_id'));?>
                                                        </div>
                                                    </div>
                                                </div>  
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Section Of Law
                                                            <span id="<?php echo $c.'_0';?>_section_of_law_id_div"></span> :</label>
                                                        <div class="controls">
                                                            <?php 
                                                            echo $this->Form->input('PrisonerCaseFile.'.$c.'.PrisonerOffence.0.section_of_law',array('div'=>false,'label'=>false,'multiple'=>true,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$sectionOfLawList, 'required'=>false,'id'=>'0_0_section_of_law_id', 'title'=>'Please select section of law.'));?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Offence Category<?php echo $req; ?> :</label>
                                                        <div class="controls">
                                                            <?php 
                                                            $idname = "'admission'";
                                                            echo $this->Form->input('PrisonerCaseFile.'.$c.'.PrisonerOffence.0.offence_category_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$offenceCategoryList, 'empty'=>'','required','id'=>'admission_offence_category_id','title'=>'Please select Offence Category.'));?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Place of offence:</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('PrisonerCaseFile.'.$c.'.PrisonerOffence.0.place_of_offence',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>"Enter Place of Offence",'required'=>false,'id'=>'place_of_offence', 'maxlength'=>'30'));?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="span6">
                                                   <div class="control-group">
                                                        <label class="control-label">District of offence:</label>
                                                        <div class="controls">
                                                            
                                                            <?php echo $this->Form->input('PrisonerCaseFile.'.$c.'.PrisonerOffence.0.district_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$allDistrictList, 'empty'=>'','required'=>false,'id'=>'district_id3'));?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }?>
                                    </div>
                                    <?php if($caseData['PrisonerCaseFile']['status'] == 'Draft')
                                    {?>
                                        <div class="clearfix"></div>
                                        <div class="">
                                            <span class="input-group-btn">
                                                <button class="btn btn-success btn-add add-count" type="button" style="padding: 8px 8px;" onclick="addOffence('<?php echo $c;?>');" id="add-count-<?php echo $c;?>">
                                                    Add Count
                                                </button>
                                            </span>
                                        </div>
                                    <?php }?>
                                    
                                    <!-- Multiple offence start -->
                                    
                                </div>
                            </div>
                            <!-- Offence detail END --> 
                        </div>
                <?php $c++; }
                }
                else if($isAdd == 1)
                {?>
                    <div class="span12 case_list" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px; position:relative; padding-bottom: 10px;">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Court File No<span id="0_court_file_no_reqd"><?php echo $req;?></span> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('PrisonerCaseFile.0.court_file_no',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp nospace','type'=>'text','placeholder'=>"Enter Court File No",'required','id'=>'0_court_file_no', 'maxlength'=>'30', 'title'=>'Court File No is required.'));?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Case File No. <?php echo $req; ?>:</label>
                                <div class="controls">
                                    <?php echo $this->Form->text('PrisonerCaseFile.0.case_file_no',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp nospace case_file_no','type'=>'text', 'placeholder'=>'CASE FILE No.','required','title'=>'Please enter case file no.', 'maxlength'=>'30'));?>
                                </div>
                            </div>
                        </div> 
                        <div class="clearfix"></div>  
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">C.R.B No<span id="0_crb_no_reqd" class="hidden"><?php echo $req;?></span>:</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('PrisonerCaseFile.0.crb_no',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp nospace','type'=>'text', 'placeholder'=>'Enter C.R.B No.','required'=>false,'id'=>'0_crb_no', 'maxlength'=>'30', 'title'=>'C.R.B No is required.'));?> 
                                </div>
                            </div>
                        </div> 
                        <?php 
                        if(isset($this->data['Prisoner']['prisoner_type_id']) && $this->data['Prisoner']['prisoner_type_id'] != Configure::read('DEBTOR'))
                        {
                            $date_of_warrant = '';
                            if(isset($this->data['PrisonerAdmission']['date_of_warrant']) && ($this->data['PrisonerAdmission']['date_of_warrant'] != '0000-00-00'))
                            {
                                $date_of_warrant = date('d-m-Y', strtotime($this->data['PrisonerAdmission']['date_of_warrant']));
                            }?>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Date Of Warrant <?php echo $req; ?>:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->text('PrisonerCaseFile.0.date_of_warrant',array('div'=>false,'label'=>false,'class'=>'form-control maxCurrentDate date_of_warrant','type'=>'text', 'placeholder'=>'Date Of Warrant','required','id'=>'date_of_warrant','readonly','value'=>$date_of_warrant));?>
                                    </div>
                                </div>
                            </div> 
                        <?php }?>
                         
                        <div class="clearfix"></div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Court Category<?php echo $req; ?>:</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('PrisonerCaseFile.0.courtlevel_id',array('div'=>false,'label'=>false,'onChange'=>'getCourtList(this.value,0)','class'=>'form-control span11 courtlevel_id pmis_select','type'=>'select','options'=>$courtLevelList, 'empty'=>'','required', 'id'=>'0_courtlevel_id', 'title'=>'Select Court Category'));?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Court Name<?php echo $req; ?>:</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('PrisonerCaseFile.0.court_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select court_id','type'=>'select','options'=>$courtList, 'onChange'=>'getCourtDetails(this.value)','empty'=>'','required','id'=>'0_court_id', 'title'=>'Select Court name'));?>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="span6">
                            <div class="control-group">
                                <?php if(isset($this->data['Prisoner']['prisoner_type_id']) && $this->data['Prisoner']['prisoner_type_id'] == Configure::read('REMAND'))
                                {?>
                                    <label class="control-label">Jurisdiction area<?php echo $req;?>:</label>
                                    <div class="controls">
                                        <?php
                                            echo $this->Form->input('PrisonerCaseFile.0.magisterial_id',array(
                                              'div'=>false,
                                              'label'=>false,
                                              'class'=>'pmis_select',
                                              'type'=>'select',
                                              'options'=>$magisterialList, 'empty'=>'',
                                              'required','title'=>"Please select Jurisdiction area","id"=>"magisterial_id"
                                            ));
                                         ?>
                                    </div>
                                <?php }
                                else {?>
                                    <label class="control-label">Jurisdiction area.:</label>
                                    <div class="controls">
                                        <?php
                                            echo $this->Form->input('PrisonerCaseFile.0.magisterial_id',array(
                                              'div'=>false,
                                              'label'=>false,
                                              'class'=>'pmis_select',
                                              'type'=>'select',
                                              'options'=>$magisterialList, 'empty'=>'',
                                              'required'=>false,'title'=>"Please select Jurisdiction area","id"=>"magisterial_id"
                                            ));
                                         ?>
                                    </div>
                                <?php }?>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <?php if(isset($this->data['Prisoner']['prisoner_type_id']) && $this->data['Prisoner']['prisoner_type_id'] == Configure::read('REMAND'))
                                {?>
                                    <label class="control-label" id="0_magistrate_level"><?php echo $judicialOfficerLevel.' '.$req;?>:</label>
                                    <div class="controls" id="0_judges">
                                        <?php echo $this->Form->text('PrisonerCaseFile.0.judicial_officer',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp','type'=>'text', 'placeholder'=>$judicialOfficerLevel,'id'=>'0_judicial_officer','required'));?>
                                    </div>
                            <?php }
                                else {?>
                                    <label class="control-label" id="0_magistrate_level"><?php echo $judicialOfficerLevel;?>:</label>
                                    <div class="controls" id="0_judges">
                                        <?php echo $this->Form->text('PrisonerCaseFile.0.judicial_officer',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp','type'=>'text', 'placeholder'=>$judicialOfficerLevel,'required'=>false,'id'=>'0_judicial_officer'));?>
                                    </div>
                            <?php }?>
                            <!-- Add panel of justices START -->
                            <?php 
                            $isRemoveJOfficer = 'hidden';
                            $isAddJOfficer = 'hidden';
                            $c = 0;
                            ?>
                            <button class="btn btn-success <?php echo $isAddJOfficer;?> btn-add judges_btn" type="button" style="padding: 8px 8px; float:right;" id="<?php echo $c;?>_judges_btn" onclick="addJudge('<?php echo $c;?>');">
                                <span class="icon icon-plus"></span>
                            </button>
                            <button class="btn btn-danger <?php echo $isRemoveJOfficer;?> btn-add judges_btn" type="button" style="padding: 8px 8px; float:right;" id="<?php echo $c;?>_judges_remove_btn" onclick="removeJudge('<?php echo $c;?>');">
                                <span class="icon icon-minus"></span>
                            </button>
                            <!-- Add panel of justices END -->
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="span6 hidden" id="0_highcourt_file_no_reqd">
                            <div class="control-group">
                                <label class="control-label">High Court File No<?php echo $req;?>:</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('PrisonerCaseFile.0.highcourt_file_no',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp nospace','type'=>'text', 'placeholder'=>'Enter High Court File No.','required'=>false,'id'=>'0_highcourt_file_no', 'maxlength'=>'30', 'title'=>' High Court File No is required.'));?> 
                                </div>
                            </div>
                        </div>
                        <!-- Offence detail START -->
                        <div class="row-fluid secondDiv widget-box" style="margin: 0;padding-bottom: 0;     border-bottom: 0;">
                            <div class="widget-title">
                                <h5>Offence Details</h5>
                            </div>
                            <div class="widget-content" style="    padding-bottom: 0;">
                                <!-- Multiple offence start -->
                                <div class="row-fluid secondDiv" id="0_offence_list">
                                    <div class="span12 offence_list" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px; position:relative; padding-bottom: 10px;">
                                        <button class="btn btn-add btn-remove btn-danger hidden offence-remove" type="button" style="padding: 8px 8px;float: right;position: absolute;right: -2px;"><span class="icon icon-minus"></span></button>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label"><span class="countno" id="0-count-0">Offence</span><?php echo $req;?>:</label>
                                                <div class="controls">
                                                    <?php 
                                                    echo $this->Form->input('PrisonerCaseFile.0.PrisonerOffence.0.offence',array('div'=>false,'label'=>false,'onChange'=>'getSOLaws(this.value,0,0)','class'=>'form-control span11 pmis_select offence','type'=>'select','options'=>$offenceList, 'empty'=>'','required','id'=>'0_0_offence_id','title'=>'Please select Offence.'));?>
                                                </div>
                                            </div>
                                        </div>  
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Section Of Law
                                                    <span class="hidden" id="0_0_section_of_law_id_div"><?php echo $req;?></span> :</label>
                                                <div class="controls">
                                                    <?php 
                                                    echo $this->Form->input('PrisonerCaseFile.0.PrisonerOffence.0.section_of_law',array('div'=>false,'label'=>false,'multiple'=>true,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$sectionOfLawList, 'required'=>false,'id'=>'0_0_section_of_law_id', 'title'=>'Please select section of law.'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Offence Category<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php 
                                                    $idname = "'admission'";
                                                    echo $this->Form->input('PrisonerCaseFile.0.PrisonerOffence.0.offence_category_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$offenceCategoryList, 'empty'=>'','required','id'=>'admission_offence_category_id','title'=>'Please select Offence Category.'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Place of offence:</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('PrisonerCaseFile.0.PrisonerOffence.0.place_of_offence',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>"Enter Place of Offence",'required'=>false,'id'=>'place_of_offence', 'maxlength'=>'30'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="span6">
                                           <div class="control-group">
                                                <label class="control-label">District of offence:</label>
                                                <div class="controls">
                                                    
                                                    <?php echo $this->Form->input('PrisonerCaseFile.0.PrisonerOffence.0.district_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$allDistrictList, 'empty'=>'','required'=>false,'id'=>'district_id3'));?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="">
                                    <span class="input-group-btn">
                                        <button class="btn btn-success offence-add-btn add-count" type="button" style="padding: 8px 8px;" onclick="addOffence('0');" id="add-count-0">
                                            <!-- <span class="icon icon-plus"></span> -->
                                            Add Count
                                        </button>
                                    </span>
                                </div>
                                <!-- Multiple offence start -->
                                
                            </div>
                        </div>
                        <!-- Offence detail END --> 
                    </div>
                <?php }
                else 
                {
                    echo 'No case file added.';
                }?>
            </div>
            <div class="">
                <?php if($isAdd == 1){?> 
                    <span class="input-group-btn">
                        <button class="btn btn-success btn-add case-btn-add" type="button" style="padding: 8px 8px;" id="add-case-file">
                            Add File
                        </button>
                    </span>
                <?php }?>
            </div>
        </div>

    </div>

<?php $deleteOffenceUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'deletePrisonerOffence'));?>
<script type="text/javascript">
var prev_sentence_capture_count = '<?php echo $total_sentence_capture_count;?>';

var addCaseURL = '<?php echo $this->Html->url(array('controller'=>'prisoners','action'=>'addCase'));?>';
var addOffenceURL = '<?php echo $this->Html->url(array('controller'=>'prisoners','action'=>'addOffence'));?>';
var deleteCaseUrl = '<?php echo $this->Html->url(array('controller'=>'prisoners','action'=>'deletePrisonerCaseFile'));?>';
$(function()
{
    //disable case modification-- START--
    $('.field_disable input').attr("disabled", "disabled");
    $('.field_disable input[type="hidden"]').removeAttr("disabled");
    $('.field_disable select').select2('destroy');
    $('.field_disable select').prop("disabled", true);
    $('.field_disable select').select2({
        placeholder: "-- Select --",
        allowClear: true
      });
    $('.field_disable .judges_btn').remove();
    $('.field_disable .add-count').remove();
    $('.field_disable .offence-remove').remove();
    //disable case modification-- END--

    //open amended offence and offence category -- START -- 
    $('.amended .offence, .amended .section_of_law, .amended .offence_category').select2('destroy');
    $('.amended .offence, .amended .section_of_law, .amended .offence_category').removeAttr("disabled");
    $('.amended .offence, .amended .section_of_law, .amended .offence_category').select2({
        placeholder: "-- Select --",
        allowClear: true
      });
    //open amended offence and offence category -- END -- 


    //Add case start 
    $(document).on('click', '.case-btn-add', function(e)
    {
        e.preventDefault();
        //disable button -- START -- 
        $('#add-case-file').html('Loading');
        $('#add-case-file').removeClass('btn-success');
        $('#add-case-file').addClass('btn-warning');
        $('#add-case-file').removeAttr( "onclick" );
        $('#add-case-file').css( "cursor","default" );
        //disable button -- END -- 
        var count = parseInt($('#case_list .case_list').length);
        var prisoner_type_id = '<?php echo $prisoner_type_id;?>';
        $.get(addCaseURL, {key:count, prisoner_type_id:prisoner_type_id}, function(res) {
            $('#case_list').append(res);
            //$('#'+count+'_offence_list .offence_list:last').hide();
        });

        setTimeout(function(){
            
            $("#"+count+"_date_of_warrant").datepicker({
                format: 'dd-mm-yyyy',
                autoclose:true,
                endDate: new Date(),
            }).on('changeDate', function (ev) {
                 $(this).datepicker('hide');
                 $(this).blur();
            });
            $("#"+count+"_courtlevel_id").select2({placeholder: "-- Select --"});
            $("#"+count+"_court_id").select2({placeholder: "-- Select --"});
            $("#"+count+"_magisterial_id").select2({placeholder: "-- Select --"});
            $("#"+count+"_0_offence_id").select2({placeholder: "-- Select --"});
            $("#"+count+"_0_section_of_law_id").select2({placeholder: "-- Select --"});
            $("#"+count+"_0_offence_category_id").select2({placeholder: "-- Select --"});
            $("#"+count+"_0_district_id").select2({placeholder: "-- Select --"});

            //Enable button -- START -- 
            $('#add-case-file').html('Add File');
            $('#add-case-file').removeClass('btn-warning');
            $('#add-case-file').addClass('btn-success');
            $('#add-case-file').attr( "onclick", "addOffence('"+case_key+"');" );
            $('#add-case-file').css( "cursor","pointer" );
            //Enable button -- END --
            setTimeout(function(){
                $('#'+count+'_offence_list .offence_list:last').show();
            }, 100);

        }, 1200);
        
    });
    //Add case start 
});
//Add offence START --
function addOffence(case_key)
{
    //disable button -- START -- 
    $('#add-count-'+case_key).html('Loading');
    $('#add-count-'+case_key).removeClass('btn-success');
    $('#add-count-'+case_key).addClass('btn-warning');
    $('#add-count-'+case_key).removeAttr( "onclick" );
    $('#add-count-'+case_key).css( "cursor","default" );
    //disable button -- END -- 
    if(case_key != '')
    {
        var count = parseInt($('#'+case_key+'_offence_list .offence_list').length);
        $.get(addOffenceURL, {key:count, case_key:case_key}, function(res) {
            $('#'+case_key+'_offence_list').append(res);
            $('#'+case_key+'_offence_list .offence_list:last').hide();
        });
        
        setTimeout(function(){ 

            if(parseInt(count) == 1)
            {
                $('#'+case_key+'-count-0').html('Count-1');
            }

            $("#"+case_key+"_"+count+"_offence_id").select2({placeholder: "-- Select --"});
            $("#"+case_key+"_"+count+"_section_of_law_id").select2({placeholder: "-- Select --"});
            $("#"+case_key+"_"+count+"_offence_category_id").select2({placeholder: "-- Select --"});
            $("#"+case_key+"_"+count+"_district_id").select2({placeholder: "-- Select --"});

            setTimeout(function(){
                $('#'+case_key+'_offence_list .offence_list:last').show();
            }, 100);
            //Enable button -- START -- 
            $('#add-count-'+case_key).html('Add Count');
            $('#add-count-'+case_key).removeClass('btn-warning');
            $('#add-count-'+case_key).addClass('btn-success');
            $('#add-count-'+case_key).attr( "onclick", "addOffence('"+case_key+"');" );
            $('#add-count-'+case_key).css( "cursor","pointer" );
            //Enable button -- END -- 
        }, 1200);
    }
}
//Add offence END --
//Remove offence START --
function removeOffence(case_key, offence_count)
{
    AsyncConfirmYesNo(
        "Are you sure want to delete count?",
        'Delete',
        'Cancel',
        function(){

            if(offence_count == 2)
            {
                $('#'+case_key+'-count-1').html("Offence");
            }
            var id = $('#'+case_key+'_'+offence_count+'_id').val();
            if(id != undefined)
            {
                var url = '<?php echo $deleteOffenceUrl;?>';
                $.post(url, {'paramId':id}, function(res) { 
                    if(res == 'SUCC'){
                        $('#'+case_key+'_'+offence_count+'_offence_list').remove();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
            else 
            {
                $('#'+case_key+'_'+offence_count+'_offence_list').remove();
            }
            //update count no in pending offence 
            var count = parseInt($('#'+case_key+'_offence_list .offence_list').length);
            
            if(count == 1)
            {
                $('#'+case_key+'-count-0').html('Offence');
            }
            else 
            {
                $('#'+case_key+'_offence_list .offence_list .countno').each(function(i)
                {
                    var id = case_key+"-count-"+i;
                    var ii = parseInt(i)+1;
                    // Give the ID to the count
                    $(this).attr("id", id);
                    $('#'+id).html('Count-'+ii)
                });
                // for(var i=0; i<count; i++)
                // {

                // }
            }
        },
        function(){}
    );
}
//Remove offence END --
//Remove offence START --
function removeCase(case_key)
{
    AsyncConfirmYesNo(
        "Are you sure want to delete case file?",
        'Delete',
        'Cancel',
        function(){
            
            var id = $('#'+case_key+'_case_id').val();
            if(id != undefined)
            {
                $.post(deleteCaseUrl, {'paramId':id}, function(res) { 
                    if(res == 'SUCC'){
                        $('#'+case_key+'_case_file').remove();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
            else 
            {
                $('#'+case_key+'_case_file').remove();
            }
        },
        function(){}
    );
}
//Remove offence END --
// //Add Panel Of justices START -- 
// function addJudge(cnt)
// {
//     var res = $('#'+cnt+'_judges input:first').clone();
//     $('#'+cnt+'_judges').append(res);
//     $('#'+cnt+'_judges input:last').val('');
//     $('#'+cnt+'_judges input:last').css('margin-top','5px');
//     var count = parseInt($('#'+cnt+'_judges input').length);
//     if(count > 1)
//     {
//         $('#'+cnt+'_judges_remove_btn').removeClass('hidden');
//     }
// }
// //Add Panel Of justices END -- 
//Remove Panel Of justices START -- 
function removeJudge(cnt)
{
    $('#'+cnt+'_judges input:last').remove();
    var count = parseInt($('#'+cnt+'_judges input').length);
    if(count == 1)
    {
        $('#'+cnt+'_judges_remove_btn').addClass('hidden');
    }
}
//Remove Panel Of justices END --
</script>

