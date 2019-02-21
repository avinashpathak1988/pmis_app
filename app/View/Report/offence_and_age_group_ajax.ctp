<?php
if(is_array($datas) && count($datas)>0){
?>
<div class="text-right" style="padding-top:10px;padding-bottom:10px;">
<?php
    $exUrl = "offenceAndAgeGroupAjax/offence_id:$offence_id/from_date:$from_date/to_date:$to_date";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
?>
</div>
<table id="OffenceTable" class="table table-bordered table-striped table-responsive">
	<thead>
		<tr>
			<th>Sl. No.</th>
			<th>offence</th>
			<th colspan="2">Total Admission</th>
			<th colspan="6">Sentenced To Imprisonment</th>
			<th colspan="16">Age Group</th>


		</tr>
		<tr>
			<th></th><th></th>
			<th colspan="2"></th>
			<th colspan="2">First Time</th>
			<th colspan="2">Second Time</th>
			<th colspan="2">Third Time</th>
			<th colspan="2">18-20</th>
			<th colspan="2">21-25</th>
			<th colspan="2">26-30</th>
			<th colspan="2">31-35</th>
			<th colspan="2">36-40</th>
			<th colspan="2">41-45</th>
			<th colspan="2">46-50</th>
			<th colspan="2">over 50</th>
		</tr>
		<tr>
			<th><th>
			<th>Male</th>
			<th>Female</th>
			<th>Male</th>
			<th>Female</th>
			<th>Male</th>
			<th>Female</th>
			<th>Male</th>
			<th>Female</th>
			<th>Male</th>
			<th>Female</th>
			<th>Male</th>
			<th>Female</th>
			<th>Male</th>
			<th>Female</th>
			<th>Male</th>
			<th>Female</th>
			<th>Male</th>
			<th>Female</th>
			<th>Male</th>
			<th>Female</th>
			<th>Male</th>
			<th>Female</th>
			<th>Male</th>
			<th>Female</th>
			
		</tr>
	</thead>
	<tbody>
<?php
	if(is_array($offenceList) && count($offenceList)>0){
	  $count = 0;
		foreach($offenceList as $offenceListKey=>$offenceListVal){
?>
<?php $count++; ?>
	<tr>
			<td><?php echo $count?></td>
			<td><?php echo $offenceListVal?></td>
			<?php 
			$totalMales = 0;
			$totalFemales = 0;
			$age18to20=0;
			$age21to25=0;
			$age26to30=0;
			$age31to35=0;
			$age36to40=0;
			$age41to45=0;
			$age46to50=0;
			$ageAbove50=0;
			$forFirstTime=0;
			$forSecondtime = 0;
			$forThirdTime =0 ;
			$age18to20F=0;
			$age21to25F=0;
			$age26to30F=0;
			$age31to35F=0;
			$age36to40F=0;
			$age41to45F=0;
			$age46to50F=0;
			$ageAbove50F=0;
			$forFirstTimeF=0;
			$forSecondtimeF = 0;
			$forThirdTimeF =0 ;
		foreach($datas as $prisonArrKey=>$prisonArrVal) {
				/*			CHECK OFFENCE HERE*/
				$Offence_number = $prisonArrVal['Sentence']['offence'];
				if($Offence_number == $offenceListKey ){


				
				
				$uuid  = $prisonArrVal['Prisoner']['uuid'];
				/*counting times in prison for same offence*/
				$noOfTimes = 0;
				foreach($totalPrisonerData as $prisonArrKey2=>$prisonArrVal2){ 
					$uuidnew  = $prisonArrVal2['Prisoner']['uuid'];
					if($uuidnew == $uuid){
						$noOfTimes++;
					}
				}
				
				if($prisonArrVal['Gender']['name'] == 'Male'){
					$totalMales++;

					if($noOfTimes == 1){
					$forFirstTime++;
						}else if($noOfTimes == 2){
							$forSecondtime++;
						}else if($noOfTimes == 3){
							$forThirdTime++;
						}

				}else{
					$totalFemales++;
					if($noOfTimes == 1){
							$forFirstTimeF++;
						}else if($noOfTimes == 2){
							$forSecondtimeF++;
						}else if($noOfTimes == 3){
							$forThirdTimeF++;
						}

				}


				$dob  = $prisonArrVal['Prisoner']['date_of_birth'];
				
			    $diff = (date('Y') - date('Y',strtotime($dob)));
			    	if($diff >= 18 ){
			    		if($diff <=20){
			    			if($prisonArrVal['Gender']['name'] == 'Male'){
			    				$age18to20++;
			    			}else{
			    				$age18to20F++;
			    			}
			    			
			    		}else if( $diff <= 25){
			    			if($prisonArrVal['Gender']['name'] == 'Male'){
			    				$age21to25++;
			    			}else{
			    				$age21to25F++;
			    			}
			    			
			    		}else if($diff <= 30){
			    			if($prisonArrVal['Gender']['name'] == 'Male'){
			    				$age26to30++;
			    			}else{
			    				$age26to30F++;
			    			}
			    			
			    		}
			    		else if($diff <= 35){
			    			if($prisonArrVal['Gender']['name'] == 'Male'){
			    				$age31to35++;
			    			}else{
			    				$age31to35F++;
			    			}
			    			
			    		}
			    		else if($diff <= 40){
			    			if($prisonArrVal['Gender']['name'] == 'Male'){
			    				$age36to40++;
			    			}else{
			    				$age36to40F++;
			    			}
			    			
			    		}
			    		else if($diff <= 45){
			    			if($prisonArrVal['Gender']['name'] == 'Male'){
			    				$age41to45++;
			    			}else{
			    				$age41to45F++;
			    			}
			    			
			    		}else if($diff <= 50){
			    			if($prisonArrVal['Gender']['name'] == 'Male'){
			    				$age46to50++;
			    			}else{
			    				$age46to50F++;
			    			}
			    			
			    		}else{
			    			if($prisonArrVal['Gender']['name'] == 'Male'){
			    				$ageAbove50++;
			    			}else{
			    				$ageAbove50F++;
			    			}
			    			
			    		}
			    	}
				 } 
				}
				 ?>
			<td><?php echo $totalMales; ?></td>	
			<td><?php echo $totalFemales; ?></td>
			<!-- for Sentenced To Imprisonment -->
			<td><?echo $forFirstTime ; ?></td>
		    <td><?echo $forFirstTimeF ; ?></td>
			<td><?echo $forSecondtime ; ?></td>
			<td><?echo $forSecondtimeF ; ?></td>
		
			<td><?echo $forThirdTime ; ?> </td>	
			<td><?echo $forThirdTimeF ; ?> </td>
			
			<!-- for age group -->

				
			<td><?php echo $age18to20; ?></td><td><?php echo $age18to20F; ?></td>
			
			<td><?php echo $age21to25; ?></td> <td><?php echo $age21to25F; ?></td> 
			
			<td><?php echo $age26to30; ?></td> <td><?php echo $age26to30F; ?></td>
			
			<td><?php echo $age31to35; ?></td> <td><?php echo $age31to35F; ?></td>
			
			<td><?php echo $age36to40; ?></td> <td><?php echo $age36to40F; ?></td>
			
			<td><?php echo $age41to45; ?></td> <td><?php echo $age41to45F; ?></td> 
			
			<td><?php echo $age46to50; ?></td><td><?php echo $age46to50F; ?></td>
			 
			<td><?php echo $ageAbove50; ?></td> <td><?php echo $ageAbove50F; ?></td> 
			

		</tr>



<?php 
		}
	}
 ?>	
 </tbody>
</table>
<?php
}else{
	echo $this->Element('norecords',array("msg" => "No Records found."));
}
?>
