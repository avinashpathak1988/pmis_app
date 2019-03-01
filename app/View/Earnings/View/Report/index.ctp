<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Reports (25)</h5>
                </div>
                <div class="widget-content" style="overflow: auto;min-height: 400px;">
                    <div class="quick-actions_homepage">
                        <div class="row-fluid">
                                <div class="prisoner-box-1 modules reportBox span4">
                                   <?php echo $this->Html->link('Prisoner Custody Demographic', '/report/prisonerCustodyDemographic', array('escape'=>false, 'role'=>'menuitem', 'tabindex'=>'-1'))?>
                                </div>
                                <div class="prisoner-box-2 modules reportBox span4">
                                   <?php echo $this->Html->link('Offence and Age Group', '/report/offenceAndAgeGroup', array('escape'=>false, 'role'=>'menuitem', 'tabindex'=>'-1'))?>
                                </div>
                                <div class="prisoner-box-1 modules reportBox span4">
                                    <?php echo $this->Html->link('Remand Category By Gender', '/report/remandCategoryByGender', array('escape'=>false, 'role'=>'menuitem', 'tabindex'=>'-1'))?>
                                </div>
						</div>
                        <div class="row-fluid">
                                <div class="prisoner-box-2 modules reportBox span4">
                                    <?php echo $this->Html->link('Prisoner Admission Summary', '/report/admissionsSummary', array('escape'=>false, 'role'=>'menuitem', 'tabindex'=>'-1'))?>
                                </div>
                                <div class="prisoner-box-1 modules reportBox span4">
                                    <?php echo $this->Html->link('Prisoner Education Level Summary', '/report/educationLevelPrisonerSummary', array('escape'=>false, 'role'=>'menuitem', 'tabindex'=>'-1'))?>
                                </div>
                                <div class="prisoner-box-2 modules reportBox span4">
                                    <?php echo $this->Html->link('Prisoner Employment Summary', '/report/employmentPrisonerSummary', array('escape'=>false, 'role'=>'menuitem', 'tabindex'=>'-1'))?>
                                </div>
						</div>
                        <div class="row-fluid">
                                <div class="prisoner-box-1 modules reportBox span4">
                                    <?php echo $this->Html->link('Prisoner Marital Status Summary', '/report/maritalStatusPrisonerSummary', array('escape'=>false, 'role'=>'menuitem', 'tabindex'=>'-1'))?>
                                </div>
                                <div class="prisoner-box-2 modules reportBox span4">
                                    <?php echo $this->Html->link('Prisoner Sentence Summary', '/report/sentencePrisonerSummary', array('escape'=>false, 'role'=>'menuitem', 'tabindex'=>'-1'))?>
                                </div>
                                <div class="prisoner-box-1 modules reportBox span4">
                                    <?php echo $this->Html->link('Prisoner Court Conviction Summary', '/report/courtConvictionSummary', array('escape'=>false, 'role'=>'menuitem', 'tabindex'=>'-1'))?>
                                </div>
						</div>
                        <div class="row-fluid">
                                <div class="prisoner-box-2 modules reportBox span4">
                                    <?php echo $this->Html->link('Tribe Admission Summary', '/report/admissionTribeSummary', array('escape'=>false, 'role'=>'menuitem', 'tabindex'=>'-1'))?>
                                </div>
                                <div class="prisoner-box-1 modules reportBox span4">
                                    <?php echo $this->Html->link('Number of Conviction Summary', '/report/admissionByNumbersSummary', array('escape'=>false, 'role'=>'menuitem', 'tabindex'=>'-1'))?>
                                </div>
                                <div class="prisoner-box-2 modules reportBox span4">
                                    <?php echo $this->Html->link('UG Forces Summary Report', '/report/admissionUgforceSummary', array('escape'=>false, 'role'=>'menuitem', 'tabindex'=>'-1'))?>
                                </div>
						</div>
                        <div class="row-fluid">
                                <div class="prisoner-box-1 modules reportBox span4">
                                    <?php echo $this->Html->link('Sentence Review Alerts', '/report/sentenceReviewAlerts', array('escape'=>false, 'role'=>'menuitem', 'tabindex'=>'-1'))?>
                                </div>
                                <div class="prisoner-box-2 modules reportBox span4">
                                    <?php echo $this->Html->link('Daily Unlock & Lock Report', '/report/dailyUnlockLockReport', array('escape'=>false, 'role'=>'menuitem', 'tabindex'=>'-1'))?>
                                </div>
                                <div class="prisoner-box-1 modules reportBox span4">
                                    <?php echo $this->Html->link('Child Handed Over Report', '/report/childHandedOverReport', array('escape'=>false, 'role'=>'menuitem', 'tabindex'=>'-1'))?>
                                </div>
						</div>
                        <div class="row-fluid">
                                <div class="prisoner-box-2 modules reportBox span4">
                                    <?php echo $this->Html->link('Children Due Handed Over', '/report/childrenDueHandedOver', array('escape'=>false, 'role'=>'menuitem', 'tabindex'=>'-1'))?>
                                </div>
                                <div class="prisoner-box-1 modules reportBox span4">
                                    <?php echo $this->Html->link('Medical Death Report', '/report/medicalDeathReport', array('escape'=>false, 'role'=>'menuitem', 'tabindex'=>'-1'))?>
                                </div>
                                <div class="prisoner-box-2 modules reportBox span4">
                                    <?php echo $this->Html->link('BMI Report', '/report/bmi', array('escape'=>false, 'role'=>'menuitem', 'tabindex'=>'-1'))?>
                                </div>
						</div>
                        <div class="row-fluid">
                                <div class="prisoner-box-1 modules reportBox span4">
                                    <?php echo $this->Html->link('Alert of Prisoners about to reach release date', '/report/appard', array('escape'=>false, 'role'=>'menuitem', 'tabindex'=>'-1'))?>
                                </div>
                                <div class="prisoner-box-2 modules reportBox span4">
                                    <?php echo $this->Html->link('List of Prisoners at Large', '/report/lopal', array('escape'=>false, 'role'=>'menuitem', 'tabindex'=>'-1'))?>
                                </div>
                                <div class="prisoner-box-1 modules reportBox span4">
                                    <?php echo $this->Html->link('Prisoners Classification and Progressive Stage System', '/report/pcap', array('escape'=>false, 'role'=>'menuitem', 'tabindex'=>'-1'))?>
                                </div>
						</div>
                        <div class="row-fluid">
                                <div class="prisoner-box-2 modules reportBox span4">
                                    <?php echo $this->Html->link('Summary of Prisoners per stage disaggregated by sex', '/report/spps', array('escape'=>false, 'role'=>'menuitem', 'tabindex'=>'-1'))?>
                                </div>
                                <div class="prisoner-box-1 modules reportBox span4">
                                    <?php echo $this->Html->link('Summary of Prisoners per stage disaggregated by age', '/report/sppa', array('escape'=>false, 'role'=>'menuitem', 'tabindex'=>'-1'))?>
                                </div>
                                <div class="prisoner-box-2 modules reportBox span4">
                                    <?php echo $this->Html->link('Punishment Ward Docket', '/InPrisonOffenceCapture/punishmentWardDocket', array('escape'=>false, 'role'=>'menuitem', 'tabindex'=>'-1'))?>
                                </div>
						</div>
                        <div class="row-fluid">
                                <div class="prisoner-box-1 modules reportBox span4">
                                    <?php echo $this->Html->link('Punishment Book', '/InPrisonOffenceCapture/punishmentBook', array('escape'=>false, 'role'=>'menuitem', 'tabindex'=>'-1'))?>
                                </div>
						</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>