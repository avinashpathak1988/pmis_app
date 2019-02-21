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
                        <?php 
                        if($is_lingterm == 1){ ?>
                            <li><a href="#stageAssign" id="stageAssignDiv">Current Stage</a></li>
                            <?php
                            if(!empty($stagehistorylast)){
                                if(!in_array($stagehistorylast["StageHistory"]["stage_id"], array(Configure::read('SPECIAL-STAGE'),Configure::read('STAGE-I')))){
                                    ?>
                            <li><a href="#stagePromotion" id="stagePromotionDiv">Stage Promotion</a></li>
                            <li><a href="#stageReinstatement" id="stageReinstatementDiv">Stage Reinstatement</a></li>
                                    <?php
                                }
                            }
                            ?>
                           
                           <li><a href="#stageHistory" id="stageHistoryDiv">Stage History</a></li>
                            
                            <!-- <li class="pull-right controls"> -->
                            <li class="controls pull-right" style="margin-top: -40px;">
                                <ul class="nav nav-tabs">
                                    <li><a href="#prev">&lsaquo; Prev</a></li>
                                    <li><a href="#next">Next &rsaquo;</a></li>
                                </ul>
                            </li>
                            <?php
                        }
                        else{
                            ?>
                            <li><a href="#stageAssign" id="stageAssignDiv">Current Stage</a></li>
                            <?php
                        }
                            ?>
                        </ul>
                        <div class="tabscontent">
                            <div id="stageAssign">
                               
                                <div class="row-fluid" style="padding-bottom: 14px;">

                                    <div class="span6" style="padding-left: 20px;">
                                        <div class="control-group">
                                            <label class="control-label">Date of Assign :
                                                <!-- <div class="controls"> -->
                                                <?php
                                                    if(!empty($stagehistorylast)){
                                                        if($stagehistorylast["StageHistory"]["date_of_stage"]!=''){
                                                            echo date('d-m-Y', strtotime($stagehistorylast["StageHistory"]["date_of_stage"]));
                                                        }
                                                        else{
                                                            echo "N/A";
                                                        }
                                                    }
                                                    else{
                                                        echo "N/A";
                                                    }
                                                ?>
                                            </label>
                                                <!-- </div> -->
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">Stage Name :
                                                <!-- <div class="controls"> -->
                                                    <?php
                                                        if(!empty($stagehistorylast)){
                                                            if($stagehistorylast["StageHistory"]["stage_id"]!=''){
                                                                echo $stagehistorylast["Stage"]["name"];
                                                            }
                                                            else{
                                                                echo "N/A";
                                                            }
                                                        }
                                                        else{
                                                            echo "N/A";
                                                        }
                                                    ?></label>
                                                <!-- </div> -->
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">Previlages :
                                                <!-- <div class="controls"> -->
                                                    <?php
                                                    if(isset($stagehistorylast["StageHistory"]["stage_id"]) && $stagehistorylast["StageHistory"]["stage_id"]!=''){
                                                        $funcall->loadModel('Privilege');
                                                        $privilagesData = $funcall->Privilege->find("all", array(
                                                            "conditions"    => array(
                                                                "Privilege.stage_id"    => $stagehistorylast["StageHistory"]["stage_id"],
                                                            ),
                                                        ));
                                                    }
                                                    
                                                    if(isset($privilagesData) && is_array($privilagesData) && count($privilagesData)>0){
                                                        foreach ($privilagesData as $privilagesDatakey => $privilagesDatavalue) {
                                                            if($privilagesDatavalue['Privilege']['interval_week']!=''){
                                                                echo $funcall->getName($privilagesDatavalue['Privilege']['privilege_right_id'],"PrivilegeRight","name")." in ".$privilagesDatavalue['Privilege']['interval_week']." weeks<br>";
                                                            }else{
                                                                echo $funcall->getName($privilagesDatavalue['Privilege']['privilege_right_id'],"PrivilegeRight","name")."<br>";
                                                            }
                                                        }
                                                    }
                                                    ?></label>
                                                <!-- </div> -->
                                        </div>
                                    </div>                             
                                       
                                </div>   
                                
                            </div>
                            <?php
                            
                             //if($is_lingterm == 1){ ?>
                            <div id="stagePromotion" align="center" style="display:none;">
                                <?php
                                $stageCondition = $funcall->checkStagePromotion($prisoner_id, $stagehistorylast["StageHistory"]["stage_id"]);
                                // debug($stageCondition);
                                ?>
                                <span style="text-align: center;font-weight: bold;">
                                    <?php
                                    echo $stageCondition['message'];
                                    ?>
                                </span>
                                    <?php echo $this->Form->create('StagePromotion',array('class'=>'form-horizontal','type'=>'file'));?>
                                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                                    <?php echo $this->Form->input('prisoner_id', array('type'=>'hidden', 'value'=>$prisoner_id))?>
                                    <?php echo $this->Form->input('uuid', array('type'=>'hidden', ))?>
                                    <div class="row" style="padding-bottom: 14px;">
                                      <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Date of Promotion <?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('promotion_date',array('div'=>false,'label'=>false,'class'=>'form-control myd ate span11','type'=>'text', 'placeholder'=>'Enter Date of Promotion','required','readonly'=>'readonly','id'=>'promotion_date','value'=>date('d-m-Y')));?>
                                                </div>
                                            </div>
                                        </div>                             
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Old Stage Name<?php echo $req; ?> :</label>
                                                <div class="controls">

                                                    <?php
                                                    $old_stage_id="";
                                                    $old_stage_nm="";
                                                        if(!empty($stagehistorylast)){
                                                            if($stagehistorylast["StageHistory"]["stage_id"]!=''){
                                                                $old_stage_id=$stagehistorylast["StageHistory"]["stage_id"];
                                                                $old_stage_nm=$stagehistorylast["Stage"]["name"];
                                                            }
                                                            
                                                        }
                                                        $new_stage_name ='' ;
                                                       $new_stage_id =  $old_stage_id + 1;
                                                       foreach ($newSatgeList as $key => $value) {
                                                           if($key == $new_stage_id){
                                                            $new_stage_name =$value;
                                                           }
                                                       }
                                                       // /echo $new_stage_name;
                                                       //$new_stage_name =  $newSatgeList[] ;

                                                        
                                                    ?>
                                                    <?php echo $this->Form->input('old_stage_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'hidden','value'=>$old_stage_id));?>
                                                    <?php echo $this->Form->input('old_stage_nm',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','required','readonly'=>'readonly','value'=>$old_stage_nm));?>
                                                    <?php //echo $this->Form->input('old_stage_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$oldSatgeList, 'empty'=>'-- Select Stage --','required','id'=>'disease_id'));?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="clearfix"></div> 
                                         <div class="span6">
                                         <div class="control-group">
                                                <label class="control-label">New Stage Name<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('new_stage_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'hidden','value'=>$new_stage_id));?>
                                                    
                                                    <?php echo $this->Form->input('new_stage_name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','required','readonly'=>'readonly','value'=>$new_stage_name));?>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Reason<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('comment',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'comment', 'cols'=>30, 'rows'=>3));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div> 
                                    </div>
                                    <div class="form-actions" align="center">
                                        <?php       
                                        //$button, remove for testing earning  
                                        echo ($stageCondition['button']) ? $this->Form->button('Add To Promotion List', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'formnovalidate'=>true)) : ''; ?>
                                    </div>
                                    <?php echo $this->Form->end();?>
        
                                <div class="table-responsive" id="sickListingDiv">

                                </div>
                            </div>
                            
                            
                            <div id="stageReinstatement">
                                
                                    <?php echo $this->Form->create('StageReinstatement',array('','class'=>'form-horizontal',));?>
                                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                                    <?php echo $this->Form->input('prisoner_id', array('type'=>'hidden', 'value'=>$prisoner_id))?>
                                    <?php echo $this->Form->input('uuid', array('type'=>'hidden', ))?>
                                    <div class="row" style="padding-bottom: 14px;">
                                      <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Reinstatement Date <?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('reinstatement_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text', 'placeholder'=>'Enter Date of Reinstatement  Date','required','readonly'=>'readonly','value'=>date('d-m-Y')));?>
                                                </div>
                                            </div>
                                        </div>                             
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Reinstated Stage Name<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                <?php echo $this->Form->input('stage_reinstated_to',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'hidden','value'=>$old_stage_id));?>
                                                    <?php echo $this->Form->input('old_stage_nm',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','required','readonly'=>'readonly','value'=>$old_stage_nm));?>
                                                    <?php //echo $this->Form->input('stage_reinstated_to',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$reinstated_stage_List, 'empty'=>'-- Select Stage --','required'));?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="clearfix"></div> 
                                         <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Probationary  Period<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                <?php echo $this->Form->input('probationary_period',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$probationary_period_list, 'empty'=>'-- Select Probationary  Period --','required'));?>
                                                     <?php //echo $this->Form->input('probationary_period',array('type'=>'text', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'probationary_period'));?>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="span6">
                                           <div class="control-group">
                                                <label class="control-label">Remark<?php echo $req; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('comment',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'comment', 'cols'=>30, 'rows'=>3));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                         
                                        
                                    </div>
                                    <div class="form-actions" align="center">
                                        <?php echo $this->Form->button('Add To Reinstatement List', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'formnovalidate'=>true, 'onclick'=>"javascript:return confirm('Are you sure to add more?')"))?>
                                    </div>
                                    <?php echo $this->Form->end();?>
                    
                                 <div class="table-responsive" id="stageReinstatementDivs">

                                </div>             
                            </div>
                            <div id="stageHistory">
                                
                                <div class="table-responsive" id="stageHistoryListDivs">

                                </div>
                            </div>
                            <?php
                        //}
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
    function deleteStagePromotionRecords(paramId,stagePromotion_uuid,new_stage_id,prisoner_id){
        if(paramId){
            if(confirm('Are you sure to delete?')){
                var url = '".$deletestagePromotionUrl."';
                url             = url + '/new_stage_id:'+new_stage_id;
                url             = url + '/paramId:'+paramId;
                url             = url + '/stagePromotion_uuid:'+stagePromotion_uuid;
                url             = url + '/prisoner_id:'+prisoner_id;
                
                $.post(url, {}, function(res) {
                    if(res == 'SUCC'){
                        showStagePromotionRecords();
                        location.reload();
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
  function deleteStageReinstatementRecords(paramId,stagesReinstatement_uuid,stage_reinstated_to,prisoner_id){
        if(paramId){
            if(confirm('Are you sure to delete?')){
                var url = '".$deletestageReinstatementUrl."';
                url             = url + '/stage_reinstated_to:'+stage_reinstated_to;
                url             = url + '/paramId:'+paramId;
                url             = url + '/stagesReinstatement_uuid:'+stagesReinstatement_uuid;
                url             = url + '/prisoner_id:'+prisoner_id;
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