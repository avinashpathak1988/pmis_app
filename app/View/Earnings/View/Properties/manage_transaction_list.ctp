<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
</style>
<!-- //code for getting the kin details  -->
<?php
// $funcall->loadModel('MedicalDeathRecord');     
// $prisonerId = $funcall->Prisoner->field("id",array("Prisoner.uuid"=>$prisoner_uuid));    
// $deathRecord = $funcall->MedicalDeathRecord->find("count", array(
//     "conditions"=> array(
//         "MedicalDeathRecord.prisoner_id"=>$prisonerId,
//         "MedicalDeathRecord.status"=>"Approved",
//         )
//     ));


?>
<div class="container-fluid">
    <div class="row-fluid">
    <div id="commonheader"></div>
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Stationwise Transaction List</h5>
                    
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                    
                        
                        <div class="tabscontent firsttab">

                            <div id="physical_property">
                                <div class="span12">
                                <?php echo $this->Form->create('Property',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                <?php echo $this->Form->input('uuid', array('type'=>'hidden'))?>
                                    <div class="row-fluid">
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">From Date  :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('propertyfrom_date',array('div'=>false,'label'=>false,'class'=>'form-control from_date span11','type'=>'text', 'placeholder'=>'Select Date ','required'=>false,'id'=>'propertyfrom_date','readonly'=>true,'value'=>date("d-m-Y")));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                            <div class="control-group">
                                            <label class="control-label">To Date:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('propertyto_date',array('div'=>false,'label'=>false,'class'=>'form-control to_date span11','type'=>'text', 'placeholder'=>'Select Date ','required'=>false,'id'=>'propertyto_date','readonly'=>true,'value'=>date("d-m-Y")));?>
                                            </div>
                                        </div>
                                    </div> 
                                    
                                    <div class="span12">
                                        <div class="form-actions" align="center">
                                            <?php echo $this->Form->button('Search', array('type'=>'button','class'=>'btn btn-primary','div'=>false,'label'=>false,'onclick'=>'showData()'))?>
                                            <?php echo $this->Form->button('Reset', array('type'=>'reset','class'=>'btn btn-danger','div'=>false,'label'=>false,'id'=>'resetPhysicalproperty'))//,'onclick'=>"resetPhysicalData('PropertyIndexForm')"?>
                                        </div>
                                        
                                    </div>  
                                </div>
                                <?php echo $this->Form->end();?>

                            </div>
                                <div class="table-responsive" id="listingDiv">

                                </div>
                            </div> 
                            
                      
                        </div>
                                <!-- <div class="table-responsive" id="listingDiv_cash">

                                </div> -->
                    </div>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$ajaxUrl = $this->Html->url(array('controller'=>'properties','action'=>'manageTransactionListAjax'));
?>

<script type="text/javascript">
$(document).ready(function(){
        showData();
});
function showData(){       
     var url ='<?php echo $ajaxUrl?>';
    // url = url + '/status_type:' + $('#status_type').val();
    // url = url + '/prisoner_uuid:' + $('#prisoner_uuid').val();
     url = url + '/propertyfrom_date:' + $('#propertyfrom_date').val();
     url = url + '/propertyto_date:'+$('#propertyto_date').val();
    // url = url + '/item_id:'+$('#item_id').val();
    // url = url + '/bag_no:'+$('#PropertyBagNo').val();
    $.post(url, {}, function(res) {
        if (res) {
            $('#listingDiv').html(res);
        }
    });
}
</script>


   