<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
</style>


<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Occurances Book</h5>
                     <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('view occurance Book'), array('action' => '/occurnce'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                    <div style="float:right;padding-top: 7px;">
                        
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                                <?php echo $this->Form->create('Occurance',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                <?php echo $this->Form->input('id',array("type"=>"hidden"))?>
                                <?php echo $this->Form->input('prison_id',array("type"=>"hidden",'value'=>$this->Session->read('Auth.User.prison_id')))?>
                                <?php echo $this->Form->input('uuid',array('type'=>'hidden'))?>
                                <?php echo $this->Form->input('created_by',array('type'=>'hidden','value'=>$this->Session->read('Auth.User.id')))?>
                                <div class="row" style="padding-bottom: 14px;">
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Name:</label>
                                            <div class="controls">
                                                <?php
                                                echo $this->Session->read('Auth.User.name');
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Force Number:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('force_number',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Auto Fetched From HRMS','class'=>'form-control','type'=>'text','required', 'maxlength'=>10,'readonly'=>true,'value'=>$this->Session->read('Auth.User.force_number')));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="clearfix"></div> 
                                     <div class="span6">
                                        <div class="control-group">
                                             <label class="control-label">Date:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Date Of Creation', 'data-date-format'=>"dd-mm-yyyy",
                                                     'readonly'=>'readonly','class'=>'form-control','type'=>'text','required','default'=>date('d-m-Y')));?>
                                            </div>
                                        </div>
                                    </div>

                                     <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Time:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('time',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Date Of Creation', 'data-date-format'=>"dd-mm-yyyy",
                                                     'readonly'=>'readonly','class'=>'form-control','type'=>'text','required','default'=>date('H:i:s')));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Rank:</label>
                                            <div class="controls">
                                              <?php echo $this->Form->input('rank',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'','class'=>'form-control','type'=>'text','required'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                             <label class="control-label">Shift<?php echo MANDATORY; ?>:</label>
                                            <div class="controls">
                                                <?php 
                                                if(isset($this->data['Occurance']['shift_id']) && $this->data['Occurance']['shift_id']!=''){
                                                  $shiftList = array();
                                                  $shiftList[$this->data['Occurance']['shift_id']] = $funcall->getName($this->data['Occurance']['shift_id'],"Shift","name");
                                                }
                                                echo $this->Form->input('shift_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','onChange'=>"getShiftId(this.value),getLockupAjax(this.value)",'type'=>'select','options'=>$shiftList, 'empty'=>'-- Select Shift --','required')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="span12 areaof" style="display: none;">
                                    <div class="span12" >
                                        <div class="control-group">
                                             <label class="control-label">Area Of deployment :</label>
                                            <div class="controls" id="areaof" >
                                               
                                            </div>
                                            <label class="control-label">Lockup Details :</label>
                                            <div id="lockup" class="controls"></div>
                                        </div>
                                    </div>

                                    </div> 
                                
								                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">No. Of Absent Staff<?php echo MANDATORY; ?>:</label>
                                            <div class="controls">
                                                <?php 
                                                echo $this->Form->input('number_of_absent_stafs',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'no. of staff','class'=>'form-control','type'=>'text','required'=>false,'readonly'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Responsibility<?php echo MANDATORY; ?>:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('responsibility',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'','class'=>'form-control','type'=>'text','required'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                             <label class="control-label">Occurances Details<?php echo MANDATORY; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->textarea('occurance_details',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Comment','class'=>'form-control','type'=>'text','required'=>true));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <?php
                                    if($this->Session->read('Auth.User.usertype_id')==Configure::read("OFFICERINCHARGE_USERTYPE")){
                                    ?>
                                    <?php echo $this->Form->input('action_by',array('type'=>'hidden','value'=>$this->Session->read('Auth.User.id')))?>
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                             <label class="control-label">Remarks<?php echo MANDATORY; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->textarea('remarks',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Remarks','class'=>'form-control','type'=>'text','required'=>false));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Action<?php echo MANDATORY; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->textarea('action',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Action','class'=>'form-control','type'=>'text','required'=>false));?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                    
                                <div class="clearfix"></div> 
                                <div class="form-actions" align="center">
                                    <?php echo $this->Form->input('save', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit'))?>
                                </div>
                                <?php echo $this->Form->end();?>     
                        </div>
                         <div class="table-responsive" id="listingDiv">

                    </div> 
                    
                    </div>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
    $( document ).ready(function() {
        <?php
        if(isset($this->data['Occurance']['id']) && $this->data['Occurance']['id']!=''){
            ?>
            getShiftId(<?php echo $this->data['Occurance']['shift_id']; ?>);
            getLockupAjax(<?php echo $this->data['Occurance']['shift_id']; ?>);
            <?php
        }
        ?>
    });
    $("#OccuranceIndexForm").validate({
        ignore: "",
    });
function getShiftId(id){
  if(id!=''){
    var shift_date=$('#OccuranceDate').val();
    var strURL = '<?php echo $this->Html->url(array('controller'=>'Occurances','action'=>'getShiftId'));?>/'+id+'/'+shift_date;
    var strAbsentURL = '<?php echo $this->Html->url(array('controller'=>'Occurances','action'=>'absentStaff'));?>/'+id+'/'+shift_date;
    $.post(strURL,{},function(data){
      if(data) { 
       //alert(data);
          $('.areaof').show();
          $('#areaof').html(data);

      }
      else
      {
          $('.areaof').hide();
          alert("Error...");  
      }
    });

    $.post(strAbsentURL,{},function(data){
      if(data) { 
        $('#OccuranceNumberOfAbsentStafs').val(data);
      }
    });
  }
  }

  function getLockupAjax(id){
      var shift_date=$('#OccuranceDate').val();
      var strURL = '<?php echo $this->Html->url(array('controller'=>'Occurances','action'=>'lockupReportAjax'));?>/'+id+'/'+shift_date;
      $.post(strURL,{},function(data){
          if(data) { 
           //alert(data);
              $('#lockup').html(data);

          }
          else
          {
              $('#lockup').html('');
              alert("Error...");  
          }
      });
  }
</script>
