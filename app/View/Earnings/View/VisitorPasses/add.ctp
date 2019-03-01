<style>
.nodisplay{display:none;}
</style>
<?php
// debug($prisonList);
if(isset($this->request->data['PrisonerTransfer']['transfer_date']) && $this->request->data['PrisonerTransfer']['transfer_date']!=''){
    $this->request->data['PrisonerTransfer']['transfer_date'] = date('d-m-Y',strtotime($this->request->data['PrisonerTransfer']['transfer_date']));
}
?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Add Vistor Pass</h5> 
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Visitor Pass List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                        <?php 
                        echo $this->Form->create('VisitorPass',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));
                        echo $this->Form->input('id',array('type'=>'hidden'));
                        echo $this->Form->input('transfer_from_station_id',array(
                            'type'=>'hidden',
                            'class'=>'transfer_from_station_id',
                            'value'=>$this->Session->read('Auth.User.prison_id')
                        ));
                        echo $this->Form->input('is_enable',array('type'=>'hidden','value'=>1));
                        ?>
                            <div class="row-fluid">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Gate Pass no <?php echo $req; ?>:</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('gate_pass',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'text', 'placeholder'=>'Auto Generated','disabled'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prison Station <?php echo $req; ?>:</label>
                                        <div class="controls">
                                             <?php echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','empty'=>'','type'=>'select','options'=>$prisonList, 'required','id'=>'prison_id','title'=>"Please select prison",'onChange'=>'getPrisonerStationInfo(this.value)'));?>
                                           
                                        </div>
                                    </div>
                                </div>
                              
                            </div> 
                            <div class="row-fluid">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">National Id Card:</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('national_card',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'ID card','required','title'=>"Please provide ID number"));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Profession of Visitor <?php echo $req; ?>:</label>
                                        <div class="controls">
                                           <?php echo $this->Form->input('profession_visitor',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Profession of Visitor','required','title'=>"Profession of Visitor"));?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="prisonerDetails">
                                
                            </div>

                            <div class="row-fluid">                                 
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Contact No <?php echo $req; ?>:</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('Contact',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Contact No ','required','id'=>'reason','title'=>"Please provide Contact No "));?>
                                        </div>
                                    </div>
                                </div>

                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prisoner to be Visit <?php echo $req; ?>:</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','empty'=>'','type'=>'select','options'=>$prisonerList, 'required','id'=>'prisoner_id','title'=>"Please select prisoner number"));?>
                                        </div>
                                    </div>
                                </div>
                                
                            </div> 
                            <div class="row-fluid">                                
                                
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">ReleationShip <?php echo $req; ?>:</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('relationships',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Relationship','required','title'=>"Please provide ReleationShip"));?>
                                        </div>
                                    </div>
                                </div>
                                 <!-- <div class="span6">
                                   <div class="control-group">

                                        <label class="control-label">Visitor Days:</label>
                                        <div class="controls">
                                           <?php  
                                               if(isset($this->data['VisitorPass']['days'])){
                                                $daysSelected = explode(',', $this->data['VisitorPass']['days']);
                                               }else{
                                                $daysSelected =array();
                                               }
                                               $default =array();
                                               foreach ($daysSelected as $key => $value) {
                                                   /*echo $value;
                                                array_push($myDays, array($value=>$value));*/
                                                $default += array($value=>$value);
                                               }
                                                $this->request->data['VisitorPass']['days']=$default;

                                               //debug($this->request->data['VisitorDay']['days']);
                                                $days = array(
                                                                    'sunday'=>'Sunday',
                                                                    'Monday'=>'Monday',
                                                                    'Tuesday'=>'Tuesday',
                                                                    'Wednesday'=>'Wednesday',
                                                                    'Thursday'=>'Thursday',
                                                                    'Friday'=>'Friday',
                                                                    'Saturday'=>'Saturday'
                                                                );
                                               echo $this->Form->input('days',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select','required','empty'=>'-- Select Prisoner Type --','options'=>$days,'multiple'=>true,'default'=>$default));?>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="span6">
                                   <div class="control-group">
                                        <label class="control-label">Date of Visit <?php echo $req; ?>:</label>
                                        <div class="controls">
                                            <?php 
                                                    echo $this->Form->input('valid_form',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Date of Visit','readonly'=>'readonly','class'=>'form-control mydatetimepicker1 span11','required', 'id'=>'escape_date',"title"=>"please provide the date and time of Visit"));?>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <!-- <div class="row-fluid">                                
                                
                                <div class="span6">
                                   <div class="control-group">
                                        <label class="control-label">Valid From <?php echo $req; ?>:</label>
                                        <div class="controls">
                                            <?php 
                                                    echo $this->Form->input('valid_form',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Valid from date','readonly'=>'readonly','class'=>'form-control mydatetimepicker1 span11','required', 'id'=>'escape_date',"title"=>"please provide the date and time of escape"));?>
                                        </div>
                                    </div>
                                </div>
                                 <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Valid Till <?php echo $req; ?>:</label>
                                        <div class="controls">
                                            <?php 
                                                    echo $this->Form->input('valid_till',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Valid till date','readonly'=>'readonly','class'=>'form-control mydatetimepicker1 span11','required', 'id'=>'escape_date',"title"=>"please provide the date and time of escape"));?>
                                        </div>
                                    </div>
                                </div>
                            </div>  -->
                             <div class="row-fluid">                                
                                
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Purpose <?php echo $req; ?>:</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('purpose',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Purpose','required','title'=>"Please provide Purpose"));?>
                                        </div>
                                    </div>
                                </div>
                                 <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Issue date<?php echo $req; ?>:</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('issue_date',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Date Of Creation', 'data-date-format'=>"dd-mm-yyyy",
                                                     'readonly'=>'readonly','class'=>'form-control','type'=>'text','required','default'=>date('d-m-Y')));?>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="form-actions" align="center">
                        <div class="span5">
                            
                        </div>
                        <div class="span7">
                            <?php echo $this->Form->input('Add Now', array('type'=>'submit','div'=>false,'class'=>'btn btn-success pull-left','label'=>false,'id'=>'submit','formnovalidate'=>true,"style"=>"margin-right:10px;"))?>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php
                        echo $this->Html->link('Reset',"javascript:;",array('escape'=>false,'class'=>'btn btn-danger pull-left','onclick'=>"resetData('VisitorPassAddForm');")); 
                        ?>
                        </div>
                        
                    </div>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function showPrisonerDetails(id){
    $.post('<?php echo $this->Html->url(array('controller'=>'PrisonerTransfers','action'=>'getDetails')) ?>/'+id, {}, function(res) {
            $('#prisonerDetails').html(res);
    });
}
$(function(){
    // $("#prisoner_id").select2();

     
    $("#VisitorPassAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[VisitorPass][prison_id]': {
                    required: true,
                },
              
            },
            messages: {
                'data[VisitorPass][prison_id]': {
                    required:'Select Prison',
                },      
            }, 
    });
  });
function resetData(){
    
}

</script>
<?php
$ajaxJudgeUrl =  $this->Html->url(array('controller'=>'VisitorPasses','action'=>'getPrisoner'));

echo $this->Html->scriptBlock("
$(document).ready(function(){
    $('#prison_id').on('change', function(e){
        var url = '".$ajaxJudgeUrl."';
        $.post(url, {'prison_id':$('#prison_id').val()}, function(res){
            $('#prisoner_id').html(res);
            $('#prisoner_id').select2('val', '');
        });
    });



    $('select').select2('val', '');


});
",array('inline'=>false));
?>

</script>
<?php

//$ajaxEscourtUrl =  $this->Html->url(array('controller'=>'PrisonerTransfers','action'=>'escortTeam'));
echo $this->Html->scriptBlock("
$(document).ready(function(){
    $('#transfer_from_station_id').on('change', function(e){
        var url = '".$ajaxJudgeUrl."';
        $.post(url, {'prison_id':$('#transfer_from_station_id').val()}, function(res){
            $('#prisoner_id').html(res);
            $('#prisoner_id').select2('val', '');
        });
    });

});
",array('inline'=>false));
?>


<script type="text/javascript">