<?php //debug($this->data['Prisoner']['id']);
//$appeal_file_no = $funcall->getAppealCaseFile($this->data['Prisoner']['id']);
//debug($appeal_file_no);

$caseFiles = $funcall->getPrisonerFileData($prisonerData['Prisoner']['id']);
$offences = $funcall->getPrisonerOffenceData($prisonerData['Prisoner']['id']);
$highCourtfileNo = $funcall->getPrisonerHighCourtFileNo($prisonerData['Prisoner']['id']);
?>
<div>
    <div class="span12 petition_hide" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px; position:relative; padding-bottom: 10px;">
        <div class="span6">
                <div class="control-group">
                    <label class="control-label">Prisoner Name :</label>
                    <div class="controls">
                        <?php echo $this->Form->input('personal_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','readonly','value'=>$prisonerData['Prisoner']['first_name']));?>
                    </div>
                </div>
            </div>
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">Prisoner Number :</label>
                    <div class="controls">
                        <?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Name Of Child','required','id'=>'prisoner_no',  'readonly','value'=>$prisonerData['Prisoner']['prisoner_no']));?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div> 
       <div class="span6">
            <div class="control-group">
                <label class="control-label">Petiton Name<?php echo $req;?>:</label>
                <div class="controls">
                    <?php 
                    echo $this->Form->input('petition_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','required','placeholder' => 'Enter Petiton Name', 'title'=>'Enter Petiton Name'));?>
                </div>
            </div>
        </div> 

        <div class="span6">
            <div class="control-group">
                <label class="control-label">Date of Submission of Petition<?php echo $req;?>:</label>
                <div class="controls">
                    <?php echo $this->Form->input('petition_date',array('div'=>false,'label'=>false,'class'=>'form-control span11 maxCurrentDate','type'=>'text','placeholder'=>"Enter petition date",'required'=>false, 'readonly', 'default'=>date('d-m-Y')));?>
                </div>
            </div>
        </div> 
        
        <div class="clearfix"></div>
        <div class="span6">
            <div class="control-group">
                <label class="control-label">Court Level:</label>
                <div class="controls">
                    <?php $cid = "'petition'";
                    echo $this->Form->input('courtlevel_id',array('div'=>false,'label'=>false,'onChange'=>'getCourtList(this.value,'.$cid.')','class'=>'form-control span11 pmis_select','type'=>'select','options'=>$courtLevelList, 'empty'=>'','required','id'=>'petition_courtlevel_id', 'title'=>'Select Court Level'));?>
                </div>
            </div>
        </div>

        <div class="span6">
            <div class="control-group">
                <label class="control-label">Court Name
                    <span id="admission_section_of_law_id_div"></span> :</label>
                <div class="controls">
                    <?php 
                    $courtList = array();
                    //$courtList = $funcall->getCourtList($caseData['PrisonerCaseFile']['courtlevel_id']);
                    echo $this->Form->input('court_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select court_id','type'=>'select','options'=>$courtList, 'empty'=>'','id'=>'petition_court_id','title'=>'Select court name'));?>
                </div>
            </div>
        </div>
        
        <div class="clearfix"></div>
        <div class="span6">
           <div class="control-group">
                <label class="control-label">Case File no:</label>
                <div class="controls">
                    <?php  echo $caseFiles;
                   
                       //echo $this->Form->input('petition_case_file_no',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','onChange'=>'getPetitionOffence(this.value)','type'=>'select','options'=>$case_file_no, 'empty'=>'','required', 'title'=>'Case File is required.', 'required' => false)); ?>
                </div>
            </div>
        </div>
        <?php if($highCourtfileNo != 'N/A')
        {?>
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">High Court File No:</label>
                    <div class="controls">
                        <?php //echo $this->Form->input('petition_court_file_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>"Enter High Court File No",'required'=>false));?>
                    </div>
                </div>
            </div>
        <?php }?>
        
        <div class="clearfix"></div>
        <div class="span6">
            <div class="control-group">
                <label class="control-label">Offence:</label>
                <div class="controls">
                    <?php echo $offences;
                    // echo implode(", ", $offenceIdList);
                   // echo $this->Form->input('offence_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$offenceIdList, 'empty'=>'','required'));?>
                </div>
            </div>
        </div> 
    </div>
</div>
<script type="text/javascript">
function getPetitionOffence(id){
  var strURL = '<?php echo $this->Html->url(array('controller'=>'Courtattendances','action'=>'getPetitionOffence'));?>/'+id;
  $.post(strURL,{},function(data){
      if(data) { 
          $('#PrisonerPetitionOffenceId').html(data);
      }
      else
      {
          alert("Error...");  
      }
  });
}
</script>