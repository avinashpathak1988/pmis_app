<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Users List</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link('Add New User',array('action'=>'add'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                             
                  
                    <div class="row">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">From Date :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('from_date', array('type'=>'text', 'id'=>'from_date', 'class'=>'span11 from_date','div'=>false,'label'=>false,'placeholder'=>'Enter from date','required','readonly'))?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">To Date :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('to_date', array('type'=>'text', 'id'=>'to_date', 'class'=>'span11 to_date','div'=>false,'label'=>false,'placeholder'=>'Enter to date','required','readonly'))?>
                                </div>
                            </div>
                        </div>  
                    </div>
                    <div class="row" style="padding-bottom: 14px;">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Prison :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','id'=>'prison_id','options'=>$prisonList,'empty'=>'', 'required'));?>
                                </div>
                            </div>
                        </div>  
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">User Type :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('usertype_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','id'=>'usertype_id','options'=>$usertypeList,'empty'=>'', 'required'));?>
                                </div>
                            </div>
                        </div>  

                    </div>           
                    <div class="form-actions" align="center">
                        <?php echo $this->Form->button('Search', array('type'=>'button', 'class'=>'btn btn-primary','div'=>false,'label'=>false,'onclick'=>"javascript:return showData();"))?>
                        <?php echo $this->Form->button('Reset', array('type'=>'reset', 'class'=>'btn btn-warning', 'div'=>false, 'label'=>false,'onclick'=>"resetData('SearchIndexForm')"))?>
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
$ajaxUrl = $this->Html->url(array('controller'=>'Users','action'=>'indexAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/from_date:'+$('#from_date').val();
        url = url + '/to_date:'+$('#to_date').val();
        url = url + '/prison_id:'+$('#prison_id').val();
        url = url + '/usertype_id:'+$('#usertype_id').val();
        $.post(url, {}, function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });
    }
    function resetData(id){
        $('#'+id)[0].reset();
        $('select').select2({minimumResultsForSearch: Infinity});
        showData();
    }
",array('inline'=>false));
?> 