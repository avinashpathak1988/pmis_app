
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<?php 
    				$exUrl = "socialisationProgramAjax";
					$urlPrint='receipt/'.$visitor['Visitor']['id'].'/reqType:PRINT'; ?>
					<h5>Visitor Gatepass <?php   echo($this->Html->link($this->Html->image("print.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlPrint, array("escape" => false,'target'=>"_blank")));
?> </h5>
					<div style="float:right;padding-top: 7px;">
						<?php echo $this->Html->link(__('Visitors Record List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
						&nbsp;&nbsp;
					</div>
				</div>
				<div class="widget-content nopadding">
					<div style="width:30%;margin-left: 40%;"><img style="width:100px;" src="/uganda/ugandalogo.png" class="img-responsive" alt=""></div>
					<div class="row-fluid">
						<!-- <?php debug($visitor) ?> -->
						<h5>Gate Pass</h5>
						<div class="span12" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;margin-bottom: 20px;">
						    <div style="width: 100%;
						    margin-left: 112px;;">
						     <div class="scountDiv2" style="margin-bottom: 20px;margin-top:20px;"> 	
						     	<div class="span5" style="width:40%">
						     		<table style="border: 1px solid;margin-bottom: 20px;">
						     		<tbody>
						     			<tr><td style="width:200px;border: 1px solid;">Prison</td><td style="width:200px;border: 1px solid;"><?php echo $visitor['Prison']['name'] ?></td></tr>
						     			<tr><td style="width:200px;border: 1px solid;">Whom to meet</td><td style="width:200px;border: 1px solid;"><?php echo $visitor['Visitor']['to_whom']!=0?$visitor['Visitor']['to_whom']:'N/A' ?></td></tr>	
						     			<tr><td style="width:200px;border: 1px solid;">Prisoner Number</td><td style="width:200px;border: 1px solid;"><?php echo $visitor['Visitor']['prisoner_no'] ?></td></tr>
						     			<tr><td style="width:200px;border: 1px solid;"> Date & Time</td><td style="width:200px;border: 1px solid;"><?php echo $visitor['Visitor']['date'] . ' ' .  $visitor['Visitor']['main_gate_in_time'] ?></td></tr>
						     			<tr><td style="width:200px;border: 1px solid;">Prison</td><td style="width:200px;border: 1px solid;"><?php echo $visitor['Prison']['name'] ?></td></tr>
						     		</tbody>
						     	</table>
						     </div>
						     <div class="span5" style="width:40%">
						     		<table style="border: 1px solid;margin-bottom: 20px;">
						     		<tbody>
						     			<tr><td style="width:200px;border: 1px solid;">Sr. no.</td><td  style="width:200px;border: 1px solid;">Visitor Name</td><td  style="width:200px;border: 1px solid;">Id Type Name</td><td  style="width:200px;border: 1px solid;">Id Number</td></tr>

						     			<?php

						     			$count =1;
						     			 foreach ($visitor['VisitorName'] as $visitorName) {?>
						     				<tr><td style="width:200px;border: 1px solid;"><?php echo $count ?></td><td style="width:200px;border: 1px solid;"><?php echo $visitorName['name'] ?></td>
						     				<td style="width:200px;border: 1px solid;"><?php echo $visitorName['Iddetail']['name'] ?></td>
						     				<td style="width:200px;border: 1px solid;"><?php echo $visitorName['nat_id'] ?></td>
						     		</tr>
						     			<?php $count++; } ?>
						     			
						     		</tbody>
						     	</table>
						     </div>
						     	
            				</div>
            				</div>
            			</div><!--  Gate pass Ends -->
            			<div>
            				
            			</div>
					</div> <!-- row ends -->
					<div class="row-fluid">
						<h5>VIsitor Items</h5>
						<div class="span12" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;">
						    <div style="width: 100%;">
						     <div class="scountDiv2" style="margin-top:20px;margin-bottom: 20px;"> 	
						     <div class="span12" style="width: 100%;">
						     	<table class="table table-bordered" style="border: 1px solid;width: 100%;margin-bottom: 20px;">
						     		<thead>
						     				<tr>
						     					<th style="border: 1px solid;text-align: center;">
						     						Sr No.
						     					</th>
						     					<th style="border: 1px solid;text-align: center;">
						     						Items
						     					</th>
						     					<th style="border: 1px solid;text-align: center;">
						     						Quantity.
						     					</th>
						     					
						     				</tr>
						     		</thead>
						     		<tbody>
						     			<?php  
						     			$count =1;
        									foreach ($visitor['VisitorItem'] as $visitorItemDetail) {
						     			?>
						     				<tr>
						     					<td style="border: 1px solid;text-align: center;"><?php echo $count ?> </td>
						     					<td style="border: 1px solid;text-align: center;"> <?php echo $visitorItemDetail['item']?> </td>
						     					<td style="border: 1px solid;text-align: center;"><?php echo $visitorItemDetail['quantity']?> </td>

						     				</tr>
						     			<?php $count++; } ?>
						     		</tbody>
						     	</table>
						     </div>
						     	
            				</div>
            				</div>
            			</div><!--  Gate pass Ends -->
            			<div>
            				
            			</div>
					</div> <!-- row ends -->

					<div class="row-fluid">
						<div class="span12" style="background:#fff;border-top:1px solid #ddd;border-bottom:1px solid #ddd;box-shadow:0 0 5px #ddd;border-left:5px solid #a03230;margin:10px 0px;width: 100%;">
						    <div style="width: 100%;">
						     <div class="scountDiv2" style="width: 100%;margin-top:20px;margin-bottom: 20px;"> 	
						     	<table  style="width: 100%;margin-bottom: 20px;">
						     		<tbody>
						     			<tr>
						     				<td style="width:100px;margin-left:10px;"><b>Disclaimer:</b></td><td style="width:40%">Adhere the Prison Rules & Regulations</td>
						     			<td style="text-align: right;">signature</td><td>...........................................</td>
						     			</tr>
						     			<tr>
						     				<td style="width:100px;"></td><td style="width:40%"></td>
						     				<td></td><td><?php echo  $visitor['Visitor']['gate_keeper']?></td>
						     			</tr>
						     			
						     		</tbody>
						     	</table>
						     
            				</div>
            				</div>
            			</div><!--  Gate pass Ends -->
            			<div>
            				
            			</div>
					</div> <!-- row ends -->

				</div>
			</div>
		</div>
	</div>
</div>
