<div class="widget-box">
                
                    <div class="widget-content nopadding">
                        <div class="">
                            <ul class="nav nav-tabs">
                                <li><a href="<?php echo $this->webroot;?>SocialReintegration"  id="menu_social_reintegration">Social Reintegration Assessment</a></li>
                                <li><a href="<?php echo $this->webroot;?>DischargeBoardSummary"  id="menu_discharge_board">Discharge Board</a></li>
                                <li><a href="<?php echo $this->webroot;?>aftercare" id="menu_aftercare">After care</a></li>
                                
                            </ul>
                        </div>
                    </div> 
                </div> 
              
               
<script type="text/javascript">
    $( document ).ready(function() {
        console.log(location.href);
        var currLocation = location.href;
        if(currLocation.indexOf('aftercare') > -1 || currLocation.indexOf('Aftercare') > -1){
            $('#menu_aftercare').attr('aria-selected','true');
            $('#menu_aftercare').parent().addClass('active');
        }else if(currLocation.indexOf('Discharge') > -1 || currLocation.indexOf('discharge') > -1){
            $('#menu_discharge_board').attr('aria-selected','true');
            $('#menu_discharge_board').parent().addClass('active');

        }else if(currLocation.indexOf('integration') > -1 ){
            $('#menu_social_reintegration').attr('aria-selected','true');
            $('#menu_social_reintegration').parent().addClass('active');

        }

       
    });
</script>                