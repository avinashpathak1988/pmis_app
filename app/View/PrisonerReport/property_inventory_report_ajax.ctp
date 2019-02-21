<?php
if(is_array($datas) && count($datas)>0){
?>
<?php if(@$file_type == '') { ?>
<div class="row">
    <div class="span5">
        <ul class="pagination">
            
<?php
    $this->Paginator->options(array(
        'update'                        => '#listingDiv',
        'evalScripts'                   => true,
        //'before'                      => '$("#lodding_image").show();',
        //'complete'                    => '$("#lodding_image").hide();',
            'url'                       => array(
            'controller'                => 'PrisonerReport',
            'action'                    => 'propertyInventoryReportAjax',
          
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    
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
   //$exUrl = "indexAjax/prison_id:$prison_id/from_date:$from_date/to_date:$to_date";
   $exUrl = "propertyInventoryReportAjax";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    $urlpdf = $exUrl.'/reqType:PDF';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download PDF")),$urlpdf, array("escape" => false)));
?>
    </div>
</div>
<?php } ?>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
    <thead>
        <tr>
            <th>Sl No#</th>
            <th>Geographical Region</th>
            <th>UPS Region</th>
            <th>UPS District</th>
            <th>Geographical District</th>
            <th>Prison</th>
            <th>Prisoner Number</th>
            <th>Prisoner Name</th>
            <th>Gender</th>

            <th colspan="3">Inventory</th>

        </tr>

        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>

            <th>In-store</th>
            <th>In-Use</th>

        </tr>
    </thead>
    <tbody>
<?php
    //debug($datas);
    $rowcnt = $this->Paginator->counter(array('format' => __('{:start}')));
         foreach($datas as $data){
                    //$convictedMales
            $inUseCount = $funcall->inventoryCount($data['Prisoner']['id'],$fromDate,$toDate,'inUse');
            $inStoreCount = $funcall->inventoryCount($data['Prisoner']['id'],$fromDate,$toDate,'inStore');

        ?>

                    <tr>
                        <td><?php echo $rowcnt; ?></td>
                        <td></td>
                        <td><?php echo isset($data['Prison']['State']['name'])?$data['Prison']['State']['name']:''?></td>
                        <td><?php echo isset($data['Prison']['PrisonDistrict']['name'])?$data['Prison']['PrisonDistrict']['name']:''?></td>
                        <td><?php echo isset($data['Prison']['GeographicalDistrict']['name'])?$data['Prison']['GeographicalDistrict']['name']:''?></td>
                        <td><?php echo isset($data['Prison']['name'])?$data['Prison']['name']:''?></td>
                        <td><?php echo isset($data['Prisoner']['prisoner_no'])?$data['Prisoner']['prisoner_no']:''?></td>
                        <td><?php echo isset($data['Prisoner']['first_name'])?$data['Prisoner']['first_name']:'' . isset($data['Prisoner']['last_name'])?$data['Prisoner']['last_name']:''?></td>
                        <td><?php echo isset($data['Gender']['name'])?$data['Gender']['name']:''?></td>

                        <td><?php echo $inUseCount ?></td>
                        <td><?php echo $inStoreCount?></td>

                    </tr>
              <?php 
                $rowcnt++;

               
            ?>
              
          
        <?php        

        }
        
?>

  
    </tbody>

</table>
<?php
}else{
    echo Configure::read('NO-RECORD'); 
}
echo $this->Js->writeBuffer();
?>