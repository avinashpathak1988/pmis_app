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
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        'url'                       => array(
            'controller'            => 'PPcashesController',
            'action'                => 'indexAjax',
            'prison_id'             => $prison_id,
      
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Js->writeBuffer();
?>
        </ul>
    </div>
     
</div>

<table id="districtTable" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>
      <th>Sl no</th>                
      <th>Name</th>
    </tr>
  </thead>
<tbody>
<?php
$count = 1;
//$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
?>
    <tr>

      <td><?php echo $count; ?>&nbsp;</td>      
      <td><?php if($data['PPCash']['name']!='')echo ucwords(h($data['PPCash']['name']));else echo Configure::read('NA'); ?>&nbsp;</td>
      <?php
      $count ++;
    }
      ?>
    </tr>
<?php
?>
  </tbody>
</table>
<?php
}else{
?>
<span style="color:red;font-weight:bold;">No Record Found!!</span>
<?php    
}
?>    
