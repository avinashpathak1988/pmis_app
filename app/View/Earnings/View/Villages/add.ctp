
<div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Add New Parish</h5>
            <div style="float:right;padding-top: 7px;">


            <?php echo $this->Html->link('Village List',array(
                                    
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
echo $this->Form->create('Village',array(
  'class'=>'form-horizontal'
));
               ?>
                  <div class="control-group">
                    <label class="control-label">District Name<?php echo MANDATORY; ?> :</label>
                    <div class="controls">
                     <?php echo $this->Form->input('district_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$allDistrictList, 'onChange'=>'showcounty(this.value)','empty'=>'-- Select District --','required'=>false,'id'=>'district_id'));?>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label">County Name<?php echo MANDATORY; ?> :</label>
                    <div class="controls">
                     <?php echo $this->Form->input('county_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$allCountyList,'onChange'=>'showsubcounty(this.value)', 'empty'=>'-- Select County --','required'=>false,'id'=>'county_id'));?>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label">Sub County Name<?php echo MANDATORY; ?> :</label>
                    <div class="controls">
                     <?php echo $this->Form->input('sub_county_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$allSubCountyList, 'onChange'=>'showparish(this.value)','empty'=>'-- Select Sub County --','required'=>false,'id'=>'sub_county_id'));?>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label">Parish Name<?php echo MANDATORY; ?> :</label>
                    <div class="controls">
                     <?php echo $this->Form->input('parish_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$allParishList, 'empty'=>'-- Select Parish --','required'=>false,'id'=>'parish_id'));?>
                    </div>
                  </div>
                    <div class="control-group">
                    <label class="control-label">Village Name<?php echo MANDATORY; ?> :</label>
                    <div class="controls">
                      <?php
                          echo $this->Form->input('name',array(
                            'div'=>false,
                            'label'=>false,
                            'class'=>'span11 alpha',
                            'type'=>'text',
                            'placeholder'=>'Enter Village Name',
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
                              'placeholder'=>'Data Entry Operator, Section Officer, etc',
                            ));
                         ?>
                      </div>
                    </div>
                
              
              
              
              <div class="form-actions" align="center">
                <button type="submit" class="btn btn-success">Save</button>
              </div>
            <?php echo $this->Form->end();?>
          </div>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript">
  $(function(){
    $("#ParishAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[Parish][name]': {
                    required: true,
                },
                
            },
            messages: {
                'data[Parish][name]': {
                    required: "This Field is Required.",
                },
                
            },
               
    });
  });
  function showcounty(id){
      
               
          var strURL = '<?php echo $this->Html->url(array('controller'=>'Villages','action'=>'getCounty'));?>';
      
          $.post(strURL,{"district_id":id},function(data){  
              
              if(data) { 
                  $('#county_id').html(data); 
                  
              }
              else
              {
                  alert("Error...");  
              }
          });
     
  }
  function showsubcounty(id){
      
               
          var strURL = '<?php echo $this->Html->url(array('controller'=>'Villages','action'=>'getSubCounty'));?>';
      
          $.post(strURL,{"county_id":id},function(data){  
              
              if(data) { 
                  $('#sub_county_id').html(data); 
                  
              }
              else
              {
                  alert("Error...");  
              }
          });
     
  }
  function showparish(id){
      
               
          var strURL = '<?php echo $this->Html->url(array('controller'=>'Villages','action'=>'getParish'));?>';
      
          $.post(strURL,{"sub_county_id":id},function(data){  
              
              if(data) { 
                  $('#parish_id').html(data); 
                  
              }
              else
              {
                  alert("Error...");  
              }
          });
     
  }
  </script>