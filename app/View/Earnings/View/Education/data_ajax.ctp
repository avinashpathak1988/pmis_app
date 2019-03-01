<?php
 if(is_array($datas) && count($datas)>0){
?>
 <div class="span5">
        <ul class="pagination">
          <?php
              $this->Paginator->options(array(
                  'update'                    => '#informalEducationList',
                  'evalScripts'               => true,
                  //'before'                    => '$("#lodding_image").show();',
                  //'complete'                  => '$("#lodding_image").hide();',
                  'url'                       => array(
                                                    'controller'            => 'Education',
                                                    'action'                => 'dataAjax',
                                                    
                                                  )
              ));         
              echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
              echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
              echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
              echo $this->Js->writeBuffer();
          ?>
        </ul>
    </div>
    <div class="span7 text-right" style="padding-top:20px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
//     $exUrl = "courtsscheduleGatepassListAjax/prisoner_id:$prisoner_id/status:$status";
//     $urlExcel = $exUrl.'/reqType:XLS';
//     $urlDoc = $exUrl.'/reqType:DOC';
//     // $urlPDF = $exUrl.'/reqType:PDF';
//     echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
//     echo '&nbsp;&nbsp;';
//     echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
// echo '&nbsp;&nbsp;';

    //  $execPath = $_SERVER['SERVER_NAME']."/uganda/courtattendances/courtsscheduleListAjaxpdf/prisoner_id:$prisoner_id/status:$status";
    // $note_name = 'courtattendance.pdf';
    // $note_path = WWW_ROOT.DS.'printpdf/'.$note_name;
    // $html2Pdfcmd = "xvfb-run -a wkhtmltopdf $execPath $note_path";
    // shell_exec($html2Pdfcmd);
    // chmod($note_path, 0777);
    // $pathtodownload="../printpdf/".$note_name;
    // echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$pathtodownload, array("target"=>"_blank","escape" => false)));
?>
    </div>
<div class="widget-box">
                                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                                     <h5> Informal Counselling List</h5>
                                          <div style="float:right;padding-top: 7px;">
                                             &nbsp;&nbsp;
                                         </div>
                                </div>
                            </div>
<table class="table table-bordered data-table table-responsive" id="cashidtbl">
    <thead>
    	<tr>
	    	<th>Sr No.</th>
	    	<th>Counselor</th>
	    	<th>Prisoner No.</th>
	    	<th>Prisoner Name</th>
	    	<th>Date of Counseling</th>
	    	<th>Opinion By Prisoner</th>
	    	<th>Theme</th>
	    	<th>Start Date</th>
	    	<th>End Date</th>
        <th>Reception Board</th>

    	</tr>



    </thead>

    <tbody>
        <?php 
        $count =1;
        foreach($datas as $data){
         // echo $data['Prisoner']['prisoner_type_id'];
        ?> 
    	<tr>
            <td><?php echo $count; ?></td>
    		<td><?php echo $data['Councellor']['name']; ?></td>
            <td><?php echo $data['Prisoner']['prisoner_no']; ?></td>
            <td><?php echo $data['Prisoner']['first_name'] . ' ' . $data['Prisoner']['last_name']  ; ?></td>

            <td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data['InformalCouncelling']['date_of_councelling']));?></td>
            <td><?php echo $data['InformalCouncelling']['opinion_by_prisoner']; ?></td>
            <td><?php echo $data['Theme']['name']; ?></td>
            <td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data['InformalCouncelling']['start_date'])); ?></td>
            <td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data['InformalCouncelling']['end_date'])); ?></td>
            <td>
                <?php
                if($data['Prisoner']['prisoner_type_id'] ==2){
                  echo $this->Html->link("View",array(
                    'controller'=>'ReceiptionBoard',
                    'action'=>'add',
                    $data['Prisoner']['id']
                  ),array(
                    'class'=>'btn btn-primary btn-mini',
                  )); 
                }
                  ?>
        </td>
    	</tr>
        <?php 
         $count ++;
    } ?>
    </tbody>
</table> 

<?php } ?>