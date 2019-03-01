<?php
//echo '<pre>'; print_r($datas); exit;
if(is_array($datas) && count($datas)>0){
    if(!isset($is_excel)){
?>
<div class="row-fluid">
    <div class="span5">
        <ul class="pagination" style="margin-left: 0px;">
          <?php
              $this->Paginator->options(array(
                  'update'                    => '#creditCashList',
                  'evalScripts'               => true,
                  //'before'                    => '$("#lodding_image").show();',
                  //'complete'                  => '$("#lodding_image").hide();',
                  'url'                       => array(
                                                    'controller'            => 'Properties',
                                                    'action'                => 'creditAjax',
                                                    'prisoner_uuid'          => $prisoner_uuid,
                                                    
                                                  )
              ));         
              echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
              echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
              echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
              echo $this->Js->writeBuffer();
          ?>
        </ul>
    </div>
    <div class="span7 text-right" style="padding-top:25px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
    $exUrl = "creditAjax/prisoner_uuid:$prisoner_uuid";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
  }
?>
    </div>
</div>
<table class="table table-bordered data-table table-responsive" id="cashidtbl">
    <thead>
        <tr>
          <th style="text-align: left;">SL#</th>
          <th style="text-align: left;">
            Date
          </th>
          <th style="text-align: left;">
            Amount
          </th>
          <th style="text-align: left;">
          Description
          </th>
          <th style="text-align: left;">
          Source
          </th>
          <th style="text-align: left;">
          Status
          </th>
          <th>
            Action
          </th>
          <!-- <th style="text-align: left;">
          Action
          </th> -->
        </tr>
    </thead>
    <tbody>
    <?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    $j=0;
    $amt=0;
    $totalamount=0;
      foreach($datas as $data){
        ?>
        
        <tr>
          <td><?php echo $rowCnt; ?></td>
          <td><?php echo date('d/m/Y H:i:s',strtotime($data['PhysicalProperty']['property_date_time']));?></td>
          <td><?php echo $data['CashItem']['amount'].' '.$data['Currency']['name']; ?><i class="icon <?php echo $data['Currency']['symbol'] ; ?>"></i></td>
          <td><?php echo $data['PhysicalProperty']['description'];?></td>
          <td><?php echo $data['PhysicalProperty']['source'];?></td>
          <td><?php echo $data['CashItem']['status'];?></td>
          <td><?php if($data['CashItem']['status'] == 'Draft'){  ?>

              <?php echo $this->Form->create('CashPropertyEdit',array('url'=>'/Properties/index/'.$prisoner_uuid.'#credit','admin'=>false,'class'=>'pull-left','style'=>'margin-right:10px'));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['PhysicalProperty']['id']));
                    
                    ?>
                    <button class="btn btn-success" type="submit" value="Edit" onclick="javascript:return confirm('Are you sure want to edit?')"><i class="icon icon-edit"></i></button>



           <?php }?></td>
          <!-- <td>
            Remove
          </td> -->
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

<style type="text/css">
  table td{
    max-width: 100px;
    overflow-x: hidden;
  }
</style>