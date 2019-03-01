<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Height List</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link('Add New Height',array('action'=>'add'),array('class' => 'btn btn-mini btn-primary'));?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                <div class="row">
                   <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Height Type:</label>
                            <div class="controls">
                                <?php 
                                $options=array('Centimetre'=>'Centimetre');//,'Inch'=>'Inch'
                                echo $this->Form->input('heighttype_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$options, 'empty'=>'','required'=>false,'id'=>'height_type'));?>
                            </div>
                        </div>
                    </div>
                </div>        
                <div class="form-actions" align="center">
                    <?php echo $this->Form->button('Search', array('type'=>'button','class'=>'btn btn-success','div'=>false,'label'=>false,'onclick'=>'javascript:showData();'))?>
                    <?php echo $this->Form->button('Reset', array('type'=>'reset', 'class'=>'btn btn-warning', 'div'=>false, 'label'=>false,'onclick'=>'javascript:resetData("SearchIndexForm")'))?>
                </div>
                <?php echo $this->Form->end();?> 
                <div class="table-responsive" id="listingDiv">
            </div>
        </div>
    </div>
</div>

<?php
$ajaxUrl = $this->Html->url(array('controller'=>'Heights','action'=>'indexAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url = '".$ajaxUrl."'; 
        url = url + '/height_type:' + $('#height_type').val();
        //url = url + '/name:' + $('#name').val();
        $.post(url, {}, function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });    
    }

    function resetData(id){ alert(1);
        $('#'+id)[0].reset();
        $('select').select2({minimumResultsForSearch: Infinity});
        showData();
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


