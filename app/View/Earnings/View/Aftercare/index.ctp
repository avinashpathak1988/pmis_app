<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
             <?php echo $this->element('social_reintegrate_menu');   ?>               

            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Social Rehabilitation After Care Services</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Add New'), array('action' => 'addAfterCare'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
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
                        <div class="aftercareList" id="aftercareList">
                        
                        </div>
                        <div id="addHeadRemarkModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->

                            <?php echo $this->Form->create('Aftercare',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <?php echo $this->Form->input('id',array('div'=>false,'label'=>false,'class'=>'form-control ','id'=>'aftercare_id','type'=>'hidden'));?>
                            

                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Add Head of Program Remark and Prisoner Qualities</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="control-group">
                                    <label class="control-label">Head of Programme Remark :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('head_remark',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'text','placeholder'=>'Enter Head of Programme Remark','id'=>'head_remark','rows'=>3,'required'=>false,'maxlength'=>1000));?>
                                    </div>
                                    </div>

                                    <div class="control-group">
                                            <label class="control-label">After Care Activity:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('activity',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'select','options'=>$activityList, 'empty'=>'-- Select Activity --','required'=>false,'id'=>'activity'));?>
                                            </div>
                                        </div>
                                    
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="HeadRemarkSaveBtn" class="btn btn-success formSaveBtn" formnovalidate="true", onclick="submitHeadRemark()">Save</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                                <?php echo $this->Form->end();?>

                        </div>
                    </div>
                    </div>
                     </div>
                    
                </div>
             </div>
         </div>

         
     </div>
</div>   

<?php
$ajaxUrlAfterCareList = $this->Html->url(array('controller'=>'Aftercare','action'=>'aftercareAjax'));
$ajaxUrlPrisonerDetails = $this->Html->url(array('controller'=>'Education','action'=>'getPrisonerDetail'));

$ajaxUrlsubmitHeadRemark = $this->Html->url(array('controller'=>'SocialRehabiliationProgramme','action'=>'submitHeadRemark'));
?>
<script type="text/javascript">
    
    $( document ).ready(function() {
        /*$('#collapsedSearch').addClass('collapsed');
        $('#searchPrisonerOne').removeClass('in');
        $('#searchPrisonerOne').css('height','0px');*/
        showListSearch();
    });


    function showListSearch(){
        var url ='<?php echo $ajaxUrlAfterCareList?>';
        $.post(url, $('#SearchIndexForm').serialize(), function(res) {
            if (res) {
                $('#aftercareList').html(res);
            }
        });
    }
    
function submitHeadRemark(){
    var url ='<?php echo $ajaxUrlsubmitHeadRemark?>';
        $.post(url,$('#AftercareIndexForm').serialize(), function(res) {
                if (res == 'success') {
                    //dynamicAlertBox('Message', 'continued successfully !');
                    location.reload();
                }else{
                    dynamicAlertBox('Message', 'continuation failed !');
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