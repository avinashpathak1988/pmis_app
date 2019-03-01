
<div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Modify station journals</h5>
            <div style="float:right;padding-top: 7px;">


            <?php echo $this->Html->link('Station journals',array(
                                    
                                    'action'=>'index'
                                ),array(
                                    'escape'=>false,
                                    'class'=>'btn btn-success btn-mini'
                                )); ?>
              <?php //echo $this->Html->link('Users List',array(
                  //'action'=>'index',
                 // array('escape'=>false,'class'=>'btn btn-success'),
              //));
              ?>
              &nbsp;&nbsp;
          </div>
          </div>
          <div class="widget-content nopadding">
              <?php
                echo $this->Form->create('Stationjournal',array(
                  'class'=>'form-horizontal','enctype'=>'multipart/form-data'
                ));
                echo $this->Form->input('id',array(
                  'type'=>'hidden',
                ));
               ?>
                
                      <div class="row-fluid">
                      <div class="span6"> 
                       <div class="control-group">
                          <label class="control-label">Date<?php echo MANDATORY; ?> :</label>
                          <div class="controls">
                             <?php
                              $journal_date=$this->request->data["Stationjournal"]["journal_date"];
                                echo $this->Form->input('journal_date',array(
                                  'div'=>false,
                                  'label'=>false,
                                  'class'=>'span11 mydate',
                                  'type'=>'text',
                                  'required',
                                  'data-date-format'=>"dd-mm-yyyy",
                                  'value'=>date('d-m-Y',strtotime($journal_date))
                                  
                                ));
                             ?>
                          </div>
                        </div>
                      </div>
                       <div class="span6">     
                        <div class="control-group">
                          <label class="control-label">Prison Station Id<?php echo MANDATORY; ?></label>
                          <div class="controls">
                            <?php
                                echo $this->Form->input('prison_id',array(
                                  'div'=>false,
                                  'label'=>false,
                                  'class'=>'span11',
                                  'options'=>$prison_id,
                                  'empty'=>'-- Select Prison--',
                                  'required',
                                  'disabled'
                                ));
                             ?>
                          </div>
                        </div>
                      </div>
                      </div>
                       <div class="row-fluid">
                      <div class="span6">  
                    <div class="control-group">
                        <label class="control-label">Name of Station<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('station_name',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11 alphanumericone',
                                'required'=>'required',
                                'placeholder'=>'Please Enter Station Name',
                                'readonly'
                              ));
                           ?>
                        </div>
                      </div>
                      </div>

                      <div class="span6">  
                    <div class="control-group">
                        <label class="control-label">State Of Prisoners<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('prisnors_state',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11 alphanumericone',
                                'type'=>'textarea',
                                'required'=>'required',
                                'rows'=>3,
                                'placeholder'=>'Please Enter State Of Prisoners'
                              ));
                           ?>
                        </div>
                      </div>
                      </div>
                      </div>

                      <div class="row-fluid">
                      <div class="span6">  
                      <div class="control-group">
                        <label class="control-label">State Of Prison<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('prisons_state',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11 alphanumericone',
                                'type'=>'textarea',
                                'required'=>'required',
                                'rows'=>3,
                                'placeholder'=>'Please Enter State Of Prison'
                              ));
                           ?>
                        </div>
                      </div>
                      </div>

                      <div class="span6">  
                    <div class="control-group">
                        <label class="control-label">Remark :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('remark',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11 alphanumericone',
                                'type'=>'textarea',
                                'required'=>'false',
                                'rows'=>3,
                                'placeholder'=>'Please Enter Remark'
                              ));
                           ?>
                        </div>
                      </div>
                      </div>
                      </div>
                      <div class="row-fluid">
                      <div class="span6">  
                      <div class="control-group">
                        <label class="control-label">Duty Officer<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                          <?php
                                echo $this->Form->input('dutyofficer_id',array('type'=>'hidden'));
                                echo $this->Form->input('dutyofficer_id1',array(
                                  'div'=>false,
                                  'label'=>false,
                                  'class'=>'span11',
                                  'type'=>'text',
                                  'value'=>$this->Session->read('Auth.User.name'),
                                  //'options'=>$duty_officer,
                                  //'empty'=>'-- Select Prison--',
                                  'required',
                                  //'default'=>$selected1,
                                  'readonly',
                                ));
                             ?>
                        </div>
                      </div>
                      </div>
                      <div class="span6">
                          <div class="control-group">
                              <label class="control-label">
                                  Upload 
                                  <!-- <i class="icon-info-sign" data-toggle="tooltip" title="Please upload max 2MB (jpg,jpeg,png,gif) type photo!" id='example'></i> -->
                                  :
                              </label>
                              <div class="controls">
                                  <div>
                                      <?php 
                                      if(isset($this->request->data["Stationjournal"]["upload"]))
                                      {?>
                                          <a href="<?php echo $this->webroot; ?>app/webroot/files/stationjournal/<?php echo $this->request->data["Stationjournal"]["upload"];?>" target="_blank" style="color:#000!important;">View</a>
                                      <?php }?>
                                  </div>
                                  <?php echo $this->Form->input('upload',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'file','id'=>'upload', 'required'=>false));?>
                              </div>
                          </div>
                      </div>
                      </div>
                      
                            
              
              <div class="form-actions" align="center">
                <button type="submit" class="btn btn-success" id="updateIdJournal">Update</button>
              <?php echo $this->Html->link(__('Cancel'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-danger ')); ?>
              </div>
            <?php
echo $this->Form->end();
             ?>
          </div>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript">
  

  
$(document).ready(function(){

  $('#updateIdJournal').click(function(){
        if($("#StationjournalEditForm").valid()){
            if( !confirm('Are you sure to Update?')) {
                return false;
            }
        }
    });
});


$(function(){
    $("#StationjournalAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[Stationjournal][journal_date]': {
                    required: true,
                },
                'data[Stationjournal][prison_id]': {
                    required: true,
                },
                'data[Stationjournal][prisnors_state]': {
                    required: true,
                },
                'data[Stationjournal][prisons_state]': {
                    required: true,
                },
                'data[Stationjournal][station_name]': {
                    required: true,
                },
                'data[Stationjournal][dutyofficer_id]': {
                    required: true,
                },
            },
            messages: {
                'data[Stationjournal][journal_date]': {
                    required: "Please choose date.",
                },
                'data[Stationjournal][prison_id]': {
                    required: "Please select prison station id.",
                },
                'data[Stationjournal][prisnors_state]': {
                    required: "Please enter state of prisoners.",
                },
                'data[Stationjournal][prisons_state]': {
                    required: "Please enter state of prison.",
                },
                'data[Stationjournal][station_name]': {
                    required: "Please enter name of station ",
                },
                'data[Stationjournal][dutyofficer_id]': {
                    required: "Please select duty officer name.",
                },
               
        
            },
               
    });
  });


</script>