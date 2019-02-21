<?php
  App::uses('Appard', 'Model');
  $this->Appard=new Appard();
 ?>
<?php
if(is_array($datas) && count($datas)>0){
?>
<div class="text-right" style="padding-top:10px;padding-bottom:10px;">
<?php
    $exUrl = "whereaboutsAjax/prison_id:$prison_id/prisoner_name:$prisoner_name";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
?>
</div>

<table id="districtTable" class="table table-bordered table-striped table-responsive">
	<thead>
        <tr>
            <th>Prisoner No</th>
            <th>Prisoner Name</th>
            <th>Station Flow</th>     
            
        </tr>
	</thead>
	<tbody>
<?php
	foreach($datas as $data){
        $prisonerData = $funcall->Prisoner->find("all", array(
            "recursive"     => -1,
            "conditions"    => array(
                "Prisoner.prisoner_unique_no"=> $data['Prisoner']['prisoner_unique_no'],
            ),
            "order"         => array(
                "Prisoner.id"   => "desc",
            ),
        ));
?>
    <tr>
        
        <td><?php if($data['Prisoner']['prisoner_no']!='')echo ucwords(h($data['Prisoner']['prisoner_no']));else echo Configure::read('NA'); ?>&nbsp;</td>
         <td><?php if($data['Prisoner']['fullname']!='')echo ucwords(h($data['Prisoner']['fullname']));else echo Configure::read('NA'); ?>&nbsp;</td>
       

        <td><?php 
            $array = array();
            if(isset($prisonerData) && count($prisonerData)>0){
                foreach ($prisonerData as $prikey => $privalue) {
                    $array[] = $funcall->getName($privalue['Prisoner']['prison_id'],"Prison","name");
                }
                echo implode(" -> ", $array);
            }
            ?></td>
       
    </tr>
<?php
	}
?>
	</tbody>

</table>
<?php
}else{
	echo Configure::read("NO-RECORD");
}
?>
