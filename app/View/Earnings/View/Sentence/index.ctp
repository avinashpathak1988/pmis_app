<style>
.nodisplay{display:none;}
</style>
<?php 
if(isset($data['Prisoner']) && !empty($data['Prisoner']))
{
?>
<div class="container-fluid">
    <div class="row-fluid">
        <div id="commonheader"></div>
        <div class="span12" style="margin-left:0">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                <h5>Sentence Details</h5>
                </div>
                <div class="prisoner-box">
                    <div class="span2">
                        <div class="text-left">
                            <?php 
                            if($data['Prisoner']['photo'] != '')
                            {
                                $filename = 'files/prisnors/'.$data["Prisoner"]["photo"];
                                $is_image = '';
                                if(file_exists($filename))
                                {
                                    $is_image = getimagesize($filename);
                                }
                                if(file_exists($filename) && is_array($is_image))
                                { 
                                    $image = $this->Html->image('../files/prisnors/'.$data["Prisoner"]["photo"], array('escape'=>false, 'class'=>'img', 'alt'=>''));
                                }
                                else if($data["Prisoner"]["gender_id"] == Configure::read('GENDER_FEMALE')){
                                   $image = $this->Html->image('../files/prisnors/female.jpg', array('escape'=>false, 'class'=>'img', 'alt'=>''));
                                }else{
                                    $image = $this->Html->image('../files/prisnors/male.jpg', array('escape'=>false, 'class'=>'img', 'alt'=>''));
                                }   
                            }else if($data["Prisoner"]["gender_id"] == Configure::read('GENDER_FEMALE')){
                                $image = $this->Html->image('female.jpg', array('escape'=>false, 'class'=>'img', 'alt'=>''));
                            }else{
                                $image = $this->Html->image('male.jpg', array('escape'=>false, 'class'=>'img', 'alt'=>''));
                            }
                            echo $this->Html->link($image, array('controller'=>'prisoners', 'action'=>'details', $data["Prisoner"]["uuid"]), array('escape'=>false));   ?>
                        </div>
                    </div>
                    <div class="span5">
                        <!-- <h4>
                            <?php echo $data["Prisoner"]["prisoner_no"]?>
                        </h4>
                        <h5>
                            DOC: 
                            <span style="font-weight: normal;">
                                <?php 
                                  if($data['Prisoner']['doc'] != '0000-00-00')
                                    echo date('d-m-Y', strtotime($data['Prisoner']['doc']));
                                  else 
                                    echo 'N/A'; 
                                ?>
                            </span>
                        </h5> -->
                        <h5>
                            Sentence Length: 
                            <span style="font-weight: normal;">
                            <?php 
                                $slengthData = (isset($data['Prisoner']['sentence_length']) && $data['Prisoner']['sentence_length']!='') ? json_decode($data['Prisoner']['sentence_length']) : '';
                                $slength = array();
                                //echo '<pre>'; print_r($lpd); exit;
                                if(isset($slengthData) && !empty($slengthData)){
                                    foreach ($slengthData as $key => $value) {
                                        if($key == 'days'){
                                            if($value > 0)
                                                $slength[2] = $value." ".$key;
                                        }
                                        if($key == 'years'){
                                            if($value > 0)
                                                $slength[0] = $value." ".$key;
                                        }
                                        if($key == 'months'){
                                            if($value > 0)
                                                $slength[1] = $value." ".$key;
                                        }                        
                                    }
                                    ksort($slength);
                                    echo implode(", ", $slength); 
                                } 
                                else {
                                    echo 'N/A';
                                }
                            ?>
                            </span>
                        </h5>
                        <?php if(isset($data['Prisoner']['tpi']) && !empty($data['Prisoner']['tpi']))
                        {?>
                            <h5>
                                TPI: 
                                <span style="font-weight: normal;">
                                <?php 
                                    $tpiData = (isset($data['Prisoner']['tpi']) && $data['Prisoner']['tpi']!='') ? json_decode($data['Prisoner']['tpi']) : '';
                                    $tpilength = array();
                                    //echo '<pre>'; print_r($lpd); exit;
                                    if(isset($tpiData) && !empty($tpiData)){
                                        foreach ($tpiData as $tpikey => $tpivalue) {
                                            if($tpikey == 'days'){
                                                if($tpivalue > 0)
                                                    $tpilength[2] = $tpivalue." ".$tpikey;
                                            }
                                            if($tpikey == 'years'){
                                                if($tpivalue > 0)
                                                    $tpilength[0] = $tpivalue." ".$tpikey;
                                            }
                                            if($tpikey == 'months'){
                                                if($tpilength > 0)
                                                    $tpilength[1] = $tpivalue." ".$tpikey;
                                            }                        
                                        }
                                        ksort($tpilength);
                                        echo implode(", ", $tpilength); 
                                    } 
                                    else {
                                        echo 'N/A';
                                    }
                                ?>
                                </span>
                            </h5>
                        <?php }?>
                        
                        <h5>
                            LPD: 
                            <span style="font-weight: normal;">
                                <?php 
                                  if($data['Prisoner']['lpd'] != '0000-00-00')
                                    echo date('d-m-Y', strtotime($data['Prisoner']['lpd']));
                                  else 
                                    echo 'N/A'; 
                                ?>
                            </span>
                        </h5>
                        <h5>
                            Remission: 
                            <span style="font-weight: normal;">
                                <?php 
                                  $lpd = (isset($data['Prisoner']['remission']) && $data['Prisoner']['remission']!='') ? json_decode($data['Prisoner']['remission']) : '';
            
                                $remission = array(); 
                                if(isset($lpd) && !empty($lpd)){
                                    foreach ($lpd as $key => $value) {
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
                                    echo implode(", ", $remission); 
                                }  
                                else 
                                {
                                  echo 'N/A';
                                } 
                                ?>
                            </span>
                        </h5>
                        <?php if($data['Prisoner']['tal'] > 0)
                        {?>
                        <h5>
                            TAL: 
                            <span style="font-weight: normal;">
                                <?php 
                                if($data['Prisoner']['tal'] != '')
                                {
                                    $tal_val = $data['Prisoner']['tal'];
                                    echo $tal_val.' days';
                                }  
                                else 
                                {
                                  echo 'N/A';
                                } 
                                ?>
                            </span>
                        </h5>
                    <?php }?>
                    <?php if($data['Prisoner']['fine_amount'] > 0)
                        {?>
                        <h5>
                            Paid Amount: 
                            <span style="font-weight: normal;">
                                <?php 
                                if($data['Prisoner']['fine_amount'] != '')
                                {
                                    echo $data['Prisoner']['fine_amount'];
                                }  
                                else 
                                {
                                  echo 'N/A';
                                } 
                                ?>
                            </span>
                        </h5>
                    <?php }?>
                        <h5>
                            EPD: 
                            <span style="font-weight: normal;">
                                <?php 
                                if($data['Prisoner']['epd'] != '0000-00-00')
                                    echo date('d-m-Y', strtotime($data['Prisoner']['epd']));
                                else 
                                    echo 'N/A'; 
                                ?>
                            </span>
                        </h5>
                        <?php //if($data['Prisoner']['dor'] != '0000-00-00')
                        //{?>
                            <!-- <h5>
                                DOR: 
                                <span style="font-weight: normal;">
                                    <?php 
                                    // if($data['Prisoner']['dor'] != '0000-00-00')
                                    //     echo date('d-m-Y', strtotime($data['Prisoner']['dor']));
                                    // else 
                                    //     echo 'N/A'; 
                                    ?>
                                </span>
                            </h5> -->
                        <?php //}
                        $isPD = $funcall->isAnyPD($data['Prisoner']['id']);
                        if($isPD > 0)
                        {
                            ?>
                            <h5>
                                FDR: 
                                <span style="font-weight: normal;">
                                    <?php 
                                    if($data['Prisoner']['dor'] != '0000-00-00')
                                        echo date('d-m-Y', strtotime($data['Prisoner']['dor']));
                                    else 
                                        echo 'N/A'; 
                                    ?>
                                </span>
                            </h5>
                            <?php 
                        }
                        ?>
                        
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                        <div id="listingDiv"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$commonHeaderUrl    = $this->Html->url(array('controller'=>'Prisoners','action'=>'getCommonHeder'));
$ajaxUrl = $this->Html->url(array('controller'=>'Sentence','action'=>'indexAjax'));
echo $this->Html->scriptBlock(" 
   
    jQuery(function($) {
         showData();
    }); 
    
    function showData(){
        var puuid = '".$puuid."';
        var url = '".$ajaxUrl."'+ '/puuid:'+'".$puuid."';
        $.post(url, function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });
    }
",array('inline'=>false));
?>
<?php }?>
