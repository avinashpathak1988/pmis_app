
<div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Add Jurisdiction Area</h5>
            <div style="float:right;padding-top: 7px;">


            <?php echo $this->Html->link('Jurisdiction Area',array(
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
                echo $this->Form->create('Magisterial',array(
                  'class'=>'form-horizontal'
                ));
               ?>
               
                        <div class="control-group">
                        <label class="control-label">Jurisdiction Area Name<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('name',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11 alpha',
                                'type'=>'text',
                                'placeholder'=>'Enter Jurisdiction Area Name',
                                'required',
                              ));
                           ?>
                        </div>
                      </div>
                    <div class="control-group">
                        <label class="control-label">Jurisdiction Area Id<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('magisterial_area_id',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11 alpha',
                                'type'=>'text',
                                 'placeholder'=>'Enter Jurisdiction Area Id',
                                'required',
                              ));
                           ?>
                        </div>
                      </div>

                      <div class="control-group">
                        <label class="control-label">Description :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->textarea('description',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11 alpha',
                                 'placeholder'=>'Enter Description',
                                
                                'required',
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
                              'class'=>'span11 pmis_select',
                              'options'=>$is_enable,
                              'default'=>1,
                              'style'=>'width:120px',
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
    $("#MagisterialAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[Magisterial][name]': {
                    required: true,
                },
                
            },
            messages: {
                'data[Magisterial][name]': {
                    required: "Please enter category.",
                },
               
        
            },
               
    });
  });
</script>