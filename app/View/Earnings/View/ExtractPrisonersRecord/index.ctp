<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Prisoners Record Search</h5>
                
                        <!-- <a class="" id="searchIcon" href="#searchBox" data-toggle="collapse" title="Search"><span class="icon"> <i class="icon-search" style="color: #000;"></i> </span></a> -->
                    
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link('Add New ',array('action'=>'addSelectPrisoner'),array('class' => 'btn btn-primary'));?>
                        &nbsp;&nbsp; 
                    </div>
                </div>
                <div class="widget-content nopadding">

                    <div class="collapse" style="height:200px;" id="searchBox">
                        <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                        <div class="row phy-prop-list">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Prisoner Number :<?php echo $req; ?>:</label>
                                    <div class="controls">
                                            <?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','options'=>$prisonersList,'onchange'=>'showFields(this.value)', 'empty'=>'-- Select Prisoner no --','required'=>false,'id'=>'prisoner_no'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                  
                                <div class="control-group">
                                    <label class="control-label">Status:</label>
                                    <div class="controls">
                                       <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Status --','options'=>$statusListData, 'class'=>'form-control','required', 'id'=>'status','default'=>$default_status));?>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="span12 add-top" align="center" valign="center">
                                <?php echo $this->Form->button('Search', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'id'=>'btnsearchcash', 'onclick'=>"showListSearch();"))?>
                                <?php echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData('SearchPhysicalPropertyListForm')"))?>
                            </div>                        
                        </div> 
                        <?php echo $this->Form->end();?>
                    </div>
                    <div id="dataList"></div>

                </div>
            </div> 
        </div>
    </div>
</div>

<?php
$ajaxUrlList = $this->Html->url(array('controller'=>'ExtractPrisonersRecord','action'=>'listAjax'));

?>
<script type="text/javascript">
    $( document ).ready(function() {
        showListSearch();
        $('#prisoner_no').select2();
    });

    function showListSearch(){
        var url ='<?php echo $ajaxUrlList?>';
        $.post(url, $('#SearchIndexForm').serialize(), function(res) {
            if (res) {
                $('#dataList').html(res);
            }
        });
    }

</script>

