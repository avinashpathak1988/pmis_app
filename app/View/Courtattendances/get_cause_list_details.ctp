<?php
if(isset($data) && is_array($data) && count($data)>0){
?>
<div class="clearfix"></div> 
<div class="span6">
   <div class="control-group">
        <label class="control-label">Next Hearing Date<?php echo $req; ?> :</label>
        <div class="controls">
            <?php 
            echo $this->Form->input('Courtattendance.attendance_date',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','readonly', 'placeholder'=>'Enter Next Hearing Date ','required','value'=>date("d-m-Y", strtotime($data['CauseList']['session_date']))));?>
        </div>
    </div>
</div>                                     
 <div class="span6">
   <div class="control-group">
        <label class="control-label">Magisterial Area<?php echo $req; ?> :</label>
        <div class="controls">
            <?php echo $this->Form->input('Courtattendance.magisterial_id',array('div'=>false,'label'=>false,'type'=>'select','options'=>array($data['CauseList']['magisterial_id']=>$data['Magisterial']['name']), 'class'=>'form-control','required', 'id'=>'magisterial_id'));?>
        </div>
    </div>
</div>
                             
<div class="clearfix"></div>     
<div class="span6">
    <div class="control-group">
        <label class="control-label">Court<?php echo $req; ?> :</label>
        <div class="controls">
            <?php echo $this->Form->input('Courtattendance.court_id',array('div'=>false,'label'=>false,'type'=>'select','options'=>array($data['CauseList']['court_id']=>$data['Court']['name']), 'class'=>'form-control','required', 'id'=>'court_id', 'style'=>'width:91.5%;'));?>
        </div>
    </div>
</div>    
<div class="span6">
    <div class="control-group">
        <label class="control-label">Court Level:</label>
        <div class="controls">
            <?php echo $this->Form->input('Courtattendance.court_level',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','id'=>'court_level','readonly'=>'readonly','placeholder'=>'Enter Court Level', 'style'=>'width:90%;'));?>
        </div>
    </div>
</div>                                             

<div class="clearfix"></div>  
<div class="span6">
    <div class="control-group">
        <label class="control-label">Case No.<?php echo $req; ?> :</label>
        <div class="controls">
            <?php echo $this->Form->input('Courtattendance.case_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','required','readonly','id'=>'case_no','placeholder'=>'Enter Case No.','value'=>$data['CauseList']['high_court_case_no']));?>
        </div>
    </div>
</div>
<div class="clearfix"></div>  
<?php
}
?>