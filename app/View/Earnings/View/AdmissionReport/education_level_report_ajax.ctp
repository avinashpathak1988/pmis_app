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
            'controller'                => 'AdmissionReport',
            'action'                    => 'educationLevelReportAjax',
          
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
   $exUrl = "educationLevelReportAjax";
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
            <th>Station Name</th>
            <th>Particulars</th>
            <th colspan="3">Convicts</th>
            <th colspan="3">Remands</th>
            <th colspan="3">Debtor</th>
            <th>G/Total</th>

        </tr>

        <tr>
            <th> </th>
            <th> </th>
            <th> </th>
            <th> </th>
            <th> </th>
            <th></th>
            <th></th>
            <th>Male</th>
            <th>Female</th>
            <th>Total</th>

            <th>Male</th>
            <th>Female</th>
            <th>Total</th>


            <th>Male</th>
            <th>Female</th>
            <th>Total</th>

            <th></th>

        </tr>
    </thead>
    <tbody>
<?php
    //debug($datas);
    $rowcnt = $this->Paginator->counter(array('format' => __('{:start}')));
    if($showOnly != 0){
        echo "djkgdghdg";
         foreach($datas as $data){
                    //$convictedMales

                        $convictedCounts = $funcall->convictedCount($data['Prison']['id'],$showOnly,$fromDate,$toDate); 
                        $remandCounts = $funcall->remandCount($data['Prison']['id'],$showOnly,$fromDate,$toDate);
                        $debtorCounts = $funcall->debtorCount($data['Prison']['id'],$showOnly,$fromDate,$toDate); ?>

                    <tr>
                        <td><?php echo $rowcnt; ?></td>
                        <td></td>
                         <td><?php echo $funcall->getName($data['Prison']['state_id'],"State","name");?></td>
                        <td><?php echo $funcall->getName($data['Prison']['district_id'],"District","name");?></td>
                        <td><?php echo $funcall->getName($data['Prison']['geographical_id'],"GeographicalDistrict","name");?></td>
                        <td><?php echo isset($data['Prison']['name'])?$data['Prison']['name']:''?></td>
                        <td><?php echo $levelOfEducation[$showOnly] ?></td>

                        <td><?php echo $convictedCounts[0]?></td>
                        <td><?php echo $convictedCounts[1]?></td>
                        <td><?php echo $convictedCounts[0] + $convictedCounts[1]?></td>



                        <td><?php echo $remandCounts[0]?></td>
                        <td><?php echo $remandCounts[1]?></td>
                        <td><?php echo $remandCounts[0] + $remandCounts[1]?></td>

                        <td><?php echo $debtorCounts[0]?></td>
                        <td><?php echo $debtorCounts[1]?></td>
                        <td><?php echo $debtorCounts[0] + $debtorCounts[1]?></td>
                        <td><?php echo $convictedCounts[0] + $convictedCounts[1] + $remandCounts[0] + $remandCounts[1] + $debtorCounts[0] + $debtorCounts[1] ;?></td>


                    </tr>
              <?php 
                $rowcnt++;

               
            ?>
              
          
        <?php        

        }
    }else{
          foreach($datas as $data){
                    //$convictedMales
                        
                foreach ($levelOfEducation as $key => $value) {


                        $convictedCounts = $funcall->convictedCount($data['Prison']['id'],$key,$fromDate,$toDate); 
                        $remandCounts = $funcall->remandCount($data['Prison']['id'],$key,$fromDate,$toDate);
                        $debtorCounts = $funcall->debtorCount($data['Prison']['id'],$key,$fromDate,$toDate); ?>

                    <tr>
                        <td><?php echo $rowcnt; ?></td>
                        <td></td>
                        <td><?php echo isset($data['State']['name'])?$data['State']['name']:''?></td>
                        <td><?php echo isset($data['PrisonDistrict']['name'])?$data['PrisonDistrict']['name']:''?></td>
                        <td><?php echo isset($data['GeographicalDistrict']['name'])?$data['GeographicalDistrict']['name']:''?></td>
                        <td><?php echo isset($data['Prison']['name'])?$data['Prison']['name']:''?></td>
                        <td><?php echo $value ?></td>

                        <td><?php echo $convictedCounts[0]?></td>
                        <td><?php echo $convictedCounts[1]?></td>
                        <td><?php echo $convictedCounts[0] + $convictedCounts[1]?></td>



                        <td><?php echo $remandCounts[0]?></td>
                        <td><?php echo $remandCounts[1]?></td>
                        <td><?php echo $remandCounts[0] + $remandCounts[1]?></td>

                        <td><?php echo $debtorCounts[0]?></td>
                        <td><?php echo $debtorCounts[1]?></td>
                        <td><?php echo $debtorCounts[0] + $debtorCounts[1]?></td>
                        <td><?php echo $convictedCounts[0] + $convictedCounts[1] + $remandCounts[0] + $remandCounts[1] + $debtorCounts[0] + $debtorCounts[1] ;?></td>


                    </tr>
              <?php 
                $rowcnt++;

               }
            ?>
              
          
        <?php        

        }
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
