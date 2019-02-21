<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Ward List</h5>
                    <div style="float:right;padding-top: 3px;">
                        <?php echo $this->Html->link('Add New Ward',array('action'=>'add'),array('class' => 'btn btn-mini btn-primary'));?>
                        &nbsp;&nbsp;
                    </div>
                </div>



                                               <div class="widget-content nopadding">
                                            <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                                            <div class="row" style="padding-bottom: 14px;">
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Prison:</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('prison', array('type'=>'select','class'=>'span11 pmis_select','id'=>'prison','empty'=>'','div'=>false,'label'=>false,'options' => $prisonlist))?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Ward Type:</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('ward_type', array('type'=>'select','class'=>'span11 pmis_select','id'=>'ward_type','empty'=>'','div'=>false,'label'=>false,'options' => $wardlist))?>
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
$ajaxUrl = $this->Html->url(array('controller'=>'Wards','action'=>'indexAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/prison:' + $('#prison').val();
        url = url + '/ward_type:' + $('#ward_type').val();
        $.post(url, {}, function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });    
    }
    
",array('inline'=>false));
?> 

<script type="text/javascript">
function confirmdelete(){
    var c=confirm("Are you sure to delete ?");
    if(c == false){
        return false;
    }else{
        return true;
    }
}
</script>
