
<div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Add prison officers</h5>
            <div style="float:right;padding-top: 7px;">


            <?php echo $this->Html->link('Prison officers',array(
                                    
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
                echo $this->Form->create('Officer',array(
                  'class'=>'form-horizontal'
                ));
                echo $this->Form->input('id');
               ?>
               
                      <div class="row-fluid">
                      <div class="span6">   
                    <div class="control-group">
                      <label class="control-label">Current Station <?php echo MANDATORY; ?></label>
                      <div class="controls">
                        <?php
                            echo $this->Form->input('prison_id',array(
                              'div'=>false,
                              'label'=>false,
                              'class'=>'span11',
                              'options'=>$prison_id,
                              'empty'=>'-- Select Prison--',
                              'required',
                            ));
                         ?>
                      </div>
                    </div>
                   </div>

                      <div class="span6">
                      <div class="control-group">
                          <label class="control-label">First Name<?php echo MANDATORY; ?> :</label>
                          <div class="controls">
                            <?php
                                echo $this->Form->input('first_name',array(
                                  'div'=>false,
                                  'label'=>false,
                                  'class'=>'span11',
                                  'type'=>'text',
                                  'required'=>'required',
                                ));
                             ?>
                          </div>
                        </div>
                      </div>
                      </div>
                      <div class="row-fluid">
                      <div class="span6">
                      <div class="control-group">
                        <label class="control-label">Last Name<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('last_name',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11',
                                'type'=>'text',
                                'required'=>'required',
                              ));
                           ?>
                        </div>
                      </div>
                      </div>

                      <div class="span6">
                      <div class="control-group">
                        <label class="control-label">Force Number<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('force_number',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11',
                                'type'=>'text',
                                'required'=>'required',
                              ));
                           ?>
                        </div>
                      </div>
                      </div>
                      </div>
                      <div class="row-fluid">
                      <div class="span6">
                        <div class="control-group">
                        <label class="control-label">D.O.B<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                          <?php
                             $dob=date('d-m-Y',strtotime($this->request->data["Officer"]["dob"]));
                              echo $this->Form->input('dob',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11 mydate',
                                'type'=>'text',
                                'required',
                                'data-date-format'=>"dd-mm-yyyy",
                                'value'=>$dob,
                                'readonly'
                              ));
                           ?>
                        </div>
                      </div>
                      </div>

                      <div class="span6">
                      <div class="control-group">
                        <label class="control-label">Rank :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('rank',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11',
                                'type'=>'text',
                              ));
                           ?>
                        </div>
                      </div>
                      </div>
                      </div>
                      
                      
                            
              
              <div class="form-actions" align="center">
                <button type="submit" class="btn btn-success">Save</button>
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
    //$('.datepicker').datepicker();
});


$(function(){
    $("#OfficerEditForm").validate({
     
      ignore: "",
            rules: {  
                'data[Officer][prison_id]': {
                    required: true,
                },
                'data[Officer][first_name]': {
                    required: true,
                },
                'data[Officer][last_name]': {
                    required: true,
                },
                'data[Officer][force_number]': {
                    required: true,
                },
                'data[Officer][dob]': {
                    required: true,
                },
                'data[Officer][staff_category]': {
                    required: true,
                },
            },
            messages: {
                'data[Officer][prison_id]': {
                    required: "Please select station",
                },
                'data[Officer][first_name]': {
                    required: "Please enter first name.",
                },
                'data[Officer][last_name]': {
                    required: "Please enter last name.",
                },
                'data[Officer][force_number]': {
                    required: "Please enter force number.",
                },
                'data[Officer][dob]': {
                    required: "Please select dob.",
                },
                'data[Officer][staff_category]': {
                    required: "Please select staff category",
                },
        
            },
               
    });
  });
</script>