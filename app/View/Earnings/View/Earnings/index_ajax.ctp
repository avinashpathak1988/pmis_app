<?php //echo '<pre>'; print_r($datas); exit;
if(is_array($datas) && count($datas)>0){
?>
<div class="row">
  <?php if(!isset($is_excel)){?>
    <div class="span5">
        <ul class="pagination">
<?php if(!isset($is_excel)){
      $this->Paginator->options(array(
          'update'                    => '#listingDiv',
          'evalScripts'               => true,
          //'before'                  => '$("#lodding_image").show();',
          //'complete'                => '$("#lodding_image").hide();',
              'url'                       => array(
              'controller'            => 'Earnings',
              'action'                => 'indexAjax'
          )
      ));  
    }       
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    
?>
        </ul>
    </div>
  <?php }?>
    <div class="span7 text-right" style="padding-top:25px;">
<?php
if(!isset($is_excel)){
  echo $this->Paginator->counter(array(
      'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
  ));
}

    $exUrl = "indexAjax";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    $urlDoc = $exUrl.'/reqType:PRINT';
	$urlPdf = $exUrl.'/reqType:PDF';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download Pdf")),$urlPdf, array("escape" => false)));
	echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("print.png",array("height" => "20","width" => "20","title"=>"Print Doc")),$urlDoc, array("escape" => false, "target"=>"_blank")));
?>
    </div>
</div>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>
      <th><?php echo $this->Paginator->sort('Sl no'); ?></th>                
      <th><?php echo $this->Paginator->sort('Prisoner Number'); ?></th>
      <th><?php echo $this->Paginator->sort('Prisoner Name'); ?></th>
      <!-- <th><?php //echo $this->Paginator->sort('Balance B/f'); ?></th> -->
      <th><?php echo $this->Paginator->sort('Earnings'); ?></th>
      <th><?php echo $this->Paginator->sort('Fines Deducted'); ?></th>
      <th><?php echo 'Gratuity'; ?></th>
      <!-- <th><?php //echo $this->Paginator->sort('Expenditure'); ?></th> -->
      <!-- <th><?php //echo $this->Paginator->sort('Paysheet'); ?></th> -->
       <th><?php echo $this->Paginator->sort('PP Cash'); ?></th>
       <th><?php echo $this->Paginator->sort('Savings'); ?></th>
       <?php if(!isset($is_excel)){?>
       <th>Final Withdraw For Discharge</th>
     <?php }?>
    </tr>
  </thead>
<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){

  $saving = 0;
  //$prisoner_earnings = $funcall->getPrisonerEarning($data['Prisoner']['id'],$start_date,$end_date);
  //$total_balance = $funcall->getPrisonerBalance($data['Prisoner']['id'],$start_date);
  //$total_expenditure = $funcall->getPrisonerExpenditure($data['Prisoner']['id'],$start_date,$end_date);
  //$paysheet = $funcall->getPrisonerPaysheet($data['Prisoner']['id'],$start_date,$end_date);

  $prisonerData = $funcall->getPrisonerEarningDetails($data['Prisoner']['id']);
  $total_balance = $prisonerData['paid_amount'];
  $prisoner_earnings = $prisonerData['total_amount'];
  $total_balance = $prisonerData['paid_amount'];

  $ppcash = $funcall->getPrisonerPPCash($data['Prisoner']['id']);
  $savingDetail = $funcall->getPrisonerSavingDetails($data['Prisoner']['id']);
  $fine = $savingDetail['fine_amount'];
  $gratuity = $savingDetail['gratuity_amount'];

  $total_savings = $funcall->getPrisonerSavingBalance($data['Prisoner']['id']);

  // if($total_balance > 0)
  // {
  //   $saving = ($total_balance+$prisoner_earnings) - ($total_expenditure+$paysheet);
  // }
?>
    <tr>
      <td><?php echo $rowCnt; ?>&nbsp;</td>
      <td><?php echo $data['Prisoner']['prisoner_no']; ?></td>
      <td><?php echo $data['Prisoner']['fullname']; ?></td>
      <!-- <td><?php //echo $total_balance; ?></td> -->
      <td><?php echo $prisoner_earnings;?></td>
      <td><?php echo $fine;?></td>
      <td><?php echo $gratuity;?></td>
      <td><?php echo $ppcash;?></td>
      <td><?php echo $total_savings; ?></td>
      <?php if(!isset($is_excel)){?>
      <td>
        <?php
        if($total_savings!=0 && !$funcall->checkWithdrawStatus($data['Prisoner']['id'])){
            ?>
            <a href="" type="button" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#verify<?php echo $data["Prisoner"]["id"]; ?>" class="btn btn-warning btn-mini singleBot">Withdraw</a>
           <!-- Verify Modal START -->
            <div id="verify<?php echo $data["Prisoner"]["id"]; ?>" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" onclick="setPUuid();">&times;</button>
                            <h4 class="modal-title">Withdraw</h4>
                        </div>
                        <div class="modal-body">
                            <?php echo $this->Form->create('Withdraw',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '#','id'=>'verifyPrisoner'.$data["Prisoner"]["id"]));?>
                                                                   
                            <div class="row" style="padding-bottom: 14px;">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Amount<?php //echo $req; ?> :</label>
                                        <div class="controls uradioBtn">
                                            <?php
                                            echo $this->Form->input('amount', array('label'=>false,'class'=>'earning'.$data["Prisoner"]["id"],'type'=>'text','value'=>$total_savings,'class'=>'form-controls','readonly'));
                                            ?>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                            <div class="form-actions" align="center" style="background:#fff;">
                                <?php echo $this->Form->button('Withdraw', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success','onclick'=>'withdrawAmount('.$data["Prisoner"]["id"].')'))?>
                            </div>
                            <?php echo $this->Form->end();?>
                        </div>
                    </div>
                </div>
            </div>                       
            <!-- Verify Modal END -->
            <?php
        }
        ?>
      </td>
    <?php }?>
    </tr>
<?php
$rowCnt++;
}
?>
  </tbody>
</table>
<?php echo $this->Js->writeBuffer();
}else{
?>
...
<?php    
}
?>    