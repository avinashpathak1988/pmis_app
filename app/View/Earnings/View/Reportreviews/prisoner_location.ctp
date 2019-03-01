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
                    <h5>Prisoner Location Report</h5>
                    <div style="float:right;padding-top:2px;">
                        <?php echo $this->Html->link('Back',array('action'=>'index'),array('class' => 'btn btn-mini btn-primary'));?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                   <div class="">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                        <div class="row">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Prisoner No</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'type'=>'text', 'class'=>'form-control span11', 'id'=>'prisoner_no'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Prisoner Ward</label>
                                     <div class="controls">
                                        <?php echo $this->Form->input('ward',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'span11 pmis_select','options'=>$wards, 'class'=>'span11 pmis_select', 'id'=>'ward'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                             <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Cell Name</label>
                                     <div class="controls">
                                        <?php echo $this->Form->input('cell_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'','options'=>$wardcell, 'class'=>'span11 pmis_select', 'id'=>'cell_id'));?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="clearfix" style="margin-bottom:20px"></div>
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
$ajaxUrl        = $this->Html->url(array('controller'=>'reportreviews','action'=>'prisonerLocationAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url   = '".$ajaxUrl."';
        url = url + '/prisoner_no:'+$('#prisoner_no').val();
        url = url + '/ward:'+$('#ward').val();
        url = url + '/cell_id:'+$('#cell_id').val();
        
        $.post(url, {}, function(res) {
            $('#listingDiv').html(res);
        });
    }
",array('inline'=>false));
?>
