<style>
.nodisplay{display:none;}
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 5px;
    text-align: left;    
}
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Application For Transfer List</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('New Application For Transfer'), array('action' => 'add'), array('escape'=>false,'class'=>'btn btn-primary')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
<div class="widget-content nopadding">
<div class="">
<?php 
echo $this->Form->create('PrisonerTransferLogin',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));
echo $this->Form->input('id',array('type'=>'hidden'));
?>
<div class="row-fluid">
<div class="span6">
<div class="control-group">
<label class="control-label">Original Station <?php echo $req; ?>:</label>
<div class="controls">
<?php 
$originPrisonList = $prisonList;
if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){
$originPrisonList = array($this->Session->read('Auth.User.prison_id')=>$funcall->getName($this->Session->read('Auth.User.prison_id'),"Prison","name"));
}
if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
$originPrisonList = array($this->Session->read('Auth.User.prison_id')=>$funcall->getName($this->Session->read('Auth.User.prison_id'),"Prison","name"));
}
echo $this->Form->input('transfer_from_station_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$originPrisonList, 'empty'=>'','required','id'=>'transfer_from_station_id'));?>
</div>
</div>
</div>
<div class="span6">
<div class="control-group">
<label class="control-label">Destination Station <?php echo $req; ?>:</label>
<div class="controls">
<?php echo $this->Form->input('transfer_to_station_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$prisonList, 'empty'=>'','required','id'=>'transfer_to_station_id'));?>
</div> 
</div>
</div>
</div>
<div class="span7">
            <?php echo $this->Form->button('Search', array('type'=>'button','class'=>'btn btn-success','div'=>false,'label'=>false,'onclick'=>'javascript:showData();'))?>
            <?php echo $this->Form->button('Reset', array('type'=>'reset', 'class'=>'btn btn-warning', 'div'=>false, 'label'=>false))?>
        </div>

</div>
<?php echo $this->Form->end(); ?>
<div class="table-responsive" id="listingDiv">

</div>
</div>
</div>
</div>
</div>
</div>
</div>
<?php
$ajaxUrl = $this->Html->url(array('controller'=>'PrisonerTransferLogins','action'=>'indexAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/transfer_from_station_id:' + $('#transfer_from_station_id').val();
        url = url + '/transfer_to_station_id:' + $('#transfer_to_station_id').val();
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













