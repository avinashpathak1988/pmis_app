<style>
#forwardBtn
{
  display: none;
}
.controls.uradioBtn .radio {
    padding-right: 5px;
    padding-top: 5px;
}
</style>

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
                    <h5>Produce/Schedule To Court</h5>
                    <div style="float:right;padding-top: 3px;">
                        <?php echo $this->Html->link('Prisoners List',array('controller'=>'prisoners','action'=>'index'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                        <?php if($isAccess == 1){?>
                            <?php echo $this->Form->create('Courtattendance',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                            <?php echo $this->Form->input('uuid', array('type'=>'hidden'))?>
                            <div class="row" style="padding-bottom: 14px;">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Production <br/>Warrent No<?php echo $req; ?> :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('production_warrent_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Production Warrent No','id'=>'production_warrent_no'));?>
                                        </div>
                                    </div>
                                </div>
                                 <div class="span6">
                                   <div class="control-group">
                                        <label class="control-label">Offence<?php echo $req; ?> :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('offence_id',array('div'=>false,'label'=>false,'type'=>'select','options'=>$offenceList, 'class'=>'form-control multiselectDropdown','required', 'id'=>'offence_id','multiple' => 'multiple'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div> 
                                <div class="span6">
                                   <div class="control-group">
                                        <label class="control-label">Next Hearing Date<?php echo $req; ?> :</label>
                                        <div class="controls">
                                        <?php
                                        $attendance_date="";
                                            if(isset($this->data['Courtattendance']['attendance_date']) && $this->data['Courtattendance']['attendance_date'] != ''){
                                                
                                                $attendance_date= date("d-m-Y H:i", strtotime($this->data['Courtattendance']['attendance_date']));
                                            }
                                            else{
                                                $attendance_date= date("d-m-Y H:i");
                                            }
                                            ?>
                                            <?php echo $this->Form->input('attendance_date',array('div'=>false,'label'=>false,'class'=>'form-control courtattendances span11','type'=>'text', 'placeholder'=>'Enter Next Hearing Date ','required','id'=>'attendance_date','value'=>$attendance_date));?>
                                        </div>
                                    </div>
                                </div>                                     
                                 <div class="span6">
                                   <div class="control-group">
                                        <label class="control-label">Magisterial Area<?php echo $req; ?> :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('magisterial_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Magisterial Area --','options'=>$magestrilareaList, 'class'=>'form-control','required', 'id'=>'magisterial_id'));?>
                                        </div>
                                    </div>
                                </div>
                                                             
                                <div class="clearfix"></div>     
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Court<?php echo $req; ?> :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('court_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'--Select Court--','options'=>$courtList, 'class'=>'form-control','required', 'id'=>'court_id', 'style'=>'width:91.5%;'));?>
                                        </div>
                                    </div>
                                </div>    
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Court Level:</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('court_level',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','id'=>'court_level','readonly'=>'readonly','placeholder'=>'Enter Court Level', 'style'=>'width:90%;'));?>
                                        </div>
                                    </div>
                                </div>                                             
                                
                                <div class="clearfix"></div>  
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Case No.<?php echo $req; ?> :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('case_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','required','id'=>'case_no','placeholder'=>'Enter Case No.'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>  
                            </div>
                            <div class="span12 add-top" align="center" valign="center">
                                <?php
                                    if(isset($this->data["Courtattendance"]) && !empty($this->data["Courtattendance"])){
                                        echo $this->Form->button('Update', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit', 'formnovalidate'=>true));?>&nbsp;&nbsp;
                                        <?php
                                        echo $this->Form->button('Cancel', array('type'=>'button', 'class'=>'btn btn-danger','div'=>false,'label'=>false,'id'=>'btn_cancel', 'formnovalidate'=>true));
                                    }
                                    else{
                                        echo $this->Form->button('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit', 'formnovalidate'=>true));
                                    }
                                ?>
                            </div>
                            <?php echo $this->Form->end();?>
                        <?php }?>
                    </div>
                    <div class="table-responsive" id="listingDiv">

                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$courtAjaxUrl   = $this->Html->url(array('controller'=>'courtattendances','action'=>'getCourtByMagisterial'));
$courtlvlAjaxUrl   = $this->Html->url(array('controller'=>'courtattendances','action'=>'getCourtlvl'));
$ajaxUrl        = $this->Html->url(array('controller'=>'courtattendances','action'=>'indexAjax'));
$commonHeaderUrl    = $this->Html->url(array('controller'=>'Prisoners','action'=>'getCommonHeder'));
$magisterial_id="";
$court_id="";
$court_level="";
if(isset($this->data["Courtattendance"])){
    $magisterial_id=$this->data["Courtattendance"]["magisterial_id"];
    $court_id=$this->data["Courtattendance"]["court_id"];
    $court_level=$this->data["Courtattendance"]["court_level"];
}
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        $('.multiselectDropdown').select2();
        if($('#CourtattendanceId').val()==''){
               $('#magisterial_id').select2('val', '');
                $('#court_id').select2('val', '');
        }
        else{
            $('#magisterial_id').select2('val', '".$magisterial_id."');
            $('#court_id').select2('val', '".$court_id."');
            $('#court_level').val('".$court_level."');
                
        }
        showCommonHeader();
        $('#btn_cancel').on('click', function(e){
            window.location='".$this->request->referer()."';
        });
        $('#magisterial_id').on('change', function(e){
            var url = '".$courtAjaxUrl."';
            $.post(url, {'magisterial_id':$('#magisterial_id').val()}, function(res){
                $('#court_id').html(res);
                $('#court_id').select2('val', '');
                $('#court_level').val('');
            });
        });
        $('#court_id').on('change', function(e){
            var url = '".$courtlvlAjaxUrl."';
            $.post(url, {'court_id':$('#court_id').val()}, function(res){
                $('#court_level').val(res);
            });
        });

        showData();
    });
    function showData(){
        var url   = '".$ajaxUrl."';
        var uuid  = '".$uuid."';
        url = url + '/production_warrent_no:'+$('#production_warrent_no').val();
        url = url + '/magisterial_id:'+$('#magisterial_id').val();
        url = url + '/court_id:'+$('#court_id').val();
        url = url + '/case_no:'+$('#case_no').val();
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
            $('#listingDiv').html(res);
        });           
    }
     //$('.mytime').datetimepicker({ dateFormat: 'yy-mm-dd' });

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
<script type="text/javascript">

    $(function(){
        $('.courtattendances').datetimepicker({
                showMeridian: false,
                defaultTime:false,
                format: 'dd-mm-yyyy hh:ii',
                autoclose:true,
                startDate: new Date(),
            }).on('changeDate', function (ev) {
                 $(this).datetimepicker('hide');
                 $(this).blur();
            });
     $.validator.addMethod("loginRegex", function(value, element) {
        return this.optional(element) || /^[a-z0-9\-\,\.\s\/]+$/i.test(value);
    }, "Username must contain only letters, numbers, or dashes.");
     
    $("#CourtattendanceIndexForm").validate({
     
      ignore: "",
            rules: {  
                'data[Courtattendance][production_warrent_no]': {
                    required: true,
                    loginRegex: true,
                    maxlength: 15
                },
                'data[Courtattendance][offence_id][]': {
                    required: true,
                },
                'data[Courtattendance][attendance_date]': {
                    required: true,
                    // datevalidateformatnew: true,
                },
                'data[Courtattendance][magisterial_id]': {
                    required: true,
                },
                'data[Courtattendance][court_id]': {
                    required: true,
                   
                },
                'data[Courtattendance][case_no]': {
                    required: true,
                    loginRegex: true,
                    maxlength: 15
                },
                
            },
            messages: {
                'data[Courtattendance][production_warrent_no]': {
                    required: "Please enter production warrent no.",
                    loginRegex: "Production warrent no. must contain only letters, numbers, Special Characters ( Comma, Hyphen, Dot, Spaces,Forward slash)",
                    maxlength: "Please enter less than 15 characters.",
                },
                'data[Courtattendance][offence_id][]': {
                    required: "Please choose offence",
                },
                'data[Courtattendance][attendance_date]': {
                    required: "Please select next hearing date",
                    // datevalidateformatnew: "Wrong Date Format"
                },
                'data[Courtattendance][magisterial_id]': {
                    required: "Please select magisterial area",
                },
                'data[Courtattendance][court_id]': {
                    required: "Please choose court",
                    
                },
                'data[Courtattendance][case_no]': {
                    required: "Please enter case No.",
                    loginRegex: "Case No. must contain only letters, numbers, Special Characters ( Comma, Hyphen, Dot, Spaces, Forward slash)",
                    maxlength: "Please enter less than 15 characters.",
                },
               
            }, 
    });
  });
</script>