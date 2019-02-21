<?php
if(is_array($datas) && count($datas)>0){
    if(!isset($is_excel)){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        //'before'                    => '$("#lodding_image").show();',
        //'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'officers',
            'action'                => 'indexAjax',
            'prison_id'             => $prison_id,

        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
   
?>
        </ul>
    </div>
    <div class="col-sm-7 text-right">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count}')
));
?>
<?php
    $exUrl = "indexAjax/prison_id:$prison_id";
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
            <th>
            <?php                 
          echo $this->Paginator->sort('Officer.first_name','First Name',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Officers','action' => 'indexAjax')));
          ?></th>
            <th>
            <?php                 
          echo $this->Paginator->sort('Officer.last_name','Last Name',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Officers','action' => 'indexAjax')));
          ?></th>
            <th>
            <?php                 
          echo $this->Paginator->sort('Officer.force_number','Force Number',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Officers','action' => 'indexAjax')));
          ?></th>
            <th>
            <?php                 
          echo $this->Paginator->sort('Officer.dob','D.O.B',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Officers','action' => 'indexAjax')));
          ?></th>
            <th>
              <?php                 
          echo $this->Paginator->sort('Officer.rank','Rank',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Officers','action' => 'indexAjax')));
          ?>
            </th>
            
<?php
if(!isset($is_excel)){
?> 
            <th>Status</th>
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
?>
        <tr>
            <td><?php echo $rowCnt; ?></td>
           
            <td><?php if($data['Officer']['first_name']!='')echo ucwords(h($data['Officer']['first_name']));else echo Configure::read('NA'); ?>&nbsp;</td>
            
            <td><?php if($data['Officer']['last_name']!='')echo ucwords(h($data['Officer']['last_name']));else echo Configure::read('NA'); ?>&nbsp;</td>
           
            <td><?php if($data['Officer']['force_number']!='')echo ucwords(h($data['Officer']['force_number']));else echo Configure::read('NA'); ?>&nbsp;</td>
                       
             <td><?php echo (isset($data['Officer']['dob']) && $data['Officer']['dob'] != '0000-00-00') ? ucwords(h(date("d-m-Y", strtotime($data['Officer']['dob'])))) :  Configure::read('NA'); ?>&nbsp;</td> 
            <td><?php if($data['Officer']['rank']!='')echo ucwords(h($data['Officer']['rank']));else echo Configure::read('NA'); ?>&nbsp;</td>

           
            
<?php
        if(!isset($is_excel)){
?>              
            <td>
                      <?php
                      if($data['Officer']['is_enable'] == 1){
                        echo $this->Html->link("Click To Disable",array(
                          'controller'=>'officers',
                          'action'=>'disable',
                          $data['Officer']['id']
                        ),array(
                          'escape'=>false,
                          'class'=>'btn btn-primary btn-mini',
                          'onclick'=>"return confirm('Are you sure you want to disable?');"
                        ));
                      }else{
                        echo $this->Html->link("Click To Enable",array(
                          'controller'=>'officers',
                          'action'=>'enable',
                          $data['Officer']['id']
                        ),array(
                          'escape'=>false,
                          'class'=>'btn btn-danger btn-mini',
                          'onclick'=>"return confirm('Are you sure you want to enable?');"
                        ));
                      }
                                 ?>
            </td>
            <td>
               <?php
                      echo $this->Html->link('<i class="icon icon-edit"></i>',array(
                        'action'=>'edit',
                        $data['Officer']['id']
                      ),array(
                          'escape'=>false,
                          'class'=>'btn btn-success btn-mini',
                          'onclick'=>"return confirm('Are you sure you want to edit?');"
                        ));
                       ?>
                                  
                                       <?php

                       echo $this->Html->link('<i class="icon icon-trash"></i>',array(
                           'action'=>'trash',
                           $data['Officer']['id']
                         ),array(
                            'escape'=>false,
                            'class'=>'btn btn-danger btn-mini',
                            'onclick'=>"return confirm('Are you sure you want to delete?');"
                          ));
                       
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
 echo $this->Js->writeBuffer();
}else{
echo Configure::read("NO-RECORD");   
}
?>                    