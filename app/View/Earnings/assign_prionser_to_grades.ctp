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
                    <h5>Assign Priosners To Earning Grades</h5>
                    <a class="toggleBtn" href="#searchEarningGradePrisoner" data-toggle="collapse"><span class="icon"> <i class="icon-search" style="color: #000;"></i> </span></a>
                    <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){?>
                        <div style="float:right;padding-top: 3px;">
                            <?php echo $this->Html->link('Assign Prisoner To Earning Grade','#assignPrisonerToEarningGrade',array('escape'=>false,'class'=>'btn btn-success btn-mini toggleBtn','data-toggle'=>"collapse")); ?>
                        </div>
                    <?php }?>
                </div>
                <div class="widget-content nopadding">
                    <div id="searchEarningGradePrisoner" class="collapse <?php if($isEdit == 0){echo 'in';}?>" <?php if($isEdit == 1){?> style="height: 0px;" <?php }?>>
                        <div class="span12">
                            <?php echo $this->Form->create('Search',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));
                            echo $this->Form->input('id',array('type'=>'hidden'));
                            echo $this->Form->input('prison_id',array(
                                'type'=>'hidden',
                                'class'=>'prison_id',
                                'value'=>$this->Session->read('Auth.User.prison_id')
                              ));
                            ?>
                            <div class="row-fluid" style="padding-bottom: 14px;">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Date of assignment :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('date_from',array('div'=>false,'label'=>false,'class'=>'form-control from_date mydate span11','type'=>'text', 'placeholder'=>'Enter From Date','required','readonly'=>'readonly','style'=>'width:150px;'));?>
                                            &nbsp;To&nbsp;
                                            <?php echo $this->Form->input('date_to',array('div'=>false,'label'=>false,'class'=>'form-control from_date mydate span11','type'=>'text', 'placeholder'=>'Enter To Date','required','readonly'=>'readonly','style'=>'width:150px;'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                  
                                    <div class="control-group">
                                        <label class="control-label">Prisoner Number:</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$SearchPrisonerList, 'empty'=>'-- Select Prisoner Number --','required','id'=>'prisoner_id'));?>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <div class="row-fluid" style="padding-bottom: 14px;">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Working Party:</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('working_party_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$workingPartyList, 'empty'=>array('-- Select Working Party --'),'required','id'=>'working_party_id'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Approval Status :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$approvalStatusList,'required'=>false, 'empty'=>array('0'=>'-- Select Approval Status --'), 'style'=>'width:90%', 'id'=>'status', 'default'=>$default_status));?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="span12">
                                    <div class="form-actions" align="center">
                                        <?php echo $this->Form->button('Search', array('type'=>'button','class'=>'btn btn-primary','div'=>false,'label'=>false, 'onclick'=>"showDataWorkingPartyPrisoner();"))?>
                                        <?php echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetForm('SearchAssignPrionsersForm')"))?>
                                    </div>
                                </div>
                            <?php echo $this->Form->end();?>
                        </div>
                    </div>    
                    <div id="assignPrisonerToEarningGrade" class="collapse <?php if($isEdit == 1){echo 'in';}?>" <?php if($isEdit == 0){?> style="height: 0px;" <?php }?>>
                        <div class="span12">
                            <?php echo $this->Form->create('EarningGradePrisoner',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));
                            echo $this->Form->input('id',array('type'=>'hidden'));
                            echo $this->Form->input('prison_id',array(
                                'type'=>'hidden',
                                'class'=>'prison_id',
                                'value'=>$this->Session->read('Auth.User.prison_id')
                              ));
                            ?>
                            <div class="row-fluid" style="padding-bottom: 14px;">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Date of assignment<?php echo $req; ?> :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('assignment_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Date of assignment','required','readonly'=>'readonly','id'=>'assignment_date'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                  
                                    <div class="control-group">
                                        <label class="control-label">Prisoner Number <?php echo $req; ?>:</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$prisonerList, 'empty'=>'-- Select Prisoner Number --','required','id'=>'prisoner_id'));?>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                                 
                            <div class="row-fluid" style="padding-bottom: 14px;">
                              
                                <!-- <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Remarks :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->textarea('remarks',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter remarks','class'=>'form-control span11','type'=>'text','required'=>false));?>
                                        </div>
                                    </div>
                                </div> -->
                              
                                <div class="span6">
                                  
                                    <div class="control-group">
                                        <label class="control-label">Earning Grade <?php echo $req; ?>:</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('grade_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$workingPartyList, 'empty'=>'-- Select Working Party --','required','id'=>'working_party_id'));?>
                                        </div>
                                    </div>
                                </div>

                                <div class="span6 hidden">
                                    <div class="control-group">
                                        <label class="control-label">Is Enable?<?php echo $req; ?> :</label>
                                        <div class="controls uradioBtn">
                                            <?php 
                                            $is_enable = 1;
                                            if(isset($this->data['WorkingPartyPrisoner']['is_enable']))
                                                $is_enable = $this->data['WorkingPartyPrisoner']['is_enable'];
                                            $options= array(0=>'No',1=>'Yes');
                                            $attributes = array(
                                                'legend' => false, 
                                                'value' => $is_enable,
                                            );
                                            echo $this->Form->radio('is_enable', $options, $attributes);
                                            ?>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                            <div class="form-actions" align="center">
                                <?php echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true,'onclick'=>"javascript:return validateForm();"))?>
                            </div>
                            <?php echo $this->Form->end();?>
                        </div>
                    </div>      
                     <div id="workingpartyprisoner_listview"></div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">


function validateForm(){
    var errcount = 0;
    $('.validate').each(function(){
        if($(this).val() == ''){
            errcount++;
            $(this).addClass('error-text');
            $(this).removeClass('success-text'); 
        }else{
            $(this).removeClass('error-text');
            $(this).addClass('success-text'); 
        }        
    });        
    if(errcount == 0){            
        //if(confirm('Are you sure want to save?')){  
            return true;            
        // }else{               
        //     return false;           
        // }        
    }else{   
        return false;
    }  
}

</script>
<?php
$earningGradePrisonerUrl = $this->Html->url(array('controller'=>'earnings','action'=>'earningGradePrisonerAjax'));
$deleteworkingPartyPrisonerUrl = $this->Html->url(array('controller'=>'earnings','action'=>'deleteEarningGradePrisoner'));
echo $this->Html->scriptBlock("
   
    jQuery(function($) {
         showDataEarningGradePrisoner();
         $('.toggleBtn').click(function(){
            $('.in.collapse').css('height','0');
            $('.in.collapse').removeClass('in');
         });
    }); 
    
    function showDataEarningGradePrisoner(){
        var url = '".$earningGradePrisonerUrl."';
        $.post(url, $('#SearchAssignPrionsersForm').serialize(), function(res) {
            if (res) {
                $('#workingpartyprisoner_listview').html(res);
            }
        });
    }

    //delete working party 
    function deleteworkingPartyPrisoner(paramId){
        if(paramId){
            if(confirm('Are you sure to delete?')){
                var url = '".$deleteworkingPartyPrisonerUrl."';
                $.post(url, {'paramId':paramId}, function(res) { 
                    if(res == 'SUCC'){
                        showDataWorkingPartyPrisoner();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }

",array('inline'=>false));
?>
<script>
$(function(){

    $("#EarningGradePrisonerAssignPrionserToGradesForm").validate({
 
    ignore: "",
        rules: {  
            'data[EarningGradePrisoner][assignment_date]': {
                required: true,
                datevalidateformat: true
            },
            'data[EarningGradePrisoner][prisoner_id]': {
                required: true,
            },
            'data[EarningGradePrisoner][grade_id]': {
                required: true,
            }
        },
        messages: {
            'data[EarningGradePrisoner][assignment_date]': {
                required: "Please choose date of assignment.",
                datevalidateformat: "Wrong Date Format"
            },
            'data[EarningGradePrisoner][prisoner_id]': {
                required: "Please select prisoner number.",
            },
            'data[EarningGradePrisoner][grade_id]': {
                required: "Please select earning grade.",
            }
        }, 
    });
});
</script>

