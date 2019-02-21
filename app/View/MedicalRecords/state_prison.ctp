<?php $this->request->data['StateOfPrisoner']['prison_date'] = isset($this->request->data['StateOfPrisoner']['prison_date']) && $this->request->data['StateOfPrisoner']['prison_date']!=''?date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($this->request->data['StateOfPrisoner']['prison_date'])):'';
  $this->request->data['StateOfPrisoner']['prisoner_date'] = isset($this->request->data['StateOfPrisoner']['prisoner_date']) && $this->request->data['StateOfPrisoner']['prisoner_date']!=''?date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($this->request->data['StateOfPrisoner']['prisoner_date'])):'';

?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Medical Records Management ( state of prison and prisoner )</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('State of prison and prisoner List'), array('action' => 'prisonerStateList'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('StateOfPrisoner',array('class'=>'form-horizontal'));?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">State Of Prison<?php echo MANDATORY; ?> :</label>
                                    <div class="controls">
                                       <?php
                                echo $this->Form->input('prison_state',array(
                                  'div'=>false,
                                  'label'=>false,
                                  'type'=>'select',
                                  'required',
                                  'empty'=>'--Select--',
                                  'options' => array(
                                    'Comments on the state of buildings'=>'Comments on the state of buildings',
                                    'Comments on Level of congestion'=>'Comments on Level of congestion',
                                    'Comments on ventilation'=>'Comments on ventilation',
                                    'Comments on light system'=>'Comments on light system',
                                    'Comments on fencing'=>'Comments on fencing',
                                    'Comments on general environment'=>'Comments on general environment',
                                    'Comments on ward environment'=>'Comments on ward environment',
                                  ),
                                ));
                             ?>
                                    </div>
                                </div>
                            </div> 
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">State Of Prisoner<?php echo MANDATORY; ?> :</label>
                                    <div class="controls">
                                       <?php
                                echo $this->Form->input('prisoner_state',array(
                                  'div'=>false,
                                  'label'=>false,
                                  'type'=>'select',
                                  'required',
                                  'empty'=>'--Select--',
                                  'options' => array(
                                    'Recommendations on transfers'=>'Recommendations on transfers',
                                    'Recommendation on allocating of labour'=>'Recommendation on allocating of labour',
                                    'Recommendation on ward allocation'=>'Recommendation on ward allocation',
                                    'State different levels of sickness'=>'State different levels of sickness',
                                  ),
                                ));
                             ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Remark<?php echo MANDATORY; ?> :</label>
                                    <div class="controls">
                                       <?php
                                echo $this->Form->input('prison_remark',array(
                                  'div'=>false,
                                  'label'=>false,
                                  'type'=>'text',
                                  'required',
                                ));
                             ?>
                                    </div>
                                </div>
                            </div> 
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Remark<?php echo MANDATORY; ?> :</label>
                                    <div class="controls">
                                       <?php
                                echo $this->Form->input('prisoner_remark',array(
                                  'div'=>false,
                                  'label'=>false,
                                  'type'=>'text',
                                  'required',
                                ));
                             ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Date<?php echo MANDATORY; ?> :</label>
                                    <div class="controls">
                                       <?php
                                echo $this->Form->input('prison_date',array(
                                  'div'=>false,
                                  'label'=>false,
                                  'type'=>'text',
                                  'required',
                                  'class'=>'form-control span11 to_date mydate',
                                  'placeholder'=>''
                                ));
                             ?>
                                    </div>
                                </div>
                            </div> 
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Date<?php echo MANDATORY; ?> :</label>
                                    <div class="controls">
                                       <?php
                                echo $this->Form->input('prisoner_date',array(
                                  'div'=>false,
                                  'label'=>false,
                                  'type'=>'text',
                                  'required',
                                  'class'=>'form-control span11 to_date mydate',
                                ));
                             ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        
                        
                    <div class="form-actions" align="center">
                        <button type="submit" class="btn btn-success" onclick="listingTable(this.value);">Save</button>

                    </div>
                    <?php echo $this->Form->end();?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
    $('.datetimepicker').datetimepicker({format: 'yyyy-mm-dd hh:ii:ss',today:'true',endDate:today});
});


    $("#StateOfPrisonerStatePrisonForm").validate({
     
      ignore: "",
            rules: {  
                'data[StateOfPrisoner][prison_state]': {
                    required: true,
                },
                'data[StateOfPrisoner][prisoner_state]': {
                    required: true,
                },
                'data[StateOfPrisoner][prison_remark]': {
                    required: true,
                },
                'data[StateOfPrisoner][prisoner_remark]': {
                    required: true,
                },
                'data[StateOfPrisoner][prison_date]': {
                    required: true,
                },
                'data[StateOfPrisoner][prisoner_date]': {
                    required: true,
                },
            },
       messages: {
                'data[StateOfPrisoner][prison_state]': {
                    required: "Please select Prison state",
                },
                'data[StateOfPrisoner][prisoner_state]': {
                    required: "Please select Prisoner state",
                },
                'data[StateOfPrisoner][prison_remark]': {
                    required: "Please enter Prison remark",
                },
                'data[StateOfPrisoner][prisoner_remark]': {
                    required: "Please enter Prisoner remark",
                },
                'data[StateOfPrisoner][prison_date]': {
                    required: "Please select prison date",
                },
                'data[StateOfPrisoner][prisoner_date]': {
                    required: "Please select prisoner date",
                },
            },
               
    });

   function listingTable(val){
   // alert(this.value);
    prisonerStateListAjax();
   }

</script>
