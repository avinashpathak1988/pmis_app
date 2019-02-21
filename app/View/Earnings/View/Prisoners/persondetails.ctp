<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Person Details List</h5>
                    
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                    <div class="row">
                       <!--<div class="span3">
                            <div class="control-group">
                                <label class="control-label">Force NO :</label>
                                <div class="controls">
                                    <?php //echo $this->Form->input('force_no', array('type'=>'text','class'=>'form-control','id'=>'force_no','div'=>false,'label'=>false))?>
                                </div>
                            </div>
                        </div>  -->
                    
                    
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label">From :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('from', array('type'=>'text','class'=>'form-control from','id'=>'from','div'=>false,'label'=>false))?>
                                </div>
                            </div>
                        </div>  
                   
                    
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label">To :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('to', array('type'=>'text','class'=>'form-control to','id'=>'to','div'=>false,'label'=>false))?>
                                </div>
                            </div>
                        </div> 
                    </div>        
                    <div class="form-actions" align="center">
                        <?php echo $this->Form->button('Search', array('type'=>'button','class'=>'btn btn-success','div'=>false,'label'=>false,'onclick'=>'javascript:showData();'))?>
                        <?php echo $this->Form->button('Reset', array('type'=>'reset', 'class'=>'btn btn-warning', 'div'=>false, 'label'=>false))?>
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
$ajaxUrl      = $this->Html->url(array('controller'=>'Prisoners','action'=>'personalDetailsAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/from:' + $('.from').val();
        url = url + '/to:' + $('.to').val();
        $.post(url, {}, function(res) {
            if (res) {
                //console.log(res);
                $('#listingDiv').html(res);
            }
        });    
    }
",array('inline'=>false));
?>  
<script type="text/javascript">
$(document).ready(function(){
    $('.from').datepicker({ dateFormat: 'yy-mm-dd' });
});
$(document).ready(function(){
    $('.to').datepicker({ dateFormat: 'yy-mm-dd' });
});
</script>











