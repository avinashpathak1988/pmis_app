<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">

            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5> Review Sentence</h5>
                  <?php  if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){ ?>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Add New'), array('action' => 'selectPrisoner'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                    <?php } ?>
                </div>
                <div class="widget-content nopadding">
                    
                        <div class="span12" style="margin-left: 0px;">
                            <div class="widget-content nopadding">
                                <div class="widget-title"> 
                                    <span class="icon"><i class="icon-th"></i></span>
                                     <h5> Search Prisoner</h5>
                                     <a class="" id="collapsedSearch" href="#searchPrisonerOne" data-toggle="collapse">
                                        <span class="icon"><i class="icon-search" style="color:#000;"></i></span>
                                     </a>
                                     <div style="float:right;padding-top: 7px;">
                                             &nbsp;&nbsp;
                                     </div>
                                </div>
                                <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                                <div id="searchPrisonerOne" class="row collapse" style="height:auto;">
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
                                            <label class="control-label">Prisoner Name. :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('sprisoner_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 ', 'type'=>'text','placeholder'=>'Search Prisoner Name.','id'=>'sprisoner_name', 'style'=>'width:200px;'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span12 add-top" align="center" valign="center">
                                        <?php echo $this->Form->button('Search', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'id'=>'btnsearchcash', 'onclick'=>"showListSearch();" ))?>
                                        <?php echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData('SearchIndexForm')"))?>
                                    </div> 
                                </div>    
                                <?php echo $this->Form->end();?>
          
                            </div>  
                        </div> 
                      <div class="row-fluid">
                         <div class="span12">
                        <div class="reviewSentenceList" id="reviewSentenceList">
                        
                        </div>
                    </div>
                     </div> 
                    
                </div>
             </div>
         </div>

         
     </div>
</div>   

<?php
$ajaxUrlDischargeSummaryList = $this->Html->url(array('controller'=>'ReviewSentence','action'=>'indexAjax'));
/*$ajaxUrlPrisonerDetails = $this->Html->url(array('controller'=>'Education','action'=>'getPrisonerDetail'));*/
?>
<script type="text/javascript">
    
    $( document ).ready(function() {
        showListSearch();
        $('#sprisoner_no').select2();

    });


    function showListSearch(){
        var url ='<?php echo $ajaxUrlDischargeSummaryList?>';
        $.post(url, $('#SearchIndexForm').serialize(), function(res) {
            if (res) {
                $('#reviewSentenceList').html(res);
            }
        });
    }

    function resetData(id){
        $('#'+id)[0].reset();

        //$('select').select2({minimumResultsForSearch: Infinity});
        $('select').select2().select2("val", null);
        showListSearch();
    }
  

</script>               