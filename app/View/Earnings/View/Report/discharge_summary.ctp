<style>
.row-fluid [class*="span"]{
  margin-left: 0px !important;
}
</style>
<?php
    $exUrl = "dischargeSummary/".@$exitData['DischargeSummary']['prisoner_id'];
    $urlPrint = $exUrl.'/reqType:PRINT';
?>
<div class="container-fluid">
    <div class="row-fluid">
        <div id="commonheader"></div>
        <div class="span12">
            <div class="widget-box">
                <?php
                    if(!isset($is_excel)){
                ?>
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Discharge Summary Board</h5>
                    <div style="float:right;padding-top:2px;">
                        <?php echo $this->Html->link('Back',"/report/dischargeLong",array('class' => 'btn btn-primary'));?>
                        <?php echo (isset($exitData['DischargeSummary']['prisoner_id'])) ?  $this->Html->link('Print',$urlPrint,array('class' => 'btn btn-primary','target'=>'_blank')) : '';?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <?php
                    }
                if(isset($exitData) && is_array($exitData) && count($exitData)>0){
                    ?>
                <div class="widget-content nopadding" style="margin: 50px;">
                    <table class="table table-responsive">
                        <caption> <h3 align="center">DISCHARGE BOARD SUMMARY</h3>
                        <h5 align="center">(To be completed three months before the month of discharge)</h5></caption>
                        
                        <tbody>
                            <tr>
                                <td><b>Prison : </b></td>
                                <td><?php echo $exitData['DischargeSummary']['prison_name'];?></td>
                            </tr>
                            <tr>
                                <td><b>Name (in full) : </b></td>
                                <td><?php echo $exitData['DischargeSummary']['fullname'];?></td>
                            </tr>
                            <tr>
                                <td><b>Former employment : </b></td>
                                <td><?php echo $exitData['DischargeSummary']['former_employment'];?></td>
                            </tr>
                            <tr>
                                <td><b>Address on discharge in non fixed, <br>state town to which proceeding : </b></td>
                                <td><?php echo $exitData['DischargeSummary']['discharge_address'];?></td>
                            </tr>
                            <tr>
                                <td><b>What he wishes to do : </b></td>
                                <td><?php echo $exitData['DischargeSummary']['wishes'];?></td>
                            </tr>
                            <tr>
                                <td><b>Any offer of help or employment : </b></td>
                                <td><?php echo $exitData['DischargeSummary']['offer'];?></td>
                            </tr>
                            <tr>
                                <td><b>Vocational and spare time training : </b></td>
                                <td><?php echo $exitData['DischargeSummary']['spare_time'];?></td>
                            </tr>
                            <tr>
                                <td><b>General remarks and suggestions for after care : </b></td>
                                <td><?php echo $exitData['DischargeSummary']['remarks'];?></td>
                            </tr>
                            <tr>
                                <td><b>Amount of private cash : </b></td>
                                <td><?php echo $exitData['DischargeSummary']['amount'];?></td>
                            </tr>
                            <tr>
                                <td><b>Earliest date of discharge : </b></td>
                                <td><?php echo date("d-m-Y", strtotime($exitData['DischargeSummary']['epd']));?></td>
                            </tr>
                            <tr>
                                <td><b>Licence expires (if has any) : </b></td>
                                <td><?php echo $exitData['DischargeSummary']['licence_expires'];?></td>
                            </tr>
                            <tr>
                                <td colspan="2" height="40">
                                    
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="span6">
                                        Date : <?php echo date("d-m-Y"); ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="span6 text-center">
                                        <h5>Superintendent</h5>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h3 align="center">Discharge board summary- continued</h3>
                                </td>
                            </tr>
                            <tr>
                                <td><b>
                                    Superintendent’s opinion and <br>recommendation :
                                    </b>
                                </td>
                                <td>
                                    <?php echo $exitData['DischargeSummary']['recommendation'];?>    
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" height="40">
                                    
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="span6">
                                        Date : <?php echo date("d-m-Y"); ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="span6 text-center">
                                        <h5>Superintendent</h5>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                </div>
                    <?php
                }else{
                    ?>
                    <div class="widget-content nopadding" style="margin: 50px;">
                    
                        <div class="">
                        <?php echo $this->Form->create('DischargeSummary',array('class'=>'form-horizontal'));?>
                            <h3 align="center">DISCHARGE BOARD SUMMARY</h3>
                            <h5 align="center">(To be completed three months before the month of discharge)</h5>

                            <div class="row">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Prison : </label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('prison_name',array('type'=>'hidden','value'=>$prisonerDetails['Prison']['name']));?>
                                            <?php echo $this->Form->input('prisoner_id',array('type'=>'hidden','value'=>$prisonerDetails['Prisoner']['id']));?>
                                            <?php echo $prisonerDetails['Prison']['name'];?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Name (in full) : </label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('fullname',array('type'=>'hidden','value'=>$prisonerDetails['Prisoner']['fullname']));?>
                                            <?php echo $prisonerDetails['Prisoner']['fullname'];?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Former employment : </label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('former_employment',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'treatment', 'cols'=>60, 'rows'=>2,'title'=>'Please provide Former employment'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Address on discharge in non fixed, <br>state town to which proceeding</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('discharge_address',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'treatment', 'cols'=>60, 'rows'=>2,'title'=>'Please provide discharge address'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">What he wishes to do</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('wishes',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'treatment', 'cols'=>60, 'rows'=>2,'title'=>'Please provide wishes'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Any offer of help or employment</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('offer',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'treatment', 'cols'=>60, 'rows'=>2,'title'=>'Please provide offer of help or employment'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Vocational and spare time training</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('spare_time',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'treatment', 'cols'=>60, 'rows'=>2,'title'=>'Please provide description'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">General remarks and suggestions for after care</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('remarks',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'treatment', 'cols'=>60, 'rows'=>2,'title'=>'Please provide description'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Amount of private cash</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('amount',array('type'=>'text', 'div'=>false,'label'=>false,'class'=>'form-control','required','title'=>'Please provide description'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Earliest date of discharge</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('epd',array('type'=>'hidden','value'=>$prisonerDetails['Prisoner']['epd']));?>
                                            <?php echo date("d-m-Y", strtotime($prisonerDetails['Prisoner']['epd']));?>

                                        </div>
                                    </div>
                                </div>
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Licence expires (if has any)</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('licence_expires',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'treatment', 'cols'=>60, 'rows'=>2,'title'=>'Please provide description'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span12">
                                    <h3 align="center">Discharge board summary- continued</h3>
                                </div>
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Superintendent’s opinion and recommendation</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('recommendation',array('type'=>'textarea', 'div'=>false,'label'=>false,'class'=>'form-control','required','id'=>'treatment', 'cols'=>60, 'rows'=>2,'title'=>'Please provide description'));?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions" align="center">
                                <button id="btnsearchcash" class="btn btn-success" type="submit">Submit</button>
                            </div>
                        <?php echo $this->Form->end();?>
                        </div>
                    </div>
                    <?php
                }
                ?>
                
            </div>
        </div>
    </div>
</div>

