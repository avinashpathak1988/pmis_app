<style type="text/css">
	 .quick-actions li {
      box-shadow: 5px 4px 6px #888888;
    height: 100px;
    width: 254px;
    /*border-top: 0px solid #2E363F;*/
  }
    .stat-boxes li a:hover, .quick-actions li a:hover, .quick-actions-horizontal li a:hover, .stat-boxes li:hover, .quick-actions li:hover, .quick-actions-horizontal li:hover {
    /*background: #2E363F;*/
   /* border-top: 2px solid #2E363F;*/
}
p.text-center a
{
  font-size: 40px;
  position: relative;
  top: 10px;
  margin-left: 10px;
}
p.text-center i{
	font-size: 50px;
	margin-right: 15px;
}
h5.text-center a{
	font-size: 16px;
}
/** Tarini design for Currency/**/
.fin .span3{
  margin-left: 0.95% !important;
  margin-right: 0.95% !important;
}
.row-fluid.fin [class*="span"]:first-child{
  margin-left: 0.95% !important;
  
}

/** {
    box-sizing: border-box;
}
*/
/* Create two equal columns that floats next to each other */
.column {
    float: left;
    width: 10%;
    padding: 10px;
}

/* Clear floats after the columns */
.row:after {
    content: "";
    display: table;
    clear: both;
}
/*------ Tarini Currency ------*/
.card {
    position: relative;
    margin-top: 0px;
    background-color: #ffffff;
    border-radius: 3px;
}
.card-shadow {
    border: none;
    box-shadow: 0 2px 4px 0px rgba(115, 108, 203, 0.45);
    border: 1px solid #ddd;
    margin: 20px 0 5px;
}
.text-light {
    color: #f8f9fa !important;
}
.card-body {
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 8px 1.25rem;
}
.row {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
}
.col-12 {
    -ms-flex: 0 0 100%;
    flex: 0 0 100%;
    max-width: 100%;
}
.bg_shedo_light_blue {
    box-shadow: 0 4px 20px 0 rgba(0,0,0,.14), 0 7px 10px -5px rgba(54, 162, 245, 0.59);
}
.bg_shedo_light_green {
    box-shadow: 0 4px 20px 0 rgba(0,0,0,.14), 0 7px 10px -5px rgba(52, 191, 163, 0.52);
}
.bg_shedo_light_red {
    box-shadow: 0 4px 20px 0 rgba(0,0,0,.14), 0 7px 10px -5px rgba(255, 81, 138, 0.6);
}
.bg_shedo_light_yellow {
    box-shadow: 0 4px 20px 0 rgba(0,0,0,.14), 0 7px 10px -5px rgba(234, 196, 89, 0.57);
}
.wb-icon-box {
    display: inline-block;
    width: 40px;
    height: 40px;
    text-align: center;
    line-height: 60px;
    float: left;
    padding-top: 5px;
    margin-left: 2px;
    border-radius: 2px;
}
.homepage_single .bg-info{
    background-color: #36a2f5 !important;
}
.homepage_single .text-info{
    color: #36a2f5 !important;
}
.homepage_single .bg-success{
    background-color: #34bfa3 !important;
}
.homepage_single .text-success{
    color: #34bfa3 !important;
}
.homepage_single .bg-danger{
    background-color: #ff518a !important;
}
.homepage_single .text-danger{
    color: #ff518a !important;
}
.homepage_single .bg-warning{
    background-color: #eac459 !important;
}
.homepage_single .text-warning{
    color: #eac459 !important;
}
.f24 {
    font-size: 24px;
}
.homepage_fl_right {
    float: right;
    /*min-width: 92px;*/
    text-align: right;
}
.homepage_fl_right h4 {
    font-size: 14px;
    color: #999999;
    font-weight: 400;
    margin-bottom: 0px;
}
.mt-0 {
    margin-top: 0px;
}
.homepage_fl_right h3 {
    font-size: 17px;
    color: #2e2e3a;
    font-weight: 500;
    margin-bottom: 0px;
    margin-top: 0px;
    text-transform: capitalize;
    line-height: 24px;
}
.homepage_single p {
    padding-top: 5px;
    font-size: 12px;
    color: #666;
    /*border-top: 1px solid #f2f2f2;*/
    border-top: 1px solid #eee;
    margin-top: 5px;
    margin-bottom: 0px;
    display: inline-block;
    width: 100%;
    text-align: left;
}
span.fl_right {
    float: right;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
}
/*------ Tarini Currency ------*/
</style>

<div class="container-fluid">
  <div class="row-fluid">
    <div class="span12">
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Dashboard</h5>
        </div>
        <div class="widget-content" style="overflow: hidden;display: <?php echo ($this->Session->read('Auth.User.usertype_id') == Configure::read('MEDICALOFFICE_USERTYPE')) ? 'none': 'block'; ?>;">
          <div class="quick-actions_homepage">
          <?php //debug($prisonerCurrencyList);?>
          
            <div class="row-fluid">
                <div class="span4">
                    <div class="prisoner-box-3 modules">
                        <p class="text-center" style="margin-bottom:25px;margin-top:15px;">
                        	<img src="../app/theme/images/icon-male.png">
                            <a href="/uganda/prisoners/index/male/convicted">
                              <?php 
                              $male =Configure::read('GENDER_MALE');
                              $female =Configure::read('GENDER_FEMALE');
                              $CONVICTEDFORLOCKUP =Configure::read('CONVICTEDFORLOCKUP');
                              
                              echo $this->requestAction('/Sites/prisonerCount/'.$male.'/'. $CONVICTEDFORLOCKUP);
                              ?>
                            </a>                                
                        </p>
                        <h5 class="text-center">
                            <a href="#">Male Convicted Prisoners</a>
                        </h5>
                    </div>    
                </div>
                <div class="span4">
                    <div class="prisoner-box-4 modules">
                        <p class="text-center" style="margin-bottom:25px;margin-top:15px;">
                        	<img src="../app/theme/images/icon-female.png">
                            <a href="/uganda/prisoners/index/female/convicted">
                              <?php 
                              echo $this->requestAction('/Sites/prisonerCount/'.$female.'/'. $CONVICTEDFORLOCKUP);

                              ?>
                            </a>                                
                        </p>
                        <h5 class="text-center">
                            <a>Female Convicted Prisoners</a>
                        </h5>
                    </div>
                </div>
                <div class="span4">
                    <div class="prisoner-box-5 modules">
                        <p class="text-center" style="margin-bottom:25px;margin-top:15px;">
                            <img src="../app/theme/images/icon-male.png">
                            <a href="/uganda/prisoners/index/male/remand">
                              <?php 
                              $REMANDFORLOCKUP = Configure::read('REMANDFORLOCKUP');
                              echo $this->requestAction('/Sites/prisonerCount/'.$male.'/'. $REMANDFORLOCKUP);

                              ?>
                            </a>                                
                        </p>
                        <h5 class="text-center">
                            <a>Male Remand Prisoners</a>
                        </h5>
                    </div>
                </div>
                <!-- <div class="span4">
                    <div class="prisoner-box-1 modules">
                        <p class="text-center" style="margin-bottom:25px;margin-top:15px;">
                        	<i class="icon icon-user"></i>
                            <a href="/uganda/prisoners/index/young">
                              <?php echo $funcall->prisonerCountByClass(Configure::read('YOUNG'));?>
                            </a>                                
                        </p>
                        <h5 class="text-center">
                            <a>Young Prisoners</a>
                        </h5>
                    </div>
                </div> -->
              </div>
            <div class="row-fluid">
                <div class="span4 dash-box">
                    <div class="prisoner-box-6 modules">
                        <p class="text-center" style="margin-bottom:25px;margin-top:15px;">
                            <img src="../app/theme/images/icon-female.png">
                            <a href="/uganda/prisoners/index/female/remand">
                              <?php 
                              echo $this->requestAction('/Sites/prisonerCount/'.$female.'/'. $REMANDFORLOCKUP);
                              ?>
                            </a>                                
                        </p>
                        <h5 class="text-center">
                            <a>Female Remand Prisoners</a>
                        </h5>
                    </div>
                </div>
                <div class="span4 dash-box">
                    <div class="prisoner-box-3 modules">
                        <p class="text-center" style="margin-bottom:25px;margin-top:15px;">
                            <img src="../app/theme/images/icon-male.png">
                            <a href="/uganda/prisoners/index/male/debtor">
                              <?php
                              $DEBTORFORLOCKUP = Configure::read('DEBTORFORLOCKUP');
                              echo $this->requestAction('/Sites/prisonerCount/'.$male.'/'. $DEBTORFORLOCKUP);

                               ?>
                            </a>                                
                        </p>
                        <h5 class="text-center">
                            <a>Male Debtor Prisoners</a>
                        </h5>
                    </div>
                </div>
                <div class="span4 dash-box">
                    <div class="prisoner-box-4 modules">
                        <p class="text-center" style="margin-bottom:25px;margin-top:15px;">
                            <img src="../app/theme/images/icon-female.png">
                            <a href="/uganda/prisoners/index/female/debtor">
                              <?php 
                              echo $this->requestAction('/Sites/prisonerCount/'.$female.'/'. $DEBTORFORLOCKUP);
                              ?>
                            </a>                                
                        </p>
                        <h5 class="text-center">
                            <a>Female Debtor Prisoners</a>
                        </h5>
                    </div>
                </div>                
                <!-- <div class="span4 dash-box">
                    <div class="prisoner-box-2 modules">
                        <p class="text-center" style="margin-bottom:25px;margin-top:15px;">
                        	<i class="icon icon-user"></i>
                            <a href="/uganda/prisoners/index/star">
                              <?php echo $funcall->prisonerCountByClass(Configure::read('STAR'));?>
                            </a>                                
                        </p>
                        <h5 class="text-center">
                            Star Prisoners
                        </h5>
                    </div>    
                </div>
                <div class="span4 dash-box">
                    <div class="prisoner-box-1 modules">
                        <p class="text-center" style="margin-bottom:25px;margin-top:15px;">
                        	<i class="icon icon-user"></i>
                            <a href="/uganda/prisoners/index/ordinary">
                              <?php echo $funcall->prisonerCountByClass(Configure::read('ORDINARY'));?>
                            </a>                                
                        </p>
                        <h5 class="text-center">
                            <a>Ordinary Prisoners</a>
                        </h5>
                    </div>
                </div>
                <div class="span4 dash-box">
                    <div class="prisoner-box-2 modules">
                        <p class="text-center" style="margin-bottom:25px;margin-top:15px;">
                        	<i class="icon icon-user"></i>
                            <a href="/uganda/prisoners/index/habitual">
                              <?php echo $funcall->habitualPrisonerCount(2);?>
                            </a>                                
                        </p>
                        <h5 class="text-center">
                            <a>Habitual Prisoners</a>
                        </h5>
                    </div>
                </div> -->
            </div>
            <div class="row-fluid">
                <div class="span4 dash-box">
                    <div class="prisoner-box-5 modules">
                        <p class="text-center" style="margin-bottom:25px;margin-top:15px;">
                            <img src="../app/theme/images/icon-default.png">
                            <a href="/uganda/LodgerStations/index">
                              <?php echo $funcall->prisonerCountByLodgers('at');?>
                            </a>                                
                        </p>
                        <h5 class="text-center">
                            <a>Lodgers IN</a>
                        </h5>
                    </div>
                </div>
              
                <div class="span4 dash-box">
                    <div class="prisoner-box-6 modules">
                        <p class="text-center" style="margin-bottom:25px;margin-top:15px;">
                            <img src="../app/theme/images/icon-default.png">
                            <a href="/uganda/LodgerStations/index/out">
                              <?php echo $funcall->prisonerCountByLodgers('out');?>
                            </a>                                
                        </p>
                        <h5 class="text-center">
                            <a>Lodgers OUT</a>
                        </h5>
                    </div>
                </div>
                <div class="span4 dash-box">
                    <div class="prisoner-box-3 modules">
                        <p class="text-center" style="margin-bottom:25px;margin-top:15px;">
                            <img src="../app/theme/images/icon-default.png">
                            <a href="/uganda/LodgerStations/index">
                              <?php echo $funcall->prisonerCount('', '');?>
                            </a>                                
                        </p>
                        <h5 class="text-center">
                            <a>Total</a>
                        </h5>
                    </div>
                </div>

            </div>

            <hr style="margin-bottom:0px;">

            <!-- Tarini Currency -->
            <div class="row-fluid">

                

               

               

               

            </div><!--/row-fluid-->
            <?php
            $prison_id=$this->Session->read('Auth.User.prison_id')?$this->Session->read('Auth.User.prison_id'):0;
            ?>
            <div class="row-fluid fin" style="display: <?php echo (!$prison_id) ? 'none' : ''; ?>">
                <?php $total = 0; 
                      $totalcredit = 0;
                      $totaldebit = 0;
                      $totalclosing = 0;
                ?>
                <?php foreach($prisonerCurrencyList as $value){
                    // debug($value);
                    
                    $openingBalance = $this->requestAction("/properties/getOpeningBalance/$prison_id/".$value['Currency']['id']."/".date("Y-m-d"));
                    $creditDebitArr = explode("***",$this->requestAction("/properties/getCreditDebit/".$value['Currency']['id'])); 
                    ?>
                    <div class="span3">
                        <div class="card text-light card-shadow">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="homepage_single">

                                               <span class="bg-danger text-center wb-icon-box bg_shedo_light_red"> <i class="glyphicon glyphicon-flag f24" aria-hidden="true"></i> </span>
                                            <div class="homepage_fl_right">
                                                <h4 class="mt-0">Currency</h4>
                                                <h3><span class="counter"><?php echo $value['Currency']['name']?></span></h3>
                                            </div>
                                            <p>Opening Balance <span class="fl_right text-danger"><?php echo $openingBalance; 
                                            $total += $openingBalance;
                                            ?></span></p>
                                            <p>Credit <span class="fl_right text-danger"><?php echo $creditDebitArr[0];
                                                $totalcredit += $creditDebitArr[0];

                                             ?></span></p>
                                            <p>Debit <span class="fl_right text-danger"><?php echo $creditDebitArr[1]; 
                                                $totaldebit += $creditDebitArr[1];

                                            ?></span></p>
                                            <p>Closing Balance <span class="fl_right text-danger"><?php echo ($openingBalance + $creditDebitArr[0]) - $creditDebitArr[1]; 
                                            $totalclosing +=($openingBalance + $creditDebitArr[0]) - $creditDebitArr[1];
                                            ?></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!--/span3-->
                  
                <?php }?>
                

            </div><!--/row-fluid-->
            <!-- / Tarini Currency -->

           <div class="span3">
                        <div class="card text-light card-shadow">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="homepage_single">

                                               <span class="bg-danger text-center wb-icon-box bg_shedo_light_red"> <i class="glyphicon glyphicon-flag f24" aria-hidden="true"></i> </span>
                                            <div class="homepage_fl_right">
                                                <h4 class="mt-0">Total</h4>
                                                
                                            </div>
                                            <p>Opening Balance <span class="fl_right text-danger"><?php echo $total; ?></span></p>
                                            <p>Credit <span class="fl_right text-danger"><?php echo $totalcredit; ?></span></p>
                                            <p>Debit <span class="fl_right text-danger"><?php echo $totaldebit[1]; ?></span></p>
                                            <p>Closing Balance <span class="fl_right text-danger"><?php echo $totalclosing; ?></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
