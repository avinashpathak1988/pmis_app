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
                    <h5>Demoted From Stage List</h5>
                    <div style="float:right;padding-top:2px;">
                        <?php //echo $this->Html->link('Back',array('action'=>'index'),array('class' => 'btn btn-primary'));?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                   <div class="">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                        <div class="row">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prison</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Prison --','options'=>$prisonList, 'class'=>'form-control', 'id'=>'prison_id'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prisoner Name</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('prisoner_name',array('div'=>false,'label'=>false,'type'=>'text', 'class'=>'form-control', 'id'=>'prisoner_name'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        <div class="row">
                           <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">EPD :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('epd_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date','type'=>'text','placeholder'=>'Start Date','id'=>'epd_from', 'readonly'=>true,'style'=>'width:110px;'));?>
                                        To
                                        <?php echo $this->Form->input('epd_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date','type'=>'text','placeholder'=>'End Date','id'=>'epd_to', 'readonly'=>true,'style'=>'width:110px;'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">LPD :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('lpd_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date','type'=>'text','placeholder'=>'Start Date','id'=>'lpd_from', 'readonly'=>true,'style'=>'width:110px;'));?>
                                        To
                                        <?php echo $this->Form->input('lpd_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date','type'=>'text','placeholder'=>'End Date','id'=>'lpd_to', 'readonly'=>true,'style'=>'width:110px;'));?>
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
$ajaxUrl = $this->Html->url(array('controller'=>'reportreviews','action'=>'prisonerStageAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url   = '".$ajaxUrl."';
        url = url + '/epd_from:'+$('#epd_from').val();
		url = url + '/epd_to:'+$('#epd_to').val();
        url = url + '/lpd_from:'+$('#lpd_from').val();
		url = url + '/lpd_to:'+$('#lpd_to').val();
        url = url + '/prison_id:'+$('#prison_id').val();
        url = url + '/prisoner_name:'+$('#prisoner_name').val();
        $.post(url, {}, function(res) {
            $('#listingDiv').html(res);
        });
    }
",array('inline'=>false));
?>
