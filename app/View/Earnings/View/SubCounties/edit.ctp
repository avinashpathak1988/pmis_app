<div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Modify SubCounty</h5>
            <div style="float:right;padding-top: 7px;">


            <?php echo $this->Html->link('SubCounty List',array(
                                    
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
              <?php //debug($this->data['SubCounty']);
echo $this->Form->create('SubCounty',array(
  'class'=>'form-horizontal'
));
echo $this->Form->input('id');
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
                     <?php echo $this->Form->input('county_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$allCountyList, 'empty'=>'-- Select County --','required'=>false,'id'=>'county_id'));?>
                    </div>
                </div>
              <div class="control-group">
                <label class="control-label">Sub County Name<?php echo MANDATORY; ?> :</label>
                <div class="controls">
                  <?php
                      echo $this->Form->input('id');
                      echo $this->Form->input('name',array(
                        'div'=>false,
                        'label'=>false,
                        'class'=>'span11 alpha',
                        'type'=>'text',
                        'placeholder'=>'Enter Sub County Name',
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
            <?php
echo $this->Form->end();
             ?>
          </div>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript">
$( document ).ready(function() {
  var dis_id='';
    <?php if(isset($this->request->data['SubCounty']['district_id'])){?>
       dis_id = '<?php echo $this->request->data['SubCounty']['district_id'];?>';
        showcounty(dis_id);
    <?php }?>
});
  function showcounty(id){
      
               
          var strURL = '<?php echo $this->Html->url(array('controller'=>'Parishes','action'=>'getCounty'));?>';
      
          $.post(strURL,{"district_id":id},function(data){  
              
              if(data) { 
                  $('#county_id').html(data); 
                  var county_id='';
                  <?php if(isset($this->request->data['SubCounty']['county_id'])){?>
                      county_id = '<?php echo $this->request->data['SubCounty']['county_id'];?>';
                      $('#county_id').val(county_id); 
                  <?php }?>
                  
              }
              else
              {
                  alert("Error...");  
              }
          });
     
  }
</script>