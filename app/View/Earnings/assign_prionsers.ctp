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
                    <h5>Assign Prisoners to Working Parties</h5>
                    <a class="toggleBtn" href="#searchWorkingPartyPrisoner" data-toggle="collapse"><span class="icon"> <i class="icon-search" style="color: #000;"></i> </span></a> 
                    <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') || $this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){?>
                        <div style="float:right;padding-top: 7px;padding-right:5px;">
                            <?php echo $this->Html->link('Assign Prisoner To Working Party','#assignPrisonerToWorkingParty',array('escape'=>false,'class'=>'btn btn-success btn-mini toggleBtn','data-toggle'=>"collapse")); ?>
                        </div>
                    <?php }?>
                </div>
                <div class="widget-content nopadding">
                    <div id="searchWorkingPartyPrisoner" class="collapse <?php if($isEdit == 0){echo 'in';}?>" <?php if($isEdit == 1){?> style="height: 0px;" <?php }?>>
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
                                            <?php echo $this->Form->input('date_from',array('div'=>false,'label'=>false,'class'=>'form-control from_date mydate span11','type'=>'text', 'placeholder'=>'Enter From Date','required','readonly'=>'readonly','style'=>'width:120px;'));?>
                                            &nbsp;To&nbsp;
                                            <?php echo $this->Form->input('date_to',array('div'=>false,'label'=>false,'class'=>'form-control from_date mydate span11','type'=>'text', 'placeholder'=>'Enter To Date','required','readonly'=>'readonly','style'=>'width:120px;'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                  
                                    <div class="control-group">
                                        <label class="control-label">Prisoner Number:</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$SearchPrisonerList, 'empty'=>'','required','id'=>'prisoner_id'));?>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <div class="row-fluid" style="padding-bottom: 14px;">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Working Party:</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('working_party_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$workingPartyList, 'empty'=>'','required','id'=>'sworking_party_id'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Approval Status :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$approvalStatusList,'required'=>false, 'empty'=>'', 'style'=>'width:90%', 'id'=>'status', 'default'=>$default_status));?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="span12">
                                    <div class="form-actions" align="center">
                                        <?php echo $this->Form->button('Search', array('type'=>'button','class'=>'btn btn-primary','div'=>false,'label'=>false, 'onclick'=>"showDataWorkingPartyPrisoner();"))?>
                                        <?php echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetForm('SearchAssignPrionsersForm'); showDataWorkingPartyPrisoner();"))?>
                                    </div>
                                </div>
                            <?php echo $this->Form->end();?>
                        </div>
                    </div>    
                    <div id="assignPrisonerToWorkingParty" class="collapse <?php if($isEdit == 1){echo 'in';}?>" <?php if($isEdit == 0){?> style="height: 0px;" <?php }?>>
                        <div class="span12">
                            <?php echo $this->Form->create('WorkingPartyPrisoner',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));
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
                                            <?php echo $this->Form->input('assignment_date',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter start date','required','readonly'=>'readonly','id'=>'assignment_date', 'default'=>date('d-m-Y')));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                  <div class="control-group">
                                        <label class="control-label">Working Party <?php echo $req; ?>:</label>
                                        <div class="controls">
                                            <?php 
                                            echo $this->Form->input('WPartyStartDate',array(
                                                'type'=>'hidden',
                                                'class'=>'prison_id',
                                                'id'=>'WPartyStartDate'
                                            ));
                                            echo $this->Form->input('working_party_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$workingPartyList, 'empty'=>'','required','id'=>'working_party_id', 'onchange'=>'getWpartyDates(this.value)'));//,getPrisonerList(this.value)?>
                                            <?php echo $this->Form->input('wpdate1',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'hidden', 'id'=>'wpdate1'));?>
                                            <?php echo $this->Form->input('wpdate2',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'hidden', 'id'=>'wpdate2'));?>
                                            <div id="WPartyStartDateDiv" class="hidden"></div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <div class="row-fluid">
                                <div class="clearfix"></div> 
                                <div class="span6">
                                    <div class="control-group">
                                     <label class="control-label">Start Date <?php echo $req; ?> :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('start_date',array('div'=>false,'label'=>false,'class'=>'form-control from_date mydate span11','type'=>'text', 'placeholder'=>'Enter start date','required','readonly'=>'readonly', 'id'=>'start_date','onchange'=>"getPrisonersToAssign();"));?>
                                        </div>
                                    </div>
                                </div> 
                                 <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">End Date <?php echo $req; ?> :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('end_date',array('div'=>false,'label'=>false,'class'=>'form-control to_date mydate span11','type'=>'text', 'placeholder'=>'Enter end date','required','readonly'=>'readonly', 'id'=>'end_date', 'onchange'=>"getPrisonersToAssign();"));?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                 
                            <div class="row-fluid" style="padding-bottom: 14px;">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prisoner Number <?php echo $req; ?>:</label>
                                        <div class="controls">
                                            <?php //debug($prisonerList);
                                            echo $this->Form->input('prisoner_id',array('div'=>false,'multiple'=>true,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$prisonerList, 'empty'=>'','title'=>'Please select prisoner number.','id'=>'prisoner_id_working_party', 'selected'=>$assigned_prisoners, 'hiddenField'=>false));?>
                                        </div>
                                    </div>
                                </div>

                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Remarks :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->textarea('remarks',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter remarks','class'=>'form-control span11','type'=>'text','required'=>false));?>
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
$(document).ready(function(){

    $('#prisoner_id_working_party').select2();

});
</script>
<?php
$is_edit=(isset($this->data['WorkingPartyPrisoner']['id']) && $this->data['WorkingPartyPrisoner']['id']!='') ? 1 : 0;
$getWorkingPartyDetails = $this->Html->url(array('controller'=>'earnings','action'=>'getWorkingPartyDetails'));
$getPrisonersToAssign = $this->Html->url(array('controller'=>'earnings','action'=>'getPrisonersToAssign')); 
$workingPartyPrisonerUrl = $this->Html->url(array('controller'=>'earnings','action'=>'workingPartyPrisonerAjax'));
$deleteworkingPartyPrisonerUrl = $this->Html->url(array('controller'=>'earnings','action'=>'deleteWorkingPartyPrisoner'));
$getworkingPartyPrisonerUrl = $this->Html->url(array('controller'=>'earnings','action'=>'getWorkingPartyPrisoner'));
echo $this->Html->scriptBlock("

    function getWpartyDates(val)
    {
        if(val != 0)
        {
            var url = '".$getWorkingPartyDetails."';
            $.post(url, {'wid':val}, function(res) {
                if (res) {
                    var data = JSON.parse(res);
                    console.log(data.WorkingParty.start_date);
                    $('#WPartyStartDate').val(data.WorkingParty.start_date);
                    //$('#WPartyStartDate').val(res);
                    $('#WPartyStartDateDiv').html('Party Creation Date:'+data.WorkingParty.created+'<br>Capacity:'+data.WorkingParty.capacity);
                    $('#WPartyStartDateDiv').removeClass('hidden');
                }
            });
        }
        else 
        {
            $('#WPartyStartDateDiv').html('');
        }
       // getPrisonersToAssign();
        var url = '".$getPrisonersToAssign."';
        var wid = $('#working_party_id').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var WorkingPartyPrisonerId = $('#working_party_id').val(); 
        if(WorkingPartyPrisonerId != '')
        {
                $.post(url, {'wid':wid, 'start_date':start_date, 'end_date':end_date, 'WorkingPartyPrisonerId':WorkingPartyPrisonerId }, function(res) {
                if (res) {
                    $('#prisoner_id_working_party').html(res);
                }
            });
        }
    }

    //get prisoners 
    function getPrisonersToAssign()
    {
        var is_edit='".$is_edit."';
        var url = '".$getPrisonersToAssign."';
        var wid = $('#working_party_id').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var WorkingPartyPrisonerId = $('#working_party_id').val();
        if(WorkingPartyPrisonerId != '')
        {
                $.post(url, {'wid':wid, 'start_date':start_date, 'end_date':end_date, 'WorkingPartyPrisonerId':WorkingPartyPrisonerId,'is_edit':is_edit,'selected_prisoner': '".$assigned_prisoners."'}, function(res) {
                if (res) {
                    $('#prisoner_id_working_party').html(res);
                    $('#prisoner_id_working_party').select2('val', [".$assigned_prisoners."]);
                }
            });
        }
    }
   function getPrisonerList(working_party_id){
     var url = '".$getworkingPartyPrisonerUrl."';
        $.post(url, {working_party_id:working_party_id}, function(res) {
            if (res) {
                $('#prisoner_id_working_party').html(res);
            }
        });
   }
    jQuery(function($) {
    
        var working_party_id = $('#working_party_id').val();
        getWpartyDates(working_party_id);
        getPrisonersToAssign();

         showDataWorkingPartyPrisoner();
         $('.toggleBtn').click(function(){
            $('.in.collapse').css('height','0');
            $('.in.collapse').removeClass('in');
         });
    }); 
    
    function showDataWorkingPartyPrisoner(){
        var url = '".$workingPartyPrisonerUrl."';
        $.post(url, $('#SearchAssignPrionsersForm').serialize(), function(res) {
            if (res) {
                $('#workingpartyprisoner_listview').html(res);
            }
        });
    }

    //delete working party 
    function deleteworkingPartyPrisoner(paramId){
        if(paramId){

            AsyncConfirmYesNo(
                'Are you sure want to delete?',
                'Delete',
                'Cancel',
                function()
                {
                    var url = '".$deleteworkingPartyPrisonerUrl."';
                    $.post(url, {'paramId':paramId}, function(res) { 
                        
                        if(res == 1){ 
                            showDataWorkingPartyPrisoner();
                        }else{
                            dynamicAlertBox('Error','Invalid request, please try again!');
                        }
                    });
                },
                function(){}
            );
        }
    }

",array('inline'=>false));
?>
<script>
$(function(){

    $("#WorkingPartyPrisonerAssignPrionsersForm").validate({
 
    ignore: "",
        rules: {  
            'data[WorkingPartyPrisoner][assignment_date]': {
                required: true,
                datevalidateformat: true
            },
            'data[WorkingPartyPrisoner][prisoner_id]': {
                required: true,
            },
            'data[WorkingPartyPrisoner][start_date]': {
                required: true,
                datevalidateformat: true,
                greaterThanOrEqual: "#assignment_date"
            },
            'data[WorkingPartyPrisoner][end_date]': {
                required: true,
                datevalidateformat: true,
                greaterThanOrEqual: "#start_date"
            },
            'data[WorkingPartyPrisoner][working_party_id]': {
                required: true,
            },
            'data[WorkingPartyPrisoner][remarks]': {
                    loginRegex: true,
                    maxlength: 250
            },
        },
        messages: {
            'data[WorkingPartyPrisoner][assignment_date]': {
                required: "Please choose date of assignment.",
                datevalidateformat: "Wrong Date Format"
            },
            'data[WorkingPartyPrisoner][prisoner_id]': {
                required: "Please select prisoner number.",
            },
            'data[WorkingPartyPrisoner][start_date]': {
                required: "Please choose start date.",
                datevalidateformat: "Wrong Date Format",
                greaterThanOrEqual: "Start date must be greater than or equal date of assignment." 
            },
            'data[WorkingPartyPrisoner][end_date]': {
                required: "Please choose end date.",
                datevalidateformat: "Wrong Date Format",
                greaterThanOrEqual: "End date must be greater than or equal start date."
            },
            'data[WorkingPartyPrisoner][working_party_id]': {
                required: "Please select working party.",
            },
            'data[WorkingPartyPrisoner][remarks]': {
                loginRegex: "Remarks must contain only letters, numbers, Special Characters ( Comma, Hyphen, Dot, Spaces)",
                maxlength: "Please enter no more than 250 characters.",
            },
        }, 
    });
});
</script>

