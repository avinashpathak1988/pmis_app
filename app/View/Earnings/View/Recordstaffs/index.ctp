<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Staff Record By Gate Keeper</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php  if ($this->Session->read('Auth.User.usertype_id')!=2) {
                             echo $this->Html->link(__('Add New Staff Record'), array('action' => 'add'), array('escape'=>false,'class'=>'btn btn-success btn-mini'));
                        } 
                        ?>
                       
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                    <div class="row">
                       <!-- <div class="span3">
                            <div class="control-group">
                                <label class="control-label">Force NO :</label>
                                <div class="controls">
                                    <?php //echo $this->Form->input('force_no', array('type'=>'text','class'=>'form-control','id'=>'force_no','div'=>false,'label'=>false))?>
                                </div>
                            </div>
                        </div>  -->
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Date :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('from_date', array('type'=>'text','class'=>'form-control from_date','id'=>'from_date','div'=>false,'label'=>false,'readonly'=>true,'value'=>date("d-m-Y"),'style'=>'width:43%;'))?>
                                    
                                    To
                                    <?php echo $this->Form->input('to_date', array('type'=>'text','class'=>'form-control to_date','id'=>'to_date','div'=>false,'label'=>false,'readonly'=>true,'style'=>'width:43%;','value'=>date("d-m-Y")))?>                                    
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Force No :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('force_no', array('type'=>'text','class'=>'form-control','id'=>'force_no','div'=>false,'label'=>false,'style'=>';','placeholder'=>'Enter Force No'))?>
                                </div>
                            </div>
                        </div>
                          <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Prison</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Prison --','options'=>$prisonList, 'class'=>'form-control', 'id'=>'prison_id'));?>
                                    </div>
                                </div>
                            </div>

                    </div>
                    <div class="row">
                        
                        <div class="span6" style="display:<?php echo ($this->Session->read('Auth.User.usertype_id')==Configure::read("OFFICERINCHARGE_USERTYPE")) ? 'block': 'none'; ?>">
                                    <div class="control-group">
                                        <label class="control-label">Status</label>
                                        <div class="controls">
                                            <?php
                                            $defaultSearch = array();
                                            if($this->Session->read('Auth.User.usertype_id')==Configure::read("OFFICERINCHARGE_USERTYPE")){
                                                $defaultSearch = array("default"=>"Draft");
                                            }
                                            echo $this->Form->input('status',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- All --','options'=>array("Draft"=>"Not Verified","Verified"=>"Verified"), 'class'=>'form-control','required'=>false, 'id'=>'status',"required"=>false)+$defaultSearch);?>
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
$ajaxUrl      = $this->Html->url(array('controller'=>'Recordstaffs','action'=>'indexAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/from_date:' + $('.from_date').val();
        url = url + '/to_date:' + $('.to_date').val();
        url = url + '/status:' + $('#status').val();
        url = url + '/prison_id:' + $('#prison_id').val();
        url = url + '/force_no:' + $('#force_no').val();
        $.post(url, {}, function(res) {
            if (res) {
                //console.log(res);
                $('#listingDiv').html(res);
            }
        });    
    }
",array('inline'=>false));
?>  












