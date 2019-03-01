<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Lockup Report</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('View Lockup Entry'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                    <div class="row lockup-report">
                       
                    
                    
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label">Date :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('from', array('type'=>'text','class'=>'form-control from_date','id'=>'from','div'=>false,'label'=>false,'value'=>date("d-m-Y"),'readonly'=>true))?>
                                </div>
                            </div>
                        </div>  
                   
                    
                        <!--<div class="span4">
                            <div class="control-group">
                                <label class="control-label">To :</label>
                                <div class="controls">
                                    <?php //echo $this->Form->input('to', array('type'=>'text','class'=>'form-control to','id'=>'to','div'=>false,'label'=>false))?>
                                </div>
                            </div>
                        </div> -->
                    </div>        
                    <div class="form-actions" align="center">
                        <?php echo $this->Form->button('Search', array('type'=>'button','class'=>'btn btn-success','div'=>false,'label'=>false,'onclick'=>'javascript:showData();'))?>
                        <?php echo $this->Form->button('Reset', array('type'=>'reset', 'class'=>'btn btn-warning', 'div'=>false, 'label'=>false, 'onclick'=>"resetLockupSearchForm();"))?>
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
$ajaxUrl      = $this->Html->url(array('controller'=>'Physicallockups','action'=>'lockupReportAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/from:' + $('#from').val();
        
        $.post(url, {}, function(res) {
            if (res) {
                //console.log(res);
                $('#listingDiv').html(res);
            }
        });    
    }
    function resetLockupSearchForm()
    {
        showData();
    }
",array('inline'=>false));
?>   











