<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Select prisoner for Review Sentence</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Review Sentence list'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
              
                <div class="widget-content nopadding">
                    <div class="row-fluid">
                        <div class="span6">
                                <div class="control-group">
                                        <label class="control-label">Prisoner No. :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('sprisoner_no',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','options'=>$prisonersList, 'empty'=>'-- Select Prisoner no --','required'=>false,'id'=>'sprisoner_no'));?>
                                        </div>
                                </div>
                        </div>
                        <div class="span6">
                                <div class="control-group">
                                    <?php echo $this->Html->link(__('Add'), array('action' => ''), array('escape'=>false,'class'=>'btn btn-success btn-proceed')); ?>
                            </div>
                        </div>

                    </div> <!--  end row fluid -->


                </div>
            </div>
        </div>
    </div>
</div>           

<?php
/*$ajaxUrlPrisonersList = $this->Html->url(array('controller'=>'ReviewSentence','action'=>'selectPrisonerAjax'));*/

?>
<script type="text/javascript">
    
    $( document ).ready(function() {
        /*showListSearch();*/
        $('#sprisoner_no').select2();
        $('.btn-proceed').on('click',function(e){
            e.preventDefault();
            id = $('#sprisoner_no').val();
            if(id == ''){
                alert("Please select Prisoner");
            }else{
                window.location = '<?php echo $this->webroot;?>ReviewSentence/add/'+ id;
            }
        });
    });


    /*function showListSearch(){
        var url ='<?php echo $ajaxUrlPrisonersList?>';
        $.post(url, {}, function(res) {
            if (res) {
                $('#prisoners_list').html(res);
            }
        });
    }*/

  

</script>               