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
                            <li><a href="#produceToCourt" id="produceToCourtBtn">To Court</a></li>
                            <li><a href="#returnFromCourt" id="returnFromCourt">From Court</a></li>
                        </ul>
                        <div class="tabscontent">                            
                            <div id="causeList" align="center">
                                <div class="">
                                    <?php if($isAccess == 1){?>
                                        <?php echo $this->Form->create('CauseList',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                        <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                                        <?php echo $this->Form->input('uuid', array('type'=>'hidden'))?>
                                        <div class="row" style="padding-bottom: 14px;text-align: left;">
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Date of Cause List :</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('date_of_cause_list',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text','placeholder'=>'Enter Date of cause list.','required'=>false,'id'=>'date_of_cause_list','readonly','title'=>'Please select  appleal to court'));?>

                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Court file Number.<?php echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->textarea('high_court_case_no',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumeric nospace','type'=>'text','placeholder'=>'Enter Court file Number','required'=>true,'id'=>'high_court_case_no','title'=>'Please provide high court case no'));?>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Next date to court<?php echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('next_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text','placeholder'=>'Enter Next date to court.','required'=>true,'id'=>'next_date','readonly','title'=>'Please select  Next date to court'));?>
                                                    </div>
                                                </div>
                                            </div>
                                           
                                            
                                             <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Case File No <?php echo $req; ?>:</label>
                                                        <div class="controls">
                                                                <?php 
                                                                echo $this->Form->input('case_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','onChange'=>'showCount(this.value)','type'=>'select','options'=>$case_file_no, 'empty'=>'-- Select Case File No --','id'=>'case_id','required', 'title'=>'Case File is required.')); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                         
                                                    <div class="control-group, span6">
                                                       <label class="control-label">Count<?php echo $req; ?> :</label>
                                                        <div class="controls">
                                                            <?php 
                                                            if(isset($this->data['Prisoner']['prisoner_type_id']) && $this->data['Prisoner']['prisoner_type_id'] == Configure::read('REMAND'))
                                                            {
                                                                echo $this->Form->input('offence_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','onChange'=>'getReturnFromCourt(this.value)','type'=>'select','options'=>$offenceIdList, 'empty'=>'-- Select Offence --','required'));
                                                            }
                                                            else 
                                                            {
                                                                echo $this->Form->input('offence_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$offenceIdList, 'empty'=>'-- Select Offence --','required'));
                                                            }
                                                            ?>
                                                        </div>
                                                </div>
                                                            <div class="span6">
                                                                <div class="control-group">
                                                                    <label class="control-label">Appeal Date<?php echo $req; ?>:</label>
                                                                    <div class="controls">
                                                                        <?php echo $this->Form->input('appeal_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text','placeholder'=>'Enter Appeal date to court.','required'=>false,'id'=>'session_date','readonly','title'=>'Please select  appleal to court'));?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Type Of Appliant<?php echo $req; ?>:</label>
                                                        <div class="controls uradioBtn" >
                                                                <?php 
                                                              

                                                                        $applicant_type = "No";
                                                                        if(isset($this->data['CauseList']['applicant_type']))
                                                                            $applicant_type = $this->data['CauseList']['applicant_type'];
                                                                        $options2= $mentalcaseList;
                                                                        $attributes2 = array(
                                                                            'legend' => false, 
                                                                            'value' => $applicant_type,
                                                                        );
                                                                        echo $this->Form->radio('applicant_type', $options2, $attributes2);
                                                
                                                              ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                 <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Appeal Status<?php echo $req; ?>:</label>
                                                        <div class="controls">
                                                                <?php 
                                                                $appeal = array('1' => 'Notes of appeal', '2' => ' Memorandum of Appeal ', '3' => 'Pending Hearing of Appeal', '4' =>'Cause List ' );
                                                                 echo $this->Form->input('appeal_status',array('div'=>false,'label'=>false,'class'=>'form-control span11','onChange'=>'showAppeal(this.value)','type'=>'select','options'=>$appeal, 'empty'=>'-- Select Case File No --','id'=>'appeal_status', 'title'=>'Appeal Status Is required.')); 

                                                                 ?>
                                                        </div>
                                                    </div>
                                                </div>
 

                                            
                                        </div>
                                        <!-- start notes of appeal  -->
                                        <div class="row" style="padding-bottom: 14px;text-align: left; display: none;" id="notes_appeal">
                                            
                                           <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Court Level<?php //echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php
                                                           echo $this->Form->input('magisterial_id',array(
                                                              'div'=>false,
                                                              'label'=>false,
                                                              'type'=>'select',
                                                              'options'=>$magisterialList, 'empty'=>'-- Select Court level --',
                                                              'required'=>false,'title'=>"Please select court level area","title"=>"Please select court level"
                                                            ));
                                                         ?>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Court <?php echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('court_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$courtList, 'empty'=>'-- Select Court --','required'=>false,'title'=>'please select court name'));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Presiding judicial officer :</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('presiding_judge_id',array('type'=>'select', 'div'=>false,'label'=>false,'class'=>'form-control','options'=>array(), 'empty'=>'--Select court Judge--'));?>
                                                    </div>
                                                </div>
                                            </div> 
                                              <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Date Of Submission<?php echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('session_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text','placeholder'=>'Enter Next date to court.','required'=>false,'id'=>'session_date','readonly','title'=>'Please select  Next date to court'));?>
                                                    </div>
                                                </div>
                                            </div>



                                        </div>
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

                                        <!-- starts pending hearing of appeal -->
                                        <div class="row" style="padding-bottom: 14px;text-align: left; display: none;" id="notes_appeal_hearing">
                                            
                                           <!--  <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Court Level<?php //echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php
                                                            //echo $this->Form->input('magisterial_id',array(
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
                                          
                                        
                                               <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Appeal No .<?php echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->textarea('appeal_text',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumeric nospace','type'=>'text','placeholder'=>'Enter Appeal No','required'=>false,'id'=>'appeal_text','title'=>'Enter Appeal No'));?>
                                                    </div>
                                                </div>
                                            </div> 



                                        </div>


                                        <!-- ends pending hearing of appeal -->

                                        
                                                           
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
                                <div class="table-responsive" id="causeListDiv"></div>
                            </div>

                            <div id="produceToCourt">
                                <div class="">
                                    <?php if($isAccess == 1){?>
                                        <?php echo $this->Form->create('Courtattendance',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                        <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                                        <?php echo $this->Form->input('uuid', array('type'=>'hidden'))?>
                                        <div class="" style="padding-bottom: 14px;">                                            
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Production Warrent Case Number<?php echo $req; ?> :</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('production_warrent_no',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Production Warrent No','id'=>'production_warrent_no'));?>
                                                    </div>
                                                </div>
                                            </div>
                                           
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

                                                        echo $this->Form->input('authority_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Cause List --','options'=>$authority,'onchange' => 'showAuthority(this.value)', 'class'=>'form-control','required','id'=>'authority_id','title'=>'Please select cause list'));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <span id="causeDetails">
                                            
                                            </span>
                                        </div>
                                        <!-- starts normal sheduled -->

                                         <div class="" style="padding-bottom: 14px; display: none;" id="normal_sheduled">

                                             <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Date For Court<?php echo $req; ?>:</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('court_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text','placeholder'=>'Enter Appeal date to court.','id'=>'court_date','readonly','title'=>'Please select  appleal to court'));?>
                                                        </div>
                                                    </div>
                                                </div>    
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">Reason:</label>
                                                            <div class="controls">
                                                                <?php echo $this->Form->textarea('reason_text',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Reason','required'=>false,'id'=>'reason_text','title'=>'Please provide the Date of Cause List'));?>
                                                            </div>
                                                        </div>
                                                    </div>                       
                                            
                                        </div>
                                        <!-- ends noramal sheduled -->

                                        <!-- starts cause list -->
                                         <div class="" style="padding-bottom: 14px; display: none;" id="cause_list">


                                                         <div class="span6">
                                                                <div class="control-group">
                                                                    <label class="control-label">Date Of Cause List<?php echo $req; ?>:</label>
                                                                    <div class="controls">
                                                                        <?php echo $this->Form->input('cause_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text','placeholder'=>'Enter Appeal date to court.','required'=>false,'id'=>'cause_date','readonly','title'=>'Please select  appleal to court'));?>
                                                                    </div>
                                                                </div>
                                                            </div>
 
                                                        <!-- <div class="span6">
                                                         <div class="control-group">
                                                            <label class="control-label">Court Level<?php //echo $req; ?>:</label>
                                                                <div class="controls">
                                                                            <?php
                                                                                //echo $this->Form->input('magisterial_id',array(
                                                                                  //  'div'=>false,
                                                                                  // 'label'=>false,
                                                                                  // 'type'=>'select',
                                                                                  // 'options'=>$magisterialList, 'empty'=>'-- Select Court level --',
                                                                                  
                                                                                 // ));
                                                                             ?>
                                                                     </div>
                                                                 </div>
                                                            </div>     -->
                                                                                          
                                                  <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">Court Name<?php echo $req; ?>:</label>
                                                            <div class="controls">
                                                                <?php echo $this->Form->input('court_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$courtList, 'empty'=>'-- Select Court --',''=>false,'title'=>'please select court name'));?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                        <div class="span6">
                                                                <div class="control-group">
                                                                    <label class="control-label">Session  Commence Date<?php echo $req; ?>:</label>
                                                                    <div class="controls">
                                                                        <?php echo $this->Form->input('commence_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text','placeholder'=>'Enter Appeal date to court.','required'=>false,'id'=>'commence_date','readonly','title'=>'Please select  appleal to court'));?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                        <div class="span6">
                                             <div class="control-group">
                                                <label class="control-label">Court file Number.<?php echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->textfields('case_file',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumeric nospace','type'=>'text','placeholder'=>'Enter Court file Number','required'=>false,'id'=>'case_file','title'=>'Please provide high court case no'));?>
                                                    </div>
                                                </div>
                                            </div> 
                                        <div class="span6">
                                             <div class="control-group">
                                                <label class="control-label">High Court File No.<?php echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->textfields('high_court_file',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumeric nospace','type'=>'text','placeholder'=>'Enter Court file Number','required'=>false,'id'=>'high_court_file','title'=>'Please provide high court case no'));?>
                                                    </div>
                                                </div>
                                            </div> 


                                         <div class="span6">
                                             <div class="control-group">
                                                <label class="control-label">CRB No.<?php echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->textfields('crb_text',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumeric nospace','type'=>'text','placeholder'=>'Enter Court file Number','required'=>false,'id'=>'crb_text','title'=>'Please provide CRB No'));?>
                                                    </div>
                                                </div>
                                            </div> 


                                          <div class="span6">
                                             <div class="control-group">
                                                <label class="control-label">Session No.<?php echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->textfields('session_text',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumeric nospace','type'=>'text','placeholder'=>'Enter Court file Number','required'=>false,'id'=>'session_text','title'=>'Please provide Session No'));?>
                                                    </div>
                                                </div>
                                            </div> 

                                            <div class="span6">
                                             <div class="control-group">
                                                <label class="control-label">Appeal No.<?php echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->textfields('appeal_text',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumeric nospace','type'=>'text','placeholder'=>'Enter Court file Number','required'=>false,'id'=>'appeal_text','title'=>'Please provide Appeal No'));?>
                                                    </div>
                                                </div>
                                            </div>


                                          <div class="span6">
                                               <div class="control-group">
                                                    <label class="control-label">Offence<?php echo $req; ?> :</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('offence_no',array('div'=>false,'label'=>false,'type'=>'select','options'=>$offenceList, 'class'=>'form-control', 'id'=>'offence_no','multiple' => 'multiple'));?>
                                                    </div>
                                                </div>
                                            </div>  
                                            
                                            <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Case File No <?php echo $req; ?>:</label>
                                                        <div class="controls">
                                                                <?php 
                                                                echo $this->Form->input('case_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','onChange'=>'showCount(this.value)','type'=>'select','options'=>$case_file_no, 'empty'=>'-- Select Case File No --','id'=>'case_id','title'=>'Case File is required.')); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                         
                                                    <div class="control-group, span6">
                                                       <label class="control-label">Count<?php echo $req; ?> :</label>
                                                        <div class="controls">
                                                            <?php 
                                                            if(isset($this->data['Prisoner']['prisoner_type_id']) && $this->data['Prisoner']['prisoner_type_id'] == Configure::read('REMAND'))
                                                            {
                                                                echo $this->Form->input('offence_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','onChange'=>'getReturnFromCourt(this.value)','type'=>'select','options'=>$offenceIdList, 'empty'=>'-- Select Offence --',));
                                                            }
                                                            else 
                                                            {
                                                                echo $this->Form->input('offence_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$offenceIdList, 'empty'=>'-- Select Offence --',));
                                                            }
                                                            ?>
                                                        </div>
                                                </div>  


                                             <div class="span6 authority">
                                                <div class="control-group">
                                                    <label class="control-label">Court Level<?php //echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php
                                                            echo $this->Form->input('magisterial_id',array(
                                                              'div'=>false,
                                                              'label'=>false,
                                                              'type'=>'select',
                                                              'options'=>$magisterialList, 'empty'=>'-- Select Court level --',
                                                              'title'=>"Please select court level area","title"=>"Please select court level"
                                                             ));
                                                         ?>
                                                    </div>
                                                </div>
                                            </div> 
                                           
                                            <div class="span6 authority">
                                                <div class="control-group">
                                                    <label class="control-label">Presiding judicial officer <?php //echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('presiding_id',array('type'=>'select', 'div'=>false,'label'=>false,'class'=>'form-control','id'=>'presiding_id','options'=>array(), 'empty'=>'--Select court Judge--',"title"=>"Please select court Judge"));?>
                                                    </div>
                                                </div>
                                            </div>   

                                            

                                            
   





                                        </div>



                                        <!-- ends cause list -->
                                        <!-- starts production warrant -->



                                         <div class="" style="padding-bottom: 14px; display: none;" id="production_warrant">



                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Production warrant date<?php echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('production_warrent_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text','placeholder'=>'Enter Production warrant date.','id'=>'production_warrent_date','readonly','title'=>'Please select production_warrent_date'));?>
                                                    </div>
                                                </div>
                                            </div>

                                            
 
                                            
                                                     <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label">Case File No <?php echo $req; ?>:</label>
                                                        <div class="controls">
                                                                <?php 
                                                                echo $this->Form->input('case_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','onChange'=>'showCount(this.value)','type'=>'select','options'=>$case_file_no, 'empty'=>'-- Select Case File No --','id'=>'case_id', 'title'=>'Case File is required.')); ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                   <div class="span6">
                                             <div class="control-group">
                                                <label class="control-label">High Court File No.<?php echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->textarea('high_court_file',array('div'=>false,'label'=>false,'class'=>'form-control span11 alphanumeric nospace','type'=>'text','placeholder'=>'Enter Court file Number','required'=>false,'id'=>'high_court_file','title'=>'Please provide high court case no'));?>
                                                    </div>
                                                </div>
                                            </div> 
                                            

                                                                   <div class="span6 " id="r_session_date_div" >
                                                        <div class="control-group">
                                                                <label class="control-label">Next date to court<?php echo $req; ?>:</label>
                                                                <div class="controls">
                                                                    <?php echo $this->Form->input('next_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text','placeholder'=>'Enter Next date to court.','disabled','id'=>'next_date','readonly','title'=>'Please select  Next date to court'));?>
                                                                </div>
                                                            </div>
                                                         </div>


                                                     <div class="span6">
                                                               <div class="control-group">
                                                                    <label class="control-label">Offence<?php echo $req; ?> :</label>
                                                                    <div class="controls">
                                                                        <?php echo $this->Form->input('offence_id',array('div'=>false,'label'=>false,'type'=>'select','options'=>$offenceList, 'class'=>'form-control', 'id'=>'offence_id','multiple' => 'multiple'));?>
                                                                    </div>
                                                                </div>
                                                      </div>  

                                                       <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">Reason:</label>
                                                            <div class="controls">
                                                                <?php echo $this->Form->textarea('reason_text',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter Reason','required'=>false,'id'=>'reason_text','title'=>'Please provide the Date of Cause List'));?>
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
                            <div id="returnFromCourt" align="center">
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
                                                        <?php echo $this->Form->input('court_file_number',array('div'=>false,'label'=>false,'class'=>'form-control  span11','type'=>'text','placeholder'=>'Enter Court file number.','required'=>true,'id'=>'s_court_file_number','title'=>'Please select court file number'));?>
                                                    </div>
                                                </div>
                                                
                                            </div> 
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">Case Type <?php echo $req; ?>:</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('case_type',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','readonly'=>'readonly','options'=>$caseTypeList, 'empty'=>'-- Select Case Type --','onchange'=>'showAppeal(this.value)','required'=>true,'title'=>'please select case Type'));?>
                                                    </div>
                                                </div> 
                                            </div> 
                                            <!-- 'onchange'=>'fetchCaseType(this.value)' -->
                                            <div class="span6">
                                                <div class="control-group">
                                                        <label class="control-label">Offence List <?php echo $req; ?>:</label>
                                                        <div class="controls">
                                                        <?php echo $this->Form->input('offence_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','onchange'=>'fetchCommitmentDate(this.value)','options'=>$offenceList, 'empty'=>'-- Select Offence--','title'=>'please select offence '));?>
                                                        </div>
                                                </div> 
                                            </div>
                                            

                                                
                                

                                <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Court Case Status <?php echo $req; ?>:</label>
                                            <div class="controls">
                                            <?php echo $this->Form->input('case_status',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>$caseStatusList,'onchange'=>'caseStatusChanged(this.value)','empty'=>'-- Select court case status --','required'=>true,'title'=>'please select case Status'));?>
                                            </div>
                                        </div> 
                                    </div>
                                
                                <div class="span6 " id="r_session_date_div" >
                                    <div class="control-group">
                                            <label class="control-label">Next date to court<?php echo $req; ?>:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('session_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text','placeholder'=>'Enter Next date to court.','disabled','id'=>'r_session_date','readonly','title'=>'Please select  Next date to court'));?>
                                            </div>
                                    </div>
                                </div>
                                <div class="span6 " id="r_commitment_date_div">
                                    <div class="control-group">
                                            <label class="control-label">Date of Commitment<?php echo $req; ?>:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('commitment_date',array('div'=>false,'label'=>false,'type'=>'hidden','id'=>'r_commitment_date_hidden'));?>
                                                <?php echo $this->Form->input('commitment_date_view',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text','placeholder'=>'Enter Date of Commitment.','disabled','id'=>'r_commitment_date','readonly','title'=>'Please select Date of Commitment'));?>
                                            </div>
                                    </div>
                                </div>
                                <div class="span6 " id="r_conviction_date_div">
                                    <div class="control-group">
                                            <label class="control-label">Date of Conviction<?php echo $req; ?>:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('conviction_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text','placeholder'=>'Enter Date of Conviction.','disabled','id'=>'r_conviction_date','readonly','title'=>'Please select Date of Conviction'));?>
                                            </div>
                                    </div>
                                </div>
                                <div class="span6 " id="r_aquited_date_div">
                                    <div class="control-group">
                                            <label class="control-label">Date of Aquited<?php echo $req; ?>:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('aquited_date',array('div'=>false,'label'=>false,'class'=>'form-control mydate span11','type'=>'text','placeholder'=>'Enter Date of Aquited.','disabled','id'=>'r_aquited_date','readonly','title'=>'Please select Date of Aquited'));?>
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
                                <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Remarks:</label>
                                            <div class="controls">
                                            <?php echo $this->Form->input('remark',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'select','options'=>array(),'empty'=>'-- Select remark --'));?>
                                            </div>
                                        </div> 
                                    </div>
                                
                                                 
                        
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
        $('select').select2();
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
        causeListData();
        courtReturnData();
        fetchCaseType();
        
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
        
        if(tab_param == 'causeList'){
            causeListData();
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
            $('#produceToCourtDiv').html(res);
        });           
    }

    function causeListData(){
        var url   = '".$ajaxCauseUrl."';
        var uuid  = '".$uuid."';
        url = url + '/production_warrent_no:'+$('#production_warrent_no').val();
        url = url + '/magisterial_id:'+$('#magisterial_id').val();
        url = url + '/court_id:'+$('#court_id').val();
        url = url + '/case_no:'+$('#case_no').val();
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
            $('#causeListDiv').html(res);
        });           
    }
    function courtReturnData(){
        var url   = '".$ajaxCourtReturnUrl."';
        var uuid  = '".$uuid."';
        url = url + '/uuid:'+uuid;
        $.post(url, {}, function(res) {
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
    function caseStatusChanged(value){   
        var caseType = $('#ReturnFromCourtCaseType').val();
        var url = '".$ajaxRemarkUrl."';
            $.post(url, {'case_status':value}, function(res){
                $('#ReturnFromCourtRemark').html(res);
                $('#ReturnFromCourtRemark').select2('val', '');
            });

         if(caseType == '1'){
                if(value == 'Mention'){

                        $('#r_session_date').removeAttr('disabled');
                        $('#r_commitment_date').attr('disabled','disabled');
                        $('#r_conviction_date').attr('disabled','disabled');
                        $('#r_aquited_date').attr('disabled','disabled');

                        $('#r_session_date_div').css('display','block');
                        $('#r_commitment_date_div_div').css('display','none');
                        $('#r_conviction_date_div').css('display','none');
                        $('#r_aquited_date_div').css('display','none');



                }else if(value == 'Commitment' ){

                        $('#r_session_date').attr('disabled','disabled');
                        $('#r_commitment_date').removeAttr('disabled');
                        $('#r_conviction_date').attr('disabled','disabled');
                        $('#r_aquited_date').attr('disabled','disabled');

                        $('#r_session_date_div').css('display','none');
                        $('#r_commitment_date_div').css('display','block');
                        $('#r_conviction_date_div').css('display','none');
                        $('#r_aquited_date_div').css('display','none');



                }else if(value == 'Hearing' || value == 'Ruling' || value == 'Defence'){
                        
                        $('#r_session_date').removeAttr('disabled');
                        $('#r_commitment_date').removeAttr('disabled');
                        $('#r_conviction_date').attr('disabled','disabled');
                        $('#r_aquited_date').attr('disabled','disabled');

                        $('#r_session_date_div').css('display','block');
                        $('#r_commitment_date_div').css('display','block');
                        $('#r_conviction_date_div').css('display','none');
                        $('#r_aquited_date_div').css('display','none');


                }else if(value == 'Judgement' || value == 'Sentence'){
                            
                        $('#r_session_date').removeAttr('disabled');
                        $('#r_commitment_date').removeAttr('disabled');
                        $('#r_conviction_date').removeAttr('disabled');
                        $('#r_aquited_date').removeAttr('disabled');

                        $('#r_session_date_div').css('display','block');
                        $('#r_commitment_date_div').css('display','block');
                        $('#r_conviction_date_div').css('display','block');
                        $('#r_aquited_date_div').css('display','block');
                       

                }else{
                        $('#r_session_date').attr('disabled','disabled');
                        $('#r_commitment_date').attr('disabled','disabled');
                        $('#r_conviction_date').attr('disabled','disabled');
                        $('#r_aquited_date').attr('disabled','disabled');

                        $('#r_session_date_div').css('display','none');
                        $('#r_commitment_date_div').css('display','none');
                        $('#r_conviction_date_div').css('display','none');
                        $('#r_aquited_date_div').css('display','none');
                }
         }else{
                
            
            $('#r_session_date').removeAttr('disabled');
            $('#r_commitment_date').attr('disabled','disabled');
            $('#r_conviction_date').removeAttr('disabled');
            $('#r_aquited_date').removeAttr('disabled');

            $('#r_session_date_div').css('display','block');
            $('#r_commitment_date_div').css('display','none');
            $('#r_conviction_date_div').css('display','block');
            $('#r_aquited_date_div').css('display','block');
         }
        if($('#r_commitment_date').val() != ''){
            $('#r_commitment_date').attr('disabled','disabled');
        }
    
    }
    

",array('inline'=>false));
?>
<script type="text/javascript">
    $(function(){

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

function showAuthority(isdual) 
{
     if(isdual!='')
    {
        $('#normal_sheduled').show();
        $('#notes_appeal').show();
        $('#s2id_offence_no').removeAttr("required");
        $('#case_id').removeAttr("required");
        $('#s2id_CourtattendanceOffenceId').removeAttr("required");
        $('#presiding_judge_id').removeAttr("required");
        $('#s2id_case_id').removeAttr("required");
        
       

        
    }
    else 
    {
        $('#normal_sheduled').hide();
        $('#notes_appeal').hide();
        $('#s2id_offence_no').attr('required', 'required');
        $('#case_id').attr('required', 'required');
        $('#s2id_CourtattendanceOffenceId').attr('required', 'required');
        $('#presiding_judge_id').attr('required', 'required');
        $('#s2id_case_id').attr('required', 'required');
       
        
         
      
        
    }

    if (isdual == 2) 
    {
        $('#cause_list').show();
        $('.authority').show();
        $('#s2id_offence_no').removeAttr("required");
        $('#case_id').removeAttr("required");
        $('#s2id_CourtattendanceOffenceId').removeAttr("required");
        $('#presiding_judge_id').removeAttr("required");
        $('#s2id_case_id').removeAttr("required");
    }
    else
    {
        $('#cause_list').hide();
        $('.authority').hide();
        $('#s2id_offence_no').attr('required', 'required');
        $('#case_id').attr('required', 'required');
        $('#s2id_CourtattendanceOffenceId').attr('required', 'required');
        $('#presiding_judge_id').attr('required', 'required');
        $('#s2id_case_id').attr('required', 'required');
    }

    if (isdual == 3) 
    {
        $('#production_warrant').show();
         $('.authority').show();
        $('#s2id_offence_no').removeAttr("required");
        $('#case_id').removeAttr("required");
        $('#s2id_CourtattendanceOffenceId').removeAttr("required");
        $('#presiding_judge_id').removeAttr("required");
        $('#s2id_case_id').removeAttr("required");
    }
    else 
    {
        $('#production_warrant').hide();
        $('.authority').hide();
        $('#s2id_offence_no').attr('required', 'required');
        $('#case_id').attr('required', 'required');
        $('#s2id_CourtattendanceOffenceId').attr('required', 'required');
        $('#presiding_judge_id').attr('required', 'required');
        $('#s2id_case_id').attr('required', 'required');
    }

} 
</script> 