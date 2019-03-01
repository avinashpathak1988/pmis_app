<?php
if(isset($this->data['GatePass']['gp_date']) && $this->data['GatePass']['gp_date'] != ''){
    $this->request->data['GatePass']['gp_date'] = date('d-m-Y', strtotime($this->data['GatePass']['gp_date']));
}
if(isset($this->data['Discharge']['escape_date']) && $this->data['Discharge']['escape_date'] != ''){
    $this->request->data['Discharge']['escape_date'] = date('d-m-Y H:i', strtotime($this->data['Discharge']['escape_date']));
}
if(isset($this->data['Discharge']['execution_date']) && $this->data['Discharge']['execution_date'] != ''){
    $this->request->data['Discharge']['execution_date'] = date('d-m-Y H:i', strtotime($this->data['Discharge']['execution_date']));
}
if(isset($this->data['Discharge']['bail_date']) && $this->data['Discharge']['bail_date'] != ''){
    $this->request->data['Discharge']['bail_date'] = date('d-m-Y', strtotime($this->data['Discharge']['bail_date']));
}
?>
<style>
.row-fluid  [class*="span"]
{
  margin-left: 0px !important;
}
input[type*="checkbox"] {
    float: left;
    margin-right: 5px;
}
</style>
<div class="container-fluid">
    <div class="row-fluid">
    <div id="commonheader"></div>
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Discharge Records</h5>
                    <div style="float:right;padding-top: 3px;">
                        <?php echo $this->Html->link('Prisoners List',array('controller'=>'prisoners','action'=>'index'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                    
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                        <ul class="nav nav-tabs">
                            <li><a href="#discharge_prisoner" id="discharge_prisoner_div">Discharge Prisoner</a></li>
                            
                            <li><a href="#child_release" id="child_release">Child Discharge</a></li>
                           
                            <!-- <li class="pull-right controls"> -->
                            <li class="controls pull-right">
                                <ul class="nav nav-tabs">
                                    <li><a href="#prev">&lsaquo; Prev</a></li>
                                    <li><a href="#next">Next &rsaquo;</a></li>
                                </ul>
                            </li>
                        </ul>
                        <div class="tabscontent">
                            <div id="discharge_prisoner">
                                <?php
                                // debug($prisonerData['Prisoner']['present_status']);
                                if($dischargeData==0 || $prisonerData['Prisoner']['present_status'] == 1){
                                ?>
                                <?php if($isAccess == 1){?>
                                <?php 
                                	if(isset($prisonerData['Prisoner']['dor']) && $prisonerData['Prisoner']['dor']!='0000-00-00'){
                                		$this->request->data['Discharge']['date_of_release'] = ($prisonerData['Prisoner']['dor']!='') ? date("d-m-Y",strtotime($prisonerData['Prisoner']['dor'])) : '';
                                	}
                                ?>
                                <?php 
                                $discharge_date = ($prisonerData['Prisoner']['dor']!='') ? $funcall->checkHoliday($prisonerData['Prisoner']['dor']) : '';
                                if(strtotime($discharge_date) < strtotime(date("d-m-Y"))){
                                    $discharge_date = date("d-m-Y");
                                }
                                // debug($discharge_date);
                                $this->request->data['Discharge']['discharge_date'] = ($discharge_date!='') ? $discharge_date : $this->request->data['Discharge']['date_of_release']; 

                                //$this->request->data['Discharge']['date_of_release'] = date("d-m-Y");
                                //$this->request->data['Discharge']['discharge_date'] = date("d-m-Y");
                                ?>
                                    <?php echo $this->Form->create('Discharge',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                                    <?php echo $this->Form->input('uuid', array('type'=>'hidden'))?>    
                                    <div class="row-fluid">
                                    	<?php
                                    	// debug($this->request->data['Discharge']['date_of_release']);
                                    	if(isset($this->request->data['Discharge']['date_of_release']) && $this->request->data['Discharge']['date_of_release']!='30-11--0001'){
                                    		if((isset($prisonerDetails['Prisoner']['is_death']) && $prisonerDetails['Prisoner']['is_death']!=1)){
                                    	?>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Date of Release <?php echo MANDATORY; ?> :</label>
                                                <div class="controls">
                                                    <?php 
                                                    echo $this->Form->input('date_of_release',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Enter Date of release','readonly'=>'readonly','class'=>'form-control span11','required',"title"=>"please update date of release"));?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    		}
                                    	}
                                        ?>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Date of Discharge <?php echo MANDATORY; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('discharge_date',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Enter Date of Discharge','readonly'=>'readonly','class'=>'form-control span11','required',"title"=>"please provide the date and time of discharge"));?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        if(strtotime($discharge_date)!=strtotime($prisonerData['Prisoner']['dor']) && strtotime($prisonerData['Prisoner']['dor'])>strtotime(date("d-m-Y"))){
                                            ?>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Reason for early release <?php echo $req; ?>:</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('Discharge.early_release',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Reason for early release','required','type'=>'textarea','rows'=>2,'title'=>"Please provide reason for early release"));?>
                                                </div>
                                            </div>
                                        </div>    
                                            <?php
                                        }
                                        ?>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">Discharge Type<?php echo MANDATORY; ?> :</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('discharge_type_id',array('type'=>'select', 'div'=>false,'label'=>false,'type'=>'select','empty'=>'--Select Discharge type--','options'=>$dischargetypeList,'class'=>'form-control','required', 'id'=>'discharge_type_id','onchange'=>"showFields(this.value)","title"=>"please select discharge type"));?>
                                                </div>
                                            </div>
                                        </div>                            
                                    </div> 
                                    <div class="row-fluid fieldsdiv" style="text-align: left;font-weight:bold;" id="showFeilds">

                                    </div>
                                    <?php echo $this->Form->end();?>
                                <?php }?>
                                <?php
                                }
                                ?>
                                <div class="table-responsive" id="listingDiv"></div>
                            </div> 

                            <div id="child_release">                                
                                <div class="table-responsive" id="DeathInCustodyListingDiv"></div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="biometricModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header" style="height: 40px;">
        <h5 class="modal-title" id="exampleModalLabel" style="float: left;">Biometric Search</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" align="center">
        <?php echo $this->Html->image('finger.gif', array('alt' => '', 'border' => '0')); ?>
        <br />
        <p>Please press finger on biometric</p>
      </div>
      <div class="modal-footer">
        <button type="button" id="testttt" onclick="stop()" class="btn btn-danger" data-dismiss="modal">Stop</button>
      </div>
    </div>
  </div>
</div>
<?php
    $biometricSearchAjax = $this->Html->url(array('controller'=>'Biometrics','action'=>'dataCheck'));
?>
<script type="text/javascript">
$(document).ready(function(){
    <?php
    if(isset($this->data['Des']) && is_array($this->data['InPrisonOffenceCapture']) && count($this->data['InPrisonOffenceCapture'])>0){
        ?>
        $('#offence_type').select2('val', '<?php echo $this->data['InPrisonOffenceCapture']['offence_type']; ?>');
        <?php
    }
    ?>
});
$(function(){
    $.validator.addMethod("loginRegex", function(value, element) {
        return this.optional(element) || /^[a-z0-9\-\,\.\s]+$/i.test(value);
    }, "Username must contain only letters, numbers, or dashes.");
     
    $.validator.addMethod( "at_least_one", function() {
        if($( "input[name='data[Discharge][clearance][]']:checked" ).length == 5){
            return 1;
        }else{
            return 0;
        }
    }, 'Please clear all module before discharge' );
    $("#DischargeIndexForm").validate({
        ignore: "",
        rules: {
            "data[Discharge][clearance][]": "at_least_one",
            }
        });

    $("#GatePassIndexForm").validate({
        ignore: "",
        rules: {
            'data[GatePass][purpose]': {
                loginRegex: true,
                maxlength: 250
            },
        },
        messages: {
            'data[GatePass][purpose]': {
                loginRegex: "reason must contain only letters, numbers, Special Characters ( Comma, Hyphen, Dot, Spaces)",
                maxlength: "Please enter no more than 255 characters.",
            },
        }, 
    });
});
</script>
<?php
$ajaxUrl        = $this->Html->url(array('controller'=>'discharges','action'=>'indexAjax'));
$gatepassajaxUrl        = $this->Html->url(array('controller'=>'discharges','action'=>'gatepassAjax'));
$commonHeaderUrl    = $this->Html->url(array('controller'=>'Prisoners','action'=>'getCommonHeder'));
$deathincustodyajaxUrl = $this->Html->url(array('controller'=>'discharges','action'=>'childDetailAjax'));
$dischargeonescapeajaxUrl = $this->Html->url(array('controller'=>'discharges','action'=>'DischargeEscapeAjax'));
$checkDischargePossablityAjaxUrl = $this->Html->url(array('controller'=>'discharges','action'=>'checkDischargePossablity'));
$checkDischargeDetailsAjaxUrl = $this->Html->url(array('controller'=>'discharges','action'=>'checkDischargeDetails'));
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
        showDischargePrisoner();
        $('#discharge_prisoner_div').on('click', function(e){
           showDischargePrisoner();
        }); 
        showGatePass();
        $('#gate_pass_div').on('click', function(e){
           showGatePass();
        });
        showDeathInCustody();
        $('#child_release').on('click', function(e){
           showDeathInCustody();
        });
        showDischargeOnEscape();
        $('#discharge_on_escape_div').on('click', function(e){
           showDischargeOnEscape();
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
        if(tab_param == 'discharge_prisoner'){
            showDischargePrisoner();
        }else if(tab_param == 'gate_pass'){
            showGatePass();
        }else if(tab_param == 'child_release'){
            showDeathInCustody();
        }
        else if(tab_param == 'discharge_on_escape'){
            showDischargeOnEscape();
        }
          
    });

    //show data discharge in custody 
    function showDischargeOnEscape(){
        var url   = '".$dischargeonescapeajaxUrl."';
        var uuid  = '".$uuid."';
        //url = url + '/date_of_escape:'+$('#date_of_escape').val();
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
            $('#DischargeOnEscapeListingDiv').html(res);
        });           
    }

    //show death in custody 
    function showDeathInCustody(){
        var url   = '".$deathincustodyajaxUrl."';
        var uuid  = '".$uuid."';
        //url = url + '/date_of_death:'+$('#date_of_death').val();
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
            $('#DeathInCustodyListingDiv').html(res);
        });           
    }
    
    //show gate pass record
    function showGatePass(){
        var url   = '".$gatepassajaxUrl."';
        var uuid  = '".$uuid."';
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
            $('#gatePassListingDiv').html(res);
        });           
    }

    function showDischargePrisoner(){
        var url   = '".$ajaxUrl."';
        var uuid  = '".$uuid."';
        //url = url + '/date_of_discharge:'+$('#date_of_discharge').val();
        //url = url + '/discharge_type_id:'+$('#discharge_type_id').val();
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
            $('#listingDiv').html(res);
        });           
    }
    //common header
    function showCommonHeader(){
        var prisoner_id = ".$prisoner_id.";;
        //console.log(prisoner_id);  
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

    // function showFields(id){  
    //     var url   = '".$checkDischargePossablityAjaxUrl."';        
    //     url = url + '/prisoner_id:".$prisonerData['Prisoner']['id']."';
    //     url = url + '/discharge_type_id:'+id;
    //     $.post(url, {}, function(res) {
    //         if(res.trim()!=''){
    //             alert(res);
    //             return false;
    //         }else{
    //             $('.fieldsdiv input').not('.select2-input').each(function(){
    //                 $(this).prop('required',false);            
    //             });
    //             $('.div'+id+' input').not('.select2-input').each(function(){
    //                 $(this).prop('required',true);
    //             });
    //             $('.fieldsdiv').hide();
    //             $('.div'+id).show();
    //         }
    //     });           
    // } 

    function showFields(id){  
        var url   = '".$checkDischargePossablityAjaxUrl."';        
        url = url + '/prisoner_id:".$prisonerData['Prisoner']['id']."';
        url = url + '/discharge_transfer_id:'+$('#DischargeId').val();
        url = url + '/discharge_type_id:'+id;
        $.post(url, {}, function(res) {
            $('#showFeilds').html(res)
        });           
    } 

    function showDetails(id,discharge_transfer_id,prisoner_id){  
        var url   = '".$checkDischargeDetailsAjaxUrl."';        
        url = url + '/prisoner_id:'+prisoner_id;
        url = url + '/discharge_transfer_id:'+discharge_transfer_id;
        url = url + '/discharge_type_id:'+id;
        $.post(url, {}, function(res) {
            $('#show_details').html(res)
        });           
    } 
",array('inline'=>false));
?> 
<script>
     $('.datetimepicker').datetimepicker({format: 'yyyy-mm-dd hh:ii:ss'});
     $(document).ready(function(){
        <?php
        if(isset($this->data['Discharge']['discharge_type_id']) && $this->data['Discharge']['discharge_type_id']!=''){
            ?>
            showFields(<?php echo $this->data['Discharge']['discharge_type_id']; ?>);
            <?php
        }
        ?>
     });

     var timer = null;

    function start() {
        $('#biometricModal').modal('show');
        tick();
        timer = setTimeout(start, 1000);  
    };

    function startOther() {
        $('#biometricModal').modal('show');
        timer = setTimeout(stopOther, 1000);  
    };

    function tick() {
        $("#link_biometric_button_in").html("Searching...");
        var url = '<?php echo $biometricSearchAjax; ?>';
        $.ajax({
            type: 'POST',
            url: url,
            success: function (res) {
                if(res.trim()!='FAIL'){
                    startOther();
                    $("#link_biometric_button_in").html("Verified");
                    $("#link_biometric_button_in").attr("onclick","");
                    $("#link_biometric_verified").val(1);
                    $("#link_biometric_button_in").addClass("btn btn-success");
                }
            },
            async:false
        });
    };

    function stop() {
        $('#biometricModal').modal('hide');
        $("#link_biometric_button_in").html("Get Punch");
        $("#link_biometric_button_in").addClass("btn btn-warning");

        clearTimeout(timer);
    };

    function stopOther() {
        $('#biometricModal').modal('hide');
        clearTimeout(timer);
    };
</script>