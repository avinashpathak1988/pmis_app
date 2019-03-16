<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Cells List</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Add New Cell'), array('action' => 'add'), array('escape'=>false,'class'=>'btn btn-mini btn-primary')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
<div class="widget-content nopadding">
    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
    <div class="row" style="padding-bottom: 14px;">
         <div class="span6">
            <div class="control-group">
                <label class="control-label">Prison Station :</label>
                <div class="controls">
                    <?php echo $this->Form->input('prison_id', array('type'=>'select','class'=>'form-control pmis_select','id'=>'prison_id','options'=> $prisonlist,'empty'=>'--All--','div'=>false,'label'=>false,'onchange'=>'showWard(this.value)'))?>
                </div>
            </div>
        </div>
        <div class="span6">
            <div class="control-group">
                <label class="control-label">Ward :</label>
                <div class="controls">
                    <?php echo $this->Form->input('ward_id', array('type'=>'select','class'=>'form-control pmis_select','id'=>'ward_id','options'=>$wardList,'empty'=>'--All--','div'=>false,'label'=>false,'onchange'=>''))?>
                </div>
            </div>
        </div>
    </div>
        <div class="row" style="padding-bottom: 14px;">
        <div class="span6">
            <div class="control-group">
                <label class="control-label">Cell Name :</label>
                <div class="controls">
                    <?php echo $this->Form->input('cell_name', array('type'=>'text','class'=>'form-control','id'=>'cell_name','placeholder'=>'Enter Cell Name','div'=>false,'label'=>false))?>
                </div>
            </div>
        </div>
        <div class="span6">
            <div class="control-group">
                <label class="control-label">Cell No :</label>
                <div class="controls">
                    <?php echo $this->Form->input('cell_no', array('type'=>'text','class'=>'form-control','id'=>'cell_no','div'=>false,'label'=>false))?>
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
$ajaxUrl = $this->Html->url(array('controller'=>'WardCells','action'=>'indexAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/ward_id:' + $('#ward_id').val();
        url = url + '/prison_id:' + $('#prison_id').val();
        url = url + '/cell_name:' + $('#cell_name').val();
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
<script>
    function showWard(id)
{
    // alert(1);
    var strURL = '<?php echo $this->Html->url(array('controller'=>'WardCells','action'=>'stationList'));?>';
    $.post(strURL,{"prison_id":id},function(data){  
        
        if(data) { 
            $('#ward_id').html(data); 
            if(id == 1)
            {
                $('#ward_id').val(1);
            }
            else 
            {
                $('#ward_id').val(0);
            }
            $('#ward_id').select2();
        }
        else
        {
            alert("Error...");  
        }
        
    });
}
</script>











