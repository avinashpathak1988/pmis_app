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
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
<?php 
if(is_array($datas) && count($datas)>0){
    if(!isset($is_excel)){
?>
<div class="row-fluid">
    <div class="span5">
        <ul class="pagination" style="margin-left: 0px;">
          <?php
              $this->Paginator->options(array(
                  'update'                    => '#dataList',
                  'evalScripts'               => true,
                  //'before'                    => '$("#lodding_image").show();',
                  //'complete'                  => '$("#lodding_image").hide();',
                  'url'                       => array(
                                                    'controller'            => 'Properties',
                                                    'action'                => 'dataAjax'
                                                    
                                                  )
              ));         
              echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
              echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
              echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
              echo $this->Js->writeBuffer();
          ?>
        </ul>
    </div>
    <div class="span7 text-right" style="padding-top:5px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
    $exUrl = "dataAjax/";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
  }
?>
    </div>
</div>
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
          <th style="text-align: left;">SL#</th>
          <th style="text-align: left;">Prisoner Number</th>
          <th style="text-align: left;">
            Date
          </th>
          <th style="text-align: left;">
            Previous Amount
          </th>
          <th style="text-align: left;">
            Debit Amount
          </th>
          <th style="text-align: left;">
            Balance Amount
          </th>
          <?php 
          if($modelName == 'CashItem')
          {?>
            <th style="text-align: left;">
            Description
            </th>
            <th style="text-align: left;">
            Source
            </th>
          <?php }
          else 
          {?>
            <th style="text-align: left;">
            Reason
            </th>
          <?php }?>
          
          <th style="text-align: left;">
          Status
          </th>
          <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    $j=0;
    $amt=0;
    $totalamount=0;
      foreach($datas as $data)
      {
        $id = $data[$modelName]['id'];?>
        <tr>
          <td><?php echo $rowCnt; ?></td>
          <?php 
          if($modelName == 'CashItem')
          {?>
            <td><?php echo $data['PhysicalProperty']['Prisoners']['prisoner_no'];?></td>
            <td><?php echo date('d/m/Y',strtotime($data['PhysicalProperty']['property_date_time']));?></td>
            <?php $currencySymbol = $data['Currency']['symbol'] ; 
              if(strpos($currencySymbol,'fa-') !== false){
                  $symbolHtml = "<span class= 'fa " . $currencySymbol . " ' ></span>";
              }else{
                $symbolHtml = "<span class='fa'>".$currencySymbol ."</span>";
              }
            ?>

            <td><?php echo $symbolHtml . ' ' . $data['CashItem']['amount'];?></td>
            <td style="max-width: 200px;text-overflow: scroll;    overflow-y: hidden;"><?php echo $data['PhysicalProperty']['description'];?></td>
            <td style="max-width: 200px;text-overflow: scroll;    overflow-y: hidden;"><?php echo $data['PhysicalProperty']['source'];?></td>
          <?php 
          }
          else 
          {?>
            <td><?php echo $data['Prisoners']['prisoner_no'];?></td>
            <td><?php echo date('d/m/Y',strtotime($data[$modelName]['debit_date_time']));?></td>
            <td><?php echo $data[$modelName]['prev_amount'].' '.$data['Currency']['name'];?></td>
            <td><?php echo $data[$modelName]['debit_amount'].' '.$data['Currency']['name'];?></td>
            <td><?php echo $data[$modelName]['balance_amount'].' '.$data['Currency']['name'];?></td>
            <td><?php echo $data[$modelName]['reason'];?></td>
          <?php }?>
          <td>
            <?php if($data[$modelName]['status'] == 'Draft')
            {
              echo $data[$modelName]['status'];
            }
            else 
            {
              $status_info = '<b>Status: </b>'.$data[$modelName]['status'].'<br>';
              if(isset($data['ApprovalProcess'][0]['created']) && ($data['ApprovalProcess'][0]['created'] != '0000-00-00 00:00:00'))
                $status_info .= '<b>Date: </b>'.date('d-m-Y H:i:s', strtotime($data['ApprovalProcess'][0]['created'])).'<br>';
              if(isset($data['ApprovalProcess'][0]['remark']) && ($data['ApprovalProcess'][0]['remark'] != ''))
                $status_info .= '<b>Remark: </b>'.$data['ApprovalProcess'][0]['remark'].'';
              ?>
              <a href="javaScript:void(0);" class="pop btn-success" pageTitle="Status Info" pageBody="<?php echo $status_info;?>"><?php echo $data[$modelName]['status'];?></a>
              <?php 
            }?>
          </td>
          <td>
            <?php if($data[$modelName]['status'] == 'Draft'){  ?>

              <?php echo $this->Form->create('DebitCashEdit',array('url'=>'/Properties/index/' . $data['Prisoners']['uuid'].'#debit' ,'admin'=>false,'class'=>'pull-left','style'=>'margin-right:10px', 'id'=>'DebitCashEdit-'.$id));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data[$modelName]['id']));
                    
                    ?>
                    <button class="btn btn-success" type="button" value="Edit" onclick="ShowConfirmEditDebit('<?php echo $id;?>');"><i class="icon icon-edit"></i></button>

                     <?php echo $this->Form->end();
                    ?> 

           <?php }?>
          
               
          </td>
        </tr>
        <?php   
        $rowCnt++;
      }
    ?>
    </tbody>
</table>    
<?php
}
else 
{
  echo '...';
}
?>
<script>
function ShowConfirmEditDebit(id) {
    AsyncConfirmYesNo(
            "Are you sure want to edit?",
            'Edit',
            'Cancel',
            function(){
              $('#DebitCashEdit-'+id).submit();
            },
            function(){
              
            }
        );
}
</script> 