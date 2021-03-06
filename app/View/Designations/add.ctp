<div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Add New Designation</h5>
            <div style="float:right;padding-top: 7px;">


            <?php echo $this->Html->link('Designations List',array(
                                    
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
echo $this->Form->create('Designation',array(
  'class'=>'form-horizontal'
));
               ?>
              <div class="control-group">
                <label class="control-label">Designation Code<?php echo MANDATORY; ?> :</label>
                <div class="controls">
                  <?php
                      echo $this->Form->input('code',array(
                        'div'=>false,
                        'label'=>false,
                        'class'=>'span11 alphanumeric',
                        'type'=>'text',
                        'placeholder'=>'1001,1002,D001,etc',
                      ));
                   ?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">Designation<?php echo MANDATORY; ?> :</label>
                <div class="controls">
                  <?php
                      echo $this->Form->input('name',array(
                        'div'=>false,
                        'label'=>false,
                        'class'=>'span11 alpha',
                        'type'=>'text',
                        'placeholder'=>'Data Entry Operator, Section Officer, etc',
                      ));
                   ?>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">Is Enabled ?</label>
                <div class="controls">
                  <?php
                      echo $this->Form->input('is_enable',array(
                        'div'=>false,
                        'label'=>false,
                        'class'=>'span11',
                        'options'=>$is_enable,
                        'default'=>1,
                        'class'=>'span11 pmis_select',
                        'style'=>'width:120px',
                        'placeholder'=>'Data Entry Operator, Section Officer, etc',
                      ));
                   ?>
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
  $(function(){
    $("#DesignationAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[Designation][name]': {
                    required: true,
                },
                'data[Designation][code]': {
                    required: true,
                },
                
            },
            messages: {
                'data[Designation][name]': {
                    required: "This Field is Required.",
                },
                'data[Designation][code]': {
                    required: "This Field is Required.",
                },
            },
               
    });
  });
  </script>