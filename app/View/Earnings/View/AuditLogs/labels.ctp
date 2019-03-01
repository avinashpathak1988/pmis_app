<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Manage Labels</h5>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                    <div class="row" style="padding-bottom: 14px;">
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label">Model :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('model', array('type'=>'select', 'id'=>'model', 'class'=>'span11','div'=>false,'label'=>false,'options'=>$modelList, 'empty'=>'--Select Model--','required'))?>
                                </div>
                            </div>
                        </div>                    
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label">From Date :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('from_date', array('type'=>'text', 'id'=>'from_date', 'class'=>'span11','div'=>false,'label'=>false,'placeholder'=>'Enter from date','required'))?>
                                </div>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label">To Date :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('to_date', array('type'=>'text', 'id'=>'to_date', 'class'=>'span11','div'=>false,'label'=>false,'placeholder'=>'Enter to date','required'))?>
                                </div>
                            </div>
                        </div>
                    </div>           
                    <div class="form-actions" align="center">
                        <?php echo $this->Form->button('Search', array('type'=>'button', 'class'=>'btn btn-primary','div'=>false,'label'=>false,'onclick'=>"javascript:return showData();"))?>
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
$ajaxUrl    = $this->Html->url(array('controller'=>'AuditLogs','action'=>'labelsAjax'));
$updateUrl  = $this->Html->url(array('controller'=>'AuditLogs','action'=>'labelsUpdate'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/model:'+$('#model').val();
        url = url + '/from_date:'+$('#from_date').val();
        url = url + '/to_date:'+$('#to_date').val();
        $.post(url, {}, function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });
    }
    function updateLabel(label_id){
        if(label_id){
            if($('#label'+label_id).val() != ''){
                $('#label'+label_id).css('background-color' ,'#F5F5F5');
                if(confirm('Are you sure to update label?')){
                    var url = '".$updateUrl."';
                    $.post(url, {'label_id':label_id, 'label':$('#label'+label_id).val()}, function(res) {
                        if(res == 'SUCC'){
                            $('#trid'+label_id).css('background-color','#7FFFD4')
                        }else{
                            $('#trid'+label_id).css('background-color','#FFB6C1')
                        }
                    });        
                }
            }else{
                $('#label'+label_id).css('background-color' ,'#FF0000');
            }
        }
    }
",array('inline'=>false));
?> 