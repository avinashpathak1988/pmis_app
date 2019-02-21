<?php //echo '<pre>'; print_r($datas); exit;
if(is_array($datas) && count($datas)>0){
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
?>
<?php
    if(!isset($is_excel)){
?>
<style type="text/css">
        th, td{border: 1px solid black;}
     </style>
<div class="row-fluid">
    <div class="span5">
        <ul class="pagination" style="margin:0px">
          <?php
              $this->Paginator->options(array(
                  'update'                    => '#listingDiv',
                  'evalScripts'               => true,
                  //'before'                    => '$("#lodding_image").show();',
                  //'complete'                  => '$("#lodding_image").hide();',
                  'url'                       => array(
                                                    'controller'            => 'Prisoners',
                                                    'action'                => 'archivelistAjax',
                                                  )+$searchData
              ));         
              echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
              echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
              echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
              echo $this->Js->writeBuffer();
          ?>
        </ul>
    </div>
    <div class="span7 text-right">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} ')
));
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
<?php

    $exUrl = $this->Html->url(array(
                'controller'            => 'Prisoners',
                'action'                => 'archivelistAjax',
              )+$searchData);
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
<?php } ?>
<table class="table table-bordered data-table table-responsive" width="100%">
    <thead>
        <tr>            
            <th width="10%">SL#</th>
            <th width="15%">
                <?php                 
                echo $this->Paginator->sort('Prisoner.prisoner_no','Prisoner Number',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Prisoners','action' => 'listAjax', 'prisoner_no' => $prisoner_no, 'prisoner_name' => $prisoner_name)));
                ?>
            </th>
            <th width="15%">
                <?php 
                echo $this->Paginator->sort('Prisoner.first_name','Name',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Prisoners','action' => 'listAjax', 'prisoner_no' => $prisoner_no, 'prisoner_name' => $prisoner_name)));
                ?>
            </th>
            <th width="10%">
                <?php 
                echo $this->Paginator->sort('Prisoner.age','Age',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Prisoners','action' => 'listAjax', 'prisoner_no' => $prisoner_no, 'prisoner_name' => $prisoner_name)));
                ?>
            </th>
            <th width="10%">Number Of times<br> in prison</th>
            <th width="10%">Number Of </br>convictions</th>
            <th width="10%">EPD</th>
            <th width="10%">In Prison Status</th>
            <?php
            if(!isset($is_excel)){
            ?> 
                <th width="10%">Action</th>
            <?php
            }
            ?>             
        </tr>
    </thead>
    <tbody>
	
        <?php 
		$style = '';
		if(@$file_type == 'pdf')
		{
			$style ="color: red !imortant;";
		}
		else
		{
			$style="background: red; color: #fff;";
		}
		
        $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
        foreach($datas as $data)
        {  
            $uuid = $data["Prisoner"]["uuid"];
            $prisoner_unique_no = $data["Prisoner"]['prisoner_unique_no'];
            ?>
            <tr class="<?php if($rowCnt == count($datas)) {echo 'lastrow';}?>">
                <td width="10%">
                <?php 
                echo $rowCnt;
                 ?>
                </td>
                <td <?php if($data['Prisoner']['habitual_prisoner'] == 1){ ?> style="<?php echo $style ; ?>"<?php }?> width="15%">
                    <?php 
                    echo $list_prisoner_no = $data['Prisoner']['prisoner_no'];
                    //echo $this->Html->link($list_prisoner_no , array('controller'=>'prisoners', 'action'=>'details', $uuid), array('escape'=>false));
                    ?>  
                </td>
                <td width="15%"><?php echo substr($data['Prisoner']['fullname'], 0, 10); ?></td>                
                <td width="10%"><?php if($data['Prisoner']['age']!='')echo ucwords(h($data['Prisoner']['age']));else echo Configure::read('NA'); ?>&nbsp;</td>    
                <td width="10%"><?php echo $funcall->getPrisonerNumberOfTimesInPrison($data['Prisoner']['prisoner_unique_no']); ?></td>
                <td width="10%"><?php echo $funcall->getPrisonerNumberOfConviction($data['Prisoner']['id']); ?></td>
                <td width="10%">
                    <?php 
                    if($data['Prisoner']['epd'] != '0000-00-00')
                    {
                        echo date('d-m-Y', strtotime($data['Prisoner']['epd']));
                    }?>
                </td>
                
                <td width="10%">
                    <?php 
                    if($data['Prisoner']['present_status'] == 1)
                        echo 'Active';
                    else 
                        echo 'Inactive';
                    ?>
                </td>
                <td width="10%">
                    <?php 
                    echo $this->Html->link('<i class="icon icon-eye-open" ></i>',array(
                        'action'=>'../prisoners/view',
                        $data['Prisoner']['uuid']
                    ),array(
                        'escape'=>false,
                        'class'=>'btn btn-success btn-mini'
                    ));

                    ?>
                  </td>
            </tr>
            <?php $rowCnt++;
        }?>
    </tbody>
</table>

<?php  
echo $this->Js->writeBuffer(); 
//pagination start 
?>

<div class="row">
    <div class="span4">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        'before'                    => '$("#lodding_image").show();',
        'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'prisoners',
            'action'                => 'listAjax',
            'prisoner_no'           => $prisoner_no,
            'prisoner_name'         => $prisoner_name,
            'age_from'              => $age_from,
            'age_to'                => $age_to,
            'epd_from'              => $epd_from,
            'epd_to'                => $epd_to,
            'prisoner_type_id'      => $prisoner_type_id,
            'prisoner_sub_type_id'  => $prisoner_sub_type_id,
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Js->writeBuffer();
?>
        </ul>
    </div>
    <div class="span8 text-right" style="padding-top:30px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
    </div>
</div>    
<?php 
//pagination end  
}else{

    echo Configure::read('NO-RECORD');
}
?>                