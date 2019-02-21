<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Station Journals</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php 
                        // debug($this->Session->read('Auth.User.usertype_id'));

                        if ($this->Session->read('Auth.User.usertype_id')!=2) {
                            echo $this->Html->link('Add Station Journal',array('action'=>'add'),array('escape'=>false,'class'=>'btn btn-success btn-mini'));
                       
                        }
                         ?>
                       
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                    <div class="row" style="padding-bottom: 14px;">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Select prison :</label>
                                <div class="controls">
                                 <?php
                                    echo $this->Form->input('prison_id',array(
                                      'div'=>false,
                                      'label'=>false,
                                      'class'=>'span11 pmis_select',
                                      'empty'=>'',
                                      'options'=>$prisonList,
                                      'id'=>'prison_id'
                                    ));
                                ?>
                                    <?php //echo $this->Form->input('code', array('type'=>'text', 'id'=>'code', 'class'=>'span11','div'=>false,'label'=>false,'placeholder'=>'Enter Station Code'))?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Date :</label>
                                <div class="controls">
                                     <?php echo $this->Form->input('journal_date', array('type'=>'text', 'id'=>'journal_date', 'class'=>'span11 mydate','div'=>false,'label'=>false,'placeholder'=>'Select Date','data-date-format'=>"dd-mm-yyyy",'readonly'=>'readonly',))?>
                                    To
                                    <?php echo $this->Form->input('to',array('div'=>false,'label'=>false,'class'=>'span11 to_date mydate','type'=>'text','placeholder'=>'End Date','id'=>'to',"readonly"=>true, 'required'=>false,'style'=>'    width:43%;'));?>
                                  
                                </div>
                            </div>
                        </div>  

                    </div>           
                    <div class="form-actions" align="center">
                        <?php echo $this->Form->button('Search', array('type'=>'button', 'class'=>'btn btn-primary','div'=>false,'label'=>false,'onclick'=>"javascript:return showData();"))?>
                        <?php echo $this->Form->button('Reset', array('type'=>'reset', 'class'=>'btn btn-warning', 'div'=>false, 'label'=>false))?>
                    </div>
                    <?php echo $this->Form->end();?> 
                     <div class="widget-content">
                        <div class="table-responsive" id="listingDiv">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$ajaxUrl = $this->Html->url(array('controller'=>'stationjournals','action'=>'indexAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/prison_id:'+$('#prison_id').val();
        url = url + '/journal_date:'+$('#journal_date').val();
        url = url + '/to:'+$('#to').val();
        $.post(url, {}, function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });
    }
",array('inline'=>false));
?> 
<script type="text/javascript">
    $(document).ready(function(){
        //$('.datepicker').datepicker();
    });
</script>