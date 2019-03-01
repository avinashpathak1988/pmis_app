<style>
.nodisplay{display:none;}
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>State of prison and prisoner List</h5> 
                     <div style="float:right;padding-top: 7px;">
                        <?php 
                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE')){
                            echo $this->Html->link(__('Add State of prison and prisoner'), array('action' => 'statePrison'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); 
                        }
                        ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php
                    echo $this->Form->create('Search',array('class'=>'form-horizontal'));
                        ?>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">State of Prison:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prison_state',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','empty'=>'','id'=>'sprison_no','options' => array(
                                    'Comments on the state of buildings'=>'Comments on the state of buildings',
                                    'Comments on Level of congestion'=>'Comments on Level of congestion',
                                    'Comments on ventilation'=>'Comments on ventilation',
                                    'Comments on light system'=>'Comments on light system',
                                    'Comments on fencing'=>'Comments on fencing',
                                    'Comments on general environment'=>'Comments on general environment',
                                    'Comments on ward environment'=>'Comments on ward environment',)));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">State of Prisoner:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prisoner_state',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','empty'=>'','id'=>'sprisoner_no','options' => array(
                                    'Recommendations on transfers'=>'Recommendations on transfers',
                                    'Recommendation on allocating of labour'=>'Recommendation on allocating of labour',
                                    'Recommendation on ward allocation'=>'Recommendation on ward allocation',
                                    'State different levels of sickness'=>'State different levels of sickness',
                                  ),));?>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="clearfix" style="margin-bottom:20px"></div>
                        <div class="form-actions" align="center">
                        <?php
                        echo $this->Html->link('Search',"javascript:;",array('escape'=>false,'class'=>'btn btn-success','onclick'=>"showData();")); 
                        ?>
                      <!--   <?php
                        //echo $this->Html->link('Reset',"javascript:;",array('escape'=>false,'class'=>'btn btn-danger','onclick'=>"resetData('Search');")); 
                    ?> -->
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
$ajaxUrl = $this->Html->url(array('controller'=>'MedicalRecords','action'=>'prisonerStateListAjax'));
echo $this->Html->scriptBlock("  
    function showData(){
        var sprisoner_no = $('#sprisoner_no').val();
        var sprison_no = $('#sprison_no').val();
        var url = '".$ajaxUrl."';
        url += '/prisoner_state:'+sprisoner_no;
        url += '/prison_state:'+ sprison_no;
        $.post(url, {}, function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });
    }

    function resetData(id){
        $('#'+id)[0].reset();
        $('#sprisoner_no').select2('val', '');
        $('#sprison_no').select2('val', '');
       
    }

",array('inline'=>false));
?>
