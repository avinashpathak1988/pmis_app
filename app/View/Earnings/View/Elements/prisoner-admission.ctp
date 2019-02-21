<?php 
$judicialOfficerLevel = 'Presiding Judicial Officer';
if(isset($this->data['PrisonerCaseFile'][0]['courtlevel_id']) && !empty($this->data['PrisonerCaseFile'][0]['courtlevel_id']))
{
    $judicialOfficerLevel = $funcall->getJudicialOfficerLevel($caseData['PrisonerCaseFile']['courtlevel_id']);
}
?>
<div class="" style="padding-bottom: 14px;">
    <div class="row-fluid secondDiv widget-box" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;">
        <div class="widget-title">
            <h5>Admission Details</h5>
        </div>
        <div class="widget-content">


            
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">Personal Number<?php echo $req; ?> :</label>
                    <div class="controls">
                        <?php echo $this->Form->input('personal_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','readonly','value'=>$this->request->data['Prisoner']['personal_no']));?>
                    </div>
                </div>
            </div>
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">Prisoner Number<?php echo $req; ?> :</label>
                    <div class="controls">
                        <?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Name Of Child','required','id'=>'prisoner_no',  'readonly','value'=>$this->request->data['Prisoner']['prisoner_no']));?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div> 
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">Prison Station<?php echo $req; ?> :</label>
                    <div class="controls">
                        <?php 
                        echo $this->Form->input('prisoner_station',array(
                            'type'=>'hidden',
                            'class'=>'prison_station',
                            'value'=>$prison_id
                          )); 
                        echo $this->Form->input('prison_station_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Prison Station','required','readonly','value'=>$prison_name, 'id'=>'prison_station_name'));?>
                    </div>
                </div>
            </div> 
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">No of Previous Conviction <?php// echo $req; ?>:</label>
                    <div class="controls">
                        <?php echo $this->Form->input('no_of_prev_conviction',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>"Enter No of Previous Conviction",'required'=>false,'id'=>'no_of_prev_conviction', 'readonly', 'value'=>$prev_conviction));?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Court details -->
    <div class="row-fluid secondDiv widget-box" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;">
        <div class="widget-title">
            <h5>Court Details</h5>
        </div>
        <?php echo $this->Form->input('PrisonerCaseFile.0.id',array('div'=>false,'label'=>false,'type'=>'hidden'));?>
        <div class="widget-content">
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">Court File No<span id="court_file_no_reqd"><?php echo $req;?></span> :</label>
                    <div class="controls">
                        <?php echo $this->Form->input('PrisonerCaseFile.0.court_file_no',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp nospace','type'=>'text','placeholder'=>"Enter Court File No",'required'=>false, 'id'=>'0_court_file_no', 'maxlength'=>'30', 'title'=>'Court File No is required.'));?>
                    </div>
                </div>
            </div>
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">Civil Suit No. :</label>
                    <div class="controls">
                        <?php echo $this->Form->text('PrisonerCaseFile.0.case_file_no',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp nospace','type'=>'text', 'placeholder'=>'Civil Suit No.','required'=>false,'id'=>'0_case_file_no', 'maxlength'=>'30', 'title'=>'Civil Suit No. is required.'));?>
                    </div>
                </div>
            </div> 
            <div class="clearfix"></div>  
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">C.R.B No<span id="crb_no_reqd" class="hidden"><?php echo $req;?></span>:</label>
                    <div class="controls">
                        <?php echo $this->Form->input('crb_no',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp nospace','type'=>'text', 'placeholder'=>'Enter C.R.B No.','required'=>false,'id'=>'0_crb_no', 'maxlength'=>'30', 'title'=>'C.R.B No is required.'));?> 
                    </div>
                </div>
            </div> 
             
            <div class="clearfix"></div>
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">Court Category<?php echo $req; ?>:</label>
                    <div class="controls">
                        <?php $debtor_id = "'debtor'";
                        echo $this->Form->input('PrisonerCaseFile.0.courtlevel_id',array('div'=>false,'label'=>false,'onChange'=>'getCourtList(this.value, '.$debtor_id.')','class'=>'form-control span11 court','type'=>'select','options'=>$courtLevelList, 'empty'=>'-- Select Court Category --','required','id'=>'0_courtlevel_id', 'title'=>'Select Court Category'));?>
                    </div>
                </div>
            </div>
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">Court Name<?php echo $req; ?>:</label>
                    <div class="controls">
                        <?php echo $this->Form->input('PrisonerCaseFile.0.court_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$courtList, 'onChange'=>'getCourtDetails(this.value)','empty'=>'-- Select Court Name --','required','id'=>'0_court_id', 'title'=>'Select court name'));?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">Jurisdiction area.:</label>
                    <div class="controls">
                        <?php
                            echo $this->Form->input('PrisonerCaseFile.0.magisterial_id',array(
                              'div'=>false,
                              'label'=>false,
                              'type'=>'select',
                              'options'=>$magisterialList, 'empty'=>'-- Select Jurisdiction Area --',
                              'required'=>false,'title'=>"Please select Jurisdiction area","id"=>"0_magisterial_id"
                            ));
                         ?>
                    </div>
                </div>
            </div>
            <div class="span6">
                <div class="control-group">
                    <label class="control-label" id="debtor_magistrate_level"><?php echo $judicialOfficerLevel;?>:</label>
                    <div class="controls" id="debtor_judges">
                        <?php 
                        $isRemoveJOfficer = 'hidden';
                        if(count($judicial_officers) > 1)
                        {
                            $isRemoveJOfficer = '';
                        }
                        $isAddJOfficer = 'hidden';
                        if(in_array($debtor_courtlevel_id,array(9,10)))
                        {
                            $isAddJOfficer = '';
                        }
                        //debug($judicial_officers); exit;
                        if(count($judicial_officers) > 0)
                        {
                            $j = 0; $judicial_officer_style = 'margin-top:0px';
                            foreach($judicial_officers as $judicial_officer)
                            {
                                if($j > 0)
                                {
                                    $judicial_officer_style = 'margin-top:5px';
                                }
                                
                                echo $this->Form->text('Debtor.PrisonerCaseFile.'.$j.'.judicial_officer.',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp judicial_officer','type'=>'text', 'placeholder'=>$judicialOfficerLevel,'id'=>$c.'_judicial_officer', 'value'=> $judicial_officer,'title'=>$judicialOfficerLevel.' is required.','style'=>$judicial_officer_style));
                                $j++;
                            }
                        }
                        else 
                        {
                            echo $this->Form->text('Debtor.PrisonerCaseFile.0.judicial_officer.',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp','type'=>'text', 'placeholder'=>'Judicial Officer','required'=>false,'required'=>false,'id'=>'debtor_judicial_officer'));
                        }
                        ?>
                    </div>
                    <button class="btn btn-success <?php echo $isAddJOfficer;?> btn-add judges_btn" type="button" style="padding: 8px 8px; float:right;" id="debtor_judges_btn" onclick="addJudge('debtor');">
                        <span class="icon icon-plus"></span>
                    </button>
                    <button class="btn btn-danger <?php echo $isRemoveJOfficer;?> btn-add judges_btn" type="button" style="padding: 8px 8px; float:right;" id="debtor_judges_remove_btn" onclick="removeJudge('debtor');">
                        <span class="icon icon-minus"></span>
                    </button>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="span6 hidden" id="0_highcourt_file_no_reqd">
                <div class="control-group">
                    <label class="control-label">High Court File No<?php echo $req;?>:</label>
                    <div class="controls">
                        <?php echo $this->Form->input('PrisonerCaseFile.0.highcourt_file_no',array('div'=>false,'label'=>false,'class'=>'form-control alphanumericsp nospace','type'=>'text', 'placeholder'=>'Enter High Court File No.','required'=>false,'id'=>'0_highcourt_file_no', 'maxlength'=>'30'));?> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
