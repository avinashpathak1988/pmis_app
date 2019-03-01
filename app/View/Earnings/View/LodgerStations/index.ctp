<?php
if(isset($this->data['Discharge']['date_of_discharge']) && $this->data['Discharge']['date_of_discharge'] != ''){
    $this->request->data['Discharge']['date_of_discharge'] = date('d-m-Y', strtotime($this->data['Discharge']['date_of_discharge']));
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
                    <h5><?php echo $heading;?></h5>
                    <a class="" href="#searchLodger" data-toggle="collapse"><span class="icon"> <i class="icon-search" style="color: #000;"></i> </span></a>
                    <div style="float:right;padding-top: 7px;padding-right:5px;">
                        <?php
                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && $lodger_type=='at'){
                            echo $this->Html->link('Add','#addLodger',array('escape'=>false,'class'=>'btn btn-success btn-mini','data-toggle'=>"collapse"));
                        }
                        ?>
                    </div>
                </div>
                <div id="searchLodger" class="collapse" style="height: 0px;">
                    <div class="span12">
                        <?php echo $this->Form->create('Search',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Original Prison :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('original_prison',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$prisonList, 'empty'=>'-- Select Original Prison --','id'=>'original_prison'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Prisoner Number :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$serchPrisonerList, 'empty'=>'-- Select Prisoner Number --','id'=>'prisoner_id'));?>
                                    </div>
                                </div>
                            </div>                                                      
                        </div>
                        <div class="row-fluid">    
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Date Of Lodging :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('from_date',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date mydate','type'=>'text','placeholder'=>'Start Date','id'=>'from_date',"readonly"=>true, 'required'=>false,'style'=>'width:42.5%;'));?>
                                        To
                                        <?php echo $this->Form->input('to_date',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date mydate','type'=>'text','placeholder'=>'End Date','id'=>'to_date',"readonly"=>true, 'required'=>false,'style'=>'width:42.5%;'));?>
                                    </div>
                                </div>
                            </div>                                        
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Destination Prison :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('destination_prison',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$prisonList, 'empty'=>'-- Select Destination Prison --','id'=>'destination_prison'));?>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Approve Status :</label>
                                    <div class="controls row">
                                        <?php 
                                        $finalStatus = $funcall->getApprovalStatusInfo();
                                        if($lodger_type=='out'){
                                            $finalStatus['statusList'] += array("IN"=>"IN");
                                            $finalStatus['default_status'] = "IN";
                                        }
                                        echo $this->Form->input('status',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$finalStatus['statusList'], 'empty'=>'-- All Status --','id'=>'search_status'));?>
                                    </div>
                                </div>
                            </div>  
                        </div>
                       
                        <div class="form-actions" align="center">
                            <?php echo $this->Form->button('Search', array('type'=>'button', 'class'=>'btn btn-success','div'=>false,'label'=>false,"onclick"=>"showData()"))?>
                        </div>
                        <?php echo $this->Form->end();?>
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div id="addLodger" class="collapse" style="height: 0px;">
                        <div class="">
                            <?php echo $this->Form->create('LodgerStation',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                            <?php echo $this->Form->input('uuid', array('type'=>'hidden'))?>
                            <?php echo $this->Form->input('is_enable', array('type'=>'hidden','value'=>1))?>
                            <?php echo $this->Form->input('lodger_type', array('type'=>'hidden','value'=>$lodger_type))?>
                            <div class="row-fluid">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Original Prison <?php echo MANDATORY; ?> :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('original_prison',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$prisonList, 'empty'=>'-- Select Original Prison --','onChange'    => "getPrisoner(this.value,'LodgerStation')",'required','id'=>'original_prison','title'=>'Please select original prison'));?>
                                        </div>
                                    </div>
                                </div> 
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prisoner Number <?php echo MANDATORY; ?> :</label>
                                        <div class="controls" id="prisonerListDiv">
                                            <?php 
                                            //$prisonerList
                                            echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>array(), 'empty'=>'-- Select Prisoner Number --','required','id'=>'prisoner_id','title'=>'Please select prisoner name'));?>
                                        </div>
                                    </div>
                                </div>                           
                            </div>
                            <div class="row-fluid">               
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Date & Time of <?php echo (isset($lodger_type) && $lodger_type=='out') ? 'Departure': 'Arrival'; ?> <?php echo MANDATORY; ?> :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('date_of_lodging',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Select Date Of Lodging','readonly'=>'readonly','class'=>'form-control mydatetim epicker1 span11','required', 'id'=>'date_of_lodging', 'value'=>date(Configure::read('UGANDA-DATE-TIME-FORMAT'))));?>
                                        </div>
                                    </div>
                                </div>                   
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Destination Prison <?php echo MANDATORY; ?> :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('destination_prison',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$prisonList, 'empty'=>'-- Select Destination Prison --','required','id'=>'destination_prison','title'=>'Please select destination prison'));?>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="row-fluid">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Reason <?php echo MANDATORY; ?> :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('reason',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'textarea','required','id'=>'reason','rows'=>2,'title'=>'Please provide reason'));?>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                           
                            <div class="form-actions" align="center">
                                <?php echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true))?>
                            </div>
                            <?php echo $this->Form->end();?>
                        </div>
                    </div>
                    <div class="table-responsive" id="listingDiv">

                    </div>                     
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $('#search_status').select2('val', '<?php echo $finalStatus['default_status']; ?>');
    showData();
});
</script>
<?php
$ajaxUrl        = $this->Html->url(array('controller'=>'LodgerStations','action'=>'indexAjax'));
$ajaxPrisonerUrl        = $this->Html->url(array('controller'=>'LodgerStations','action'=>'getPrisoner'));
$ajaxUpdateOutUrl        = $this->Html->url(array('controller'=>'LodgerStations','action'=>'updateOut'));
$current_userType = $this->Session->read('Auth.User.usertype_id');
$receptionist = Configure::read('RECEPTIONIST_USERTYPE');
$principal_officer = Configure::read('PRINCIPALOFFICER_USERTYPE');
$officer_incharge = Configure::read('OFFICERINCHARGE_USERTYPE');
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
        $('select').select2();
    });

    $(function(){
        $('#LodgerStationIndexForm').validate({
            ignore: '',
            rules: {  
                
            },
            messages: {
                
            }, 
        });
    });
    function showData(){
        var url   = '".$ajaxUrl."';
        var lodger_type         = '".$lodger_type."';
        url = url + '/lodger_type:'+lodger_type;
        $.post(url, $('#SearchIndexForm').serialize(), function(res) {
            $('#listingDiv').html(res);
            showCheckBox();
        });           
    }

    function getPrisoner(prison_id,model_name) 
    { 
        if(prison_id != '')
        {
            var strURL = '".$ajaxPrisonerUrl."';
        
            $.post(strURL,{'prison_id':prison_id,'model_name':model_name},function(data){  
                $('#prisonerListDiv').html(data);
            });
        }
    }

    function showCheckBox()
    { 
        var usertype_id='".$current_userType."';
        var user_typercpt='".$receptionist."';
        var user_typepoi='".$principal_officer."';
        var user_typeoiu='".$officer_incharge."';
     
        if(usertype_id==user_typercpt)
        {
            if($('#status').val()=='Saved' || $('#status').val()=='Approved' || $('#status').val()=='Approve-Rejected'){
                 $('td:first-child').each(function() {
                       $(this).remove();
                });
                 $('th:first-child').each(function() {
                       $(this).remove();
                });
            }
        }
        if(usertype_id==user_typepoi)
        {
            if($('#status').val()=='Reviewed' || $('#status').val()=='Approved' || $('#status').val()=='Approve-Rejected'){
                 $('td:first-child').each(function() {
                       $(this).remove();
                });
                 $('th:first-child').each(function() {
                       $(this).remove();
                });
            }
        }
        if(usertype_id==user_typeoiu)
        {
            if($('#status').val()=='Approved'){
                 $('td:first-child').each(function() {
                       $(this).remove();
                });
                 $('th:first-child').each(function() {
                       $(this).remove();
                });
            }
        }
    }

    function updateLodgerOut(lodger_station_id) 
    { 
        if(lodger_station_id != '')
        {
            var strURL = '".$ajaxUpdateOutUrl."';
        
            $.post(strURL,{'reason':$('#reason'+lodger_station_id).val(),'stay_duration':$('#stay_duration'+lodger_station_id).val(),'id':lodger_station_id},function(data){  
                showData();
                $('.modal-backdrop').hide();
            });
        }
    }

",array('inline'=>false));
?>