<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
             <?php echo $this->element('social_rehab_menu');   ?>               
            
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Specific Case Treatment</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Add New'), array('action' => 'addSpecificCaseTreatment'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
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
                                                <label class="control-label">End Date:</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('sprisoner_end_date',array('div'=>false,'label'=>false,'class'=>'form-control to_date','id'=>'to_date','type'=>'text','readonly'=>'readonly', 'placeholder'=>'Enter End Date'));?>
                                                </div>
                                </div>
                                
                          </div>
                          <div class="span12 add-top" align="center" valign="center">
                                <?php echo $this->Form->button('Search', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'id'=>'btnsearchcash', 'onclick'=>"showListSearch();"))?>
                                <?php echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData('SearchSpecificCaseTreatmentForm')"))?>
                            </div> 
                    </div> 
                                <?php echo $this->Form->end();?>
                    </div>
                    <div id="SpecificCaseTreatmentList"></div>
                    <div id="addHeadRemarkModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->

                            <?php echo $this->Form->create('SpecificCaseTreatment',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <?php echo $this->Form->input('id',array('div'=>false,'label'=>false,'class'=>'form-control ','id'=>'specific_case_id','type'=>'hidden'));?>
                            

                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Add Head of Program Remark </h4>
                                </div>
                                <div class="modal-body">
                                    <div class="control-group">
                                    <label class="control-label">Head of Programme Remark :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('head_remark',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'text','placeholder'=>'Enter Head of Programme Remark','id'=>'head_remark','rows'=>3,'required'=>false,'maxlength'=>1000));?>
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
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
<?php

$ajaxUrlSpecificCaseTreatmentList = $this->Html->url(array('controller'=>'SocialRehabiliationProgramme','action'=>'specificCaseTreatmentAjax'));
$ajaxUrlDiscontinue = $this->Html->url(array('controller'=>'SocialRehabiliationProgramme','action'=>'discontinueItem'));
$ajaxUrlcontinue = $this->Html->url(array('controller'=>'SocialRehabiliationProgramme','action'=>'continueItem'));
$ajaxUrlsubmitHeadRemark = $this->Html->url(array('controller'=>'SocialRehabiliationProgramme','action'=>'submitHeadRemark'));
$ajaxUrlFinalSave = $this->Html->url(array('controller'=>'SocialRehabiliationProgramme','action'=>'finalSave'));
?>
<script type="text/javascript">

    $( document ).ready(function() {
            $('#sprisoner_no').select2();
		  
            var url ='<?php echo $ajaxUrlSpecificCaseTreatmentList?>';
            $.post(url,{},function(res) {
                if (res) {
                    $('#SpecificCaseTreatmentList').html(res);
                    
                 }else{
                    
                    $('#SpecificCaseTreatmentList').html('No records found !');

                 }
            });
    });  
     
function showListSearch(){
   
        var url ='<?php echo $ajaxUrlSpecificCaseTreatmentList?>';
        $.post(url, $('#SearchSpecificCaseTreatmentForm').serialize(), function(res) {
            if (res) {
                        $('#SpecificCaseTreatmentList').html(res);
                 }else{
                        $('#SpecificCaseTreatmentList').html('No records found !');

                     }
            });
 }
 function finalSave(id){
        console.log(id);
        
        if(confirm("Are you sure you want to save?")){
            var url ='<?php echo $ajaxUrlFinalSave?>';
        $.post(url,{'id':id,'model':'SpecificCaseTreatment'}, function(res) {
                if (res == 'success') {
                    //dynamicAlertBox('Message', 'continued successfully !');
                    location.reload();
                }else{
                    dynamicAlertBox('Message', 'continuation failed !');
                }
            });
        }
        
    }
function submitHeadRemark(){
    var url ='<?php echo $ajaxUrlsubmitHeadRemark?>';
        $.post(url,$('#SpecificCaseTreatmentSpecificCaseTreatmentForm').serialize(), function(res) {
                if (res == 'success') {
                    //dynamicAlertBox('Message', 'continued successfully !');
                    location.reload();
                }else{
                    dynamicAlertBox('Message', 'continuation failed !');
                }
            });
  }

function discontinueItem(id){
    console.log(id);
    var url ='<?php echo $ajaxUrlDiscontinue?>';
        $.post(url,{'id':id,'model':'SpecificCaseTreatment'}, function(res) {
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
        $.post(url,{'id':id,'model':'SpecificCaseTreatment'}, function(res) {
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
</script>            
