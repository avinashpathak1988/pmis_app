<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Escort Team List</h5>
                    <div style="float:right;padding-top: 6px;">
                        <?php echo $this->Html->link(__('Add New Escort Team'), array('action' => 'add'), array('escape'=>false,'class'=>'btn btn-primary btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                    <div class="row" style="padding-bottom: 14px;">
                        
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Escort Type :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('escort_type', array('type'=>'select','label'=>false,'empty'=>"--All--" ,'options' => array("Transfer"=>"Transfer","Hospital"=>"Hospital","Court"=>"Court","Labour party"=>"Labour party","Dicharge"=>"Dicharge"),'id'=>'escort_type',"title"=>"Please select Escort Type")); ?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Escort Team Name :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('name', array('type'=>'text','class'=>'form-control','id'=>'name','div'=>false,'label'=>false))?>
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
$ajaxUrl      = $this->Html->url(array('controller'=>'EscortTeams','action'=>'indexAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/escort_type:' + $('#escort_type').val();
        url = url + '/name:' + $('#name').val();
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












