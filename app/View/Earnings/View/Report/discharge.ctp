<?php //debug($prisonernumber); ?>
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
                    <h5>Release List(30 days)</h5>
                    <div style="float:right;padding-top:2px;">
                        <?php //echo $this->Html->link('Back',array('action'=>'index'),array('class' => 'btn btn-primary'));?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php  ?>
                    <div class="">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                        <div class="row">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">From Date</label>
                                    <div class="controls">
                                        <?php  $date_future = date("d-m-Y", strtotime(" "));
                                        echo $this->Form->input('epd',array('div'=>false,'label'=>false,'type'=>'text','id'=>'epd','placeholder'=>'', 'data-date-format'=>"dd-mm-yyyy",
                                             'readonly'=>'readonly','value'=>$date_future,'class'=>'form-control from_date','type'=>'text','required',));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">To Date</label>
                                    <div class="controls">
                                        <?php $date_future = date("d-m-Y", strtotime(" +1 Month"));
                                        echo $this->Form->input('epd_to',array('div'=>false,'label'=>false,'type'=>'text','id'=>'epd_to','placeholder'=>'', 'data-date-format'=>"dd-mm-yyyy",'value'=>$date_future,
                                             'readonly'=>'readonly','class'=>'form-control to_date','type'=>'text','required',));?>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-actions" align="center">
                            <button id="btnsearchcash" class="btn btn-success" type="button" onclick="javascript:showData();">Search</button>
                             <?php //echo $this->Form->button('Reset', array('type'=>'reset', 'class'=>'btn btn-warning', 'div'=>false, 'label'=>false))?>
                        </div>
                    <?php echo $this->Form->end();?>
                    </div>
                    <?php  ?>
                    <div class="table-responsive" id="listingDiv">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$ajaxUrl        = $this->Html->url(array('controller'=>'report','action'=>'dischargeAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        //alert('1');
        var url   = '".$ajaxUrl."';
         url = url + '/epd:'+$('#epd').val();
         url = url + '/epd_to:'+$('#epd_to').val();
        $.post(url, {}, function(res) {
            $('#listingDiv').html(res);
        });
    }
",array('inline'=>false));
?>
