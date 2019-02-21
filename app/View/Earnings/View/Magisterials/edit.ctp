
<div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Modify Magisterial Area</h5>
            <div style="float:right;padding-top: 7px;">


            <?php echo $this->Html->link('Magisterial Area',array(
                                    
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
                 echo $this->Form->input('id');
               ?>
               
                        <div class="control-group">
                        <label class="control-label">Magisterial Area Name<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('name',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11 alpha',
                                'type'=>'text',
                                'required',
                              ));
                           ?>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label">Magisterial Area Id<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                          <?php
                              echo $this->Form->input('magisterial_area_id',array(
                                'div'=>false,
                                'label'=>false,
                                'class'=>'span11 alpha',
                                'type'=>'text',
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
                              'class'=>'span11',
                              'options'=>$is_enable,
                              'default'=>1,
                              'style'=>'width:120px',
                            ));
                         ?>
                      </div>
                    </div>
                            
              
              <div class="form-actions" align="center">
                <button type="submit" class="btn btn-success">Update</button>
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