<style>
#forwardBtn
{
  display: none;
}
.controls.uradioBtn .radio {
    padding-right: 5px;
    padding-top: 5px;
}
</style>
<?php
if(is_array($datas) && count($datas)>0){
  if(!isset($is_excel)){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                        => '#listingDiv',
        'evalScripts'                   => true,
        //'before'                      => '$("#lodding_image").show();',
        //'complete'                    => '$("#lodding_image").hide();',
            'url'                       => array(
              'controller'                => 'Gatepasses',
              'action'                    => 'gatepassGroupListAjax',
        )+$searchData
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
   
?>
        </ul>
    </div>
    <div class="col-sm-7 text-right" style="padding-top:30px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
    $exUrl = $this->Html->url(array('controller'=>'Gatepasses','action'=>'gatepassGroupListAjax')+$searchData,true);;
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    $urlPDF = $exUrl.'/reqType:PDF';
  $urlPrint = $exUrl.'/reqType:PRINT';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
  echo '&nbsp;&nbsp;';
  echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download Pdf")),$urlPDF, array("escape" => false)));
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
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('MAIN_GATEKEEPER_USERTYPE'))
{
  $btnName = "Verify";
  $isModal = 0;
}
else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
{
  $btnName = "Approved";
  $isModal = 0;
}
echo $this->Form->create('ApprovalProcessForm',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/Gatepasses/gatepassGroupList'));?>
<?php if($isModal == 1)
{?>
  <!-- Verify Modal START -->
  <?php echo $this->element('verify-modal');?>                       
  <!-- Verify Modal END -->
<?php }?>

<button type="button" onclick="ShowConfirmYesNo();" tabcls="next" id="forwardBtn" class="btn btn-success btn-mini"><?php echo $btnName;?></button> 
<?php
    }
?>
<?php
        if(isset($is_excel)){
          ?>
          <style type="text/css">
              th, td{border: 1px solid black;}
           </style>
          <?php
        }
          ?> 
  <table id="districtTable" class="table table-bordered table-striped table-responsive">
    <thead>
        <tr>
        <?php
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') || $this->Session->read('Auth.User.usertype_id')==Configure::read('MAIN_GATEKEEPER_USERTYPE')){
            if(!isset($is_excel)){
              ?>
            <th>
                <?php 
                echo $this->Form->input('checkAll', array(
                      'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'checkAll',
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                ));
                ?>
              </th>
              <?php
            }
        }
          ?>
            <th><?php echo $this->Paginator->sort('Sl no'); ?></th>  
                        
            <th>
                <?php 
                echo $this->Paginator->sort('gp_no',"Gate Pass No.",array(
                    'update'                        => '#listingDiv',
                    'evalScripts'                   => true,
                        'url'                       => array(
                            'controller'                => 'Gatepasses',
                            'action'                    => 'gatepassGroupListAjax',
                        )+$searchData
                )); 
                ?>
            </th>                
            <th><?php echo $this->Paginator->sort('Date'); ?></th>
            <th><?php echo $this->Paginator->sort('Gatepass Type'); ?></th>
            <th><?php echo $this->Paginator->sort('Gatepass'); ?></th>
            <th>Status</th>
            <?php
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('MAIN_GATEKEEPER_USERTYPE')){
                ?>
                <th>Main Gate Verified</th>
                <?php
            }
            ?>
        </tr>
    </thead>
    <tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
        // $display_status = Configure::read($data['Gatepass']['status']);
        $gatepassDetails = $funcall->Gatepass->findById($data[0]['id']);
        // debug($gatepassDetails);
?>
        <tr>
        <?php
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') || $this->Session->read('Auth.User.usertype_id')==Configure::read('MAIN_GATEKEEPER_USERTYPE')){
            if(!isset($is_excel)){
          ?>
            <td>
            <?php
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && ($data['Gatepass']['status'] == 'Draft'))
            { 
                  echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['Gatepass']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
             }
             else if($this->Session->read('Auth.User.usertype_id')==Configure::read('MAIN_GATEKEEPER_USERTYPE') && $data["Gatepass"]['is_verify'] == 0)
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['Gatepass']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
              else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && $data["Gatepass"]['approval_status'] == 'Draft')
              {
                echo $this->Form->input('ApprovalProcess.'.$rowCnt.'.fid', array(
                      'type'=>'checkbox', 'value'=>$data['Gatepass']['id'],'hiddenField' => false, 'label'=>false,
                      'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                  ));
              }
            ?>
          </td>
          <?php
            }
          }
          ?>
            <td><?php echo $rowCnt; ?></td>
            <td><?php echo ucwords(h($data['Gatepass']['gp_no'])); ?>&nbsp;</td>            
            <td>
            <?php 
            if($data['Gatepass']['gp_date'] != '0000-00-00')
                echo ucwords(h(date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($data['Gatepass']['gp_date']))));
            else
                echo 'N/A';?>
            &nbsp;
            </td> 
            <td><?php echo ucwords(h($data['Gatepass']['gatepass_type'])); ?>&nbsp;</td>
            <td><?php echo $this->Html->link("View",array('controller'=>'Gatepasses','action'=>'gatepassViewPdf',$data['Gatepass']['id']), array("escape" => false,'class'=>'btn btn-warning btn-mini','target'=>"_blank")); ?>&nbsp;</td>            
            <?php /* ?>
            <td>
            <?php
            if(isset($data['Gatepass']['is_verify']) && $data['Gatepass']['is_verify']==0 && $this->Session->read('Auth.User.usertype_id')==Configure::read('MAIN_GATEKEEPER_USERTYPE') && $data['Gatepass']['gatepass_status']=='out'){
                if(!isset($is_excel)){
                    ?>
                    <span id="link_biometric_span_<?php echo $data['Gatepass']['id']; ?>"></span>
                    <?php 
                    echo $this->Form->button('Verify', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn','id'=>'link_biometric_button_'.$data['Gatepass']['id'],"onclick"=>"verifyData(".$data['Gatepass']['prisoner_id'].",".$data['Gatepass']['id'].")"));

                    ?>&nbsp;
                    <?php
                }
            }else{
                echo ($data['Gatepass']['is_verify']==0) ? "Not Verified" : "Verified";
            }
                ?>
            </td>
            <?php */ ?>
            <?php
            //$this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')
            if(true){
            ?>
            <td>
            <?php 
            // debug($data['ApprovalProcess']);
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('MAIN_GATEKEEPER_USERTYPE')){
                if($data["Gatepass"]['is_verify'] == 1)
                {
                  echo "Verified";
                }
                else 
                {
                  echo "Not Verified";
                }
            }

            if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
                if($data["Gatepass"]['approval_status'] == 'Draft')
                {
                  echo "Draft";
                }
                else 
                {
                  echo "Approved";
                }
            }

            ?>
          </td>
          <?php
            }
          ?>
          <?php
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('MAIN_GATEKEEPER_USERTYPE')){
                ?>
          <td>
            <?php 
            if(isset($data['Gatepass']['is_verify']) && $data['Gatepass']['is_verify']==1 && isset($data['Gatepass']['inverification_verify']) && trim($data['Gatepass']['inverification_verify'])==0){

              ?>
              <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal_<?php echo $data['Gatepass']['id']?>">In Verification</button>
              <div id="myModal_<?php echo $data['Gatepass']['id']?>" class="modal fade verifyPopupModal" role="dialog">
                    <div class="modal-dialog">
                    
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" onclick="setPUuid();">&times;</button>
                                <h4 class="modal-title">In Verification</h4>
                            </div>
                            <div class="modal-body">
                                <div class="" style="padding-bottom: 14px;">
                                  <input type="hidden" id="gatepassId" name="id" value="<?php echo $data['Gatepass']['gp_no']?>">
                                <?php if(isset($data['Gatepass']['gatepass_type']) && $data['Gatepass']['gatepass_type']=='Court Attendance'){?>
                                
                                  <div class="control-group">
                                        <label class="control-label">Court name <span id="isRemarkValidate" style="display:none;"><?php echo $req; ?></span>:</label>
                                        <div class="controls uradioBtn">
                                           <?php 
                                            //debug($data['Gatepass']['gatepass_type']);
                                            
                                            $funcall->loadModel($gatepassDetails['Gatepass']['model_name']);
                                            
                                            $getCourt = $funcall->$gatepassDetails['Gatepass']['model_name']->field('court_id',array($gatepassDetails['Gatepass']['model_name'].'.id'=>$gatepassDetails['Gatepass']['reference_id']
                                              )
                                              );
                                            echo $funcall->getName($getCourt,'Court','name');
                                           ?>
                                        </div>
                                    </div> 
                                    <?php }?>
                                    <div class="control-group">
                                        <label class="control-label">Incharge of Escort party <span id="isRemarkValidate" style="display:none;"><?php echo $req; ?></span>:</label>
                                        <div class="controls uradioBtn">
                                           <?php 
                                            echo $funcall->getName($gatepassDetails['Gatepass']['escort_team'],'EscortTeam','name');
                                           ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">No.Of prisoners <span id="isRemarkValidate" style="display:none;"><?php echo $req; ?></span>:</label>
                                        <div class="controls uradioBtn">
                                           <?php 
                                           //debug($data['Gatepass']['gp_no']);
                                           $getPrisonerCount = $funcall->Gatepass->find('count',array(
                                              'conditions'=>array('Gatepass.gp_no'=>$data['Gatepass']['gp_no'])
                                              )
                                              );
                                            
                                            echo $getPrisonerCount;
                                           ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label"> Date and Time <span id="isRemarkValidate" style="display:none;"><?php echo $req; ?></span>:</label>
                                        <div class="controls uradioBtn">
                                           <?php echo $this->Form->input('inverification_time',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'','id'=>'inverification_time','readonly','required'=>false,'value'=>date('d-m-Y')));?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Remark <span id="isRemarkValidate" style="display:none;"><?php echo $req; ?></span>:</label>
                                        <div class="controls uradioBtn">
                                           <?php echo $this->Form->input('inverification_remark',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'textarea','placeholder'=>'Enter Remark','id'=>'inverification_remark','rows'=>3,'required'=>false));?>
                                           <div style="clear:both;"></div>
                                            <div class="error-message" id="verification_message_err" style="display:none;">Verification type is required !</div>
                                        </div>
                                    </div>         
                                </div>
                                <div class="form-actions" align="center" style="background:#fff;">
                                    <?php echo $this->Form->button('Save', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success','id'=>'verifyBtn','onclick'=>'submitInVerification()'))?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
            }else if(isset($data['Gatepass']['is_verify']) && $data['Gatepass']['is_verify']==1 && isset($data['Gatepass']['inverification_verify']) && trim($data['Gatepass']['inverification_verify'])==1){
                ?>
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal_<?php echo $data['Gatepass']['id']?>">In Verified</button>
              <div id="myModal_<?php echo $data['Gatepass']['id']?>" class="modal fade verifyPopupModal" role="dialog">
                    <div class="modal-dialog">
                    
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" onclick="setPUuid();">&times;</button>
                                <h4 class="modal-title">In Verification</h4>
                            </div>
                            <div class="modal-body">
                                <div class="" style="padding-bottom: 14px;">
                                  <input type="hidden" id="gatepassId" name="id" value="<?php echo $data['Gatepass']['id']?>">
                                <?php if(isset($data['Gatepass']['gatepass_type']) && $data['Gatepass']['gatepass_type']=='Court Attendance'){?>
                                
                                  <div class="control-group">
                                        <label class="control-label">Court name <span id="isRemarkValidate" style="display:none;"><?php echo $req; ?></span>:</label>
                                        <div class="controls uradioBtn">
                                           <?php 
                                            //debug($data['Gatepass']['gatepass_type']);
                                            
                                            $funcall->loadModel($gatepassDetails['Gatepass']['model_name']);
                                            
                                            $getCourt = $funcall->$gatepassDetails['Gatepass']['model_name']->field('court_id',array($gatepassDetails['Gatepass']['model_name'].'.id'=>$gatepassDetails['Gatepass']['reference_id']
                                              )
                                              );
                                            echo $funcall->getName($getCourt,'Court','name');
                                           ?>
                                        </div>
                                    </div> 
                                    <?php }?>
                                    <div class="control-group">
                                        <label class="control-label">Incharge of Escort party <span id="isRemarkValidate" style="display:none;"><?php echo $req; ?></span>:</label>
                                        <div class="controls uradioBtn">
                                           <?php 
                                            echo $funcall->getName($gatepassDetails['Gatepass']['escort_team'],'EscortTeam','name');
                                           ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">No.Of prisoners <span id="isRemarkValidate" style="display:none;"><?php echo $req; ?></span>:</label>
                                        <div class="controls uradioBtn">
                                           <?php 
                                           //debug($data['Gatepass']['gp_no']);
                                           $getPrisonerCount = $funcall->Gatepass->find('count',array(
                                              'conditions'=>array('Gatepass.gp_no'=>$data['Gatepass']['gp_no'])
                                              )
                                              );
                                            
                                            echo $getPrisonerCount;
                                           ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label"> Date <span id="isRemarkValidate" style="display:none;"><?php echo $req; ?></span>:</label>
                                        <div class="controls uradioBtn">
                                           <?php
                                           echo date("d-m-Y", strtotime($gatepassDetails['Gatepass']['inverification_time']));
                                           ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Remark <span id="isRemarkValidate" style="display:none;"><?php echo $req; ?></span>:</label>
                                        <div class="controls uradioBtn">
                                           <?php
                                           echo $gatepassDetails['Gatepass']['inverification_remark'];
                                           ?>
                                        </div>
                                    </div>         
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
          </td>
          <?php
        }
          ?>
        </tr>
<?php
$rowCnt++;
}
?>
    </tbody>
</table>
<?php
echo $this->Form->end();
}else{
echo Configure::read("NO-RECORD");    
}
 echo $this->Js->writeBuffer();
?>    
<?php if(@$file_type != 'pdf') { ?>
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
      $('#ApprovalProcessFormGatepassGroupListAjaxForm').submit();
    }
}
function MyNoFunction() {
    
}
</script>
<?php } ?>