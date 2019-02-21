<?php
if(isset($this->data['StagePromotion']['promotion_date']) && $this->data['StagePromotion']['promotion_date'] != ''){
    $this->request->data['StagePromotion']['promotion_date'] = date('d-m-Y', strtotime($this->data['StagePromotion']['promotion_date']));
}

if(isset($this->data['StageDemotion']['demotion_date']) && $this->data['StageDemotion']['demotion_date'] != ''){
    $this->request->data['StageDemotion']['demotion_date'] = date('d-m-Y', strtotime($this->data['StageDemotion']['demotion_date']));
}
?>
<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div id="commonheader"></div>
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Stage Records</h5>
                    <div style="float:right;padding-top: 3px;">
                        <?php echo $this->Html->link('Prisoners List',array('controller'=>'prisoners','action'=>'index'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                        <ul class="nav nav-tabs">
                        <?php if($is_lingterm == 1){ ?>
                            <li><a href="#stageAssign" id="stageAssignDiv">Stage Assign</a></li>
                            <li><a href="#stagePromotion" id="stagePromotionDiv">Stage Promotion</a></li>
                            <li><a href="#stageReinstatement" id="stageReinstatementDiv">Stage Reinstatement</a></li>
                           <li><a href="#stageHistory" id="stageHistoryDiv">Stage History</a></li>
                            
                            <!-- <li class="pull-right controls"> -->
                            <li class="controls pull-right">
                                <ul class="nav nav-tabs">
                                    <li><a href="#prev">&lsaquo; Prev</a></li>
                                    <li><a href="#next">Next &rsaquo;</a></li>
                                </ul>
                            </li>
                            <?php
                        }
                        else{
                            ?>
                            <li><a href="#stageAssign" id="stageAssignDiv">Stage Assign</a></li>
                            <?php

                        }
                            ?>
                        </ul>
                        <div class="tabscontent">
                            <div id="stageAssign">
                                <?php if($isAccess == 1){?>
                                    <?php echo $this->Form->create('StageAssign',array('class'=>'form-horizontal','type'=>'file'));?>
                                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                                    <?php echo $this->Form->input('prisoner_id', array('type'=>'hidden', 'value'=>$prisoner_id))?>
                                    <?php echo $this->Form->input('uuid', array('type'=>'hidden', ))?>
                                    <div class="row" style="padding-bottom: 14px;">
                                      <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Date of Assign <?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('date_of_assign',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Date of assign','required','readonly'=>'readonly','id'=>'date_of_assign'));?>
                                                </div>
                                            </div>
                                        </div>                             
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Stage Name<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('stage_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$newSatgeList, 'empty'=>'-- Select Stage --','required','id'=>'stage_name'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div> 
                                         <div class="span6">
                                          <div class="control-group">
                                                <label class="control-label">Comment<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('comment',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'comment', 'cols'=>30, 'rows'=>3));?>
                                                </div>
                                            </div>
                                            
                                        </div>
                                      
                                        <div class="clearfix"></div> 
                                    </div>
                                    <div class="form-actions" align="center">
                                        <?php echo $this->Form->button('Add More', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'formnovalidate'=>true, 'onclick'=>"javascript:return confirm('Are you sure to add more?')"))?>
                                    </div>
                                    <?php echo $this->Form->end();?>
                                <?php }?>
                                <div class="table-responsive" id="stageAssignListDiv">

                                </div>
                            </div>
                            <?php if($is_lingterm == 1){ ?>
                            <div id="stagePromotion">
                                <?php if($isAccess == 1){?>
                                    <?php echo $this->Form->create('StagePromotion',array('class'=>'form-horizontal','type'=>'file'));?>
                                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                                    <?php echo $this->Form->input('prisoner_id', array('type'=>'hidden', 'value'=>$prisoner_id))?>
                                    <?php echo $this->Form->input('uuid', array('type'=>'hidden', ))?>
                                    <div class="row" style="padding-bottom: 14px;">
                                      <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Date of Promotion <?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('promotion_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Date of Promotion','required','readonly'=>'readonly','id'=>'promotion_date'));?>
                                                </div>
                                            </div>
                                        </div>                             
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Old Stage Name<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('old_stage_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$oldSatgeList, 'empty'=>'-- Select Stage --','required','id'=>'disease_id'));?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="clearfix"></div> 
                                         <div class="span6">
                                         <div class="control-group">
                                                <label class="control-label">New Stage Name<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('new_stage_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$newSatgeList, 'empty'=>'-- Select Stage --','required','id'=>'disease_id'));?>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Comment<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('comment',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'comment', 'cols'=>30, 'rows'=>3));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div> 
                                    </div>
                                    <div class="form-actions" align="center">
                                        <?php echo $this->Form->button('Add More', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'formnovalidate'=>true, 'onclick'=>"javascript:return confirm('Are you sure to add more?')"))?>
                                    </div>
                                    <?php echo $this->Form->end();?>
                                <?php }?>
                                <div class="table-responsive" id="sickListingDiv">

                                </div>
                            </div>
                            <div id="stageDemotion">
                                <?php if($isAccess == 1){?>
                                    <?php echo $this->Form->create('StageDemotion',array('','class'=>'form-horizontal',));?>
                                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                                    <?php echo $this->Form->input('prisoner_id', array('type'=>'hidden', 'value'=>$prisoner_id))?>
                                    <?php echo $this->Form->input('uuid', array('type'=>'hidden', ))?>
                                    <div class="row" style="padding-bottom: 14px;">
                                      <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Demotion Date <?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('demotion_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Demotion  Date','required','readonly'=>'readonly',));?>
                                                </div>
                                            </div>
                                        </div>                             
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Old Stage Name<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('old_stage_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$oldSatgeList, 'empty'=>'-- Select Stage --','required','id'=>'disease_id'));?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="clearfix"></div> 
                                         <div class="span6">
                                         <div class="control-group">
                                                <label class="control-label">New Stage Name<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('new_stage_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$newSatgeList, 'empty'=>'-- Select Stage --','required','id'=>'disease_id'));?>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="span6">
                                           <div class="control-group">
                                                <label class="control-label">Probationary  Period<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                     <?php echo $this->Form->input('probationary_period',array('type'=>'text', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'probationary_period'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                         
                                         <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Comment<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('comment',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'comment', 'cols'=>30, 'rows'=>3));?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="clearfix"></div> 
                                    </div>
                                    <div class="form-actions" align="center">
                                        <?php echo $this->Form->button('Add More', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'formnovalidate'=>true, 'onclick'=>"javascript:return confirm('Are you sure to add more?')"))?>
                                    </div>
                                    <?php echo $this->Form->end();?>
                                <?php }?>
                                 <div class="table-responsive" id="stageDemotionDivs">

                                </div>             
                            </div>
                            
                            <div id="stageReinstatement">
                                <?php if($isAccess == 1){?>
                                    <?php echo $this->Form->create('StageReinstatement',array('','class'=>'form-horizontal',));?>
                                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                                    <?php echo $this->Form->input('prisoner_id', array('type'=>'hidden', 'value'=>$prisoner_id))?>
                                    <?php echo $this->Form->input('uuid', array('type'=>'hidden', ))?>
                                    <div class="row" style="padding-bottom: 14px;">
                                      <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Reinstatement Date <?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('reinstatement_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Date of Reinstatement  Date','required','readonly'=>'readonly',));?>
                                                </div>
                                            </div>
                                        </div>                             
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Reinstated Stage Name<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('stage_reinstated_to',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$reinstated_stage_List, 'empty'=>'-- Select Stage --','required'));?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="clearfix"></div> 
                                         <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Probationary  Period<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                     <?php echo $this->Form->input('probationary_period',array('type'=>'text', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'probationary_period'));?>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="span6">
                                           <div class="control-group">
                                                <label class="control-label">Comment<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('comment',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'comment', 'cols'=>30, 'rows'=>3));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                         
                                        
                                    </div>
                                    <div class="form-actions" align="center">
                                        <?php echo $this->Form->button('Add More', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'formnovalidate'=>true, 'onclick'=>"javascript:return confirm('Are you sure to add more?')"))?>
                                    </div>
                                    <?php echo $this->Form->end();?>
                                <?php }?>
                                 <div class="table-responsive" id="stageReinstatementDivs">

                                </div>             
                            </div>
                            <div id="stageHistory">
                                
                                <div class="table-responsive" id="stageHistoryListDivs">

                                </div>
                            </div>
                            <?php
                        }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$stageAssignUrl         = $this->Html->url(array('controller'=>'Stages','action'=>'stagesAssignAjax'));
$deleteStageAssignUrl   = $this->Html->url(array('controller'=>'Stages','action'=>'deleteStageAssign'));
$stagePromotionUrl         = $this->Html->url(array('controller'=>'Stages','action'=>'stagesPromotionAjax'));
$deletestagePromotionUrl   = $this->Html->url(array('controller'=>'Stages','action'=>'deleteStagePromotion'));
$stageDemotionUrl   = $this->Html->url(array('controller'=>'Stages','action'=>'stagesDemotionAjax'));
$deletestageDemotionUrl   = $this->Html->url(array('controller'=>'Stages','action'=>'deleteStageDemotion'));
$stageReinstatementUrl   = $this->Html->url(array('controller'=>'Stages','action'=>'stagesReinstatementAjax'));
$deletestageReinstatementUrl   = $this->Html->url(array('controller'=>'Stages','action'=>'deleteStageReinstatement'));
$stageHistoryUrl   = $this->Html->url(array('controller'=>'Stages','action'=>'stagesHistoryAjax'));

$commonHeaderUrl    = $this->Html->url(array('controller'=>'Prisoners','action'=>'getCommonHeder'));

echo $this->Html->scriptBlock("
    var tab_param = '';
    var tabs;
    jQuery(function($) {

        showCommonHeader();
        
        tabs = $('.tabscontent').tabbedContent({loop: true}).data('api');
        // Next and prev actions
        $('.controls a').on('click', function(e) {
            var action = $(this).attr('href').replace('#', ''); 
            tabs[action]();
            e.preventDefault();
        });
        showStageAssignRecords(); 
        $('#stageAssignDiv').on('click', function(e){
            
             showStageAssignRecords();
        });
        $('#stagePromotionDiv').on('click', function(e){
            
             showStagePromotionRecords();
        });
        $('#stageDemotionDiv').on('click', function(e){
            showStageDemotionRecords();
        });
        $('#stageReinstatementDiv').on('click', function(e){
            showStageReinstatementRecords();
        });
        $('#stageHistoryDiv').on('click', function(e){
            showStageHistoryRecords();
        });
        var cururl = window.location.href;
        var urlArr = cururl.split('/');
        var param = '';
        for(var i=0; i<urlArr.length;i++){
            param = urlArr[i];
        }
        if(param != ''){
            var paramArr = param.split('#');
            for(var i=0; i<paramArr.length;i++){
                tab_param    = paramArr[i];
            }
        }
        console.log(tab_param);
        if(tab_param == 'stagePromotion'){
            showStagePromotionRecords();
        }else if(tab_param == 'stageDemotion'){
            showStageDemotionRecords();
        }
        else if(tab_param == 'stageReinstatement'){
            showStageReinstatementRecords();
        }
    });
   
    function showStageAssignRecords(){
        var prisoner_id = ".$prisoner_id.";
        var uuid        = '".$uuid."';
        var url         = '".$stageAssignUrl."';
        url = url + '/prisoner_id:'+prisoner_id;
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
            if (res) {
                $('#stageAssignListDiv').html(res);
            }
        }); 
    }
    function deleteStageAssignRecords(paramId){
        if(paramId){
            if(confirm('Are you sure to delete?')){
                var url = '".$deleteStageAssignUrl."';
                $.post(url, {'paramId':paramId}, function(res) {
                    if(res == 'SUCC'){
                        showStageAssignRecords();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }
    function showStagePromotionRecords(){
        var prisoner_id = ".$prisoner_id.";
        var uuid        = '".$uuid."';
        var url         = '".$stagePromotionUrl."';
        url = url + '/prisoner_id:'+prisoner_id;
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
            if (res) {
                $('#sickListingDiv').html(res);
            }
        }); 
    }
    function deleteStagePromotionRecords(paramId){
        if(paramId){
            if(confirm('Are you sure to delete?')){
                var url = '".$deletestagePromotionUrl."';
                $.post(url, {'paramId':paramId}, function(res) {
                    if(res == 'SUCC'){
                        showStagePromotionRecords();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }
function showStageDemotionRecords(){
        var prisoner_id = ".$prisoner_id.";
        var uuid        = '".$uuid."';
        var url         = '".$stageDemotionUrl."';
        url             = url + '/prisoner_id:'+prisoner_id;
        url             = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
            if (res) {
                $('#stageDemotionDivs').html(res);
            }
        });         
    }
    function deleteStageDemotionRecords(paramId){

        if(paramId){
            if(confirm('Are you sure to delete?')){
                var url = '".$deletestageDemotionUrl."';
                $.post(url, {'paramId':paramId}, function(res) {
                    
                    if(res == 'SUCC'){
                        showStageDemotionRecords();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }
   function showStageReinstatementRecords()
   {
     var prisoner_id = ".$prisoner_id.";
        var uuid        = '".$uuid."';
        var url         = '".$stageReinstatementUrl."';
        url             = url + '/prisoner_id:'+prisoner_id;
        url             = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
            if (res) {
                $('#stageReinstatementDivs').html(res);
            }
        });    
   }
  function deleteStageReinstatementRecords(paramId){
        if(paramId){
            if(confirm('Are you sure to delete?')){
                var url = '".$deletestageReinstatementUrl."';
                $.post(url, {'paramId':paramId}, function(res) {
                    res=res.trim();
                    if(res == 'SUCC'){
                        showStageReinstatementRecords();
                    }else{
                        alert('Invalid request, please try again!');
                    }
                });
            }
        }
    }
    function showStageHistoryRecords()
   {
     var prisoner_id = ".$prisoner_id.";
        var uuid        = '".$uuid."';
        var url         = '".$stageHistoryUrl."';
        url             = url + '/prisoner_id:'+prisoner_id;
        url             = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
            if (res) {
                $('#stageHistoryListDivs').html(res);
            }
        });    
   }
    //common header
    function showCommonHeader(){ 
        var prisoner_id = ".$prisoner_id.";;
        console.log(prisoner_id);  
        var uuid        = '".$uuid."';
        var url         = '".$commonHeaderUrl."';
        url = url + '/prisoner_id:'+prisoner_id;
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
           
            if (res) {
                $('#commonheader').html(res);
            }
        }); 
    }

",array('inline'=>false));
?> 