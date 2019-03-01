
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
                    <h5>Prisoner Outgoing Property List</h5>
                    <div style="float: right;">
                        <a class="" id="searchIcon" href="#searchBox" data-toggle="collapse" title="Search"><span class="icon"> <i class="icon-search" style="color: #000;"></i> </span></a>
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="collapse" style="height: 0px;" id="searchBox">
                        <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                        <div class="row" style="padding-bottom: 14px;">
                            <div class="span6">
                                
                                <!-- <div class="control-group">
                                    <label class="control-label">Property Items:</label>
                                    <div class="controls">
                                       <?php echo $this->Form->input('item_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$propertyItemList, 'empty'=>'','required'=>false,'id'=>'item_id'));?>
                                    </div>
                                </div> -->
                                 <div class="control-group">
                                    <label class="control-label">Prison:</label>
                                    <div class="controls">
                                       <?php echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$prisonList, 'empty'=>'','required'=>false,'id'=>'search_prison_id'));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Bag No. :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('bag_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Bag No.','id'=>'bag_no', 'style'=>''));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Property Type:</label>
                                    <div class="controls">
                                       <?php 
                                       $property_typeList = array('In Use'=>'In Use','In Store'=>'In Store');
                                       echo $this->Form->input('property_type',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$property_typeList, 'empty'=>'','required'=>false,'id'=>'property_type'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                              
                                <div class="control-group">
                                    <label class="control-label">Status:</label>
                                    <div class="controls">
                                       <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'','options'=>$sttusListData, 'class'=>'span11 pmis_select','required', 'id'=>'status','default'=>$default_status));?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Date :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('date_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date mydate','type'=>'text','placeholder'=>'Start Date','id'=>'date_from',"readonly"=>true, 'style'=>'width:42%;'));?>
                                        To
                                        <?php echo $this->Form->input('date_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date mydate','type'=>'text','placeholder'=>'End Date','id'=>'date_to',"readonly"=>true, 'style'=>'width:42%;'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span12 add-top" align="center" valign="center">
                                <?php echo $this->Form->button('Search', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'id'=>'btnsearchcash', 'onclick'=>"showData();"))?>
                                <?php echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData('SearchPhysicalPropertyListForm')"))?>
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
$ajaxUrlItem = $this->Html->url(array('controller'=>'properties','action'=>'outgoingPropertyListAjax'));
$getPrisonerSubajaxUrl = $this->Html->url(array('controller'=>'prisoners','action'=>'getPrisonerSubType'));
?>
<script type="text/javascript">

$(document).ready(function(){
        showData();
        $('#item_id').select2('val','');
        $('#property_type').select2('val','');
});
function showData(){       
    var url ='<?php echo $ajaxUrlItem?>';
    $.post(url, $('#SearchOutgoingPropertyListForm').serialize(), function(res) {
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
    /*$('select').select2({minimumResultsForSearch: Infinity});*/
     
    $('select').select2().select2("val", null);
    
    showData();
}
</script>

   