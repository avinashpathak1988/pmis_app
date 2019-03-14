<div class="container-fluid">     <div class="row-fluid">         <div
class="span12">             <div class="widget-box">                 <div
class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
<h5>Shift Deployment List</h5>                     <div style="float:right
;padding-top: 7px;">                         <?php                          //

if ($this->Session->read('Auth.User.usertype_id')!=2) {
echo $this->Html->link(__('Add Shift Deployment'), array('action' => '/add'),
array('escape'=>false,'class'=>'btn btn-success btn-mini'));
}                          ?>
                        
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Shift</label>
                                <div class="controls">
                                      <?php echo $this->Form->input('shift_id',array('div'=>false,'label'=>false,'type'=>'select','options'=>$shiftList,'empty'=>'','class'=>'span11 pmis_select','required','id'=>'shift_id'));?>
                                </div>
                            </div>
                        </div>

                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Force:</label>
                                <div class="controls">
                                     <?php echo $this->Form->input('',array('div'=>false,'label'=>false,'type'=>'select','options'=>$forceList,'empty'=>'','class'=>'span11 pmis_select','required','id'=>'force_id'));?>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row-fluid">

                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Area Of Deployment:</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('deploy_area',array('div'=>false,'label'=>false,'type'=>'select','options'=>$arealist,'empty'=>'','class'=>'span11 pmis_select','required','id'=>'deploy_area'));?>
                                </div>
                            </div>
                        </div>
                      
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Date:</label>
                                <div class="controls">
                                     <?php echo $this->Form->input('shift_date',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Start date', 'data-date-format'=>"dd-mm-yyyy",
                                                     'readonly'=>'readonly','class'=>'form-control to_date','type'=>'text','id'=>'shift_date','required'));?>
                                </div>
                            </div>
                        </div>
                    </div>
                      <div class="row-fluid">

                       <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Prison</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'','options'=>$prisonList, 'class'=>'form-control pmis_select', 'id'=>'prison_id'));?>
                                    </div>
                                </div>
                            </div>
                      
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">End Date:</label>
                                <div class="controls">
                                     <?php echo $this->Form->input('created',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter End date', 'data-date-format'=>"dd-mm-yyyy",
                                                     'readonly'=>'readonly','class'=>'form-control to_date','type'=>'text','id'=>'created','required'));?>
                                </div>
                            </div>
                        </div>
                    </div>


                    
                </div>
                <div class="row-fluid">
              
                    <div class="form-actions" align="center">
                        <?php echo $this->Form->button('Search', array('type'=>'button','class'=>'btn btn-success','div'=>false,'label'=>false,'onclick'=>'javascript:showData();'))?>
                         <?php echo $this->Form->input('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData()"))?>
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
$ajaxUrl      = $this->Html->url(array('controller'=>'ShiftDeployments','action'=>'indexAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        
        var url = '".$ajaxUrl."';
        var shift_id = $('#shift_id').val();
        var force_id = $('#force_id').val();
        var deploy_area = $('#deploy_area').val();
        var shift_date = $('#shift_date').val();
        var prison_id = $('#prison_id').val();
        var created = $('#created').val();
        url = url + '/shift_id:' + shift_id + '/force_id:' + force_id + '/deploy_area:' + deploy_area + '/shift_date:' + shift_date + '/prison_id:' + prison_id + '/created:' + created  ;
        $.post(url, {}, function(res) {
           
            if (res) {
                //console.log(res);
                $('#listingDiv').html(res);
            }
        });    
    }

    function resetData()
    {
        // alert(1);
        $('#shift_id').val('');
        $('#force_id').val('');
        $('#s2id_shift_id span').html(' -- Select Shift --');
        $('#s2id_force_id span').html(' -- Select Force --');
        $('#s2id_deploy_area span').html(' -- Select Area Of Deployment --');
        showData();
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











