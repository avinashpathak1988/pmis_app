<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
</style>
<?php
if(isset($this->data['EarningRate']['start_date']) && $this->data['EarningRate']['start_date'] != ''){
    $this->request->data['EarningRate']['start_date'] = date('d-m-Y', strtotime($this->data['EarningRate']['start_date']));
}
if(isset($this->data['EarningRate']['end_date']) && $this->data['EarningRate']['end_date'] != ''){
    $this->request->data['EarningRate']['end_date'] = date('d-m-Y', strtotime($this->data['EarningRate']['end_date']));
}
?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Assign/Promote Earning Grades To Prisoner</h5>
                    
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                                <?php echo $this->Form->create('EarningGradePrisoner',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                <?php //echo $this->Form->input('id',array("type"=>"hidden"))?>
                                <?php //echo $this->Form->input('uuid',array('type'=>'hidden'))?>
                                <?php echo $this->Form->input('prison_id',array('type'=>'hidden', 'value'=>$this->Session->read('Auth.User.prison_id')))?>
                                
                                <div class="row" style="padding-bottom: 14px;">
                                    <div class="span6">
                                        <div class="control-group">
                                        <label class="control-label">Prisoner No<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select span11','type'=>'select','options'=>$prisonerlist,'empty'=>'','onchange'=>'getPrisonerGradelist(this.value);', 'title'=>'Please select prisoner no.', 'required'));?>
                                            </div>
                                           
                                        </div>
                                    </div>
                                    <div class="span6">
                                       
                                        <div class="control-group">
                                             <label class="control-label">Grade<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('grade_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$gradeslist,'empty'=>'', 'required'));?>
                                            </div>
                                        </div>
                                    </div>
                                    </div> 
                                   <div class="row"> 
                                     <div class="span6">
                                        <div class="control-group">


                                            <label class="control-label">Assignment/<br>Promotion Date<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php $currentDate = date('d-m-Y');
                                                echo $this->Form->input('assignment_date',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Assignment/Prommotion date', 'data-date-format'=>"dd-mm-yyyy",
                                                     'readonly'=>'readonly','class'=>'form-control maxCurrentDate','type'=>'text','required', 'default'=>$currentDate));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">


                                            <label class="control-label"> In Stage- :</label>
                                            <div class="controls">
                                                <?php 
                                                echo $this->Form->input('prisoner_stage_id',array('type'=>'hidden','id'=>'prisoner_stage_id'));
                                                echo $this->Form->input('prisoner_stage_name',array('div'=>false,'label'=>false,'placeholder'=>'Prisoner Current Stage',
                                                     'readonly'=>'readonly','class'=>'form-control','type'=>'text', 'id'=>'prisoner_stage_name'));?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Remarks :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->textarea('remarks',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Remarks','class'=>'form-control','type'=>'text'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                <div class="span12">
                                    <div class="form-actions" align="center">
                                        <?php echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit'))?>
                                    </div>
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


// function validateForm(){
//     var errcount = 0;
//     $('.validate').each(function(){
//         if($(this).val() == ''){
//             errcount++;
//             $(this).addClass('error-text');
//             $(this).removeClass('success-text'); 
//         }else{
//             $(this).removeClass('error-text');
//             $(this).addClass('success-text'); 
//         }        
//     });        
//     if(errcount == 0){            
//         if(confirm('Are you sure want to save?')){  
//             return true;            
//         }else{               
//             return false;           
//         }        
//     }else{   
//         return false;
//     }  
// }
 $(function(){
    $("#EarningGradePrisonerAssignGradesForm").validate({
     
      ignore: "",
            rules: {  
                'data[EarningGradePrisoner][EarningGradePrisonerPrisonerId]': {
                    required: true,
                },
                'data[EarningGradePrisoner][EarningGradePrisonerGradeId]': {
                    required: true,
                },
                'data[EarningGradePrisoner][EarningGradePrisonerAssignmentDate]': {
                    required: true,
                },
                
           },
            messages: {
                'data[EarningGradePrisoner][EarningGradePrisonerPrisonerId]': {
                    required: "This Field is Required.",
                },
                'data[EarningGradePrisoner][EarningGradePrisonerGradeId]': {
                    required: "This Field is Required.",
                },
                'data[EarningGradePrisoner][EarningGradePrisonerAssignmentDate]': {
                    required: "This Field is Required.",
                },
                
            },
               
    });
  });

</script>
<?php
$ajaxUrl        = $this->Html->url(array('controller'=>'earningRates','action'=>'assignGradeAjax'));
$getPrisonerGradeListUrl        = $this->Html->url(array('controller'=>'earningRates','action'=>'getPrisonerGradeList'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });

    function getPrisonerGradelist(val)
    {
        $('#EarningGradePrisonerGradeId').val('');
        $('#EarningGradePrisonerGradeId').select2({
            placeholder: '-- Select --',
            allowClear: true
        });
        if(val != '')
        {
            var url   = '".$getPrisonerGradeListUrl."';
            url = url + '/prisoner_id:'+val;
            $.post(url, {}, function(res) {
                var data = JSON.parse(res);
                $('#EarningGradePrisonerGradeId').html(data.gradelist);
                $('#prisoner_stage_id').val(data.currentStage);
                $('#prisoner_stage_name').val(data.currentStageName);
                $('#EarningGradePrisonerRemarks').val(data.stagePromotionRemark);
                $('#EarningGradePrisonerGradeId').select2({
                    placeholder: '-- Select --',
                    allowClear: true
                });
            });
        }
        else 
        {
            $('#EarningGradePrisonerGradeId').html('');
            $('#prisoner_stage_id').val('');
            $('#prisoner_stage_name').val('');
            $('#EarningGradePrisonerRemarks').val('');
        }
    }

    function showData(){
        var url   = '".$ajaxUrl."';
        //url = url + '/name:'+$('#name').val();
        //url = url + '/grade_description:'+$('#grade_description').val();
        $.post(url, {}, function(res) {
            $('#listingDiv').html(res);
        });           
    }
",array('inline'=>false));
?>

