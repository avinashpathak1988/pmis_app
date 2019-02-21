<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
/*
.committmentdate, .nextdatetocourt ,.convictiondate ,.aquiteddate{
    display: none;
}*/
</style>
<?php
if(isset($this->request->data['CauseList']['session_date']) && $this->request->data['CauseList']['session_date']!=''){
    $this->request->data['CauseList']['session_date'] = date("d-m-Y", strtotime($this->request->data['CauseList']['session_date']));
}
if(isset($this->request->data['CauseList']['next_date']) && $this->request->data['CauseList']['next_date']!=''){
    $this->request->data['CauseList']['next_date'] = date("d-m-Y", strtotime($this->request->data['CauseList']['next_date']));
}
// debug($this->request->data);
?>
<div class="container-fluid">
    <div class="row-fluid">
        <div id="commonheader"></div>
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Manage Court Attendance</h5>
                    <div style="float:right;padding-top: 3px;">
                        <?php echo $this->Html->link('Prisoners List',array('controller'=>'prisoners','action'=>'index'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                        <ul class="nav nav-tabs">                            
                            <li><a href="#causeList" id="causeListBtn">Application To Court</a></li>
                            <li><a href="#produceToCourt" id="produceToCourtBtn" onclick="ToCourt()">To Court</a></li>
                            <li><a href="#returnFromCourt" id="returnFromCourt" onclick="returnFromCourt()">From Court</a></li>
                        </ul>
                        <div class="tabscontent">                            
                            <div id="causeList" align="center">
                                <div class="">
                                    <?php if($isAccess == 1){?>
                                        <?php echo $this->Form->create('ApplicationToCourt',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                        <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                                        <?php echo $this->Form->input('uuid', array('type'=>'hidden','value'=>$uuid))?>
                                        <div class="row" style="padding-bottom: 14px;text-align: left;">
											<div class="span12">
                                                <div class="control-group">
                                                    <label class="control-label"></label>
                                                    <div class="controls uradioBtn">
													<?php
                                                       $to_options= array('1'=>'Out of time','2'=>'Any other');
                                                    $attributes2 = array(
                                                        'legend' => false, 
														'onclick'=> 'dissplayApplicationName(this.value)'
                                                    );
                                                    echo $this->Form->radio('application_name_option', $to_options, $attributes2);
													?>
														</div>
                                                </div>
                                            </div> 
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Application name :</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('application_name',array('id'=>'application_name','div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter application name.','required'=>true,'id'=>'application_name','title'=>'Please enter  application name'));?>                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Prisoner Number :</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->imput('prioner_no',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumeric nospace','type'=>'text','placeholder'=>'Enter Court file Number','required'=>true,'id'=>'prisoner_no','value'=>isset($prisoner_no) ? $prisoner_no :'','title'=>'Please provide high court case no'));?>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Submission Date :</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('submission_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate maxCurrentDate','type'=>'text','placeholder'=>'Enter Submission date.','required'=>true,'id'=>'submission_date','readonly','title'=>'Please select Submission date'));?>
                                                    </div>
                                                </div>
                                            </div>
                                           
                                            
                                             <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Court Level<?php //echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php
                                                           echo $this->Form->input('court_level',array(
                                                              'div'=>false,
                                                              'label'=>false,
                                                              'type'=>'select',
															   'class'=>'pmis_select',
                                                              'options'=>$magisterialList, 'empty'=>'',
                                                              'required'=>true,
															  'title'=>"Please select court level area",
															  'onchange'=>'showCourtName(this.value,1)'
                                                            ));
                                                         ?>
                                                    </div>
                                                </div>
                                            </div>

											<div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Court Name<?php //echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php
                                                           echo $this->Form->input('court_name',array(
                                                              'div'=>false,
                                                              'label'=>false,
                                                              'type'=>'select',
                                                              'options'=>$magisterialList, 'empty'=>'',
                                                              'required'=>true,
															  'title'=>"Please select court name area",
															  'id'=>'court_name',
															  'class'=>'pmis_select',
                                                            ));
                                                         ?>
                                                    </div>
                                                </div>
                                            </div> 	
												
											<div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Upload :</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('upload_file',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumeric nospace','type'=>'file','placeholder'=>'Enter Court file Number','required'=>false,'id'=>'upload_file','title'=>''));?>
                                                    </div>
                                                </div>
                                            </div> 
                                                         
                                                   
                                              
                                                <div class="clearfix"></div>
                                                 <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Case File No<?php echo $req; ?>:</label>
                                                        <div class="controls">
                                                                <?php 
                                                               
                                                                 echo $this->Form->input('case_file_no',array('id'=>'case_file_no','div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$caseFileno, 'empty'=>'','id'=>'appeal_status', 'title'=>'Appeal Status Is required.')); 

                                                                 ?>
                                                        </div>
                                                    </div>
                                                </div>
 

                                            
                                        </div>
                                        <!-- start notes of appeal  -->
                                       
                                        <!-- end notes of appeal  -->
                                        <!-- starts memorandum of appeal -->
                                        <div class="row" style="padding-bottom: 14px;text-align: left; display: none;" id="notes_appeal_memorandum">
                                            
                                             <!--   <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Court Level<?php //echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php
                                                           // echo $this->Form->input('magisterial_id',array(
                                                            //   'div'=>false,
                                                            //   'label'=>false,
                                                            //   'type'=>'select',
                                                            //   'options'=>$magisterialList, 'empty'=>'-- Select Court level --',
                                                            //   'required','title'=>"Please select court level area","title"=>"Please select court level"
                                                            // ));
                                                         ?>
                                                    </div>
                                                </div>
                                            </div> -->
                                           <!--  <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Court <?php //echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php //echo $this->Form->input('court_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$courtList, 'empty'=>'-- Select Court --','required'=>false,'title'=>'please select court name'));?>
                                                    </div>
                                                </div>
                                            </div> -->
                                            
                                             
                                               <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Appeal No </label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->textarea('appeal_text',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumeric nospace','type'=>'text','placeholder'=>'Enter Appeal No','required'=>false,'id'=>'appeal_text',));?>
                                                    </div>
                                                </div>
                                            </div> 



                                        </div>
                                        <!-- end memerandom of appeal -->

                                        
                                                                                
                                                           
                                        <div class="span12 add-top" align="center" valign="center">
                                            <?php
                                                if(isset($this->data["Courtattendance"]) && !empty($this->data["Courtattendance"])){
                                                    echo $this->Form->button('Update', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit', 'formnovalidate'=>true));?>&nbsp;&nbsp;
                                                    <?php
                                                    echo $this->Form->button('Cancel', array('type'=>'button', 'class'=>'btn btn-danger','div'=>false,'label'=>false,'id'=>'btn_cancel', 'formnovalidate'=>true));
                                                }
                                                else{
                                                    echo $this->Form->button('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit', 'formvalidate'=>true));
                                                }
                                            ?>
                                        </div>
                                        <?php echo $this->Form->end();?>
                                    <?php }?>
                                </div>
                                <div class="table-responsive" id="causeListDiv"></div>
                            </div>

                            <div id="produceToCourt">
                                <div class="">
                                    <?php if($isAccess == 1){?>
                                        <?php echo $this->Form->create('Courtattendance',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                        <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                                        <?php echo $this->Form->input('uuid', array('type'=>'hidden','id'=>'uuid','value'=>$uuid))?>
										<?php echo $this->Form->input('prisoner_id', array('type'=>'hidden','id'=>'prisoner_id','value'=>$prisoner_id))?>
										<?php echo $this->Form->input('prison_id', array('type'=>'hidden','id'=>'prison_id','value'=>$prison_id))?>
                                        <div class="" style="padding-bottom: 14px;">                                            
                                          <!--  <div class="clearfix"></div>
                                            
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Cause List cvnvcn<?php //echo $req; ?> :</label>
                                                    <div class="controls">
                                                        <?php //echo $this->Form->input('cause_list_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Cause List --','options'=>$causeList, 'class'=>'form-control','required','title'=>'Please select cause list'));?>
                                                    </div>
                                                </div>
                                            </div> -->
                                             <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Authority<?php echo $req; ?> :</label>
                                                    <div class="controls">
                                                        <?php $authority = array('1' => 'Normal Schedule', '2' => 'Cause list', '3' =>'Production Warrant ');

                                                        echo $this->Form->input('authority_type',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Cause List --','options'=>$authority,'onchange' => 'showAuthority(this.value)', 'class'=>'form-control','required','id'=>'authority_id','title'=>'Please select cause list'));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <span id="causeDetails">
                                            
                                            </span>
                                        </div>
                                        <!-- starts normal sheduled -->
										
										<!-- for common field -->
										<div style="padding-bottom: 14px; display: none;" id="common_field">
										<div class="span6" id="crb_level">
													<div class="control-group">
														<label class="control-label">CRB no:</label>
														<div class="controls">
															<?php echo $this->Form->text('crb_no',array('div'=>false,'label'=>false,'class'=>'form-control span11 commontab','type'=>'text','placeholder'=>'Enter CRB No.','id'=>'crb_no','title'=>'Please CRB No.','autocomplete'=>'off'));?>
														</div>
													</div>
												</div>                       
												<div class="span6">
													<div class="control-group">
														<label class="control-label">Court Level:</label>
														<div class="controls">
															<?php
                                                           echo $this->Form->input('court_level',array(
                                                              'div'=>false,
                                                              'label'=>false,
                                                              'type'=>'select',
                                                              'options'=>$magisterialList, 'empty'=>'-- Select Court level --',
                                                              'title'=>"Please select court level area",
															  'onchange'=>'showCourtName(this.value,2)',
															  'id'=>'court_level',
															  'class'=>'commontab'
                                                            ));
                                                         ?>
														</div>
													</div>
												</div>
												<?php if(isset($this->request->data['Courtattendance']['court_id']) && $this->request->data['Courtattendance']['court_id']!= '') {
												$options = $courtList;
												}else { $options = ''; }?>
												<div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Court Name<?php //echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php
                                                           echo $this->Form->input('court_id',array(
                                                              'div'=>false,
                                                              'label'=>false,
                                                              'type'=>'select',
                                                              'options'=>$options, 'empty'=>'-- Select Court Name --',
                                                              'title'=>"Please select court name area",
															  'id'=>'court_name2',
															  'class'=>'commontab'
                                                            ));
                                                         ?>
                                                    </div>
                                                </div>
												</div> 
											<div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Court File No<?php echo $req; ?>:</label>
                                                        <div class="controls">
                                                                <?php 
                                                               
                                                                 echo $this->Form->input('court_file_no',array('id'=>'court_file_no','div'=>false,'label'=>false,'class'=>'form-control span11 commontab','type'=>'text', 'title'=>'Court file no .','autocomplete'=>'off')); 

                                                                 ?>
                                                        </div>
                                                    </div>
                                                </div>
											<?php if(isset($this->request->data['Courtattendance']['court_level']) && $this->request->data['Courtattendance']['court_level']== 8) {
														$display ="";
											 }else{ $display ="display:none;"; } ?>	
											<div class="span6" style="<?php echo $display;?>" id="high_court_file">
                                                    <div class="control-group">
                                                        <label class="control-label">High Court File no<?php echo $req; ?>:</label>
                                                        <div class="controls">
                                                                <?php 
                                                               
                                                                 echo $this->Form->input('high_court_file_no',array('id'=>'high_court_file_no','div'=>false, 'label'=>false,'class'=>'form-control span11 commontab','type'=>'text', 'title'=>'Enter high court file no.','autocomplete'=>'off')); 

                                                                 ?>
                                                        </div>
                                                    </div>
                                                </div>
											
										</div>		
										<!-- end -->
										
										

                                         <div class="" style="padding-bottom: 14px; display: none;" id="normal_sheduled">

                                             <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Date For Court<?php echo $req; ?>:</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('court_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11 normaltab','type'=>'text','placeholder'=>'Enter Appeal date to court.','id'=>'court_date','readonly','title'=>'Please select  appleal to court'));?>
                                                        </div>
                                                    </div>
                                                </div>    
												
										
											
                                        </div>
                                        <!-- ends noramal sheduled -->

                                        <!-- starts cause list -->
                                         <div class="" style="padding-bottom: 14px; display: none;" id="cause_list">

										 <div class="span6">
												<div class="control-group">
													 <label class="control-label">Production Warrant:</label>
													<div class="controls">
														<?php echo $this->Form->input('is_production_warrant',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'checkbox','id'=>'from_court_type',));?>
														
													</div>
												</div>
										</div>
										
										 <div class="span6">
												<div class="control-group">
													<label class="control-label">Date Of Cause List<?php echo $req; ?>:</label>
													<div class="controls">
														<?php echo $this->Form->input('cause_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11 causelisttab','type'=>'text','placeholder'=>'Enter Appeal date to court.','id'=>'cause_date','readonly','title'=>'Please select  appleal to court'));?>
													</div>
												</div>
											</div>
							
										<div class="span6">
												<div class="control-group">
													<label class="control-label">Session  Commence Date<?php echo $req; ?>:</label>
													<div class="controls">
														<?php echo $this->Form->input('commence_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11 causelisttab','type'=>'text','placeholder'=>'Enter session commence date.','id'=>'commence_date','readonly','title'=>'Please enter session commence date'));?>
													</div>
												</div>
											</div>
                                       
                                          <div class="span6">
                                             <div class="control-group">
                                                <label class="control-label">Session No.<?php echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->textfields('session_text',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumeric nospace causelisttab','type'=>'text','placeholder'=>'Enter session no','id'=>'session_text','title'=>'Please provide Session No','autocomplete'=>'off'));?>
                                                    </div>
                                                </div>
                                            </div> 

                                            <div class="span6">
                                             <div class="control-group">
                                                <label class="control-label">Appeal No.<?php echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->textfields('appeal_text',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumeric nospace causelisttab','type'=>'text','placeholder'=>'Enter Appeal No','id'=>'appeal_text','title'=>'Please provide Appeal No','autocomplete'=>'off'));?>
                                                    </div>
                                                </div>
                                            </div>

											<?php if(isset($remark) && $remark == '7'){?>
											<div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Remark From Court<?php echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('remark_from_court',array('div'=>false,'label'=>false,'class'=>'form-control span11 warranttab','type'=>'text','placeholder'=>'Enter remark from court','id'=>'remark_from_court',));?>
                                                    </div>
                                                </div>
                                            </div>
											<?php } ?>

                                        </div>



                                        <!-- ends cause list -->
										
										<!-- common drop offence drop down -->
										<div id="common_offence" style="display:none;">
										<?php if(isset($this->request->data['Courtattendance']['case_no']) && $this->request->data['Courtattendance']['case_no']!= '') {
												$case_no = explode(',',$this->request->data['Courtattendance']['case_no']);
										}else { $case_no = ''; }?>
											<div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">File no<?php echo $req; ?>:</label>
                                                        <div class="controls">
                                                                <?php 
                                                               
                                                                 echo $this->Form->input('case_no.',array('value'=>$case_no,'id'=>'file_no','div'=>false,'label'=>false,'class'=>'form-control span11 commontab','type'=>'select', 'onchange'=>'showOffenceCount(this.value,2)', 'multiple','options'=>$caseFileno, 'empty'=>'--Select file no--')); 

                                                                 ?>
                                                        </div>
                                                    </div>
                                                </div>
												<?php if(isset($this->request->data['Courtattendance']['offence_id']) && $this->request->data['Courtattendance']['offence_id']!= '') {
													$offence_id = explode(',',$this->request->data['Courtattendance']['offence_id']);
													$offence = array();
													foreach($offence_id as $offkey => $offval)
													{
														$offence[$offval] = $offval;
													}
													
													?>
												<div class="span6" id="offence_div2">
												   <div class="control-group">
														<label class="control-label">Offence<?php echo $req; ?> :</label>
														<div class="controls">
															<?php echo $this->Form->input('offence_id.',array('value'=>$offence,'options'=>$offencess,'div'=>false,'label'=>false,'type'=>'select', 'class'=>'form-control span11 commontab', 'id'=>'offence_no2','multiple'));?>
														</div>
													</div>
												</div> 
											<?php }else{ ?>
												<div class="span6" style="display:none;" id="offence_div2">
												   <div class="control-group">
														<label class="control-label">Offence<?php echo $req; ?> :</label>
														<div class="controls">
															<?php echo $this->Form->input('offence_id.',array('div'=>false,'label'=>false,'type'=>'select', 'class'=>'form-control span11 commontab', 'id'=>'offence_no2','multiple'));?>
														</div>
													</div>
												</div> 
                                            <?php } ?>												
                                            <?php /*if(isset($this->request->data['Courtattendance']['offence_count']) && $this->request->data['Courtattendance']['offence_count']!= '') {
													$offence_count = explode(',',$this->request->data['Courtattendance']['offence_count']);
													$count = array();
													foreach($offence_count as $key => $val)
													{
														$count[$val] = $val;
													}
													
													?>
													<div class="span6" id="count_div2">                                                         
													<div class="control-group">
														   <label class="control-label">Count<?php echo $req; ?> :</label>
															<div class="controls">
																<?php 
																
																	echo $this->Form->input('offence_count.',array('value'=>$count,'options'=>$offencecountList,'id'=>'offence_count2','div'=>false,'label'=>false,'class'=>'form-control span11 commontab','type'=>'select', 'placeholder'=>'Enter count','multiple'));
															   
																?>
															</div>
													</div>  
												</div>
											<?php }else{
												?>
												<div class="span6" style="display:none;" id="count_div2">                                                         
													<div class="control-group">
														   <label class="control-label">Count<?php echo $req; ?> :</label>
															<div class="controls">
																<?php 
																
																	echo $this->Form->input('offence_count.',array('id'=>'offence_count2','div'=>false,'label'=>false,'class'=>'form-control span11 commontab','type'=>'select', 'placeholder'=>'Enter count','multiple'));
															   
																?>
															</div>
													</div>  
												</div>
												<?php } */?>
												 <div class="span6" id="presiding_judge" style="display:none;">
													<div class="control-group">
														
															<label class="control-label" for="1_magistrate_level">Presiding judge:</label>
															<div class="controls">
																<?php echo $this->Form->input('presiding_judge',array('type'=>'text', 'div'=>false,'label'=>false,'class'=>'form-control commontab', 'id'=>'presiding_judge_id',"title"=>"Please select Presiding Judge",'autocomplete'=>'off'));?>
															</div>
													
													</div>
												</div>
												
											</div>	
										<!-- common dropn down-->
										
                                        <!-- starts production warrant -->


                                         <div class="" style="display: none;" id="production_warrant">

											 <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">From Cause List<?php echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('from_cause_list',array('div'=>false,'label'=>false,'class'=>'form-control span11 warranttab','type'=>'checkbox','id'=>'from_cause_list'));?>
														
													</div>
                                                </div>
                                            </div>

											<div class="span6" id="from_cause_list_date" style="display:none;">
                                                <div class="control-group">
                                                    <label class="control-label">From Cause List Date<?php echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('from_cause_list_date',array('div'=>false,'onchange'=>'fromCauseListData(this.value)','label'=>false,'class'=>'form-control span11 warranttab','empty'=>'--Select Cause list date--','type'=>'select','options'=>$cause_list_date));?>
														
													</div>
                                                </div>
                                            </div>
											
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Production warrant date<?php echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('production_warrent_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11 warranttab','type'=>'text','placeholder'=>'Enter Production warrant date.','id'=>'production_warrent_date','readonly','title'=>'Please select production_warrent_date'));?>
                                                    </div>
                                                </div>
                                            </div>
											<?php if(isset($remark) && $remark == '7'){?>
											<div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Remark From Court<?php echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('remark_from_court',array('div'=>false,'label'=>false,'class'=>'form-control span11 warranttab','type'=>'text','placeholder'=>'Enter remark from court','id'=>'remark_from_court',));?>
                                                    </div>
                                                </div>
                                            </div>
											<?php } ?>
                                      
                                        </div>
										
										
										<div id="reason_for_court" style="display:none;">
										 <div class="span6"  id="court_reason">
													<div class="control-group" >
														
															<label class="control-label">Reason for court:</label>
															<div class="controls">
																<?php echo $this->Form->input('reason',array('type'=>'text', 'div'=>false,'label'=>false,'class'=>'form-control','id'=>'reason_courts',"title"=>"Enter reason for court",'autocomplete'=>'off'));?>
															</div>
													
													</div>
												</div>
										</div>



                                        <!-- ends production warrant -->
                                        
                                        <div class="span12 add-top" align="center" valign="center">
                                            <?php
                                                if(isset($this->data["Courtattendance"]) && !empty($this->data["Courtattendance"])){
                                                    echo $this->Form->button('Update', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit', 'formnovalidate'=>true));?>&nbsp;&nbsp;
                                                    <?php
                                                    echo $this->Form->button('Cancel', array('type'=>'button', 'class'=>'btn btn-danger','div'=>false,'label'=>false,'id'=>'btn_cancel', 'formnovalidate'=>true));
                                                }
                                                else{
                                                    echo $this->Form->button('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit', 'formnovalidate'=>true));
                                                }
                                            ?>
                                        </div>
                                        <?php echo $this->Form->end();?>
                                    <?php }?>
                                </div>
                                <div class="table-responsive" id="produceToCourtDiv"></div>  
                            </div>
                            <div id="returnFromCourt" align="center"  >
                                <div class="">
                                    <?php if($isAccess == 1){?>
                                        <?php echo $this->Form->create('ReturnFromCourt',array('class'=>'form-horizontal'));?>
                                        <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                                        <?php echo $this->Form->input('uuid', array('type'=>'hidden','value'=>$uuid))?>
                                        <?php echo $this->Form->input('prisoner_id', array('type'=>'hidden','value'=>$prisoner_id))?>
                                        <div class="row" style="padding-bottom: 14px;text-align: left;">
                                            <div class="span6">
                                                
                                                <div class="control-group">

                                                <label class="control-label">Case File Number <?php echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('case_file_number',array('div'=>false,'label'=>false,'class'=>'form-control  span11 pmis_select','onchange'=>'showOffenceCountReturn(this.value,2)','type'=>'select','options'=>$caseFileno,'required'=>true,'id'=>'s_court_file_number','empty'=>''));?>
                                                    </div>
                                                </div>
                                                
                                            </div> 
											<div class="span6">
                                                <div class="control-group">
                                                        <label class="control-label">Offence List <?php echo $req; ?>:</label>
                                                        <div class="controls">
                                                        <?php echo $this->Form->input('offence_id',array('div'=>false,'id'=>'return_offence_id','onchange'=>'showCaseTypeReturn(this.value)','label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>'', 'empty'=>'','title'=>'please select offence '));?>
														<?php //echo $this->Form->input('offence_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','onchange'=>'fetchCommitmentDate(this.value)','options'=>$offenceList, 'empty'=>'-- Select Offence--','title'=>'please select offence '));?>
                                                        </div>
                                                </div> 
                                            </div>
                                            
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Case Type <?php echo $req; ?>:</label>
                                                    <div class="controls">
													<?php 
													//debug($offenceCategory);
													if (isset($offenceCategory) && count($offenceCategory) > 0)
													{
														$options = $offenceCategory;
													}
													else{
														
														$options = $caseTypeList;
													}
													?>
                                                        <?php echo $this->Form->input('case_type',array('div'=>false,'id'=>'case_type_return','label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>'', 'empty'=>'','required'=>true,'title'=>'please select case Type'));?>
														<?php //echo $this->Form->input('case_type',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$caseTypeList, 'empty'=>'-- Select Case Type --','onchange'=>'showOffence(this.value)','required'=>true,'title'=>'please select case Type'));?>
                                                    </div>
                                                </div> 
                                            </div> 
                                          
                                            <!-- <div class="span6 " id="r_decission_date_div" style="display:none;">
													<div class="control-group">
															<label class="control-label">Decission Date<?php echo $req; ?>:</label>
															<div class="controls">
																<?php echo $this->Form->input('decission_date',array('required'=>true,'div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text','placeholder'=>'Enter Decission date.','disabled','id'=>'r_decission_date','readonly','title'=>'Please select  Decission date'));?>
															</div>
													</div>
											</div>-->

 
                                <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Court Case Status <?php echo $req; ?>:</label>
                                            <div class="controls">
                                            <?php echo $this->Form->input('case_status',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$caseStatusList,'onchange'=>'caseStatusChanged(this.value)','empty'=>'-- Select court case status --','required'=>true));?>
                                            </div>
                                        </div> 
                                    </div>
									<div class="span6" id="r_on_notice_div" >
                                                <div class="control-group">
                                                    <label class="control-label">On Notice<?php echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('on_notice',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox','id'=>'on_notice'));?>
														
													</div>
                                                </div>
                                            </div>

									<div class="span6" id="r_next_session_div" >
                                                <div class="control-group">
                                                    <label class="control-label">Next Session<?php echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('next_session',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'checkbox','id'=>'next_session'));?>
														
													</div>
                                                </div>
                                            </div>   
                                <div class="span6 " id="r_session_date_div" >
                                    <div class="control-group">
                                            <label class="control-label">Next date to court<?php echo $req; ?>:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('session_date',array('required'=>true,'div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text','placeholder'=>'Enter Next date to court.','disabled','id'=>'r_session_date','readonly','title'=>'Please select  Next date to court'));?>
                                            </div>
                                    </div>
                                </div>
								<div class="span6 " id="r_commitment_date_div" style="display:none;">
                                    <div class="control-group">
                                            <label class="control-label">Date of Commitment<?php echo $req; ?>:</label>
                                            <div class="controls">
                                                <?php //echo $this->Form->input('commitment_date',array('div'=>false,'label'=>false,'type'=>'hidden','id'=>'r_commitment_date_hidden'));?>
                                                <?php echo $this->Form->input('commitment_date',array('required'=>true,'value'=>$commit_date,'div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text','placeholder'=>'Enter Date of Commitment.','disabled','id'=>'r_commitment_date','readonly','title'=>'Please select Date of Commitment'));?>
                                            </div>
                                    </div>
                                </div>
								<div class="span12" id="bail_legal" style="display:none;">
											<div class="control-group">
												<label class="control-label"></label>
												<div class="controls uradioBtn">
												<?php
												$to_options= array('1'=>'Bail Legal Requirement Met','2'=>'Bail Legal Requirement Not Yet Met');
												$attributes2 = array(
													'legend' => false, 
													//'onclick'=> 'dissplayApplicationName(this.value)'
												);
												echo $this->Form->radio('bail_legal_status', $to_options, $attributes2);
												?>
													</div>
											</div>
                                     </div> 
									 
									
									
									<div class="span12" id="rulling_option" style="display:none;">
											<div class="control-group">
												<label class="control-label"></label>
												<div class="controls uradioBtn">
												<?php
												$to_options= array('Yes'=>'Case to Answer','No'=>'No case to answer');
												$attributes2 = array(
													'legend' => false,
													'class'	 => 'case_to_answer',												
													'onclick'=> 'dissplayRulling(this.value)'
												);
												echo $this->Form->radio('case_to_answer', $to_options, $attributes2);
												?>
													</div>
											</div>
                                     </div> 
								 <div class="span6" id="r_remarks" style="display:none;">
                                        <div class="control-group">
                                            <label class="control-label">Remarks:</label>
                                            <div class="controls">
                                            <?php echo $this->Form->input('remark',array('onchange'=>'showRemarkStatus(this.value)','id'=>'remark','div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>'','empty'=>'--Select--'));?>
                                            </div>
                                        </div> 
                                    </div>
									 <div class="span6" id="r_pmo_option1" style="display:none;">
                                        <div class="control-group">
                                            <label class="control-label">Unsound Mind:</label>
                                            <div class="controls">
											<?php $pmo_option = array('1'=>'Criminal Lunatic','2'=>'Insane'); ?>
                                            <?php echo $this->Form->input('pmo_remark',array('id'=>'pmo_remark','div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$pmo_option,'empty'=>'-- Select --'));?>
                                            </div>
                                        </div> 
                                    </div>
									
                                
                                <div class="span6 " id="r_conviction_date_div" style="display:none;">
                                    <div class="control-group">
                                            <label class="control-label">Date of Conviction<?php echo $req; ?>:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('conviction_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text','value'=>$conviction_date,'placeholder'=>'Enter Date of Conviction.','disabled','id'=>'r_conviction_date','readonly','title'=>'Please select Date of Conviction'));?>
                                            </div>
                                    </div>
                                </div>
                                <div class="span6 " id="r_aquited_date_div" style="display:none;">
                                    <div class="control-group">
                                            <label class="control-label">Date of Aquited<?php echo $req; ?>:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('aquited_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text','value'=>$aquited_date,'placeholder'=>'Enter Date of Aquited.','disabled','id'=>'r_aquited_date','readonly','title'=>'Please select Date of Aquited'));?>
                                            </div>
                                    </div>
                                </div>
								
								 <div class="span6 " id="r_sentence_date_div" style="display:none;">
                                    <div class="control-group">
                                            <label class="control-label">Date of Sentence<?php echo $req; ?>:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('sentence_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text','placeholder'=>'Enter Date of Senetnce.','disabled','id'=>'r_sentence_date','readonly','title'=>'Please select Date of Sentence'));?>
                                            </div>
                                    </div>
                                </div>
								 <div class="span6" id="sentence_remark" style="display:none;">
									<div class="control-group">
										<label class="control-label"></label>
										<div class="controls uradioBtn">
										
												<?php
												$to_options= array('sentencing_option'=>'Sentencing','awaiting'=>'Awaiting','pmo'=>'PMO');
												$attributes2 = array(
													'legend' => false,
													'class'	 => '',												
													'onclick'=> 'dissplaySentence(this.value)'
												);
												echo $this->Form->radio('sentence_option', $to_options, $attributes2);
												?>
											
										</div>
									</div>
                                </div>
								 <div class="span6" id="r_appeal_status" style="display:none;">
									<div class="control-group">
										<label class="control-label"></label>
										<div class="controls uradioBtn">
										
												<?php
												$to_options= array('Completed'=>'Completed','Ongoing'=>'Ongoing');
												$attributes2 = array(
													'legend' => false,
													'class'	 => '',												
													//'onclick'=> 'dissplayAppealStatus(this.value)'
												);
												echo $this->Form->radio('appeal_status', $to_options, $attributes2);
												?>
											
										</div>
									</div>
                                </div>
                                <!-- <div class="span6">
                                    <div class="control-group">

                                        <label class="control-label">Remarks:</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('remark',array('div'=>false,'label'=>false,'class'=>'form-control  span11','type'=>'textarea','placeholder'=>'Enter Remark.','id'=>'r_remark','title'=>'Please Enter remarks'));?>
                                        </div>
                                    </div>
                                </div> -->
                               
                                
                                                 
                        
                    </div>
                                            
                                            
                                             
                                        <div class="span12 add-top" align="center" valign="center">
                                            <?php
                                                if(isset($this->data["ReturnFromCourt"]) && !empty($this->data["ReturnFromCourt"])){
                                                    echo $this->Form->button('Update', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit', 'formnovalidate'=>true));?>&nbsp;&nbsp;
                                                    <?php
                                                    echo $this->Form->button('Cancel', array('type'=>'button', 'class'=>'btn btn-danger','div'=>false,'label'=>false,'id'=>'btn_cancel', 'formnovalidate'=>true));
                                                }
                                                else{
                                                    echo $this->Form->button('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','onclick'=>'setCommitmentDate()', 'formnovalidate'=>true));
                                                }
                                            ?>
                                        </div> 
                                        <?php echo $this->Form->end();?>
                                    <?php }?>
                                </div>
                            </div>
                            <div class="table-responsive" id="courtReturnDiv"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

 

<?php
$ajaxApplicationtoCourt =  $this->Html->url(array('controller'=>'courtattendances','action'=>'getApplicationToCourt'));
$ajaxJudgeUrl =  $this->Html->url(array('controller'=>'courtattendances','action'=>'getJudgeByCourt'));
$ajaxRemarkUrl =  $this->Html->url(array('controller'=>'courtattendances','action'=>'getRemarkByCaseStatus'));

$courtAjaxUrl   = $this->Html->url(array('controller'=>'courtattendances','action'=>'getCourtByMagisterial'));
$courtByCourtLevelAjaxUrl   = $this->Html->url(array('controller'=>'courtattendances','action'=>'getCourtByCourtLevel'));


$courtlvlAjaxUrl   = $this->Html->url(array('controller'=>'courtattendances','action'=>'getCourtlvl'));
$ajaxUrl        = $this->Html->url(array('controller'=>'courtattendances','action'=>'indexAjax'));
$ajaxCauseUrl        = $this->Html->url(array('controller'=>'courtattendances','action'=>'indexCauseAjax'));
$ajaxCauseDetailsUrl        = $this->Html->url(array('controller'=>'courtattendances','action'=>'getCauseListDetails'));
$ajaxCourtReturnUrl = $this->Html->url(array('controller'=>'courtattendances','action'=>'indexCourtReturnAjax'));
$fetchCaseTypeAjax = $this->Html->url(array('controller'=>'courtattendances','action'=>'fetchCaseTypeAjax'));

$fetchCommitmentDateAjax = $this->Html->url(array('controller'=>'courtattendances','action'=>'fetchCommitmentDateAjax'));

$commonHeaderUrl    = $this->Html->url(array('controller'=>'Prisoners','action'=>'getCommonHeder'));
$ajaxUrloffcount = $this->Html->url(array('controller'=>'courtattendances','action'=>'getCountOffence'));
$ajaxUrlNormalSchedule = $this->Html->url(array('controller'=>'courtattendances','action'=>'getNormalSchedule'));
$ajaxUrlproductionWarrant = $this->Html->url(array('controller'=>'courtattendances','action'=>'getProductionWarrant'));
$ajaxfromCauseListUrl = $this->Html->url(array('controller'=>'courtattendances','action'=>'getfromCauselistdata'));

$magisterial_id="";
$court_id="";
$court_level="";
if(isset($this->data["Courtattendance"])){
    $magisterial_id=$this->data["Courtattendance"]["magisterial_id"];
    $court_id=$this->data["Courtattendance"]["court_id"];
    $court_level=$this->data["Courtattendance"]["court_level"];
}
echo $this->Html->scriptBlock("
    var tab_param = '';
    var tabs;
    jQuery(function($) {
        //$('select').select2();
        showCommonHeader();

        if($('#CourtattendanceId').val()==''){
               $('#magisterial_id').select2('val', '');
                $('#court_id').select2('val', '');
        }
        else{
            $('#magisterial_id').select2('val', '".$magisterial_id."');
                $('#court_id').select2('val', '".$court_id."');
                 $('#court_level').val('".$court_level."');
                
        }
        showProduceToCourtData();
        //causeListData();
        
        //fetchCaseType();
        
        tabs = $('.tabscontent').tabbedContent({loop: true}).data('api');
        // Next and prev actions
        $('.controls a').on('click', function(e) {
            var action = $(this).attr('href').replace('#', ''); 
            //tabs[action]();
            e.preventDefault();
        });
        
        var cururl = window.location.href;
        var urlArr = cururl.split('/');
        var param = '';
        for(var i=0; i<urlArr.length;i++){
            param = urlArr[i];
        }
        if(param != ''){
            var paramArr = param.split('#');
            for(var i=0; i<paramArr.length;i++){
                tab_param    = paramArr[i];
            }
        }
        
        if(tab_param == 'produceToCourt'){
            showNormalSchedule();
        }else if(tab_param == 'produceToCourtDiv'){
            showProduceToCourtData();
        }else if(tab_param == 'returnFromCourt'){
            courtReturnData();
            fetchCaseType();

        }

        //
        $('#btn_cancel').on('click', function(e){
            window.location='".$this->request->referer()."';
        });
        $('#magisterial_id').on('change', function(e){
            var url = '".$courtAjaxUrl."';
            $.post(url, {'magisterial_id':$('#magisterial_id').val()}, function(res){
                $('#court_id').html(res);
                $('#court_id').select2('val', '');
                $('#court_level').val('');
            });
        });
        $('#court_id').on('change', function(e){
            var url = '".$courtlvlAjaxUrl."';
            $.post(url, {'court_id':$('#court_id').val()}, function(res){
                $('#court_level').val(res);
            });
        });

        $('#CauseListMagisterialId').on('change', function(e){
           

            var url = '".$courtByCourtLevelAjaxUrl."';
            $.post(url, {'courtlevel_id':$('#CauseListMagisterialId').val()}, function(res){
                $('#CauseListCourtId').html(res);
                $('#CauseListCourtId').select2('val', '');
                $('#CauseListPresidingJudgeId').html('');
                $('#CauseListPresidingJudgeId').select2('val', '');
            });
        });

        $('#CourtattendanceMagisterialId').on('change', function(e){
            alert('partha');

            var url = '".$courtByCourtLevelAjaxUrl."';
            $.post(url, {'courtlevel_id':$('#CourtattendanceMagisterialId').val()}, function(res){
                $('#CourtattendanceCourtId').html(res);
                $('#CourtattendanceCourtId').select2('val', '');
                $('#presiding_id').html('');
                $('#presiding_id').select2('val', '');
            });
        });
        $('#CauseListCourtId').on('change', function(e){
            var url = '".$ajaxJudgeUrl."';
            $.post(url, {'court_id':$('#CauseListCourtId').val()}, function(res){
                $('#CauseListPresidingJudgeId').html(res);
                $('#CauseListPresidingJudgeId').select2('val', '');
            });
        });
         $('#CourtattendanceCourtId').on('change', function(e){
            var url = '".$ajaxJudgeUrl."';
            $.post(url, {'court_id':$('#CourtattendanceCourtId').val()}, function(res){
                $('#presiding_id').html(res);
                $('#presiding_id').select2('val', '');
            });
        });


        $('#CourtattendanceCauseListId').on('change', function(e){
            var url = '".$ajaxCauseDetailsUrl."';
            $.post(url, {'id':$(this).val()}, function(res){
                $('#causeDetails').html(res);
                showCourtLebel();
            });
        });
    });
    
    function showProduceToCourtData(){
        var url   = '".$ajaxUrl."';
        var uuid  = '".$uuid."';
        // url = url + '/production_warrent_no:'+$('#production_warrent_no').val();
        // url = url + '/magisterial_id:'+$('#magisterial_id').val();
        // url = url + '/court_id:'+$('#court_id').val();
        // url = url + '/case_no:'+$('#case_no').val();
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
            $('#causeListDiv').html(res);
        });           
    }

    function causeListData(auth_type){
        var url   = '".$ajaxCauseUrl."';
        var uuid  = '".$uuid."';
        url = url + '/authority_type:'+auth_type;
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
            $('#produceToCourtDiv').html(res);
        });           
    }
	
	function showNormalSchedule(auth_type){
        var url   = '".$ajaxUrlNormalSchedule."';
        var uuid  = '".$uuid."';
        url = url + '/authority_type:'+auth_type;
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
		
            $('#produceToCourtDiv').html(res);
        });           
    }
	
	function showProductWarrant(auth_type){
        var url   = '".$ajaxUrlproductionWarrant."';
        var uuid  = '".$uuid."';
        url = url + '/authority_type:'+auth_type;
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
		
            $('#produceToCourtDiv').html(res);
        });           
    }
	
    function courtReturnData(){
        var url   = '".$ajaxCourtReturnUrl."';
        var uuid  = '".$uuid."';
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
			$('#courtReturnDiv').show();
            $('#courtReturnDiv').html(res);
        });           
    }
    function setCommitmentDate(){
        //alert($('#r_commitment_date').val());
        //console.log('here');
        if($('#r_commitment_date').val() != ''){
            $('#r_commitment_date_hidden').val($('#r_commitment_date').val());
        }else{
            $('#r_commitment_date_hidden').val('');
        }
        return true;
    }
    //common header
    function showCommonHeader(){ 
        var prisoner_id = ".$prisoner_id.";;
        // console.log(prisoner_id);  
        var uuid        = '".$uuid."';
        var url         = '".$commonHeaderUrl."';
        url = url + '/prisoner_id:'+prisoner_id;
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
           
            if (res) {
                $('#commonheader').html(res);
            }
        }); 
    }

    function showCourtLebel(){
        var url = '".$courtlvlAjaxUrl."';
        $.post(url, {'court_id':$('#court_id').val()}, function(res){
            $('#court_level').val(res);
        });
    }
    function fetchCaseType(){
        var url = '".$fetchCaseTypeAjax."';
        $.post(url, {'uuid':'".$uuid."' }, function(res){
            //console.log(res);
            var list = res.split(',');
            //console.log(list[0]);
            if(res != ''){
                
                $('#s_court_file_number').val(list[0]);
                $('#ReturnFromCourtCaseType').select2('val',list[1]);
                // $('#ReturnFromCourtCaseType').val(list[1]).change();

                showOffence(list[1]);
            }else{
                $('#ReturnFromCourtCaseStatus').attr('disabled','disabled');
            }
        });
    }
    function fetchCommitmentDate(offenceId){
        //alert(offenceId);
        var url = '".$fetchCommitmentDateAjax."';
        //var offenceId = $('#ReturnFromCourtOffenceId').val();
        $.post(url, {'offence_id':offenceId,'uuid':'".$uuid."' }, function(res){
            console.log(res);
            if(res != ''){
               var formattedDate = $.datepicker.formatDate('d-m-yy', new Date(res));
                $('#r_commitment_date').val(formattedDate );
                //$('#r_commitment_date').removeClass('mydate ');
                 //$('#r_commitment_date').datepicker('option', 'disabled', true);
                $('#r_commitment_date').attr('disabled','disabled');

            }else{
                
            }
        });
    }

    function showOffence(value){   
        //alert(value); 
        //fetchCommitmentDate();
        if(value == '2'){
            
            $('#r_session_date').removeAttr('disabled');
            $('#r_commitment_date').attr('disabled','disabled');
            $('#r_conviction_date').removeAttr('disabled');
            $('#r_aquited_date').removeAttr('disabled');

            $('#r_session_date_div').css('display','block');
            $('#r_commitment_date_div').css('display','none');
            $('#r_conviction_date_div').css('display','block');
            $('#r_aquited_date_div').css('display','block');

        }else if(value == '1'){

            $('#r_session_date').attr('disabled','disabled');
            $('#r_commitment_date').attr('disabled','disabled');
            $('#r_conviction_date').attr('disabled','disabled');
            $('#r_aquited_date').attr('disabled','disabled');

            $('#r_session_date_div').css('display','none');
            $('#r_commitment_date_div').css('display','none');
            $('#r_conviction_date_div').css('display','none');
            $('#r_aquited_date_div').css('display','none');
        }
    
    }
   
    

",array('inline'=>false));
?>
<script type="text/javascript">
function ToCourt()
{
	showNormalSchedule();
}
function returnFromCourt(){
	courtReturnData();
}
/* for sentencencing remark */
function dissplaySentence(sentence_option)
{
	if(sentence_option=='sentencing_option')
	{
		$('#r_conviction_date_div').css('display','block');	
		$('#r_conviction_date').removeAttr('disabled');
		$('#r_conviction_date').attr('required','required');
				
		$('#r_sentence_date_div').css('display','block');	
		$('#r_sentence_date').removeAttr('disabled');
		$('#r_sentence_date').attr('required','required');

		$('#r_commitment_date_div').css('display','block');
		$('#r_commitment_date').removeAttr('disabled');	
		
		<?php if(isset($commit_date) && !empty($commit_date) ){?>
			$('#r_commitment_date').val(<?php echo date('d-m-Y',strtotime($commit_date));?>);
			<?php } ?>
		
		<?php if(isset($conviction_date) && !empty($conviction_date) ){?>
			
			$('#r_conviction_date').val(<?php echo date('d-m-Y',strtotime($conviction_date));?>);
		<?php } ?>
	}
	
	if(sentence_option=='awaiting')
	{
		$('#r_sentence_date_div').css('display','none');	
		$('#r_sentence_date').attr('disabled','disabled');
		
		$('#r_conviction_date_div').css('display','block');	
		$('#r_conviction_date').removeAttr('disabled');		
		
		$('#r_session_date_div').css('display','block');
		$('#r_session_date').removeAttr('disabled');
		$('#r_sentence_date').attr('required','required');
		
		<?php if(isset($conviction_date) && !empty($conviction_date) ){?>			
			$('#r_conviction_date').val(<?php echo date('d-m-Y',strtotime($conviction_date));?>);
		<?php } ?>
		
		$('#r_commitment_date_div').css('display','block');
		$('#r_commitment_date').removeAttr('disabled');	
		
		<?php if(isset($commit_date) && !empty($commit_date) ){?>
			$('#r_commitment_date').val(<?php echo date('d-m-Y',strtotime($commit_date));?>);
		<?php } ?>
	}
}
/* for rulling status */
function dissplayRulling(val)
{
	var prisoner_type = "<?php echo $prisoner_type_id ;?>";
	if(val=='Yes')
	{
		$('#r_remarks').css('display','block');
		
		if(prisoner_type==1)
		{
			option = '<option>--Select Remark--</option><option value="9">Defence</option><option value="1">Further remanded</option>';
		}
		if(prisoner_type==2)
		{
			option = '<option>--Select Remark--</option><option value="9">Defence</option><option value="13">Case Adjourned</option>';
		}
		$('#remark').html(option);
		$('#bail_legal').css('display','none');
		$('#r_pmo_option1').hide('display','none');
					   
	}
	if(val=='No')
	{
		if(prisoner_type == 1)
		{
			$('#r_remarks').css('display','block');
			option = '<option>--Select Remark--</option><option value="7">Pending Minister Order</option>';
			$('#remark').html(option);
			$('#bail_legal').css('display','none');
			
			$('#r_session_date_div').css('display','none');
			$('#r_session_date').attr('disabled','disabled');
			$('#r_pmo_option1').hide();	
		}
		if(prisoner_type == 2)
		{
			$('#r_remarks').css('display','none');
		}
				
	}
	
}
/* rulling status end */

 function caseStatusChanged(value){   
        var caseType = $('#case_type_return').val();
		
        //var url = '".$ajaxRemarkUrl."';
            /*$.post(url, {'case_status':value}, function(res){
                $('#ReturnFromCourtRemark').html(res);
                $('#ReturnFromCourtRemark').select2('val', '');
            });*/
		var option = '';
		var prisoner_type = "<?php echo $prisoner_type_id ;?>";
         if(prisoner_type == '1'){
                if(value == 'Mention'){

						//$('#r_session_date').removeAttr('disabled');
						$('#r_commitment_date').attr('disabled','disabled');
						$('#r_commitment_date_div').attr('disabled','disabled');
						
						option = '<option>--Select--</option><option value="1">Further remanded</option><option value="2">Released on Bond</option><option value="3">Granted Bail</option><option value="4">Adjourned to next session</option><option value="5">Case amended</option><option value="6">Noelle Presque</option><option value="7">Pending Minister Order</option>';
						
                      
                         $('#r_conviction_date').attr('disabled','disabled');
                        $('#r_aquited_date').attr('disabled','disabled');

                       // $('#r_session_date_div').css('display','block');
						$('#r_remarks').css('display','block');
						
						
						$('#remark').html(option);
                        $('#r_commitment_date_div').css('display','none');
						$('#r_pmo_option1').css('display','none');
						$('#rulling_option').css('display','none');
						$('#bail_legal').css('display','none');
						$('.checked').addClass('unchecked');
						$('#bail_legal').css('display','none');
						
                        $('#r_conviction_date_div').css('display','none');
                        $('#r_aquited_date_div').css('display','none');
						$('#sentence_remark').css('display','none');
					
						 //$('#r_decission_date_div').css('display','block');


                }else if(value == 'Commitment' ){

                        $('#r_session_date').attr('disabled','disabled');
                        $('#r_commitment_date').removeAttr('disabled');
						
						$('#r_remarks').css('display','block');
						option = '<option>--Select--</option><option value="8">Commitment</option>';
						
						$('#remark').html(option);
                        $('#r_conviction_date').attr('disabled','disabled');
                        $('#r_aquited_date').attr('disabled','disabled');
						$('#bail_legal').css('display','none');
					   $('#r_pmo_option1').css('display','none');
                        $('#r_session_date_div').css('display','none');
					   $('#rulling_option').css('display','none');
						//$('#r_remarks').css('display','none');
                        $('#r_commitment_date_div').css('display','block');
						$('.checked').addClass('unchecked');
						$('#bail_legal').css('display','none');
                        $('#r_conviction_date_div').css('display','none');
                        $('#r_aquited_date_div').css('display','none');
						$('#sentence_remark').css('display','none');

						$('#r_decission_date_div').css('display','block');
						$('#r_decission_date').removeAttr('disabled');	
						
						$('#r_on_notice_div').css('display','none');
						$('#r_next_session_div').css('display','none');

                }else if(value == 'Hearing'){
                        
                        $('#r_session_date').removeAttr('disabled');
						$('#r_session_date').removeAttr('required');
                        $('#r_commitment_date').removeAttr('disabled');
						
						$('#r_remarks').css('display','block');
						option = '<option>--Select Remark--</option><option value="1">Further remanded</option><option value="7">Pending Minister Order</option>';
						$('#remark').html(option);
						
                       $('#r_conviction_date').attr('disabled','disabled');
                         $('#r_aquited_date').attr('disabled','disabled');

                        $('#r_session_date_div').css('display','block');
                        $('#r_commitment_date_div').css('display','block');
						$('#rulling_option').css('display','none');
						$('#r_commitment_date').val(<?php echo (isset($commit_date) && $commit_date != '') ? date('d-m-Y',strtotime($commit_date)) : ''; ?>);
						$('#r_pmo_option1').css('display','none');
						$('#bail_legal').css('display','none');
						$('.checked').addClass('unchecked');
						$('#bail_legal').css('display','none');
                        $('#r_conviction_date_div').css('display','none');
                        $('#r_aquited_date_div').css('display','none');
						$('#sentence_remark').css('display','none');

                }else if(value == 'Ruling'){
                        
                        $('#r_session_date').attr('disabled','disabled');;
						$('#r_commitment_date').attr('disabled','disabled');;
                       
												
                        $('#r_conviction_date').attr('disabled','disabled');
                        $('#r_aquited_date').attr('disabled','disabled');
						
						$('#rulling_option').css('display','block');
                        $('#r_session_date_div').css('display','none');
                        $('#r_commitment_date_div').css('display','block');
						 $('#r_commitment_date').removeAttr('disabled');
						$('#r_commitment_date').val(<?php echo (isset($commit_date) && $commit_date != '') ? date('d-m-Y',strtotime($commit_date)) : ''; ?>);
						$('#r_pmo_option1').css('display','none');
						$('#bail_legal').css('display','none');
						$('#r_remarks').hide();

						$('#bail_legal').css('display','none');
                        $('#r_conviction_date_div').css('display','none');
                        $('#r_aquited_date_div').css('display','none');
						$('#sentence_remark').css('display','none');

                }else if(value == 'Amendment'){
                        $('#r_session_date_div').css('display','block');
                        $('#r_session_date').removeAttr('disabled','disabled');;
						$('#r_commitment_date').attr('disabled','disabled');;
                       
												
                        $('#r_conviction_date').attr('disabled','disabled');
                        $('#r_aquited_date').attr('disabled','disabled');
						
						$('#rulling_option').css('display','none');
                        $('#r_commitment_date_div').css('display','none');
						$('#r_pmo_option1').css('display','none');
						$('#bail_legal').css('display','none');

						$('#r_remarks').css('display','none');
                        $('#r_conviction_date_div').css('display','none');
                        $('#r_aquited_date_div').css('display','none');
						$('#sentence_remark').css('display','none');

                }else if(value == 'Judgement'){
                            
                        $('#r_session_date').removeAttr('disabled');
                        $('#r_commitment_date').removeAttr('disabled');
                        //$('#r_conviction_date').removeAttr('disabled');
                        //$('#r_aquited_date').removeAttr('disabled');

						$('#rulling_option').css('display','none');
                        $('#r_pmo_option1').css('display','none');
						$('#bail_legal').css('display','none');
                        $('#r_session_date_div').css('display','block');
                        $('#r_commitment_date_div').css('display','block');
						$('#r_commitment_date').val(<?php echo (isset($commit_date) && $commit_date != '') ? date('d-m-Y',strtotime($commit_date)) : ''; ?>);
						
						$('#r_remarks').css('display','block');
						option = '<option>--Select Remark--</option><option value="10">Acquitted</option><option value="11">Convicted</option><option value="12">Judgement differed</option>';
						$('#remark').html(option);
                       // $('#r_conviction_date_div').css('display','block');
                        //$('#r_aquited_date_div').css('display','block');
						//$('#r_pmo_option1').css('display','none');
						$('#sentence_remark').css('display','none');
						
                 

                }else if(value == 'Sentencing'){
                         
						$('#sentence_remark').css('display','block');	
                        $('#r_session_date').attr('disabled','disabled');
                        $('#r_commitment_date').attr('disabled','disabled');
                        $('#r_conviction_date').attr('disabled','disabled');
                        $('#r_aquited_date').attr('disabled','disabled');

						$('#r_session_date_div').css('display','none');
                        $('#r_commitment_date_div').css('display','none');                       
					    $('#r_remarks').css('display','none');
                        $('#r_conviction_date_div').css('display','none');
                        $('#r_aquited_date_div').css('display','none');
						$('#rulling_option').css('display','none');
                        $('#r_pmo_option1').css('display','none');
						$('#bail_legal').css('display','none');
						
                       

                }else{
				
						$('#r_session_date_div').css('display','none');
						$('#r_remarks').css('display','block');
						$('#remark').html('');
				
                        $('#r_session_date').attr('disabled','disabled');
                        $('#r_commitment_date').attr('disabled','disabled');
                        $('#r_conviction_date').attr('disabled','disabled');
                        $('#r_aquited_date').attr('disabled','disabled');

						$('#r_pmo_option').css('display','none');
                        $('#r_session_date_div').css('display','none');
                        $('#r_commitment_date_div').css('display','none');
						$('#r_remarks').css('display','none');
						
						
                       // $('#r_conviction_date_div').css('display','none');
                        //$('#r_aquited_date_div').css('display','none');
                }
         }
		 if(prisoner_type == '2')
		 {
			if(value == 'Hearing'){
                        
                        $('#r_session_date').removeAttr('disabled');
						$('#r_session_date').attr('required','required');
                       						
						$('#r_remarks').css('display','block');
						option = '<option>--Select Remark--</option><option value="13">Case Adjourned</option><option value="2">Released on Bond</option><option value="3">Granted Bail</option><option value="4">Adjourned to next session</option><option value="5">Case amended</option>';
						$('#remark').html(option);
						
                       $('#r_conviction_date').attr('disabled','disabled');
                         $('#r_aquited_date').attr('disabled','disabled');

                        $('#r_session_date_div').css('display','block');
                        $('#r_commitment_date_div').css('display','none');
						$('#rulling_option').css('display','none');
						$('#r_commitment_date').attr('disabled','disabled');
						$('#r_pmo_option1').css('display','none');
						$('#bail_legal').css('display','none');
						$('.checked').addClass('unchecked');
						$('#bail_legal').css('display','none');
                        $('#r_conviction_date_div').css('display','none');
                        $('#r_aquited_date_div').css('display','none');
						$('#sentence_remark').css('display','none');
						$('#r_appeal_status').css('display','none');
			}
			if(value == 'Ruling'){
                        
                        $('#rulling_option').css('display','block');
						
                        $('#r_conviction_date').attr('disabled','disabled');
                        $('#r_aquited_date').attr('disabled','disabled');
						$('#r_remarks').css('display','none');
                        $('#r_session_date_div').css('display','none');
						$('#r_session_date').attr('disabled','disabled');
                        $('#r_commitment_date_div').css('display','none');
						$('#r_commitment_date').attr('disabled','disabled');
						$('#r_pmo_option1').css('display','none');
						$('#bail_legal').css('display','none');
						$('.checked').addClass('unchecked');
						$('#bail_legal').css('display','none');
                        $('#r_conviction_date_div').css('display','none');
                        $('#r_aquited_date_div').css('display','none');
						$('#sentence_remark').css('display','none');
						$('#r_appeal_status').css('display','none');
			}
			if(value == 'Judgement'){
                        
                        $('#r_session_date').removeAttr('disabled');
						$('#r_session_date').attr('required','required');
                       						
						$('#r_remarks').css('display','block');
						option = '<option>--Select Remark--</option><option value="10">Acquitted</option><option value="12">Judgement differed</option>';
						$('#remark').html(option);
						
                       $('#r_conviction_date').attr('disabled','disabled');
                        $('#r_aquited_date').attr('disabled','disabled');

                        $('#r_session_date_div').css('display','block');
                        $('#r_commitment_date_div').css('display','none');
						$('#rulling_option').css('display','none');
						$('#r_commitment_date').attr('disabled','disabled');
						$('#r_pmo_option1').css('display','none');
						$('#bail_legal').css('display','none');
						$('.checked').addClass('unchecked');
						$('#bail_legal').css('display','none');
                        $('#r_conviction_date_div').css('display','none');
                        $('#r_aquited_date_div').css('display','none');
						$('#sentence_remark').css('display','none');
						$('#r_appeal_status').css('display','none');
			}
			if(value == 'Appeal Status'){
                        
                        $('#r_session_date').attr('disabled','disabled');
										
						$('#r_appeal_status').css('display','block');
						
                        $('#r_conviction_date').attr('disabled','disabled');
                        $('#r_aquited_date').attr('disabled','disabled');

                        $('#r_session_date_div').css('display','none');
                        $('#r_commitment_date_div').css('display','none');
						$('#rulling_option').css('display','none');
						$('#r_commitment_date').attr('disabled','disabled');
						$('#r_pmo_option1').css('display','none');
						$('#bail_legal').css('display','none');
						$('#bail_legal').css('display','none');
                        $('#r_conviction_date_div').css('display','none');
                        $('#r_aquited_date_div').css('display','none');
						$('#sentence_remark').css('display','none');
			}
			
		 }	 
		 
    
    }

function showRemarkStatus(remark_id)
{
	var prisoner_type = "<?php echo $prisoner_type_id ;?>";
	if(remark_id==3)
	{
		 if(prisoner_type==1)
		 {
			$('#bail_legal').css('display','block');
			$('#r_pmo_option1').css('display','none');
			$('#r_remarks').css('display','none');
		 }
	}
	else if(remark_id==7)
	{
		var Mention = $('#ReturnFromCourtCaseStatus').val();
		$('#r_remarks').css('display','block');
		$('#r_pmo_option1').show();
		
	}
	else if(remark_id==1)
	{
		if($('#ReturnFromCourtCaseStatus').val()=='Ruling' && $('#ReturnFromCourtCaseType').val()==1)
		{
		
			$('#r_session_date_div').css('display','block');
			$('#r_session_date').removeAttr('disabled');
			$('#r_remarks').show();
			<?php if(isset($commit_date) && !empty($commit_date) ){?>
			$('#r_commitment_date_div').css('display','none');
			$('#r_commitment_date').removeAttr('disabled');
			$('#r_commitment_date').val(<?php echo date('d-m-Y',strtotime($commit_date));?>);
			<?php } ?>
			$('#r_pmo_option1').css('display','none');
			
		}	
		
						/*$('#r_session_date_div').css('display','block');
						$('#r_session_date').removeAttr('disabled');
						$('#r_on_notice_div').css('display','block');
						$('#r_next_session_div').css('display','block');
						$('#r_decission_date_div').css('display','block');
						$('#r_decission_date').removeAttr('disabled');*/
		
		
		
	}
	else if(remark_id==9)
	{	
			$('#r_session_date_div').css('display','block');
			$('#r_session_date').removeAttr('disabled');	
	}
	else if(remark_id==10)
	{	
			$('#r_aquited_date_div').css('display','block');
			$('#r_aquited_date').removeAttr('disabled');	
			$('#r_aquited_date').attr('required','required');
						
			//$('#r_session_date_div').css('display','none');	
			//$('#r_commitment_date_div').css('display','none');	
			
			$('#r_pmo_option1').css('display','none');
			
			$('#r_conviction_date_div').css('display','none');	
			$('#r_conviction_date').attr('disabled','disabled');	

			$('#r_session_date').removeAttr('disabled','disabled');
            $('#r_commitment_date').removeAttr('disabled','disabled');
           
	}
	else if(remark_id==11)
	{
			$('#r_conviction_date_div').css('display','block');	
			$('#r_conviction_date').removeAttr('disabled');
			
			
			$('#r_pmo_option1').css('display','none');
			
			$('#r_aquited_date_div').css('display','none');
			$('#r_aquited_date').attr('disabled','disabled');	
			
			$('#r_session_date').removeAttr('disabled');
            $('#r_commitment_date').removeAttr('disabled');
           
	}
	else if(remark_id==12)
	{
		if(prisoner_type==2)
		{
			$('#r_conviction_date_div').css('display','none');	
			$('#r_conviction_date').attr('disabled','disabled');
			
			$('#r_session_date_div').css('display','none');
			$('#r_session_date').attr('disabled','disabled');
			
		
		}		
			
           
	}
	else if(remark_id==13)
	{
		if(prisoner_type==1)
		{
			$('#r_session_date_div').css('display','none');
			$('#r_session_date').attr('disabled','disabled');
		}
	}
	else
	{
		$('#r_pmo_option1').hide();
		$('#bail_legal').css('display','none');
		
	}
}


$('#next_session').click(function(){
	var isChecked = $('#next_session').is(':checked');
	
	if(isChecked)
	{
		$('#r_session_date').attr('disabled','disabled');
	}
	else{
	$('#r_session_date').removeAttr('disabled','disabled');
	}
});

$('#on_notice').click(function(){
	var isChecked = $('#on_notice').is(':checked');
	
	if(isChecked)
	{
		$('#r_session_date').attr('disabled','disabled');
	}else{
		$('#r_session_date').removeAttr('disabled','disabled');
	}
});

function fromCauseListData(id)
{
	var prison_id = $('#prison_id').val();
	var prisoner_id = $('#prisoner_id').val();
	var uuid = $('#uuid').val();
	
	var url = "<?php echo $ajaxfromCauseListUrl;?>";
				url = url + '/prison_id:'+prison_id;
				url = url + '/prisoner_id:'+prisoner_id;
				url = url + '/uuid:'+uuid;
				$.post(url, {}, function(res) {
                        var json = JSON.parse(res);				
						//alert(json['file_no'].split(','));
							$('#common_field').show();
							$('#common_offence').show();
							$('#presiding_judge').hide();
							$('#reason_for_court').show();
							$('#cause_list').hide();
							
							
							
							
							$('.normaltab').removeAttr("required");
							$('.warranttab').attr("required","required");
						   
							$('.offencetab').removeAttr("required");
							$('#crb_no').removeAttr("required");
							$('#presiding_judge_id').removeAttr("required");
							
							$('#court_level').val(json['court_level']).change();
							$('#court_name2').val(json['court_id']).change();
							$('#file_no').val(json['file_no'].split(',')).change();
							$('#court_file_no').val(json['court_file_no']);
							$('#high_court_file_no').val(json['high_court_file_no']);
							$('#offence_no2').val(json['offence'].split(',')).change();
							
				});
}

$('#from_cause_list').click(function(){
	var isChecked = $('#from_cause_list').is(':checked');
	var prison_id = $('#prison_id').val();
	var prisoner_id = $('#prisoner_id').val();
	var uuid = $('#uuid').val();
	
	if(isChecked)
	{
		
		
		$('#from_cause_list_date').show();
         
		
		/*$('#common_field').show();
		$('#common_offence').show();
		$('#presiding_judge').hide();
		//$('#court_reason').hide();
		//$('#reason_for_court').show();
		//$('#offence_tab').hide();
		//$('#crb_level').show();
        $('#cause_list').hide();
		//$('#presiding_judge').show();
		
        
		//$('.commontab').attr('required', 'required');
        $('.normaltab').removeAttr("required");
		$('.warranttab').removeAttr("required");
       // $('.causelisttab').attr('required', 'required');
		$('.offencetab').removeAttr("required");
		$('#crb_no').removeAttr("required");
		$('#presiding_judge_id').removeAttr("required");
		
		var url = "<?php echo $ajaxfromCauseListUrl;?>";
				url = url + '/prison_id:'+prison_id;
				url = url + '/prisoner_id:'+prisoner_id;
				url = url + '/uuid:'+uuid;
				$.post(url, {}, function(res) {		
					 var json = jQuery.parseJSON(res);
					alert(json);
				});  */         
		
		
		//$('#authority_id').val(2).change();	
	}
	else
	{
		$('#from_cause_list_date').hide();
		$('#production_warrant').show();
		$('#common_field').hide();
		$('#offence_tab').hide();
		$('#common_offence').hide();
		
       
		$('#reason_for_court').show();
        $('#cause_list').hide();
		
        $('#normal_sheduled').hide();
		$('.normaltab').removeAttr("required");
		$('.warranttab').attr('required', 'required');
        $('.causelisttab').removeAttr("required"); 
		$('.offencetab').removeAttr('required');
		
		
	}

});

<?php if(isset($this->request->data['Courtattendance']['authority_type']) && $this->request->data['Courtattendance']['authority_type'] != ''){

	if($this->request->data['Courtattendance']['authority_type']==1)
	{
	?>
		$('#common_field').show();
		$('#common_offence').show();
        $('#normal_sheduled').show();
		$('#reason_for_court').show();
		$('#cause_list').hide();
		$('#offence_tab').hide();
		$('#production_warrant').hide();
		$('#presiding_judge').show();
		
        $('.commontab').attr('required', 'required');
		$('.normaltab').attr('required', 'required');
		$('.causelisttab').removeAttr("required");
		$('.warranttab').removeAttr("required");
		$('.offencetab').removeAttr("required");
		
		<?php if($this->request->data['Courtattendance']['high_court_file_no']=='')
		{
		?>
			$('#high_court_file_no').removeAttr("required");
		<?php
		}
		?>
		$('#CourtattendanceFromCauseListDate').removeAttr("required");
		//showNormalSchedule(isdual);
	<?php	
	}
	if($this->request->data['Courtattendance']['authority_type']==2)
	{
	?>
		$('#common_field').show();
		$('#common_offence').show();
		$('#reason_for_court').hide();
		$('#offence_tab').hide();
		$('#crb_level').show();
        $('#cause_list').show();
		$('#presiding_judge').show();
		
        $('#normal_sheduled').hide();
		$('#production_warrant').hide();
		$('.commontab').attr('required', 'required');
        $('.normaltab').removeAttr("required");
		$('.warranttab').removeAttr("required");
        $('.causelisttab').attr('required', 'required');
		$('.offencetab').removeAttr("required");
		<?php if($this->request->data['Courtattendance']['high_court_file_no']=='')
		{
		?>
			$('#high_court_file_no').removeAttr("required");
		<?php
		}
		?>
		$('#CourtattendanceFromCauseListDate').removeAttr("required");
		//causeListData(isdual);
	<?php	
	} 
	if($this->request->data['Courtattendance']['authority_type']==3)
	{?>
	
	$('#from_cause_list_date').hide();
	
	 $('#production_warrant').show();
		$('#reason_for_court').show();
		$('#common_field').show();
		$('#common_offence').show();
		$('#presiding_judge').hide();
		$('#crb_level').hide();
		$('#cause_list').hide();
		
		
		
		
		$('.normaltab').removeAttr("required");
		$('.warranttab').attr("required","required");
	   
		$('.offencetab').removeAttr("required");
		$('#crb_no').removeAttr("required");
		$('#presiding_judge_id').removeAttr("required");
    
		$('#uniform-from_cause_list').find('span').removeClass('checked');
		$('#uniform-from_cause_list').find('span').addClass('unchecked');
		<?php if($this->request->data['Courtattendance']['high_court_file_no']=='')
		{
		?>
			$('#high_court_file_no').removeAttr("required");
		<?php
		}
		?>
		$('#CourtattendanceFromCauseListDate').removeAttr("required");
		//showProductWarrant(isdual);
		
	<?php }

}else {?>

function showAuthority(isdual) 
{
    if(isdual == 1)
    {
		$('#common_field').show();
		$('#common_offence').show();
        $('#normal_sheduled').show();
		$('#reason_for_court').show();
		$('#cause_list').hide();
		$('#offence_tab').hide();
		$('#production_warrant').hide();
		$('#presiding_judge').show();
		
        $('.commontab').attr('required', 'required');
		$('.normaltab').attr('required', 'required');
		$('.causelisttab').removeAttr("required");
		$('.warranttab').removeAttr("required");
		$('.offencetab').removeAttr("required");
		
		//showNormalSchedule(isdual);
    }
    

    if (isdual == 2) 
    {
		$('#common_field').show();
		$('#common_offence').show();
		$('#reason_for_court').hide();
		$('#offence_tab').hide();
		$('#crb_level').show();
        $('#cause_list').show();
		$('#presiding_judge').show();
		
        $('#normal_sheduled').hide();
		$('#production_warrant').hide();
		$('.commontab').attr('required', 'required');
        $('.normaltab').removeAttr("required");
		$('.warranttab').removeAttr("required");
        $('.causelisttab').attr('required', 'required');
		$('.offencetab').removeAttr("required");
		
		//causeListData(isdual);
    }
    

    if (isdual == 3) 
    {
	    $('#production_warrant').show();
		//$('#common_field').show();
		//$('#offence_tab').show();
		$('#common_offence').hide();
		$('#common_field').hide();
		$('#count_div2').hide();
		$('#presiding_judge').hide();
		$('#crb_level').hide();
       
	    $('#crb_no').removeAttr("required");
		$('#presiding_judge_id').removeAttr("required");
		$('#file_no').removeAttr("required");
		$('#offence_no2').removeAttr("required");
		$('#reason_for_court').show();
        $('#cause_list').hide();
		//$('#common_offence').show();
        $('#normal_sheduled').hide();
		$('.normaltab').removeAttr("required");
		$('.warranttab').attr('required', 'required');
        $('.causelisttab').removeAttr("required"); 
		$('.offencetab').attr('required', 'required');	
    
		$('#uniform-from_cause_list').find('span').removeClass('checked');
		$('#uniform-from_cause_list').find('span').addClass('unchecked');
		//showProductWarrant(isdual);
    }

}
<?php } ?>



    $(function(){

	$("#file_no").select2();
	$("#offence_no2").select2();
	$("#offence_count2").select2();
	
    <?php
    if(isset($this->data['CauseList']) && count($this->data['CauseList'])>0){
        ?>
        var url = '<?php echo $courtAjaxUrl; ?> ';
        $.post(url, {'magisterial_id':$('#CauseListMagisterialId').val()}, function(res){
            $('#CauseListCourtId').html(res);
            $('#CauseListCourtId').select2('val', <?php echo $this->data['CauseList']['court_id'] ?>);
            $('#CauseListPresidingJudgeId').html('');
            $('#CauseListPresidingJudgeId').select2('val', '');
            $('#CauseListPresidingJudgeId').val('');
            var url = '<?php echo $ajaxJudgeUrl; ?>';
            $.post(url, {'court_id':$('#CauseListCourtId').val()}, function(res){
                $('#CauseListPresidingJudgeId').html(res);
                $('#CauseListPresidingJudgeId').select2('val', '<?php echo $this->data['CauseList']['presiding_judge_id'] ?>');
                $('#CauseListPresidingJudgeId').val(<?php echo $this->data['CauseList']['presiding_judge_id'] ?>);
            }); 
            });;
        <?php
    }
    ?>

    $.validator.addMethod("loginRegex", function(value, element) {
        return this.optional(element) || /^[a-z0-9\-\,\.\s\/]+$/i.test(value);
    }, "Username must contain only letters, numbers, or dashes.");
     

    $("#CauseListIndexForm").validate({
        ignore: "",
            rules: { 
                 },
            messages: {
            }
    });

    $("#CourtattendanceIndexForm").validate({
     
      ignore: "",
            rules: {  
                'data[Courtattendance][production_warrent_no]': {
                    required: false,
                    loginRegex: false,
                    maxlength: 15
                },
                'data[Courtattendance][offence_id][]': {
                    required: false,
                },
                'data[Courtattendance][attendance_date]': {
                    required: false,
                    // datevalidateformatnew: true,
                },
                'data[Courtattendance][magisterial_id]': {
                    required: false,
                },
                'data[Courtattendance][court_id]': {
                    required: false,
                   
                },
                'data[Courtattendance][case_no]': {
                    required: false,
                    loginRegex: false,
                    maxlength: 15
                },
                
            },
            messages: {
                'data[Courtattendance][production_warrent_no]': {
                    required: "Please enter production warrent no.",
                    loginRegex: "Production warrent no. must contain only letters, numbers, Special Characters ( Comma, Hyphen, Dot, Spaces,Forward slash)",
                    maxlength: "Please enter less than 15 characters.",
                },
                'data[Courtattendance][offence_id][]': {
                    required: "Please choose offence",
                },
                'data[Courtattendance][attendance_date]': {
                    required: "Please select next hearing date",
                    // datevalidateformatnew: "Wrong Date Format"
                },
                'data[Courtattendance][magisterial_id]': {
                    required: "Please select magisterial area",
                },
                'data[Courtattendance][court_id]': {
                    required: "Please choose court",
                    
                },
                'data[Courtattendance][case_no]': {
                    required: "Please enter case No.",
                    loginRegex: "Case No. must contain only letters, numbers, Special Characters ( Comma, Hyphen, Dot, Spaces, Forward slash)",
                    maxlength: "Please enter less than 15 characters.",
                },
               
            }, 
    });

    $("#ReturnFromCourtIndexForm").validate({
     
      ignore: "",
            rules: {  
                'data[ReturnFromCourt][court_file_number]': {
                    required: false,
                    maxlength: 15
                },
                'data[ReturnFromCourt][offence_id]': {
                    required: false,
                },
                
            },
            messages: {
                'data[ReturnFromCourt][court_file_number]': {
                    required: "Please enter court file no.",
                    maxlength: "Please enter less than 15 characters.",
                },
                'data[ReturnFromCourt][offence_id]': {
                    required: "Please select offence.",
                },
               
            }, 
    });

  });

function getReturnFromCourt(offence_id)
{
    var url = '<?php echo $this->Html->url(array('controller'=>'Courtattendance','action'=>'getReturnFromCourt')); ?>';
    var prisoner_id = '<?php echo $prisoner_id;?>';
    $.post(url, {'offence_id':offence_id, 'prisoner_id':prisoner_id}, function(res)
    {
        console.log(res);
        var result = jQuery.parseJSON(res); 
        $('#s2id_CauseListOffenceId').val(result.commitment_date);
        $('#s2id_CauseListOffenceId').val(result.conviction_date);
    });
}

function showCount(id){
    //alert('test1');
  var strURL = '<?php echo $this->Html->url(array('controller'=>'Courtattendances','action'=>'showCount'));?>/'+id;
  $.post(strURL,{},function(data){

        //alert('test');
      if(data) { 


          $('#CauseListOffenceId').html(data);

       
      }
      else
      {
          alert("Error...");  
      }
  });
}
function showCaseTypeReturn(id)
{
alert(id);
	var strURL = '<?php echo $this->Html->url(array('controller'=>'Courtattendances','action'=>'showCaseTypeReturn'));?>/'+id;
   $.post(strURL,{},function(data){
        //alert('test');
      if(data) { 
          $('#case_type_return').html(data);      
      }
      else
      {
          alert("Error...");  
      }
  });
}
/* for show court name onchange */
function showCourtName(id,count)
{
	var strURL = '<?php echo $this->Html->url(array('controller'=>'Courtattendances','action'=>'showCourtName'));?>/'+id;
  $.post(strURL,{},function(data){

       //alert('test');
      if(data) 
	  {
		if(count==1)
		{
			 $('#court_name').html(data);
			 if(id==8)
			 {
				$('#high_court_file').show();
			 }
			else
			{
				$('#high_court_file_no').attr('required',false);
				$('#high_court_file').hide();
			}
				
		}
        if(count==2)
		{
			 $('#court_name2').html(data); 
			if(id==8)
			 {
				$('#high_court_file').show();
			 }
			else
			{
				$('#high_court_file_no').attr('required',false);
				$('#high_court_file').hide();
			}
		}
		if(count==3)
		{
			 $('#court_name3').html(data); 
			 if(id==8)
			 {
				$('#high_court_file').show();
			 }
			else
			{
				$('#high_court_file_no').attr('required',false);
				$('#high_court_file').hide();
			}
		}  	
      }
	  
	if(id == 8)
    {
        $('label[for="0_magistrate_level"]').text("Judges:");
        $('label[for="1_magistrate_level"]').text("Judges:");
    }
    
    if(id == 5)
    {
        $('label[for="0_magistrate_level"]').text("Magistrate:");
        $('label[for="1_magistrate_level"]').text("Magistrate:");
    }
    
    if(id == 6)
    {
        $('label[for="0_magistrate_level"]').text("Magistrate:");
        $('label[for="1_magistrate_level"]').text("Magistrate:");
    }
    
    if(id == 7)
    {
        $('label[for="0_magistrate_level"]').text("Chief Magistrate:");
        $('label[for="1_magistrate_level"]').text("Chief Magistrate:");
    }
    
    if(id == 9)
    {
        $('label[for="0_magistrate_level"]').text("Panel Of Justices:");
       $('label[for="1_magistrate_level"]').text("Panel Of Justices:");
    }
    
    if(id == 10)
    {
        $('label[for="0_magistrate_level"]').text("Panel Of Justices:"); 
        $('label[for="1_magistrate_level"]').text("Panel Of Justices:"); 
    }
   
      
  });
}
/* -- */
function showAppeal(isdual)
{
    if(isdual!='')
    {
        $('#notes_appeal').show();
       // $('#hospital_id').removeAttr("disabled");

        
    }
    else 
    {
        $('#notes_appeal').hide();
        //$('#hospital_id').attr('disabled', 'disabled');
        
    }


    if (isdual == 2) {
        $('#notes_appeal_memorandum').show();
    }else
    {
        $('#notes_appeal_memorandum').hide();
    }


   
    if(isdual == 3)
    {
        $('#notes_appeal_hearing').show();
        
    }
    else 
    {
        $('#notes_appeal_hearing').hide();
        
    }

}



function dissplayApplicationName(value)
{
	if(value==1)
	{
		$("#application_name").val('Application for appeal out of time');
	}
	if(value==2)
	{
		$("#application_name").val('');
	}
} 

/*function ApplicationToCourtSave()
{
	var application_name = $("#application_name").val();
	var prisoner_no = $("#prisoner_no").val();
	var submission_date = $("#submission_date").val();
	var court_level = $("#ApplicationToCourtCourtLevel").val();
	var court_name = $("#court_name").val();
	var case_file_no = $("#case_file_no").val();
	
	var error = '';
	if(application_name != '')
	{
		
	}
	
	var url   = "<?php echo $ajaxUrl ;?>";
    url = url + '/application_name:'+application_name+'/prisoner_no:'+prisoner_no+'/submission_date:'+submission_date+'/court_level:'+court_level+'/court_name:'+court_name+'/case_file_no:'+case_file_no;
        $.post(url, {}, function(res) {
           if(res == 1)
		   {
			   alert('Successfully Updated');
			   //window.location.href = '<?php echo $landingpage;?>#causeList';
			   window.location.reload(); 
		   }
		   else
		   {
			   alert('Some error occured!');
		   }
        });    
}*/

function getOffenceCount(id, cnt)
{
	
    /*var strURL = '<?php echo $this->Html->url(array('controller'=>'prisoners','action'=>'courtList'));?>';
    $.post(strURL,{"courtlevel_id":id},function(data){ 
        
        if(data) { 
            $('#'+cnt+'_court_id').html(data); 
        }
        else
        {
            alert("Error...");  
        }
    }); */
    

    // $(".court").change(function(){
    //     if(this.value == 7){
    //                    $('label[for="field_e6lis62"]').text("aaaa");
    //                 }
    //       if(this.value == 9){
    //            $('label[for="field_e6lis62"]').text("bbbbz");
    //         }
    //         //alert(this.value);
    // });

}

function showOffenceCount(id,count)
{
	var prisoner_case_file_id = '';
	
	$('#file_no :selected').each(function(i, sel){ 
		prisoner_case_file_id +=  $(sel).val()+','; 

	});	
	
	var url   = "<?php echo $ajaxUrloffcount ;?>";
    url = url + '/prisoner_case_file_id:'+prisoner_case_file_id;
        $.post(url, {}, function(res) {
		 var string = res.split("##");
		
          if(string!='')
		  {
				
				
				if(count==2)
				{
					$("#offence_div2").show();
					//$("#count_div2").show();
					$("#offence_no2").select2('destroy'); 
					$("#offence_no2").html(string[0]);
					$("#offence_no2").select2();
					//$("#offence_count2").html(string[1]);
				}			
			  
		   }
		   else
		   {
			   alert('Some error occured!');
			   }
		   
        });    
}
function showOffenceCountReturn(id,count)
{
	var prisoner_case_file_id = '';
	
	
	
	var url   = "<?php echo $ajaxUrloffcount ;?>";
    url = url + '/prisoner_case_file_id:'+id;
        $.post(url, {}, function(res) {
		 var string = res.split("##");
		
          if(string!='')
		  {
			
					$("#return_offence_id").html(string[0]);
		
		   }
		   else
		   {
			   alert('Some error occured!');
			   }
		   
        });    
}

function showgetCourtStatus(id)
{
	if(id==1)
	{
	
		var prisoner_type_id = "<?php echo isset($prisoner_type_id) ? $prisoner_type_id : '';?>";
			if(prisoner_type_id =="1"){
				
				var option = '';
				 option += '<option value="">--Select Remark--</option>';
				 option += '<option value="Mention">Mention</option>';
				 option += '<option value="Commitment">Commitment</option>';
				 option += '<option value="Hearing">Hearing</option>';
				 option += '<option value="Ruling">Ruling</option>';
				 option += '<option value="Defence">Defence</option>';
				 option += '<option value="Amendment">Amendment</option>';
				 option += '<option value="Judgement">Judgement</option>';
				
				$('#ReturnFromCourtCaseStatus').html(option);
					
			} 
	}
	if(id==2)
	{
	
		var prisoner_type_id = "<?php echo isset($prisoner_type_id) ? $prisoner_type_id : '';?>";
			if(prisoner_type_id =="1"){
				
				var option = '';
				option += '<option value="">--Select Remark--</option>';
				 option += '<option value="Mention">Mention</option>';
				 option += '<option value="Commitment">Commitment</option>';
				 option += '<option value="Hearing">Hearing</option>';
				 option += '<option value="Ruling">Ruling</option>';
				 option += '<option value="Defence">Defence</option>';
				 option += '<option value="Amendment">Amendment</option>';
				 option += '<option value="Judgement">Judgement</option>';
				$('#ReturnFromCourtCaseStatus').html(option);				
			} 
	}
}
</script> 