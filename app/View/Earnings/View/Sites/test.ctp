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
}
p.text-center i{
	font-size: 50px;
	margin-right: 15px;
}
h5.text-center a{
	font-size: 16px;
}
</style>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span12">
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Dashboard</h5>
        </div>
        <div class="widget-content" style="overflow: hidden;">
          <div class="quick-actions_homepage">
            <div class="row-fluid">
                <div class="span4">
                    <div class="prisoner-box-1 modules">
                        <p class="text-center" style="margin-bottom:25px;margin-top:15px;">
                        	<i class="icon icon-user"></i>
                            <a href="/uganda/prisoners/index/male">
                              <?php echo $funcall->prisonerCount(Configure::read('GENDER_MALE'), Configure::read('CONVICTED'));?>
                            </a>                                
                        </p>
                        <h5 class="text-center">
                            <a href="#">Male Convicted Prisoners</a>
                        </h5>
                    </div>    
                </div>
                <div class="span4">
                    <div class="prisoner-box-2 modules">
                        <p class="text-center" style="margin-bottom:25px;margin-top:15px;">
                        	<i class="icon icon-user"></i>
                            <a href="/uganda/prisoners/index/female">
                              <?php echo $funcall->prisonerCount(Configure::read('GENDER_FEMALE'), Configure::read('CONVICTED'));?>
                            </a>                                
                        </p>
                        <h5 class="text-center">
                            <a>Female Convicted Prisoners</a>
                        </h5>
                    </div>
                </div>
                <div class="span4">
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
                </div>
              </div>
            <div class="row-fluid">
                <div class="span4 dash-box">
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
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
