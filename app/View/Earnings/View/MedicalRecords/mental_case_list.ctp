<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
</style>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Mental Case Records</h5>
                   <div style="float:right;padding-top: 7px;">
                        <?php 
                        //$this->Session->read('Auth.User.usertype_id')!=Configure::read('OFFICERINCHARGE_USERTYPE')
                        if(true){
                            echo $this->Html->link(__('Add Mental Case'), array('action' => '/mentalCases'), array('escape'=>false,'class'=>'btn btn-success btn-mini'));
                        }
                        ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                    <?php echo $this->Form->create('MentalCase',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <div class="row" style="padding-bottom: 14px;">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prisoner No</label>
                                        <div class="controls">
                                            <?php   echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Prisoner --','options'=>$prisonerListData, 'class'=>'form-control','required', 'id'=>'prisoner_id'));?>
                                        </div>
                                    </div>
                                </div><!-- 
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Status</label>
                                        <div class="controls">
                                            <?php //echo $this->Form->input('status',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Status --','options'=>$sttusListData, 'class'=>'form-control','required', 'id'=>'status','default'=>$default_status));?>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="clearfix"></div> 
                            </div>
                            <div class="form-actions" align="center">
                            <button id="btnsearchcash" class="btn btn-success" type="button" formnovalidate="formnovalidate" onclick="showData()">Search</button>
                                
                            </div>
                            <?php echo $this->Form->end();?>
                     </div>           
                    <div class="table-responsive" id="listingDiv">

                    </div>                    
                </div>
                   
            </div>
        </div>
    </div>
</div>
      


<?php
$ajaxUrl        = $this->Html->url(array('controller'=>'MedicalRecords','action'=>'mentalCaseAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url   = '".$ajaxUrl."';
        url = url + '/prisoner_id:'+$('#prisoner_id').val();
        $.post(url, {}, function(res) {
           
            $('#listingDiv').html(res);
        });           
    }
",array('inline'=>false));
?>

