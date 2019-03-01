<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
             <?php echo $this->element('social_rehab_menu');   ?>               
            
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Non Formal Education</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Add New'), array('action' => 'addNonFormalEducation'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                	
                    <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    
                        <h5>Search Non Formal Education</h5>
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
                                                <label class="control-label">Start Date:</label>
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
                                <?php echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData('SearchNonFormalEducationForm')"))?>
                            </div> 
                    </div> 
                                <?php echo $this->Form->end();?>
                    </div>
                        <div class="NonFormalEducationList" id="NonFormalEducationList">

                        </div>
                        <div id="addHeadRemarkModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->

                            <?php echo $this->Form->create('NonFormalEducation',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <?php echo $this->Form->input('id',array('div'=>false,'label'=>false,'class'=>'form-control ','id'=>'non_formal_id','type'=>'hidden'));?>
                            

                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Add Head of Program Remark and Prisoner Award</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="control-group">
                                    <label class="control-label">Head of Programme Remark :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('head_remark',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'text','placeholder'=>'Enter Head of Programme Remark','id'=>'head_remark','rows'=>3,'required'=>false,'maxlength'=>1000));?>
                                    </div>
                                    </div>
                                    <div class="control-group">
                                            <label class="control-label">Awarded :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('awarded',array('div'=>false,'label'=>false,'class'=>'form-control awarded','type'=>'textarea','placeholder'=>'Awarded','id'=>'awarded','rows'=>1,'required'=>false));?>
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
<?php

$ajaxUrlNonFormalList = $this->Html->url(array('controller'=>'Education','action'=>'NonFormalDataAjax'));
$ajaxUrlDiscontinue = $this->Html->url(array('controller'=>'SocialRehabiliationProgramme','action'=>'discontinueItem'));
$ajaxUrlsubmitHeadRemark = $this->Html->url(array('controller'=>'SocialRehabiliationProgramme','action'=>'submitHeadRemark'));
$ajaxUrlFinalSave = $this->Html->url(array('controller'=>'SocialRehabiliationProgramme','action'=>'finalSave'));
$ajaxUrlcontinue = $this->Html->url(array('controller'=>'SocialRehabiliationProgramme','action'=>'continueItem'));
?>
<script type="text/javascript">

    $( document ).ready(function() {
            $('#sprisoner_no').select2();
		

        var url ='<?php echo $ajaxUrlNonFormalList?>';
        $.post(url,{}, function(res) {
            if (res) {
                $('#NonFormalEducationList').html(res);
                
             }else{
                $('#NonFormalEducationList').html('No records found !');

             }
        });    
    });
function finalSave(id){
        console.log(id);
        
        if(confirm("Are you sure you want to save?")){
            var url ='<?php echo $ajaxUrlFinalSave?>';
        $.post(url,{'id':id,'model':'NonFormalEducation'}, function(res) {
                if (res == 'success') {
                    //dynamicAlertBox('Message', 'continued successfully !');
                    location.reload();
                }else{
                    dynamicAlertBox('Message', 'continuation failed !');
                }
            });
        }
        
    }
    function continueItem(id){
    console.log(id);
     var url ='<?php echo $ajaxUrlcontinue?>';
        $.post(url,{'id':id,'model':'NonFormalEducation'}, function(res) {
                if (res == 'success') {
                    //dynamicAlertBox('Message', 'continued successfully !');
                    location.reload();
                }else{
                    dynamicAlertBox('Message', 'continuation failed !');
                }
            });
  }
function submitHeadRemark(){
    var url ='<?php echo $ajaxUrlsubmitHeadRemark?>';
        $.post(url,$('#NonFormalEducationNonFormalEducationForm').serialize(), function(res) {
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
        $.post(url,{'id':id,'model':'NonFormalEducation'}, function(res) {
                if (res == 'success') {
                    //dynamicAlertBox('Message', 'Discontinued successfully !');
                    location.reload();

                }else{
                   dynamicAlertBox('Message', 'Discontinuation failed !');
                }
            });
  }

    function showListSearch(){
   
        var url ='<?php echo $ajaxUrlNonFormalList?>';
        $.post(url, $('#SearchNonFormalEducationForm').serialize(), function(res) {
            if (res) {
                        $('#NonFormalEducationList').html(res);
                 }else{
                    console.log('here');
                        $('#NonFormalEducationList').html('No records found !');

                     }
            });
     }
   
    function resetData(id){
        $('#'+id)[0].reset();

        $('select').select2().select2("val", null);
        showListSearch();
    }
</script>            
