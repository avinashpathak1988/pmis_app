
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
                    <h5>Prisoner Debit List</h5>
                    <div style="float: right;">
                        <a class="" id="searchIcon" href="#searchBox" data-toggle="collapse"><span class="icon"> <i class="icon-search" style="color: #000;"></i> </span></a>
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="collapse" style="height: 0px;" id="searchBox">
                        <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                        <div class="row" style="padding-bottom: 14px;">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Prison:</label>
                                    <div class="controls">
                                       <?php echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$prisonList, 'empty'=>'','required'=>false,'id'=>'search_prison_id'));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Prisoner No. :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('sprisoner_no',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumeric', 'type'=>'text','placeholder'=>'Enter Prisoner No.','id'=>'sprisoner_no', 'style'=>''));?>
                                    </div>
                                </div>
                            
                                <!-- <div class="control-group">
                                    <label class="control-label">Prisoner Name :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prisoner_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text','placeholder'=>'Enter Prisoner Name','id'=>'prisoner_name', 'style'=>''));?>
                                    </div>
                                </div> -->
                                <!-- <div class="control-group">
                                    <label class="control-label">Prisoner Type:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prisoner_type_id',array('div'=>false,'label'=>false,'onChange'=>'showPrisonerSubType(this.value)','class'=>'span11 pmis_select','type'=>'select','options'=>$prisonerTypeList, 'empty'=>'','required'=>false,'id'=>'prisoner_type_id'));?>
                                    </div>
                                </div>  -->
                                
                                <div class="control-group">
                                    <label class="control-label">Currency:</label>
                                    <div class="controls">
                                       <?php echo $this->Form->input('currency',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$currencyList, 'empty'=>'-- Select Currency --','required'=>false,'id'=>'status','default'=>$default_status));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Debit Date :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('date_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date','type'=>'text','placeholder'=>'Start Date','id'=>'date_from',"readonly"=>true, 'style'=>'width:43%;'));?>
                                        To
                                        <?php echo $this->Form->input('date_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date','type'=>'text','placeholder'=>'End Date','id'=>'date_to',"readonly"=>true, 'style'=>'width:43%;'));?>
                                    </div>
                                </div>
                                <!-- <div class="control-group">
                                    <label class="control-label">Age between:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('age_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','placeholder'=>'Provide Age','id'=>'age_from', 'style'=>'width:100px;'));?>
                                        &
                                        <?php echo $this->Form->input('age_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 numeric','type'=>'text','placeholder'=>'Provide Age','id'=>'age_to', 'style'=>'width:100px;'));?>
                                    </div>
                                </div> -->
                                <!-- <div class="control-group">
                                    <label class="control-label">Prisoner Sub Type:</label>
                                    <div class="controls">
                                       <?php echo $this->Form->input('prisoner_sub_type_id',array('div'=>false,'label'=>false,'onChange'=>'showCountries(this.value)','class'=>'form-control span11','type'=>'select','options'=>array(), 'empty'=>'-- Select Prisoner Type --','required'=>false,'id'=>'prisoner_sub_type_id'));?>
                                    </div>
                                </div>  -->
                                <div class="control-group">
                                    <label class="control-label">Status:</label>
                                    <div class="controls">
                                       <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$statusList, 'empty'=>'','required'=>false,'id'=>'status','default'=>$default_status));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span12 add-top" align="center" valign="center">
                                <?php echo $this->Form->button('Search', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'id'=>'btnsearchcash', 'onclick'=>"showData();"))?>
                                <?php echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData('SearchDebitListForm')"))?>
                            </div>                        
                        </div> 
                        <?php echo $this->Form->end();?>
                    </div>
                    <div id="dataList"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$ajaxUrlCredit = $this->Html->url(array('controller'=>'properties','action'=>'DebitDataAjax'));
$getPrisonerSubajaxUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'getPrisonerSubType'));
?>
<script type="text/javascript">
$(document).ready(function(){
        showData();
});
function showData(){       
    var url ='<?php echo $ajaxUrlCredit?>';
    $.post(url, $('#SearchDebitListForm').serialize(), function(res) {
        if (res) {
            $('#dataList').html(res);
            //show Check Box
            showCheckBox();
         }
    });
}
function showCheckBox()
{ 
    var usertype_id='<?php echo $this->Session->read('Auth.User.usertype_id');?>';
    var user_typercpt='<?php echo Configure::read('RECEPTIONIST_USERTYPE');?>';
    var user_typepoi='<?php echo Configure::read('PRINCIPALOFFICER_USERTYPE');?>';
    var user_typeoiu='<?php echo Configure::read('OFFICERINCHARGE_USERTYPE');?>';
 
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
function showPrisonerSubType(){
    var url = '<?php echo $getPrisonerSubajaxUrl;?>';
    $.post(url, {'prisoner_type_id':$('#prisoner_type_id').val()}, function(res) {
        if (res) {
            $('#prisoner_sub_type_id').html(res);
        }
    });
}
$(document).ready(function(){
    //open search box 
    $('#searchIcon').click();
    var defaultStatus = '<?php echo $default_status;?>';
    $('#status').select2('val', defaultStatus);
    $('#status option[value='+defaultStatus+']').attr('selected','selected');
    showData();
    
});
function resetData(id){
    $('#'+id)[0].reset();
    //$('select').select2({minimumResultsForSearch: Infinity});
    $('select').select2().select2("val", null);
    showData();
}
</script>

   