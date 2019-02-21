<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Incident List</h5>
                    <div style="float:right;padding-top: 3px;">
                          <?php echo $this->Html->link(__('Add Incident Management'), array('action' => 'add'), array('escape'=>false,'class'=>'btn btn-mini btn-primary')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>



                                               <div class="widget-content nopadding">
                                            <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                                            <div class="row" style="padding-bottom: 14px;">
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Prisoner Number:</label>
                                                        <div class="controls">
                                                           <?php echo $this->Form->input('prisoner_no', array('type'=>'select','class'=>'form-control pmis_select','options'=>$prisonerList,'id'=>'prisoner_no','empty'=>'','div'=>false,'label'=>false,'hiddenField'=>false))?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Incident Type:</label>
                                                        <div class="controls">
                                                               <?php echo $this->Form->input('incident_type', array('type'=>'select','class'=>'form-control pmis_select','id'=>'incident_type','options'=> $incidentTypeList,'empty'=>'','div'=>false,'label'=>false))?>
                                                        </div>
                                                    </div>
                                                </div>  

                                            </div>           
                                            <div class="form-actions" align="center">
                                                <?php echo $this->Form->button('Search', array('type'=>'button','class'=>'btn btn-success','div'=>false,'label'=>false,'onclick'=>'javascript:showData();'))?>
                                                <?php echo $this->Form->input('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData()"))?>
                                            </div>
                                            <?php echo $this->Form->end();?> 
                                            <div class="table-responsive" id="listingDiv">

                                            </div>
                                        </div>
                                        
                                </div>
                            </div>
                        </div>
                    </div>

<?php
$ajaxUrl = $this->Html->url(array('controller'=>'IncidentManagements','action'=>'indexAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function resetData(){

       
        $('select').select2('val', '');
        showData();
        
    }
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/prisoner_no:' + $('#prisoner_no').val();
        url = url + '/incident_type:' + $('#incident_type').val();
        $.post(url, {}, function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });    
    }
    
    
",array('inline'=>false));

?> 


<script type="text/javascript">
function confirmdelete(){
    var c=confirm("Are you sure to delete ?");
    if(c == false){
        return false;
    }else{
        return true;
    }
}

</script>
