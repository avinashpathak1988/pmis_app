<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
             <?php echo $this->element('social_rehab_menu');   ?>               
            
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Counselling and Guidance</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Add New'), array('action' => 'addCounsellingAndGuidance'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                
                <div class="widget-content nopadding">
                    
                    <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    
                    <h5>Search</h5>
                </div>
                
                    <div class="row-fluid">
            
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                        <div id="searchPrisonerTwo" class="row collapse" style="height:auto;">
                          <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Prisoner No. :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('sprisoner_no',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','options'=>$prisonersList, 'empty'=>'-- Select Prisoner no --','required'=>false,'id'=>'sprisoner_no'));?>
                                    </div>
                                </div>
                                <div class="control-group start_date_search">
                                                <label class="control-label">Start Date :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('sprisoner_start_date',array('div'=>false,'label'=>false,'class'=>'form-control from_date','id'=>'from_date','type'=>'text','readonly'=>'readonly', 'placeholder'=>'Enter Start Date'));?>
                                                </div>
                                </div> 
                                
                                
                          </div>
                          <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Prisoner Name. :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('sprisoner_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 ', 'type'=>'text','placeholder'=>'Search Prisoner Name.','id'=>'sprisoner_name', 'style'=>'width:200px;'));?>
                                    </div>
                                </div>
                                <div class="control-group end_date_search">
                                                <label class="control-label">End Date :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('sprisoner_end_date',array('div'=>false,'label'=>false,'class'=>'form-control to_date','id'=>'to_date','type'=>'text','readonly'=>'readonly', 'placeholder'=>'Enter End Date'));?>
                                                </div>
                                </div>
                                
                          </div>
                          <div class="span12 add-top" align="center" valign="center">
                                <?php echo $this->Form->button('Search', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'id'=>'btnsearchcash', 'onclick'=>"showListSearch();"))?>
                                <?php echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData('SearchCounsellingAndGuidanceForm')"))?>
                            </div> 
                    </div> 
                                <?php echo $this->Form->end();?>
                    </div>
                    <div id="CouncelingAndGuidanceList"></div>
                    <div id="addHeadRemarkModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->

                            <?php echo $this->Form->create('CounsellingAndGuidance',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <?php echo $this->Form->input('id',array('div'=>false,'label'=>false,'class'=>'form-control ','id'=>'counceling_id','type'=>'hidden'));?>
                            

                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Add Head of Program Remark </h4>
                                </div>
                                <div class="modal-body">
                                    <?php echo $this->Form->input('session',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'hidden','required'));?>
                                    <div class="control-group">
                                    <label class="control-label">Head of Programme Remark :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('head_remark',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'text','placeholder'=>'Enter Head of Programme Remark','id'=>'head_remark','rows'=>3,'required','maxlength'=>1000));?>
                                    </div>
                                </div>
                                    
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="HeadRemarkSaveBtn" class="btn btn-success formSaveBtn" formnovalidate="true", onclick="submitHeadRemark()">Save</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                                <?php echo $this->Form->end();?>

                        </div>
                    </div> <!-- end head remark modal -->
                    <!-- Modal -->
                    <div id="changeSessionModal" class="modal fade" role="dialog" style="z-index: 9999 !important;">
                        <div class="modal-dialog">

                            <!-- Modal content-->

                            <?php echo $this->Form->create('CounsellingAndGuidance',array('class'=>'form-horizontal','id'=>'changeSessionForm','enctype'=>'multipart/form-data'));?>
                            
                            <?php echo $this->Form->input('id',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'hidden'));?>
                            

                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Change Session</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="control-group ">
                                        <label class="control-label">Sessions :</label>
                                        <div class="controls">
                                                <?php echo $this->Form->input('session',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','options'=>$sessionList, 'empty'=>'','required'=>false));?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                                <label class="control-label">Start Date<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('start_date',array('div'=>false,'label'=>false,'class'=>'form-control from_date2','type'=>'text','readonly'=>'readonly', 'placeholder'=>'Enter Start Date','required','id'=>'start_date'));?>
                                                </div>
                                </div>
                                <div class="control-group">
                                                <label class="control-label">End Date<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('end_date',array('div'=>false,'label'=>false,'class'=>'form-control to_date2','type'=>'text','readonly'=>'readonly', 'placeholder'=>'Enter End Date','required','id'=>'end_date'));?>
                                                </div>
                                </div>
                                <div class="control-group ">
                                        <label class="control-label">Theme :<?php echo $req; ?></label>
                                        <div class="controls">
                                                <?php echo $this->Form->input('theme',array('div'=>false,'label'=>false,'class'=>'form-control pmis_select','type'=>'select','multiple'=>'multiple','options'=>$themelist, 'empty'=>'','required'));?>
                                        </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Prisoners Input  :<?php echo $req; ?></label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prisoners_input',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'text','placeholder'=>'Enter Prisoners Input','id'=>'prisoners_input','rows'=>3,'required','maxlength'=>1000));?>
                                    </div>
                                </div>
                                    
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="SaveBtn" class="btn btn-success formSaveBtn" formnovalidate="true", onclick="submitChangeSession()">Save</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                                <?php echo $this->Form->end();?>

                        </div>
                    </div> <!-- modal ends -->

                </div>
            </div>
        </div>
    </div>
</div>
<?php

$ajaxUrlCouncelingList = $this->Html->url(array('controller'=>'SocialRehabiliationProgramme','action'=>'counsellingAndGuidanceAjax'));
$ajaxUrlDiscontinue = $this->Html->url(array('controller'=>'SocialRehabiliationProgramme','action'=>'discontinueItem'));
$ajaxUrlcontinue = $this->Html->url(array('controller'=>'SocialRehabiliationProgramme','action'=>'continueItem'));
$ajaxUrlsubmitHeadRemark = $this->Html->url(array('controller'=>'SocialRehabiliationProgramme','action'=>'submitHeadRemark'));
$ajaxUrlFinalSave = $this->Html->url(array('controller'=>'SocialRehabiliationProgramme','action'=>'finalSave'));
$ajaxUrlChangeSession = $this->Html->url(array('controller'=>'SocialRehabiliationProgramme','action'=>'changeSession'));
?>
<script type="text/javascript">

    $( document ).ready(function() {
		
            $('#sprisoner_no').select2();
             /*$('#themes_z').select2();*/
            
            var url ='<?php echo $ajaxUrlCouncelingList?>';
            $.post(url,{},function(res) {
                if (res) {
                    $('#CouncelingAndGuidanceList').html(res);
                    
                 }else{
                    
                    $('#CouncelingAndGuidanceList').html('No records found !');

                 }
            });

            $('.from_date2').datepicker({
    format: 'dd-mm-yyyy',
    startDate: new Date(),
    autoclose:true,
}).on('changeDate', function (selected) {
    var minDate = new Date(selected.date.valueOf());
    $('.to_date2').datepicker('setStartDate', minDate);
     $(this).datepicker('hide');
     $(this).blur();
});
$('.to_date2').datepicker({
    format: 'dd-mm-yyyy',
    autoclose:true,
}).on('changeDate', function (selected) {
    var minDate = new Date(selected.date.valueOf());
    $('.from_date2').datepicker('setEndDate', minDate);
     $(this).datepicker('hide');
     $(this).blur();
});
    });

     function showListSearch(){
   
        var url ='<?php echo $ajaxUrlCouncelingList?>';
        $.post(url, $('#SearchCounsellingAndGuidanceForm').serialize(), function(res) {
            if (res) {
                        $('#CouncelingAndGuidanceList').html(res);
                 }else{
                        $('#CouncelingAndGuidanceList').html('No records found !');

                     }
            });
 }
function submitHeadRemark(){
    var url ='<?php echo $ajaxUrlsubmitHeadRemark?>';
        $.post(url,$('#CounsellingAndGuidanceCounsellingAndGuidanceForm').serialize(), function(res) {
                if (res == 'success') {
                    //dynamicAlertBox('Message', 'continued successfully !');
                    location.reload();
                }else{
                    dynamicAlertBox('Message', 'continuation failed !');
                }
            });
  }
     function finalSave(id){
        console.log(id);
        
        if(confirm("Are you sure you want to save?")){
            var url ='<?php echo $ajaxUrlFinalSave?>';
        $.post(url,{'id':id,'model':'CounsellingAndGuidance'}, function(res) {
                if (res == 'success') {
                    //dynamicAlertBox('Message', 'continued successfully !');
                    location.reload();
                }else{
                    dynamicAlertBox('Message', 'continuation failed !');
                }
            });
        }
        
    }
    function submitChangeSession(){
        if($('#changeSessionForm').valid()){
                var url ='<?php echo $ajaxUrlChangeSession?>';
        $.post(url,$('#changeSessionForm').serialize(), function(res) {
                if (res == 'success') {
                    //dynamicAlertBox('Message', 'continued successfully !');
                    location.reload();
                }else{
                    dynamicAlertBox('Message', 'session change failed !');
                }
        });
        }
        
        
    }
    
    function changeSession(id,curr_session){
        console.log(curr_session);
            $("#changeSessionForm #session option").css('display','block');

        for(i=curr_session;i>0;i--){
            //$('select').val('1').trigger('change');
            //$('#s2id_CounsellingAndGuidanceSession').find("option[value='" + i + "']").css('display','none');
            //$('#changeSessionForm select').val("'"+i+"'").css('display','none');
            $("#changeSessionForm #CounsellingAndGuidanceSession option[value='"+i+"']").css('display','none');
        }
        //console.log(id);
        $('#changeSessionForm #CounsellingAndGuidanceId').val(id);
        $('#changeSessionModal').modal('show');
        
    }
    
 function discontinueItem(id){
    console.log(id);
    var url ='<?php echo $ajaxUrlDiscontinue?>';
        $.post(url,{'id':id,'model':'CounsellingAndGuidance'}, function(res) {
                if (res == 'success') {
                    //dynamicAlertBox('Message', 'Discontinued successfully !');
                    location.reload();

                }else{
                   dynamicAlertBox('Message', 'Discontinuation failed !');
                }
            });
  }
  function continueItem(id){
    console.log(id);
     var url ='<?php echo $ajaxUrlcontinue?>';
        $.post(url,{'id':id,'model':'CounsellingAndGuidance'}, function(res) {
                if (res == 'success') {
                    //dynamicAlertBox('Message', 'continued successfully !');
                    location.reload();
                }else{
                    dynamicAlertBox('Message', 'continuation failed !');
                }
            });
  }
 
 function resetData(id){
        $('#'+id)[0].reset();

        $('select').select2().select2("val", null);
        showListSearch();
    }
    $(function(){
        $("#changeSessionForm").validate({
     
            ignore: "",
                rules: {  
                    
                    'data[CounsellingAndGuidance][session]': {
                        required: true,
                    },
                    
                    
                    
                    
                },
                messages: {
                    
                    'data[CounsellingAndGuidance][session]': {
                        required: "Please select Session.",
                        
                    },
                    
                    
                },
            });
    }); 

</script>            
