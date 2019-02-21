
<div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Add New County</h5>
            <div style="float:right;padding-top: 7px;">


            <?php echo $this->Html->link('Counties List',array(
                                    
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
echo $this->Form->create('County',array(
  'class'=>'form-horizontal'
));
               ?>
                  <div class="control-group">
                    <label class="control-label">District Name<?php echo MANDATORY; ?> :</label>
                    <div class="controls">
                     <?php echo $this->Form->input('district_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$allDistrictList, 'empty'=>'','required'=>false,'id'=>'district_id2'));?>
                    </div>
                  </div>
                    <div class="control-group">
                    <label class="control-label">County Name<?php echo MANDATORY; ?> :</label>
                    <div class="controls">
                      <?php
                          echo $this->Form->input('name',array(
                            'div'=>false,
                            'label'=>false,
                            'class'=>'span11 alpha',
                            'type'=>'text',
                            'placeholder'=>'Enter County Name',
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
    $("#CountyAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[County][name]': {
                    required: true,
                },
                
            },
            messages: {
                'data[County][name]': {
                    required: "This Field is Required.",
                },
                
            },
               
    });
  });
  </script>