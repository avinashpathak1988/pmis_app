<?php 
//if(is_array($Prisoner) && count($Prisoner)>0){
?>
<div class="row">
    <div class="span5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        //'before'                    => '$("#lodding_image").show();',
        //'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'Report',
            'action'                => 'prisonerVisitorBookAjax',
            'name'             		=> $name,
            'geographical_id'  		=> $geographical_id,
            'state_id'		   		=> $state_id,
            'district_id'  	   		=> $district_id,
            'prison_id'		   		=> $prison_id,
            'geographical_region_id'=> $geographical_region_id,
           

        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    
?>
        </ul>
    </div>
<div class="text-right" style="padding-top:10px;padding-bottom:10px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count}')
));
?>
<?php
    $exUrl = "prisonerVisitorBookAjax/name:$name";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
?>
</div>
<table class="table table-bordered table-striped table-responsive">
	<thead>
		<tr>
			<th>Sl. No.</th>
			<th>Geographical Region</th>
			<th>UPS Region</th>
			<th>UPS District</th>
			<th>Geographical District</th>
			<th>Prison Station</th>
			<th>Date of Visiting</th>
			<th>Name of Visitor</th>
			<th>Visitor ID</th>
			<th>Visitor Type</th>
			<th>Gender</th>
			<th>Reason Of Visit</th>
			<th colspan = 2>Prisoner Visited</th>					
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
			<th></th>
			<th></th>
			<th></th>
			<th>Prisoner No</th>
			<th>Name</th>					
		</tr>
		
	</thead>
	<tbody>
<?php
	//if(is_array($Prisoner) && count($Prisoner)>0){
	  $count = 0;
	 
		foreach($Prisoner as $PrisonerKey=>$PrisonerVal){
		
		
//debug($PrisonerVal);
?>
<?php $count++; ?>
	<tr>

			<td><?php echo $count?></td>
			<td></td>				
			<td><?php echo $funcall->getName($PrisonerVal['Prison']['state_id'],'State','name');?></td>
			<td><?php echo $funcall->getName($PrisonerVal['Prison']['district_id'],'District','name');?></td>
			<td><?php echo $funcall->getName($PrisonerVal['Prison']['geographical_id'],'GeographicalDistrict','name');?></td>	
			<td><?php echo $PrisonerVal['Prison']['name'];?></td>
			<td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($PrisonerVal['Visitor']['date']));  ?></td>

			<td><?php echo $PrisonerVal['Visitor']['name'];?></td>
			<td><?php echo $PrisonerVal['Visitor']['id'];?></td>
			<td><?php echo $PrisonerVal['Visitor']['subcategory'];?></td>
			<td><?php echo $PrisonerVal['Gender']['name'];?></td>
			<td><?php echo $PrisonerVal['Visitor']['reason'];?></td>	
			<td><?php echo $PrisonerVal['Prisoner']['prisoner_no'];?></td>	
			<td><?php echo $PrisonerVal['Prisoner']['first_name'];?></td>
			
		</tr> 
 


<?php 
		}
	
 ?>	
 </tbody>
</table>
<?php
echo $this->Js->writeBuffer();
?>
<?php
/*}else{
	echo $this->Element('norecords',array("msg" => "No Records found."));
}*/
?>
