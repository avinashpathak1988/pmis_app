<style>
.nodisplay{display:none;}
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>PMO Report</h5> 
                   
                </div>
                <div class="widget-content nopadding">
                    <?php
                    echo $this->Form->create('Search',array('class'=>'form-horizontal'));
                        ?>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Prisoner No:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','empty'=>'-- Select--','id'=>'prisoner_no','options'=>$prisonerListname));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Prisoner Station:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','empty'=>'-- Select --','id'=>'prison_id','options' => $prisonListname));?>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="form-actions" align="center">
                        <?php
                        echo $this->Html->link('Search',"javascript:;",array('escape'=>false,'class'=>'btn btn-success','onclick'=>"showData();")); 
                        ?>
                        <?php
                        echo $this->Html->link('Reset',"javascript:;",array('escape'=>false,'class'=>'btn btn-danger','onclick'=>"resetData('SearchIndexForm');")); 
                    ?>
                    </div>
                    <?php echo $this->Form->end();
                    ?>
                </div>                            
                <div id="listingDiv"></div>                         
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    // $('select').select2('val', '');
    // $('#status').select2('val', '<?php echo (isset($status) && $status!='') ? $status : ''; ?>');
    showData();
});
</script>
<?php
$ajaxUrl = $this->Html->url(array('controller'=>'MedicalRecords','action'=>'prisonerRemarksAjax'));
$checkDischargeDetailsAjaxUrl = $this->Html->url(array('controller'=>'MedicalRecords','action'=>'prisonerRemarksAjax'));
echo $this->Html->scriptBlock("  
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/prisoner_no:' + $('#prisoner_no').val();
        url = url + '/prison_id:' + $('#prison_id').val();
        $.post(url, {}, function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });    
    }
     function showDetails(id,discharge_transfer_id,prisoner_id){  
        var url   = '".$checkDischargeDetailsAjaxUrl."';        
        url = url + '/prisoner_id:'+prisoner_id;
        url = url + '/discharge_transfer_id:'+discharge_transfer_id;
        url = url + '/discharge_type_id:'+id;
        $.post(url, {}, function(res) {
            $('#show_details'+discharge_transfer_id).html(res)
        });           
    } 
    function resetData(id){
        $('#'+id)[0].reset();
        $('#sprisoner_no').select2('val', '');
        $('#sprison_no').select2('val', '');
        showData();
    }
",array('inline'=>false));
?>
