<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Select prisoner to add Discharge Summary</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Discharge Summary list'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
              
                <div class="widget-content nopadding">
                	<div class="row-fluid">
                        <div class="span12 ">
                        <!-- form2 -->
                        <div class="aftercareform">
                            <div id="prisoners_list"></div>
                        </div>
                    </div> 
                    </div> <!--  end row fluid -->


                </div>
            </div>
        </div>
    </div>
</div>           

<?php
$ajaxUrlDischargePrisonersList = $this->Html->url(array('controller'=>'DischargeBoardSummary','action'=>'addSelectPrisonerAjax'));

?>
<script type="text/javascript">
    
    $( document ).ready(function() {
        showListSearch();
    });


    function showListSearch(){
        var url ='<?php echo $ajaxUrlDischargePrisonersList?>';
        $.post(url, {}, function(res) {
            if (res) {
                $('#prisoners_list').html(res);
            }
        });
    }

  

</script>               