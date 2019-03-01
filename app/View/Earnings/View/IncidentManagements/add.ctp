<?php
if(isset($this->data['IncidentManagement']['prisoner_no']) && $this->data['IncidentManagement']['prisoner_no']!=''){
    $this->request->data['IncidentManagement']['prisoner_no'] = explode(",", $this->data['IncidentManagement']['prisoner_no']);
}
if(isset($this->data['IncidentManagement']['officer_present']) && $this->data['IncidentManagement']['officer_present']!=''){
    $this->request->data['IncidentManagement']['officer_present'] = explode(",", $this->data['IncidentManagement']['officer_present']);
}
if(isset($this->data['IncidentManagement']['date']) && $this->data['IncidentManagement']['date']!=''){
    $this->request->data['IncidentManagement']['date'] = date('d-m-Y',strtotime($this->data['IncidentManagement']['date']));
}
?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Add Incident Management</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Incident Management Listing'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-mini btn-primary')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
<div class="widget-content nopadding">
    <?php echo $this->Form->create('IncidentManagement',array('class'=>'form-horizontal'));?>
    <?php echo $this->Form->input('prison_id', array('type'=>'hidden', 'value'=> $this->Session->read('Auth.User.prison_id')))?>
    <div class="row" style="padding-bottom: 14px;">
         <div class="span6">
           <div class="control-group">
                <label class="control-label">Incident Type: <?php echo MANDATORY; ?></label>
                <div class="controls">
                   <!-- <?php   //$options = array('Attempted Escape'=>'Attempted Escape','Attempted Suicide'=>'Attempted Suicide','Strike'=>'Strike','Others'=>'Others'); ?> -->
                    <?php echo $this->Form->input('incident_type', array('type'=>'select','class'=>'form-control pmis_select','required'=>true,'options'=> $incidentTypeList,'empty'=>'','div'=>false,'label'=>false,'title'=>'Please select type'))?>
                </div>
            </div>
        </div>
        <div class="span6">
            <div class="control-group">
                <label class="control-label">Incident Name: <?php echo MANDATORY; ?></label>
                <div class="controls">
                    <?php echo $this->Form->input('incident_name', array('type'=>'text','class'=>'form-control','placeholder'=>'Enter Incident','div'=>false,'label'=>false,'required'=>true,'title'=>'please enter name'))?>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="padding-bottom: 14px;">
        <div class="span6">
            <div class="control-group">
                <label class="control-label">Prisoner Number: <?php echo MANDATORY; ?></label>
                <div class="controls">
                    <?php echo $this->Form->input('prisoner_no', array('type'=>'select','class'=>'form-control pmis_select','multiple'=>true,'options'=>$prisonerList,'empty'=>'','required'=>true,'div'=>false,'label'=>false,'hiddenField'=>false,'title'=>'Pleaes select prisoner no'))?>
                </div>
            </div>
        </div>
        <div class="clear-fix"></div>
           <div class="span6">
            <div class="control-group">
                <label class="control-label">Date:</label>
                <div class="controls">
                     <?php echo $this->Form->input('date',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Date Of Creation', 'data-date-format'=>"dd-mm-yyyy",
                                                     'readonly'=>'readonly','class'=>'form-control','type'=>'text','required','default'=>date('d-m-Y')));?>
                </div>
            </div>
        </div>
      <!--   <div class="span6">
            <div class="control-group">
            <label class="control-label">Prisoner Name:</label>
            <div class="controls">
                <?php// echo $this->Form->input('prisoner_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha nospace','type'=>'text','multiple'=>true,'placeholder'=>'Prisoner Name','id'=>'prisoner_name','readonly'=>'readonly'));?>
            </div>
        </div>
        </div>   -->
    </div>     
    <div class="row" style="padding-bottom: 14px;">
        <div class="span6">
            <div class="control-group">
                <label class="control-label">Remarks:</label>
                <div class="controls">
                    <?php echo $this->Form->input('remarks', array('type'=>'textarea','class'=>'form-control','rows'=>3,'id'=>'remarks','div'=>false,'label'=>false))?>
                </div>
            </div>
        </div>
        <div class="span6">
            <div class="control-group">
                <label class="control-label">Officer Present:</label>
                <div class="controls">
                    <?php echo $this->Form->input('officer_present', array('type'=>'select','class'=>'form-control pmis_select','id'=>'officer_present','multiple'=> true,'empty'=>'--All--','div'=>false,'options'=>$officePresent,'label'=>false))?>
                </div>
            </div>
        </div>  
    </div> 
    <div class="row" style="padding-bottom: 14px;">
     
        <div class="span6">
            
        </div>  
    </div>       
        <div class="form-actions" align="center">
        <button type="submit" class="btn btn-success">Save</button>
           <?php echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData('IncidentManagementAddForm')"))?>
        </div>
        <?php echo $this->Form->end();?> 
        <div class="table-responsive" id="listingDiv">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(function(){
    $("#IncidentManagementAddForm").validate({ 
        
    });
});
 function resetData(id){
        $('#'+id)[0].reset();

        //$('select').select2({minimumResultsForSearch: Infinity});
        $('select').select2().select2("val", null);
        
    }

</script>











