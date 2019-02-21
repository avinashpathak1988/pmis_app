<?php
if(is_array($datas) && count($datas)>0){
    if(!isset($is_excel)){
?>
<div class="row">
    <div class="span5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#item_listview',
        'evalScripts'               => true,
        //'before'                    => '$("#lodding_image").show();',
        //'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'Earnings',
            'action'                => 'itemAjax'

        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    
?>
        </ul>
    </div>
    <div class="span7 text-right">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
    $exUrl = "itemAjax";
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
    }
?>                    
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
            <th>SL#</th>
            <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('ADMIN_USERTYPE'))
            {?>
                <th>Prison Station</th>
            <?php }?>
            <th>
                <?php                 
                echo $this->Paginator->sort('Item.name','Name',array('update'=>'#item_listview','evalScripts' => true,
                  'url'=>array(
                    'controller' => 'Earnings',
                    'action' => 'itemAjax', 
                    //'amount'                => $amount,
                    //'start_date'            => $start_date,
                    //'end_date'              => $end_date
                  )
                ));
                ?>
            </th>
            <th>
                <?php                 
                echo $this->Paginator->sort('Item.price','Price',array('update'=>'#item_listview','evalScripts' => true,
                  'url'=>array(
                    'controller' => 'Earnings',
                    'action' => 'itemAjax', 
                    //'amount'                => $amount,
                    //'start_date'            => $start_date,
                    //'end_date'              => $end_date
                  )
                ));
                ?>
            </th>
            <th>Comment</th>
<?php
if(!isset($is_excel)){
?> 
            <th>Action</th>
<?php
}
?>             
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));

    foreach($datas as $data){
        //debug($data); exit;
      $id = $data['Item']['id'];
?>
        <tr>
            <td><?php echo $rowCnt; ?></td>
            <?php if($this->Session->read('Auth.User.usertype_id')==Configure::read('ADMIN_USERTYPE'))
            {?>
                <td><?php echo $data['Prison']['code']; ?></td>
            <?php }?>
            <td><?php echo $data['Item']['name']; ?></td>
            <td><?php echo $data['Item']['price']; ?></td>
            <td><?php echo $data['Item']['comment']; ?></td>
<?php
        if(!isset($is_excel)){
?>              
            
            <td>
                <?php echo $this->Form->create('itemEdit',array('url'=>'/earnings/createarticle','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $id));
                ?>
                <?php echo $this->Form->button('<i class="icon icon-edit"></i>', array('label'=>false,'class'=>'btn btn-primary','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); 
                echo $this->Form->end();?> 
                    
                <?php 
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('ADMIN_USERTYPE'))
                {
                    echo $this->Form->button('<i class="icon icon-remove"></i>', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"javascript:deleteItem('$id');"));
                }
                else 
                {
                    if($data['Item']['is_added_by_admin'] == 0)
                    {
                        echo $this->Form->button('<i class="icon icon-remove"></i>', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"javascript:deleteItem('$id');"));
                    }
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
<?php echo $this->Js->writeBuffer();
}else{
?>
    ...
<?php    
}
?>                    