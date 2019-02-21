<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Visitors Passes</h5>
                    
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Add Visitor Pass'), array('action' => 'add'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                    
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                    
                    <div class="row prison-visitor">
                        <div class="span6 prison-visitor-inn">
                            <div class="control-group">
                                <label class="control-label">Visit Date :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date','type'=>'text','placeholder'=>'Start Date','id'=>'from', 'readonly'=>true,'style'=>'width:150px;'));?>
                                    To
                                    <?php echo $this->Form->input('to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date','type'=>'text','placeholder'=>'End Date','id'=>'to', 'readonly'=>true,'style'=>'width:150px;'));?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Status</label>
                                <div class="controls">
                                    <?php
                                  
                                        $defaultSearch = array("Valid"=>"Valid Passes",'Expired'=>'Expired Passes');
                                    
                                    echo $this->Form->input('pass_status',array('div'=>false,'label'=>false,'type'=>'select','options'=>$defaultSearch, 'class'=>'form-control','required', 'id'=>'verify_status','default'=>'Valid') ) ?>
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
$ajaxUrl   = $this->Html->url(array('controller'=>'VisitorPasses','action'=>'indexAjax'));
?>

<?php echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();

    });
   
    function showData(){
        var url = '".$ajaxUrl."';
        $.post(url, $('#SearchIndexForm').serialize(), function(res) {
            if (res) {
                //console.log(res);
                $('#listingDiv').html(res);
            }
        });    
    }


      ");
      ?>
      <script type="text/javascript">
          function printForm(id) 
            {

              var divToPrint=document.getElementById('printModal_'+id);

              var newWin=window.open('','Print-Window');

              newWin.document.open();

              newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

              newWin.document.close();

              setTimeout(function(){newWin.close();},10);

            }
      </script>