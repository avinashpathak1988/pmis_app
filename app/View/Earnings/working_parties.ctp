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
                    <h5>Working Parties Record</h5>
                    <a class="toggleBtn" href="#searchWorkingParty" data-toggle="collapse"><span class="icon"> <i class="icon-search" style="color: #000;"></i> </span></a>
                    <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') || $this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){?>
                        <div style="float:right;padding-top: 7px;padding-right:5px;">
                            <?php echo $this->Html->link('Add Working Party','#addWorkingParty',array('escape'=>false,'class'=>'btn btn-success btn-mini toggleBtn','data-toggle'=>"collapse")); ?>
                        </div>
                    <?php }?>
                </div>
                <div class="widget-content nopadding">
                    <div id="searchWorkingParty" class="collapse <?php if($isEdit == 0){echo 'in';}?>" <?php if($isEdit == 1){?> style="height: 0px;" <?php }?>>
                        <div class="span12">
                            <?php echo $this->Form->create('Search',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <div class="row-fluid">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">From Date :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('date_from',array('div'=>false,'label'=>false,'class'=>'form-control from_date mydate span11','type'=>'text', 'placeholder'=>'Select From Date ','required','readonly'=>'readonly','id'=>'date_from'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                        <div class="control-group">
                                        <label class="control-label">To Date :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('date_to',array('div'=>false,'label'=>false,'class'=>'form-control to_date mydate span11','type'=>'text', 'placeholder'=>'Select To Date ','required','readonly'=>'readonly','id'=>'date_to'));?>
                                        </div>
                                    </div>
                                </div> 
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">In charge working party :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('officer_incharge',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$userList, 'empty'=>'','required'=>false, 'style'=>'width:90%'));?>
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
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Working party Name :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('keyword',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Working party Name ','required', 'maxlength'=>'30'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Working Status :</label>
                                        <div class="controls">
                                            <?php 
                                            $workingStatusList = array('0'=>'Closed','1'=>'Open');
                                            echo $this->Form->input('open_status',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$workingStatusList,'required'=>false, 'empty'=>'', 'style'=>'width:90%', 'id'=>'open_status'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Employment Type :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('employment_type_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$employmentTypeList,'required'=>false, 'empty'=>'', 'style'=>'width:90%'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="span12">
                                    <div class="form-actions" align="center">
                                        <?php echo $this->Form->button('Search', array('type'=>'button','class'=>'btn btn-primary','div'=>false,'label'=>false,'onclick'=>"showDataWorkingParty();"))?>
                                        <?php echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData('SearchWorkingPartiesForm')"))?>
                                    </div>
                                </div>
                            </div>
                            <?php echo $this->Form->end();?>
                        </div>
                    </div>
                    <div id="addWorkingParty" class="collapse <?php if($isEdit == 1){echo 'in';}?>" <?php if($isEdit == 0){?> style="height: 0px;"<?php }?>>
                        <div class="span12">
                            <?php echo $this->Form->create('WorkingParty',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));
                            echo $this->Form->input('id',array('type'=>'hidden'));
                            echo $this->Form->input('status',array('type'=>'hidden','value'=>'Draft'));
                            echo $this->Form->input('prison_id',array(
                                        'type'=>'hidden',
                                        'class'=>'prison_id',
                                        'value'=>$this->Session->read('Auth.User.prison_id')
                                      ));
                            ?>
                            <div class="row" style="padding-bottom: 14px;">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Creation Date <?php echo $req; ?> :</label>
                                        <div class="controls">
                                            <?php if(isset($this->data['WorkingParty']['id']) && (int)$this->data['WorkingParty']['id'] > 0)
                                            {
                                                $currentDate = date('d-m-Y', strtotime($this->data['WorkingParty']['start_date']));
                                            }
                                            else 
                                            {
                                                $currentDate = date('d-m-Y');
                                            }
                                            echo $this->Form->input('start_date',array('div'=>false,'label'=>false,'class'=>'form-control previousDate span11','type'=>'text', 'placeholder'=>'Select date','required','readonly'=>'readonly','id'=>'start_date', 'value'=>$currentDate));
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Name of Working<br> Party  <?php echo $req; ?> :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('name',array('div'=>false,'label'=>false,'class'=>'form-control span11','class'=>'form-control span11 ','type'=>'text','placeholder'=>'Enter Name of Working Party','id'=>'name', 'maxlength'=>'30'));?>
                                        </div>
                                    </div>
                                </div>
                                       
                                <div class="clearfix"></div> 
                                 <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Remarks :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->textarea('remarks',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter remarks','class'=>'form-control span11','type'=>'text','required'=>false));?>
                                        </div>
                                    </div>
                                </div>
                                
                               <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">In charge working party<?php echo $req; ?> :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('officer_incharge',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select','type'=>'select','options'=>$userList, 'empty'=>'','required', 'style'=>'width:90%'));?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Name of staff in charge:</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('staff_in_charge',array('div'=>false,'label'=>false,'class'=>'form-control alpha','type'=>'text','title'=>'Name of staff in charge.'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Working Party <br>Capacity <?php echo $req;?>:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('capacity',array('div'=>false,'label'=>false,'class'=>'form-control numeric','type'=>'text','placeholder'=>'Enter Working Party Capacity','required', 'maxlength'=>'4'));?>
                                    </div>
                                </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Special Party
                                        <i class="icon-info-sign" data-toggle="tooltip" id="example" data-original-title="Special Working Party which works on Sunday."></i>
                                        :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('is_special',array('div'=>false,'label'=>false,'class'=>'form-control numeric','type'=>'checkbox','title'=>'Special Working Party which works on Sunday.','value'=>1));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Is Out Party:</label>
                                        <div class="controls">
                                        <?php echo $this->Form->input('is_out_party',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox', 'onClick'=>'showIsout()'));?>
                                        </div>
                                    </div>  
                                </div>
                                <div class="span6" id="isout" style="display: none;">
                                    <div class="control-group">
                                        <label class="control-label">Destinations:</label>
                                        <div class="controls">
                                            <?php echo $this->Form->textfield('destination_is_out',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Destinations','class'=>'form-control span11','type'=>'text','required'=>false));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Employment Type :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('employment_type_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$employmentTypeList,'required'=>false, 'empty'=>'', 'style'=>'width:90%', 'id'=>'employment_type_id'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="span6 hidden">
                                    <div class="control-group">
                                        <label class="control-label">Is Enable?<?php echo $req; ?> :</label>
                                        <div class="controls uradioBtn">
                                            <?php 
                                            $is_enable = 1;
                                            if(isset($this->data['WorkingParty']['is_enable']))
                                                $is_enable = $this->data['WorkingParty']['is_enable'];
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
                                <div class="span12">
                                    <div class="form-actions" align="center">
                                        <?php echo $this->Form->button('Save', array('type'=>'submit','class'=>'btn btn-primary','div'=>false,'label'=>false))?>
                                        <?php echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetForm('WorkingPartyWorkingPartiesForm')"))?>
                                    </div>
                                </div>
                                <!-- <div class="span12">
                                    <div class="form-actions" align="center">
                                        <div class="form-actions" align="center">
                                            <?php //echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true,'onclick'=>"javascript:return validateForm();"))?>
                                            <?php //echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetCreditData('WorkingPartyWorkingPartiesForm')"))?>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                            <?php echo $this->Form->end();?>
                        </div>
                    </div>
                    <div class="">
                        <div id="workingparty_listview"></div>
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
function showIsout() {
    
     if($('#WorkingPartyIsOutParty').is(":checked"))
    {
        $('#isout').show();
        //add validation
       
    }
    else 
    {
        $('#isout').hide();
        //remove validation
       
    }
}
</script>
<?php
$current_userType = $this->Session->read('Auth.User.usertype_id');
$receptionist = Configure::read('RECEPTIONIST_USERTYPE');
$principal_officer = Configure::read('PRINCIPALOFFICER_USERTYPE');
$officer_incharge = Configure::read('OFFICERINCHARGE_USERTYPE');

$workingPartyUrl = $this->Html->url(array('controller'=>'earnings','action'=>'workingPartyAjax'));
$deleteworkingPartyUrl = $this->Html->url(array('controller'=>'earnings','action'=>'deleteWorkingParty'));
$closeworkingPartyUrl = $this->Html->url(array('controller'=>'earnings','action'=>'closeWorkingParty'));
echo $this->Html->scriptBlock("

    //validation 
    $(function(){
 
        $.validator.addMethod('datevalidateformat', function(value, element) {
            //return this.optional(element) || /^[a-z0-9\-\s]+$/i.test(value);
            var dtRegex = new RegExp('^([0]?[1-9]|[1-2]\\d|3[0-1])-(01|02|03|04|05|06|07|08|09|10|11|12)-[1-2]\\d{3}$', 'i');
            return dtRegex.test(value);
        });

       $('#SearchIndexForm').validate({
     
      ignore: '',
            rules: { 
                
                'data[Search][date_from]': {
                    'notEmpty': {
                        'datevalidateformat': true,
                    }
                },
                'data[Search][date_to]': {
                    'notEmpty': {
                        'datevalidateformat': true,
                    }
                },
            },
            messages: {
                
                'data[Search][date_from]': {
                    'notEmpty': {
                        'datevalidateformat': 'Invalid date'
                    }
                },
                'data[Search][date_to]': {
                    'datevalidateformat': 'Invalid date'
                }
            },
        }); 
    });
   
    jQuery(function($) {
         showDataWorkingParty();

         $('.toggleBtn').click(function(){
            $('.in.collapse').css('height','0');
            $('.in.collapse').removeClass('in');
         });
    }); 
    
    function showDataWorkingParty(){
        var url = '".$workingPartyUrl."';
        $.post(url,  $('#SearchWorkingPartiesForm').serialize(), function(res) {
            if (res) {
                $('#workingparty_listview').html(res);
                showCheckBox();
            }
        });
    }

    //delete working party 
    function deleteworkingParty(paramId){
        if(paramId){

            AsyncConfirmYesNo(
                'Are you sure want to delete?',
                'Delete',
                'Cancel',
                function()
                {
                    var url = '".$deleteworkingPartyUrl."';
                    $.post(url, {'paramId':paramId}, function(res) { 
                        if(res == 1){
                            showDataWorkingParty();
                        }else{
                            dynamicAlertBox('Error','Invalid request, please try again!');
                        }
                    });
                },
                function()
                {
                }
            );
        }
    }

     //close working party 
    function closeworkingParty(paramId){
        if(paramId){

            AsyncConfirmYesNo(
                'Are you sure want to close?',
                'Close',
                'Cancel',
                function()
                {
                    var url = '".$closeworkingPartyUrl."';
                    $.post(url, {'paramId':paramId}, function(res) { 
                        if(res == 1){
                            showDataWorkingParty();
                        }else{
                            dynamicAlertBox('Error','Invalid request, please try again!');
                        }
                    });
                },
                function()
                {
                }
            );
        }
    }

    function showCheckBox()
    { 
        var usertype_id='".$current_userType."';
        var user_typercpt='".$receptionist."';
        var totalCheckboxes = $('#ApprovalProcessFormWorkingPartyAjaxForm input:checkbox').length;
        if(totalCheckboxes <= 1)
        {
            $('td:first-child').each(function() {
                   $(this).remove();
            });
            $('th:first-child').each(function() {
                   $(this).remove();
            });
        }
        if((usertype_id==user_typercpt) && $('#status').val() != 'Draft' && $('#status').val() != 'Review-Rejected' && $('#status').val() != 'Approve-Rejected' && $('#status').val() != '' && $('#status').val() != 0)
        {
            $('td:last-child').each(function() {
                   $(this).remove();
            });
            $('th:last-child').each(function() {
                   $(this).remove();
            });
        }
    }

    function resetData(id){
        $('#'+id)[0].reset();
        $('select').select2({minimumResultsForSearch: Infinity});
        showDataWorkingParty();
    }

",array('inline'=>false));
?>
<script>
$(function(){

    $("#WorkingPartyWorkingPartiesForm").validate({
 
    ignore: "",
        rules: {  
            'data[WorkingParty][start_date]': {
                required: true,
                datevalidateformat: true
            },
            'data[WorkingParty][name]': {
                required: true,
            },
            'data[WorkingParty][capacity]': {
                required: true,
            },
            'data[WorkingParty][remarks]': {
                    loginRegex: true,
                    maxlength: 250
            },
            'data[WorkingParty][officer_incharge]': {
                required: true
            }
        },
        messages: {
            'data[WorkingParty][start_date]': {
                required: "Please choose date of birth.",
                datevalidateformat: "Wrong Date Format"
            },
            'data[WorkingParty][name]': {
                required: "Please enter name of working party.",
            },
            'data[WorkingParty][capacity]': {
                required: "Please enter working party capacity.",
            },
            'data[WorkingParty][remarks]': {
                loginRegex: "Remarks must contain only letters, numbers, Special Characters ( Comma, Hyphen, Dot, Spaces)",
                maxlength: "Please enter no more than 250 characters.",
            },
            'data[WorkingParty][officer_incharge]': {
                required: "Please select In charge working party"
            }
        }, 
    });
});
</script>

