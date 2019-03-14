<div class="">
  <?php
  $geographical= $funcall->getGeographicalListMain();
  
  ?>
    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
        <div class="row">
            <div class="span6">
                <div class="control-group">
                    <label class="control-label">Geographical Region:</label>
                    <div class="controls">
                      <?php
                          echo $this->Form->input('geographical_region_id',array(
                                        'type'=>'select',
                                        'div'=>false,
                                        'label'=>false,
                                        'id'=>'geographical_region_id',
                                        'options'=>$geographical,
                                        'empty'=>'',
                                        'class'=>'span11 pmis_select',
                                        'onchange'=>'javascript:showGeographical(this.value);',
                                        'required'
                                      ));

                      ?>
                    </div>
                </div>
                </div>
                    <div class="span6">
                      <div class="control-group">
                        <label class="control-label">UPS Region :</label>
                        <div class="controls">
                         <?php
                                   echo $this->Form->input('state_id',array(
                                   'type'=>'select',
                                   'div'=>false,
                                   'label'=>false,
                                   'id'=>'state_id',
                                   'options'=>'',
                                   'empty'=>'',
                                   'class'=>'span11 pmis_select',
                                   'onchange'=>'javascript:showDistrict(this.value);',
                                   
                                 ));

                             ?>
                        </div>
                      </div>
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">UPS PrisonDistrict :</label>
                            <div class="controls">
                                <?php 
                                      
                                  echo $this->Form->input('district_id', array('type'=>'select','class'=>'span11 pmis_select','id'=>'district_id','options'=>'','empty'=>'','div'=>false,'label'=>false,'onchange'=>'javascript:showGeoDistrict(this.value);'));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Geographical District :</label>
                            <div class="controls">
                                <?php 
                                    echo $this->Form->input('geographical_id', array('type'=>'select','class'=>'span11 pmis_select','id'=>'geographical_id','options'=>'','empty'=>'','div'=>false,'label'=>false,'onchange'=>'javascript:showDistrictPrison(this.value);'));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Prison :</label>
                            <div class="controls">
                              <?php 
                                  echo $this->Form->input('prison_id', array('type'=>'select','class'=>'span11 pmis_select','id'=>'prison_id','multiple','options'=>'','empty'=>'','div'=>false,'label'=>false));
                                     ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="clearfix"></div>
        </div>
            <div class="row" style="padding-bottom: 14px;">
                  <div class="span6">
                      <div class="control-group">
                          <label class="control-label">From Date :</label>
                              <div class="controls">
                                  <?php echo $this->Form->input('from_date', array('type'=>'text', 'id'=>'from_date', 'class'=>'span11 from_date','div'=>false,'label'=>false,'placeholder'=>'Enter from date','required'))?>
                                        </div>
                              </div>
                      </div>
                  <div class="span6">
                      <div class="control-group">
                          <label class="control-label">To Date :</label>
                          <div class="controls">
                              <?php echo $this->Form->input('to_date', array('type'=>'text', 'id'=>'to_date', 'class'=>'span11 to_date','div'=>false,'label'=>false,'placeholder'=>'Enter to date','required'))?>
                          </div>
                      </div>
                  </div>
                  <div class="span6">
                      <div class="control-group">
                          <label class="control-label">Month - Year:</label>
                          <div class="controls">
                              <?php $monthsList=array(
                                                ''=>'-- Month -- ',
                                                '01'=>'Jan',
                                                '02'=>'Feb',
                                                '03'=>'Mar',
                                                '04'=>'Apr',
                                                '05'=>'May',
                                                '06'=>'Jun',
                                                '07'=>'Jul',
                                                '08'=>'Aug',
                                                '09'=>'Sep',
                                                '10'=>'Oct',
                                                '11'=>'Nov',
                                                '12'=>'Dec',
                                                );

                                            $yearsList=array(
                                                ''=>'-- Year -- ',
                                                '2013'=>'2013',
                                                '2014'=>'2014',
                                                '2015'=>'2015',
                                                '2016'=>'2016',
                                                '2017'=>'2017',
                                                '2018'=>'2018',
                                                '2019'=>'2019',
                                                '2020'=>'2020',
                                                '2021'=>'2021',
                                            );
                            ?>
                            <?php echo $this->Form->input('selected_month',array('div'=>false,'label'=>false,'class'=>'span5 pmis_select','type'=>'select','options'=>$monthsList, 'empty'=>'','required'=>false,'id'=>'selected_month_id'));?>
                            <?php echo $this->Form->input('selected_year',array('div'=>false,'label'=>false,'class'=>'span5 pmis_select','type'=>'select','options'=>$yearsList, 'empty'=>'','required'=>false,'style'=>'margin-left:5px;','id'=>'selected_year_id'));?>
                        </div>
                    </div>
                </div>  
                <?php foreach ($otherFields as $otherField) {?>
                    <div class="span6">
                        <div class="control-group">
                          <label class="control-label"><?php echo $otherField['lable']?>:</label>
                          <div class="controls">
                              <?php echo $this->Form->input($otherField['name'],array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$otherField['listing'], 'empty'=>'','required'=>false,'id'=>$otherField['id']));?>
                          </div>
                        </div>
                  </div>
               <?php } ?>
                

            </div>
            <div class="form-actions" align="center">
                <button id="btnsearchReport" class="btn btn-success" type="button" onclick="javascript:showData();">Search</button>
                <button id="btnsearchreset" class="btn btn-warning" type="reset" onclick="javascript:resetData();">reset</button>
            </div>
      <?php echo $this->Form->end();?>
</div>

<?php
$geographicalajaxUrl = $this->Html->url(array('controller'=>'PrisonerReport','action'=>'getgeographicalAjax'));
$districtajaxUrl = $this->Html->url(array('controller'=>'PrisonerReport','action'=>'getdistrictAjax'));
$geodistrictajaxUrl = $this->Html->url(array('controller'=>'PrisonerReport','action'=>'getgeodistrictAjax'));
$getDistrictPrisonajaxUrl = $this->Html->url(array('controller'=>'PrisonerReport','action'=>'getDistrictPrisonAjax'));
?>
<script type="text/javascript">
    function showGeographical(id)
    {
        var url = '<?php echo $geographicalajaxUrl ?>';
        url = url + '/geographical_region_id:' + id ;
        $.post(url, {}, function(res){
            if (res) {
                $('#state_id').html(res);
            }
        });
    }
    function showDistrict(id)
    {
        var url = '<?php echo $districtajaxUrl ?>';
        url = url + '/state_id:' + id ;
        $.post(url, {}, function(res){
            if (res) {
                $('#district_id').html(res);
            }
        });
    }
    function showGeoDistrict(id)
    {
        var url = '<?php echo $geodistrictajaxUrl ?>';
        url = url + '/district_id:' + id ;
        $.post(url, {}, function(res){
            if (res) {
                $('#geographical_id').html(res);
            }
        });
    }
    function showDistrictPrison(id)
    {
        var url = '<?php echo $getDistrictPrisonajaxUrl ?>';
        url = url + '/district_id:' + id ;
        $.post(url, {}, function(res){
            if (res) {
                $('#prison_id').html(res);
            }
        });
    }

    function resetData(){

        $('select').select2('val', '');
        showData();
    }
</script>