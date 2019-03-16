<style>
.row-fluid [class*="span"]{
  margin-left: 0px !important;
}
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div id="commonheader"></div>
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Unfit Prisoner List</h5>
                    
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                        <div class="row">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Prisoner No</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'','options'=>$prisonerList, 'class'=>'form-control pmis_select', 'id'=>'prisoner_id'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">From Date</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('from_date',array('div'=>false,'label'=>false,'type'=>'text', 'class'=>'form-control', 'id'=>'from_date', 'readonly'=>true));?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">To Date</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('to_date',array('div'=>false,'label'=>false,'type'=>'text', 'class'=>'form-control', 'id'=>'to_date', 'readonly'=>true));?>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-actions" align="center">
                            <button id="btnsearchcash" class="btn btn-success" type="button" onclick="javascript:showData();">Search</button>
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
$ajaxUrl        = $this->Html->url(array('controller'=>'MedicalRecords','action'=>'urbanLabourListAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url   = '".$ajaxUrl."';
        url = url + '/prisoner_id:'+$('#prisoner_id').val();
        url = url + '/from_date:'+$('#from_date').val();
        url = url + '/to_date:'+$('#to_date').val();
        $.post(url, {}, function(res) {
            $('#listingDiv').html(res);
        });
    }
",array('inline'=>false));
?>