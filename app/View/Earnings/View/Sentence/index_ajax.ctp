<?php //debug($datas);
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
        //'before'                  => '$("#lodding_image").show();',
        //'complete'                => '$("#lodding_image").hide();',
            'url'                       => array(
            'controller'            => 'SentenceController',
            'action'                => 'indexAjax'
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Js->writeBuffer();
?>
        </ul>
    </div>
    <div class="col-sm-7 text-right">
      <?php
      echo $this->Paginator->counter(array(
          'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
      ));
      ?>
      <?php
        $exUrl = "indexAjax";
        $urlExcel = $exUrl.'/reqType:XLS';
        $urlDoc = $exUrl.'/reqType:DOC';
        echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
        echo '&nbsp;&nbsp;';
        echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
      ?>
    </div>
</div>
<?php
    }
?>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>
      <th><?php echo $this->Paginator->sort('Sl no'); ?></th> 
     <!--  <th>Sentence No</th> -->
      <th>Date Of Conviction</th>
      <th>Sentence Of</th>
      <th>Sentence Length</th>
      <?php
      if(!isset($is_excel)){
      ?> 
        <th>Action</th>
       
      <?php }?>
    </tr>
  </thead>
<tbody>
<?php
$consecutive_array = '';
$concurrent_array  = '';
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
	// debug($data);
?>
    <tr>
      <td><?php echo $rowCnt; ?>&nbsp;</td>
      <!-- <td><?php //echo $data['PrisonerSentence']['sentence_no']; ?>&nbsp;</td> -->
      <td>
        <?php 
        if(isset($data['PrisonerSentence']['date_of_conviction']) && !empty($data['PrisonerSentence']['date_of_conviction']) && ($data['PrisonerSentence']['date_of_conviction'] != '0000-00-00'))
        {
          echo date('d-m-Y', strtotime($data['PrisonerSentence']['date_of_conviction']));
        }
        ?>
      </td>
      <td><?php echo $funcall->getName($data['PrisonerSentence']['sentence_of'],'SentenceOf', 'name'); ?></td>
      <td>
        <?php $sentence_count = '';
        if($data['PrisonerSentence']['years'] > 0)
        {
          $sentence_count .= $data['PrisonerSentence']['years'].' years ';
        }
        if($data['PrisonerSentence']['months'] > 0)
        {
          $sentence_count .= $data['PrisonerSentence']['months'].' months ';
        }
        if($data['PrisonerSentence']['days'] > 0)
        {
          $sentence_count .= $data['PrisonerSentence']['days'].' days ';
        }
        echo $sentence_count .= $funcall->getName($data['PrisonerSentence']['sentence_type'],'SentenceType', 'name')." "
        ?>
      </td>
      <?php if(!isset($is_excel))
      {
        $viewDetail = '<b>LPD: </b>'.date('d-m-Y', strtotime($data['PrisonerSentence']['lpd'])).'<hr>';
        $remission_val = (isset($data['PrisonerSentence']['remission']) && $data['Prisoner']['remission']!='') ? json_decode($data['PrisonerSentence']['remission']) : '';
        $remission_view = 'N/A';    
        $remission = array(); 
        if(isset($remission_val) && !empty($remission_val)){
            foreach ($remission_val as $key => $value) {
                if($key == 'days'){
                    $remission[2] = $value." ".$key;
                }
                if($key == 'years'){
                    $remission[0] = $value." ".$key;
                }
                if($key == 'months'){
                    $remission[1] = $value." ".$key;
                }                        
            }
            ksort($remission);
            $remission_view = implode(", ", $remission); 
        } 
        $viewDetail .= '<b>Remission: </b>'.$remission_view.'<hr>';
        $viewDetail .= '<b>EPD: </b>'.date('d-m-Y', strtotime($data['PrisonerSentence']['epd'])).'<hr>';
        ?>
        <td>
          <a href="javaScript:void(0);" class="pop btn btn-success" pageTitle="Senetence Details" pageBody="<?php echo $viewDetail;?>">
              <i class="icon-eye-open"></i>
          </a>

          <?php 
          		$appeldata = $funcall->checkAppealData($data['PrisonerSentence']['case_id'],$data['PrisonerSentence']['offence_id'] );
          		// debug($appeldata);
          		if ($appeldata>0) {

           ?>


           <button type="button" class="btn btn-info btn-mini" data-toggle="modal" data-target="#myModal<?php echo $data['PrisonerSentence']['id'] ?>">Appeal Details</button>

              <!-- Modal -->
              <div id="myModal<?php echo $data['PrisonerSentence']['id'] ?>" class="modal fade" role="dialog">
                <div class="modal-dialog">

                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Appeal Deatils</h4>
                    </div>
                    <div class="modal-body">
                    	<?php $senetence = $funcall->getPrisonerSentence($data['PrisonerSentence']['prisoner_id'], $data['PrisonerSentence']['case_id'],$data['PrisonerSentence']['offence_id']);
                    	  //debug($senetence);
                    	 $k =1;


                    	foreach ($senetence as $key => $value) {
                    		if($k ==1){
                    		?>
                    		
                      <table class="table table-bordered data-table table-responsive">
                          <tbody>
                           
                            	<tr>
                            		<td width="25%"><strong>File No </strong>: <?php echo $funcall->getName($value['PrisonerSentenceAppeal']['case_file_id'],"PrisonerCaseFile", "file_no");?></td>
                            		<td width="25%"><strong> Count No </strong>: <?php echo $funcall->getName($value['PrisonerSentenceAppeal']['offence_id'], "PrisonerOffence", "offence_no");?></td>
                            		
                            	</tr>
                              
                              <tr>
                              	<td><strong> Type Of Appeal </strong>: <?php echo $value['PrisonerSentenceAppeal']['type_of_appeallant'];?></td>
                              	
                              	<td><strong>Court Label </strong>: <?php echo $funcall->getName($value['PrisonerSentenceAppeal']['courtlevel_id'], "Courtlevel", "name");?></td>
                              	
                              	
                              </tr>
                              <tr>
                              	<td><strong> Court Name</strong>: <?php echo $funcall->getName($value['PrisonerSentenceAppeal']['court_id'], "Court", "name");?></td>
                              	
                              	<td><strong> Appeal Submission </strong>: <?php
                              	if ($value['PrisonerSentenceAppeal']['appeal_date']!=0000-00-00) {
                              		echo $value['PrisonerSentenceAppeal']['appeal_date'];
                              	}else{
                              		echo Configure::read('NA');
                              	}

                              	 ?></td>
                              
                              
                              </tr>
                          </tbody>
                      </table>
                      <?php }if($k ==1){?>
                      <table style="margin-top: 20px" class="table table-bordered data-table table-responsive">
                        <tbody>
                          <tr>
                            <td width="25%"><span style="font-weight:bold">Appeal Status</span></td>
                            <!-- <td width="25%" style="">Appeal</td> -->
                            <td width="25%"><span style="font-weight:bold">Date</span></td>
                          </tr>
                         <?php }?> 
                          <tr>
                            <td width="25%">
                              <?php if($value['PrisonerSentenceAppeal']['appeal_status'] != '')echo $value['PrisonerSentenceAppeal']['appeal_status'];
                              else echo 'Completed';?>
                            </td>
                            <td width="25%"><?php 
                            if($value['PrisonerSentenceAppeal']['created']!='') {
                                echo date('d-m-Y', strtotime($value['PrisonerSentenceAppeal']['created']));
                             } else{
                              echo Configure::read('NA');}
                            ?></td>

                          
                            
                          </tr>
                          
                          
                          <?php if($k ==count($senetence)){?>  
                        </tbody>
                      </table>
                      <?php }
                      $k++;

                      } ?>
                      <?php //$senetence = $funcall->getPrisonerSentence($data['PrisonerSentence']['prisoner_id']);

                       
                    		
                    		?>
                      
                      
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>

                </div>
              </div>
              <?php } ?>

          
               
          
        </td>
       
      <?php }?>
    </tr>
<?php
$rowCnt++;
}
?>
  </tbody>
</table>
<?php
}
?>    