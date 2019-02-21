<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Presiding Judges List</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Add New Presiding Judge'), array('action' => 'add'), array('escape'=>false,'class'=>'btn btn-mini btn-primary')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                    <div class="row" style="padding-bottom: 14px;">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Court Name :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('court_id', array('type'=>'select','class'=>'span11 pmis_select','options'=>$magisterialList,'empty'=>'','div'=>false,'label'=>false,'id'=>'magisterial_id'))?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Court Name :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('court_id', array('type'=>'select','class'=>'span11 pmis_select','options'=>array(),'empty'=>'','div'=>false,'label'=>false,'id'=>'court_id'))?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Presiding Judge Name :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('name', array('type'=>'text','class'=>'form-control','div'=>false,'label'=>false,'placeholder'=>'Enter Presiding Judge Name'))?>
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
$ajaxUrl      = $this->Html->url(array('controller'=>'PresidingJudges','action'=>'indexAjax'));
$courtAjaxUrl   = $this->Html->url(array('controller'=>'courtattendances','action'=>'getCourtByMagisterial'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
        $('#magisterial_id').on('change', function(e){
            var url = '".$courtAjaxUrl."';
            $.post(url, {'magisterial_id':$('#magisterial_id').val()}, function(res){
                $('#court_id').html(res);
                $('#court_id').select2('val', '');
                $('#court_level').val('');
            });
        });
    });
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/magisterial_id:' + $('#magisterial_id').val();
        url = url + '/court_id:' + $('#court_id').val();
        url = url + '/name:' + $('#SearchName').val();
        $.post(url, {}, function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });    
    }
    function confirmdelete(){
        var c=confirm('Are you sure to delete ?');
        if(c == false){
            return false;
        }else{
            return true;
        }
    }

    
",array('inline'=>false));
?>  












