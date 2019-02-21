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
                    <h5>Quick identification of dangerous prisoners</h5>
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
                                        <?php echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'','options'=>$prisonList, 'class'=>'span11 pmis_select', 'id'=>'prison_id'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Prisoner Name</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prisoner_name',array('div'=>false,'label'=>false,'type'=>'text', 'class'=>'form-control', 'id'=>'prisoner_name','placeholder'=>'Enter Prisoner Name'));?>
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
$ajaxUrl        = $this->Html->url(array('controller'=>'report','action'=>'dangerousPrisonerAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url   = '".$ajaxUrl."';
        url = url + '/prison_id:'+$('#prison_id').val();
        url = url + '/prisoner_name:'+$('#prisoner_name').val();
        $.post(url, {}, function(res) {
            $('#listingDiv').html(res);
        });
    }
",array('inline'=>false));
?>
