<?php
if(is_array($prisonerDatas) && count($prisonerDatas)>0){
	//debug($prisonerDatas);
?>
<div class="report-sec-wrapper" style="margin-bottom: 20px;">
	 <table id="districtTable" class="table table-bordered table-striped table-responsive">
    <thead>
    	<tr>
    		<th colspan="9">Gate Pass Report</th>
    	</tr>
        <tr>

            
            <th><?php echo $this->Paginator->sort('Sl no'); ?></th>  
            <th><?php echo $this->Paginator->sort('Prisoner_no'); ?></th>
            <th>
                <?php 
                echo $this->Paginator->sort('Prisoner.fullname',"Prisoner Name",array(
                    'update'                        => '#listingDiv',
                    'evalScripts'                   => true,
                        'url'                       => array(
                            'controller'                => 'Gatepasses',
                            'action'                    => 'gatepassListAjax',
                        )+$searchData
                )); 
                ?>    
            </th>              
            <th>
                <?php 
                echo $this->Paginator->sort('gp_no',"Gate Pass No.",array(
                    'update'                        => '#listingDiv',
                    'evalScripts'                   => true,
                        'url'                       => array(
                            'controller'                => 'Gatepasses',
                            'action'                    => 'gatepassListAjax',
                        )+$searchData
                )); 
                ?>
            </th>                
            <th><?php echo $this->Paginator->sort('Date'); ?></th>
            <th><?php echo $this->Paginator->sort('Gatepass Type'); ?></th>
            <th>Destination</th>
            <th><?php echo $this->Paginator->sort('Out Time'); ?></th>
            <th><?php echo $this->Paginator->sort('In Time'); ?></th>

        </tr>
    </thead>
    <tbody>
		<?php $rowCnt = 1; ?>

    	<?php foreach ($prisonerDatas as $data){
    		 $display_status = Configure::read($data['Gatepass']['status']);
  			 $prisonerDetails = $funcall->getPrisonerDetails($data['Gatepass']['prisoner_id']);
    	 ?>
    	 <tr>
	    	<td><?php echo $rowCnt; ?></td>
            <td><?php echo $prisonerDetails["Prisoner"]["prisoner_no"]?> </td>
            <td><?php echo $prisonerDetails["Prisoner"]["first_name"]." ".$prisonerDetails["Prisoner"]["last_name"]?> </td> 
            <td><?php echo ucwords(h($data['Gatepass']['gp_no'])); ?>&nbsp;</td>            
            <td>
            <?php 
            if($data['Gatepass']['gp_date'] != '0000-00-00'){
                echo ucwords(h(date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($data['Gatepass']['gp_date']))));
            }
            else{
                echo 'N/A';
            }?>
            &nbsp;
            </td> 
            <td><?php echo ucwords(h($data['Gatepass']['gatepass_type'])); ?>&nbsp;</td>
            <!-- <td><?php echo $this->Html->link("Print",array('controller'=>'Gatepasses','action'=>'gatepassPdf',$data['Gatepass']['id']), array("escape" => false,'class'=>'btn btn-warning btn-mini','target'=>"_blank")); ?>&nbsp;</td>  -->   
            <td><?php echo $funcall->getDestinationName($data['Gatepass']['model_name'],$data['Gatepass']['reference_id']) ?></td>        
            <td>
            <?php
            // echo date("Y-m-d H:i:s");
            // debug($this->Session->read('Auth.User.usertype_id')."--".Configure::read('GATEKEEPER_USERTYPE'));
            if(isset($data['Gatepass']['out_time']) && $data['Gatepass']['out_time']=='0000-00-00 00:00:00' && $this->Session->read('Auth.User.usertype_id')==Configure::read('GATEKEEPER_USERTYPE')){
                  
                  if((in_array($data['Gatepass']['gatepass_type'],array('Court Attendance','Transfer')) && strtotime(date("Y-m-d 17:00:00")) < strtotime(date("Y-m-d H:i:s"))) || in_array($data['Gatepass']['gatepass_type'],array('Court Attendance','Transfer')) && strtotime(date("Y-m-d H:i:s")) < strtotime(date("Y-m-d 07:00:00"))){
                    
                  }else{
                    ?>
                    <span id="link_biometric_span_out<?php echo $data['Gatepass']['id']; ?>"></span>
                    <?php 
                    echo $this->Form->button('Get Punch', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-warning btn-mini','id'=>'link_biometric_button_out'.$data['Gatepass']['id'],"onclick"=>"checkData(".$data['Gatepass']['prisoner_id'].",".$data['Gatepass']['id'].",'out')"));

                    ?>&nbsp;
                    <?php
                  }
                
            }else{
                echo (isset($data['Gatepass']['out_time']) && $data['Gatepass']['out_time']=='0000-00-00 00:00:00') ? '' : h(date("d-m-Y h:i A",strtotime($data['Gatepass']['out_time'])));
            }
                ?>
            
            </td>
            <td>
            <?php
            if(isset($data['Gatepass']['in_time']) && $data['Gatepass']['in_time']=='0000-00-00 00:00:00' && $this->Session->read('Auth.User.usertype_id')==Configure::read('GATEKEEPER_USERTYPE')  && $data['Gatepass']['gatepass_status']=='out' && $data['Gatepass']['is_verify']==1 && !in_array($data['Gatepass']['gatepass_type'], array('Discharge','Transfer'))){
                if(!isset($is_excel)){

                    ?>
                    <span id="link_biometric_span_in<?php echo $data['Gatepass']['id']; ?>"></span>
                    <?php 
                    echo $this->Form->button('Get Punch', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-warning btn-mini','id'=>'link_biometric_button_in'.$data['Gatepass']['id'],"onclick"=>"checkData(".$data['Gatepass']['prisoner_id'].",".$data['Gatepass']['id'].",'in')"));

                    ?>&nbsp;
                    <?php

                }
            }else{
                echo (isset($data['Gatepass']['in_time']) && $data['Gatepass']['in_time']=='0000-00-00 00:00:00') ? '' : h(date("d-m-Y h:i A",strtotime($data['Gatepass']['in_time'])));
            	}
            ?>
            </td>
    	</tr>
    	<?php 
    	$rowCnt++;
    } ?>
    </tbody>
</table>
</div>
<?php } ?>
<?php
if(is_array($visitordatas) && count($visitordatas)>0){
?>
<div class="report-sec-wrapper" style="margin-bottom: 20px;">

 <table id="districtTable" class="table table-bordered table-striped table-responsive">
    <thead>
    	<tr>
    		<th colspan="14">Visitor Report</th>
    	</tr>
        <tr>

            <th><?php echo 'Sl no'; ?></th>  
            <th><?php echo 'Visitor Name'; ?></th>                
            <th><?php echo 'Category'; ?></th>                
            <th><?php echo 'Date'; ?></th>

            <th><?php echo 'Reason'; ?></th>
            <th><?php echo 'Prison Name'; ?></th>
            <th><?php echo 'To whom you are meeting'; ?></th>
            <th><?php echo 'Gate keeper Name'; ?></th>
            <th><?php echo 'Time In'; ?></th>
            <th><?php echo 'Time Out'; ?></th>
            <th><?php echo 'Duration'; ?></th>
            <th><?php echo 'Main Gate Time In'; ?></th>
            <th><?php echo 'Main Gate Time Out'; ?></th>
            <th><?php echo 'Main Gate Duration'; ?></th>

        </tr>
    </thead>
    <tbody>
    	<?php $rowCnt = 1; ?>

    	<?php foreach ($visitordatas as $data) {
    	
    	 ?>
    	 <tr>

    	<td><?php echo $rowCnt; ?>&nbsp;</td>
      <td><?php echo $funcall->getVisitorName($data['Visitor']['id']); ?>&nbsp;</td>
      <td><?php echo ucwords(h($data['Visitor']['category'])); ?>&nbsp;</td>
      <td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data['Visitor']['date'])); ?>&nbsp;</td> 
      
      <td><?php echo ucwords(h($data['Visitor']['reason'])); ?>&nbsp;</td>
      <td><?php //echo $data['Visitor']['prisoner_no'];
      echo $funcall->getName($data["Visitor"]["prison_id"],'Prison','name');  ?>&nbsp;</td>
      <td><?php echo ($data['Visitor']['category']=='Visiting Prisoner') ? $funcall->getName($data["Visitor"]["name"],'Prisoner','fullname')."<br>(".$data['Visitor']['prisoner_no'].")": $data['Visitor']['to_whom'];  ?>&nbsp;</td>
      
      <td><?php echo ucwords(h($data['Visitor']['gate_keeper'])); ?>&nbsp;</td>                 
      <td><?php echo $data['Visitor']['time_in']; ?>&nbsp;</td>
      <td>
      
        <?php echo $data['Visitor']['time_out']; ?>
      </td>
      <td><?php 
        if($data['Visitor']['duration'] != ''){
          $duration = $data['Visitor']['duration'];
          $durationArray = explode(':', $duration);
          echo $durationArray[0]." Hr ".":".$durationArray[1]." Min";
        }
       ?></td>
      <td><?php echo $data['Visitor']['main_gate_in_time']; ?></td>
      <td><?php echo $data['Visitor']['main_gate_out_time']; ?></td>
      
       <td><?php
       if($data['Visitor']['main_gate_duration'] != ''){
          $duration = $data['Visitor']['main_gate_duration'];
          $durationArray = explode(':', $duration);
          echo $durationArray[0]." Hr ".":".$durationArray[1]." Min";
        }
       ?></td>
   </tr>
   <?php 
    	$rowCnt++;
    } ?>
    </tbody>
</table>
</div>
<?php } ?>
<?php
if(is_array($staffDatas) && count($staffDatas)>0){
?>
<div class="report-sec-wrapper" style="margin-bottom: 20px;">

 <table id="districtTable" class="table table-bordered table-striped table-responsive">
    <thead>
    	<tr>
    		<th colspan="5">Staff Report</th>
    	</tr>
        <tr>

            <th>Sr No.</th>
            <th>Date</th>
            <th>Force No</th>
            <th>In Time</th>
            <th>Out Time</th>

        </tr>
    </thead>
    <tbody>
    	<?php $rowCnt = 1; ?>

    	<?php foreach ($staffDatas as $data) {
    	
    	 ?>
    	 <tr>

    	<td><?php echo $rowCnt; ?>&nbsp;</td>
      <td><?php echo h(date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data['RecordStaff']['recorded_date']))); ?>&nbsp;</td> 
      <td><?php echo ucwords(h($data['RecordStaff']['force_no'])); ?>&nbsp;</td> 
      <td><?php echo h(date("h:i A", strtotime($data['RecordStaff']['time_in']))); ?>&nbsp;</td> 
      <td>	<?php if($data['RecordStaff']['time_out']=='' && $this->Session->read('Auth.User.usertype_id')!=Configure::read('OFFICERINCHARGE_USERTYPE')){ ?>
               
                    <span id="link_biometric_span_out<?php echo $data['RecordStaff']['id']; ?>"></span>
                    <?php 
                    echo $this->Form->button('Out Time', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-warning btn-mini','id'=>'link_biometric_button_out'.$data['RecordStaff']['id'],"onclick"=>"checkData(".$data['RecordStaff']['id'].")"));

                    ?>&nbsp;
                    <?php
              
            }else{
                echo ($data['RecordStaff']['time_out']!='') ? h(date("h:i A", strtotime($data['RecordStaff']['time_out']))): '';
            }
			?>
	  &nbsp;</td>
</tr>
   <?php 
    	$rowCnt++;
    } ?>

    </tbody>
</table>
</div>
<?php } ?>