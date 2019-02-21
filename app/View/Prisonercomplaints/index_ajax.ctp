<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
#forwardBtn
{
  display: none;
}
.controls.uradioBtn .radio {
    padding-right: 5px;
    padding-top: 5px;
}
.checked {
    color: orange;
}
div.stars {
  width: 195px;
  /*display: inline-block;*/
}

input.star { display: none; }

label.star {
  float: right;
  padding: 10px;
  font-size: 20px;
  color: #444;
  transition: all .2s;
}

input.star:checked ~ label.star:before {
  content: '\f005';
  color: #FD4;
  transition: all .25s;
}

input.star-5:checked ~ label.star:before {
  color: #FE7;
  text-shadow: 0 0 20px #952;
}

input.star-1:checked ~ label.star:before { color: #F62; }

label.star:hover { transform: rotate(-15deg) scale(1.3); }

label.star:before {
  content: '\f006';
  font-family: FontAwesome;
}
</style>

<?php
if(is_array($datas) && count($datas)>0){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        //'before'                  => '$("#lodding_image").show();',
        //'complete'                => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'Prisonercomplaints',
            'action'                => 'indexAjax',
            'from'             => $from,
            'to'             => $to,       
            'status'             => $status,       
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Js->writeBuffer();
?>
        </ul>
    </div>
     <div class="col-sm-7 text-right">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
    <?php
    
      $exUrl = "indexAjax/from:$from/to:$to";
      $urlExcel = $exUrl.'/reqType:XLS';
      $urlDoc = $exUrl.'/reqType:DOC';
      $urlPDF = $exUrl.'/reqType:PDF';
   $urlPrint = $exUrl.'/reqType:PRINT';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download PDF")),$urlPDF, array("escape" => false)));
  echo '&nbsp;&nbsp;';
  echo($this->Html->link($this->Html->image("print.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlPrint, array("escape" => false,'target'=>"_blank")));
    ?>
    </div>
</div>
<?php 
$btnName = Configure::read('SAVE');
$isModal = 0;
if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
{
  $btnName = Configure::read('SAVE');
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
{
  $btnName = Configure::read('REVIEW');
  $isModal = 1;
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
{
  $btnName = Configure::read('APPROVE');
  $isModal = 1;
}
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/Prisonercomplaints/add'));?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php }?>
<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="forwardBtn" class="btn btn-success btn-mini"><?php echo $btnName;?></button>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>
    <?php
        if(!isset($is_excel)){
          ?>
        <!-- <th>
            <?php 
            // echo $this->Form->input('checkAll', array(
            //       'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'checkAll',
            //       'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
            // ));
            ?>
          </th> -->
          <?php
        }
          ?>
      <th><?php echo 'Sl no'; ?></th>                
      <th><?php echo 'Date'; ?></th>
      <th><?php echo 'Time'; ?></th>
      
      <th><?php echo 'Prisoner No'; ?></th>
      <th><?php echo 'Priority'; ?></th>
      
      <th><?php echo 'Complaint'; ?></th>

      <th><?php echo 'Response'; ?></th>
      <th><?php echo 'Action Taken'; ?></th>
      
      <?php
      if($this->Session->read('Auth.User.usertype_id')!=Configure::read('PRINCIPALOFFICER_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('OFFICERINCHARGE_USERTYPE')){
      ?>
      <th width="8%">Action</th>
      <?php
      }
      ?>
    </tr>
  </thead>
<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
  $display_status = Configure::read($data['Prisonercomplaint']['status']);
?>
    <tr>
        <?php
        /*
        ?>
          <td>
            <?php
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && ($data['Prisonercomplaint']['status'] == 'Draft'))
            { 
                  echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['Prisonercomplaint']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
             }
             else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && ($data['Prisonercomplaint']['status'] == 'Saved'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['Prisonercomplaint']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && ($data['Prisonercomplaint']['status'] == 'Reviewed'))
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['Prisonercomplaint']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
            ?>
          </td>
          <?php
        */
        ?>
      <td><?php echo $rowCnt; ?>&nbsp;</td>
      <td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($data['Prisonercomplaint']['date'])); ?>&nbsp;</td> 
      <td><?php echo $data['Prisonercomplaint']['time']; ?>&nbsp;</td> 
      
      <td><?php echo $funcall->getPrisonerNumber($data['Prisonercomplaint']['prisoner_no']); ?>&nbsp;</td>
      <td><?php echo ucwords(h($data['Prisonercomplaint']['priority'])); ?>&nbsp;</td>
      
      <td><?php echo ucwords(h($data['Prisonercomplaint']['complaint'])); ?>&nbsp;</td>
      
      <td>
        <?php
        //if($data['Prisonercomplaint']['status']=='Draft' && $this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')){
        if($data['Prisonercomplaint']['status']=='Draft' && $data['Prisonercomplaint']['is_complaint_forward']==0 && $this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){
            ?>
        <a href="" type="button" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#myModal<?php echo $data["Prisonercomplaint"]["id"]; ?>" class="btn btn-link">Response</a>   
        <!-- Modal -->
        <div id="myModal<?php echo $data["Prisonercomplaint"]["id"]; ?>" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Response Details</h4>
              </div>
              <div class="modal-body"> 
                  
                <div class="row-fluid">
                    <div class="span12">
                        <div class="control-group">
                            <label class="control-label">Remark :</label>
                            <div class="controls">
                                <?php 
                                echo $this->Form->textarea('response',array('div'=>false,'label'=>false,'placeholder'=>'Enter Response','class'=>'form-control alphanumeric','id'=>'response'.$data['Prisonercomplaint']['id']));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                        <div class="span12">
                            <div class="control-group">
                                <label class="control-label">Rating :</label>
                                <div class="controls stars">
                                      <input class="star star-5" id="star-5" type="radio" value="5" name="response_star_mark"/>
                                      <label class="star star-5" for="star-5"></label>
                                      <input class="star star-4" id="star-4" type="radio" value="4" name="response_star_mark"/>
                                      <label class="star star-4" for="star-4"></label>
                                      <input class="star star-3" id="star-3" type="radio" value="3" name="response_star_mark"/>
                                      <label class="star star-3" for="star-3"></label>
                                      <input class="star star-2" id="star-2" type="radio" value="2" name="response_star_mark"/>
                                      <label class="star star-2" for="star-2"></label>
                                      <input class="star star-1" id="star-1" type="radio" value="1" name="response_star_mark"/>
                                      <label class="star star-1" for="star-1"></label>
                                      
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="row-fluid">
                    <div class="span6">
                        &nbsp;
                    </div>
                    <div class="span6">
                        <?php echo $this->Form->button('Submit', array('type'=>'button', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true,'onclick'=>"saveComplaint(".$data['Prisonercomplaint']['id'].",'Response');"))?>
                    </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>

          </div>
        </div>
        <?php
        }
         if($data['Prisonercomplaint']['status']=='Draft' && $data['Prisonercomplaint']['is_complaint_forward']==1 && $this->Session->read('Auth.User.usertype_id')==$data['Prisonercomplaint']['forwarded_to']){
        ?>
            <a href="" type="button" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#myModal<?php echo $data["Prisonercomplaint"]["id"]; ?>" class="btn btn-link">Response</a>   
        <!-- Modal -->
        <div id="myModal<?php echo $data["Prisonercomplaint"]["id"]; ?>" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Response Details</h4>
              </div>
              <div class="modal-body"> 
                  
                <div class="row-fluid">
                    <div class="span12">
                        <div class="control-group">
                            <label class="control-label">Remark :</label>
                            <div class="controls">
                                <?php 
                                echo $this->Form->textarea('response',array('div'=>false,'label'=>false,'placeholder'=>'Enter Response','class'=>'form-control alphanumeric','id'=>'response'.$data['Prisonercomplaint']['id']));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                        <div class="span12">
                            <div class="control-group">
                                <label class="control-label">Rating :</label>
                                <div class="controls stars">
                                      <input class="star star-5" id="star-5" type="radio" value="5" name="response_star_mark"/>
                                      <label class="star star-5" for="star-5"></label>
                                      <input class="star star-4" id="star-4" type="radio" value="4" name="response_star_mark"/>
                                      <label class="star star-4" for="star-4"></label>
                                      <input class="star star-3" id="star-3" type="radio" value="3" name="response_star_mark"/>
                                      <label class="star star-3" for="star-3"></label>
                                      <input class="star star-2" id="star-2" type="radio" value="2" name="response_star_mark"/>
                                      <label class="star star-2" for="star-2"></label>
                                      <input class="star star-1" id="star-1" type="radio" value="1" name="response_star_mark"/>
                                      <label class="star star-1" for="star-1"></label>
                                      
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="row-fluid">
                    <div class="span6">
                        &nbsp;
                    </div>
                    <div class="span6">
                        <?php echo $this->Form->button('Submit', array('type'=>'button', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true,'onclick'=>"saveComplaint(".$data['Prisonercomplaint']['id'].",'Response');"))?>
                    </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>

          </div>
        </div>
        <?php
        }
        if($data['Prisonercomplaint']['status']!='Draft'){
            $status_info = '<b>Status: </b>'.$data['Prisonercomplaint']['status'].'<br>';
            if(isset($data['Prisonercomplaint']['respond_by']) && ($data['Prisonercomplaint']['respond_by'] != ''))
            $status_info .= '<b>Respond By: </b>'.$funcall->getName($data['Prisonercomplaint']['respond_by'],"User","name").'<br>';
            if(isset($data['Prisonercomplaint']['date_of_response']) && ($data['Prisonercomplaint']['date_of_response'] != ''))
            $status_info .= '<b>Date of Response: </b>'.date("d-m-Y", strtotime($data['Prisonercomplaint']['date_of_response'])).'<br>';
            if(isset($data['Prisonercomplaint']['response']) && ($data['Prisonercomplaint']['response'] != ''))
            $status_info .= '<b>Response: </b>'.$data['Prisonercomplaint']['response'].'<br>';
          if(isset($data['Prisonercomplaint']['response_star_mark']) && ($data['Prisonercomplaint']['response_star_mark'] != ''))
            $status_info .= '<b>Rating: </b>'.$data['Prisonercomplaint']['response_star_mark'].'<br>';
            ?>
            <a href="javaScript:void(0);" class="pop btn-success" pageTitle="Status Info" pageBody="<?php echo $status_info;?>">Response</a>
        <?php }?>
      </td>
      <td>
        <?php
        //if($data['Prisonercomplaint']['action']=='' && $data['Prisonercomplaint']['status']=='Response' && $this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
         if($data['Prisonercomplaint']['action']=='' && $data['Prisonercomplaint']['status']=='Response' && $data['Prisonercomplaint']['is_complaint_forward']==0 && $this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){
            ?>
            <a href="" type="button" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#myModal1<?php echo $data["Prisonercomplaint"]["id"]; ?>" class="btn btn-link">Action</a>   
            <!-- Modal -->
            <div id="myModal1<?php echo $data["Prisonercomplaint"]["id"]; ?>" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Action Details</h4>
                  </div>
                  <div class="modal-body"> 
                  <div class="row-fluid">
                    <div class="span12">
                            <div class="control-group">
                                <label class="control-label">Verification Type<?php echo $req; ?> :</label>
                                <div class="controls uradioBtn">
                                    <?php 
                                    $verification_type = array();
                                    $default = array();
                                    $currentController = $this->params['controller'];
                                    $user_type1 = Configure::read('RECEPTIONIST_USERTYPE');
                                    $user_type2 = Configure::read('PRINCIPALOFFICER_USERTYPE');
                                    $user_type3 = Configure::read('OFFICERINCHARGE_USERTYPE');
                                    $user_type4 = '';
                                   
                                    // if($this->Session->read('Auth.User.usertype_id')==$user_type3){
                                    //     $verification_type = array('Approved'=>'Approve','Rejected'=>'Reject');    
                                    //     $default = array("default"=>"Approved");
                                    // }
                                    $verification_type = array('Approved'=>'Approve','Rejected'=>'Reject');    
                                    $default = array("default"=>"Approved");
                                    echo $this->Form->radio('type', $verification_type,array("legend"=>false,'class'=>'verification_type radio', 'onclick'=>'checkVerifyType(this.value);')+$default);
                                    ?>
                                    <div style="clear:both;"></div>
                                    <div class="error-message" id="verification_type_err" style="display:none;">Verification type is required !</div>
                                </div>
                            </div>
                        </div>
                  </div>        
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="control-group">
                                <label class="control-label">Remark :</label>
                                <div class="controls">
                                    <?php 
                                    echo $this->Form->textarea('action',array('div'=>false,'label'=>false,'placeholder'=>'Enter Response','class'=>'form-control alphanumeric','id'=>'action'.$data['Prisonercomplaint']['id']));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="control-group">
                                <label class="control-label">Rating :</label>
                                <div class="controls stars">
                                      <input class="star star-5" id="star-5" type="radio" value="5" name="star_mark"/>
                                      <label class="star star-5" for="star-5"></label>
                                      <input class="star star-4" id="star-4" type="radio" value="4" name="star_mark"/>
                                      <label class="star star-4" for="star-4"></label>
                                      <input class="star star-3" id="star-3" type="radio" value="3" name="star_mark"/>
                                      <label class="star star-3" for="star-3"></label>
                                      <input class="star star-2" id="star-2" type="radio" value="2" name="star_mark"/>
                                      <label class="star star-2" for="star-2"></label>
                                      <input class="star star-1" id="star-1" type="radio" value="1" name="star_mark"/>
                                      <label class="star star-1" for="star-1"></label>

                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            &nbsp;
                        </div>
                        <div class="span6">
                            <?php echo $this->Form->button('Submit', array('type'=>'button', 'class'=>'btn btn-warning','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true,'onclick'=>"saveComplaint(".$data['Prisonercomplaint']['id'].",'Action');"))?>
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>

              </div>
            </div>
            <?php
        }
        
        if($data['Prisonercomplaint']['action']=='' && $data['Prisonercomplaint']['status']=='Response' && $data['Prisonercomplaint']['is_complaint_forward']==1 && $this->Session->read('Auth.User.usertype_id')==$data['Prisonercomplaint']['forwarded_to']){
            ?>
            <a href="" type="button" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#myModal1<?php echo $data["Prisonercomplaint"]["id"]; ?>" class="btn btn-link">Action</a>   
            <!-- Modal -->
            <div id="myModal1<?php echo $data["Prisonercomplaint"]["id"]; ?>" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Action Details</h4>
                  </div>
                  <div class="modal-body"> 
                  <div class="row-fluid">
                    <div class="span12">
                            <div class="control-group">
                                <label class="control-label">Verification Type<?php echo $req; ?> :</label>
                                <div class="controls uradioBtn">
                                    <?php 
                                    $verification_type = array();
                                    $default = array();
                                    $currentController = $this->params['controller'];
                                    $user_type1 = Configure::read('RECEPTIONIST_USERTYPE');
                                    $user_type2 = Configure::read('PRINCIPALOFFICER_USERTYPE');
                                    $user_type3 = Configure::read('OFFICERINCHARGE_USERTYPE');
                                    $user_type4 = '';
                                   
                                    // if($this->Session->read('Auth.User.usertype_id')==$user_type3){
                                    //     $verification_type = array('Approved'=>'Approve','Rejected'=>'Reject');    
                                    //     $default = array("default"=>"Approved");
                                    // }
                                    $verification_type = array('Approved'=>'Approve','Rejected'=>'Reject');    
                                    $default = array("default"=>"Approved");
                                    echo $this->Form->radio('type', $verification_type,array("legend"=>false,'class'=>'verification_type radio', 'onclick'=>'checkVerifyType(this.value);')+$default);
                                    ?>
                                    <div style="clear:both;"></div>
                                    <div class="error-message" id="verification_type_err" style="display:none;">Verification type is required !</div>
                                </div>
                            </div>
                        </div>
                  </div>        
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="control-group">
                                <label class="control-label">Remark :</label>
                                <div class="controls">
                                    <?php 
                                    echo $this->Form->textarea('action',array('div'=>false,'label'=>false,'placeholder'=>'Enter Response','class'=>'form-control alphanumeric','id'=>'action'.$data['Prisonercomplaint']['id']));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="control-group">
                                <label class="control-label">Rating :</label>
                                <div class="controls stars">
                                      <input class="star star-5" id="star-5" type="radio" value="5" name="star_mark"/>
                                      <label class="star star-5" for="star-5"></label>
                                      <input class="star star-4" id="star-4" type="radio" value="4" name="star_mark"/>
                                      <label class="star star-4" for="star-4"></label>
                                      <input class="star star-3" id="star-3" type="radio" value="3" name="star_mark"/>
                                      <label class="star star-3" for="star-3"></label>
                                      <input class="star star-2" id="star-2" type="radio" value="2" name="star_mark"/>
                                      <label class="star star-2" for="star-2"></label>
                                      <input class="star star-1" id="star-1" type="radio" value="1" name="star_mark"/>
                                      <label class="star star-1" for="star-1"></label>

                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            &nbsp;
                        </div>
                        <div class="span6">
                            <?php echo $this->Form->button('Submit', array('type'=>'button', 'class'=>'btn btn-warning','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true,'onclick'=>"saveComplaint(".$data['Prisonercomplaint']['id'].",'Action');"))?>
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>

              </div>
            </div>
            <?php 
        }
        if($data['Prisonercomplaint']['action']!=''){
            $status_info = '<b>Status: </b>'.$data['Prisonercomplaint']['status'].'<br>';
            if(isset($data['Prisonercomplaint']['is_approved']) && ($data['Prisonercomplaint']['is_approved'] != ''))
            $status_info .= '<b>Verification Type: </b>'.$data['Prisonercomplaint']['is_approved'].'<br>';
            if(isset($data['Prisonercomplaint']['action_by']) && ($data['Prisonercomplaint']['action_by'] != ''))
            $status_info .= '<b>Action By: </b>'.$funcall->getName($data['Prisonercomplaint']['action_by'],"User","name").'<br>';
            if(isset($data['Prisonercomplaint']['action_date']) && ($data['Prisonercomplaint']['action_date'] != ''))
            $status_info .= '<b>Action Date: </b>'.date("d-m-Y", strtotime($data['Prisonercomplaint']['action_date'])).'<br>';
            if(isset($data['Prisonercomplaint']['action']) && ($data['Prisonercomplaint']['action'] != ''))
            $status_info .= '<b>Action Remark: </b>'.$data['Prisonercomplaint']['action'].'<br>';
          if(isset($data['Prisonercomplaint']['star_mark']) && ($data['Prisonercomplaint']['star_mark'] != ''))
            $status_info .= '<b>Rating: </b>'.$data['Prisonercomplaint']['star_mark'].'<br>';
            ?>
            <a href="javaScript:void(0);" class="pop btn-success" pageTitle="Status Info" pageBody="<?php echo $status_info;?>">Action</a>
            <?php
        }
        ?>
        
      </td>
      
         <td class="actions">
      <?php if($data['Prisonercomplaint']['status']=='Draft' && $this->Session->read('Auth.User.usertype_id')==$data['Prisonercomplaint']['forwarded_to'] && $data['Prisonercomplaint']['forwarded_to']==Configure::read('PRINCIPALOFFICER_USERTYPE') && $data['Prisonercomplaint']['is_complaint_forward']==1){?>
            <?php echo $this->Form->create('PrisonercomplaintForward',array('url'=>'/prisonercomplaints/add','admin'=>false));?> 
          <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Prisonercomplaint']['id'])); ?>
          <?php echo $this->Form->button('Forward',array('class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to forward?')")); ?> 
          <?php echo $this->Form->end();?> 
      <?php }?>
      <?php
      if($this->Session->read('Auth.User.usertype_id')!=Configure::read('PRINCIPALOFFICER_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('OFFICERINCHARGE_USERTYPE') && $data['Prisonercomplaint']['status']=='Draft'){
      ?>
        
          <?php echo $this->Form->create('PrisonercomplaintEdit',array('url'=>'/prisonercomplaints/add','admin'=>false));?> 
          <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Prisonercomplaint']['id'])); ?>
          <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
          <?php echo $this->Form->end();?> 
        
          <?php echo $this->Form->create('PrisonercomplaintDelete',array('url'=>'/prisonercomplaints/add','admin'=>false));?> 
          <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Prisonercomplaint']['id'])); ?>
          <?php echo $this->Form->button('<i class="icon icon-trash"></i>',array('class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
          <?php echo $this->Form->end();?>
      
      <?php
      }
      ?>
      </td>
    </tr>
<?php
$rowCnt++;
}
?>
  </tbody>
</table>
<?php
}else{
?>
...
<?php    
}
?>  
<script>
$(document).ready(function(){
  
        $("#checkAll").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $('input[type="checkbox"]').click(function(){
          var atLeastOneIsChecked = $('input[type="checkbox"]:checked').length;
          var is_checkall = $('input[id="checkAll"]:checked').length;
          if(is_checkall == 1 && atLeastOneIsChecked == 1)
          { 
            $('#checkAll').attr('checked', false);
            $('#forwardBtn').hide();
          }
          else if(atLeastOneIsChecked >= 1)
          {
            $('#forwardBtn').show();
          }
          else 
          {
            $('#forwardBtn').hide();
          }
        });
});
//Dynamic confirmation modal -- START --
var btnName = '<?php echo $btnName;?>';
var isModal = '<?php echo $isModal;?>';
function ShowConfirmYesNo() {
    AsyncConfirmYesNo(
            "Are you sure want to "+btnName+"?",
            btnName,
            'Cancel',
            MyYesFunction,
            MyNoFunction
        );
}

function MyYesFunction() {
  if(isModal == 1)
  {
    $('#verify').modal('show');
  }
  else 
  {
    $('#ApprovalProcessFormIndexAjaxForm').submit();
  }
}
function MyNoFunction() {
    
}
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-46156385-1', 'cssscript.com');
  ga('send', 'pageview');
//Dynamic confirmation modal -- END --
</script>  