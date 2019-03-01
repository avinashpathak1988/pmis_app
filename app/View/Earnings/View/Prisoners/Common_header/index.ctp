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
        <h4 >
            <?php echo $data["Prisoner"]["prisoner_no"]?>
        </h4>
        <h5 >
            <?php echo $data["Prisoner"]["personal_no"]?>
        </h5>
        <?php if(isset($data["Prisoner"]["also_known_as"]) && ($data["Prisoner"]["also_known_as"] != ''))
        {?>
            <h5>Also known as : <?php echo substr($data["Prisoner"]["also_known_as"], 0, 10)?></h5>
        <?php }?>
        <h5>Gender : <?php echo $data["Gender"]["name"]?></h5>
        <h5>Date of Birth : <?php echo date('d-m-Y',strtotime($data["Prisoner"]["date_of_birth"]));?></h5>
        <?php $place_of_birth = '';
        $place_of_birth .= $data["PlaceOfBirthDistrict"]["name"];
        if(isset($data["PlaceOfBirthCounty"]["name"]) && !empty($data["PlaceOfBirthCounty"]["name"]))
        {
            if($place_of_birth != '')
                $place_of_birth .= ',';
            $place_of_birth .= $data["PlaceOfBirthCounty"]["name"];
        }
        if(isset($data["PlaceOfBirthSubCounty"]["name"]) && !empty($data["PlaceOfBirthSubCounty"]["name"]))
        {
            if($place_of_birth != '')
                $place_of_birth .= ',';
            $place_of_birth .= $data["PlaceOfBirthSubCounty"]["name"];
        }
        if(isset($data["PlaceOfBirthParish"]["name"]) && !empty($data["PlaceOfBirthParish"]["name"]))
        {
            if($place_of_birth != '')
                $place_of_birth .= ',';
            $place_of_birth .= $data["PlaceOfBirthParish"]["name"];
        }
        if(isset($data["PlaceOfBirthVillage"]["name"]) && !empty($data["PlaceOfBirthVillage"]["name"]))
        {
            if($place_of_birth != '')
                $place_of_birth .= ',';
            $place_of_birth .= $data["PlaceOfBirthVillage"]["name"];
        }
        if($place_of_birth != '')
        {
            echo '<h5>Place of Birth: </h5>'.$place_of_birth;
        }?>
    </div>
    <div class="span5">
        <h4 >
            <p>
                <?php //echo substr($data["Prisoner"]["fullname"], 0, 10);
                echo $data["Prisoner"]["fullname"];?>
            </p>
        </h4>
        <h5>Father Name : <?php echo ($data["Prisoner"]["father_name"])? substr($data["Prisoner"]["father_name"], 0, 10):Configure::read('NA');?></h5>
        <h5>Mother Name : <?php echo ($data["Prisoner"]["mother_name"])? substr($data["Prisoner"]["mother_name"], 0, 10):Configure::read('NA');?></h5>
        <h5>Country : <?php echo $data["Country"]["name"]?></h5>
        <?php if($data["District"]["name"] != '')
        { echo '<h5>District: '.$data["District"]["name"].'</h5>';}?>
    </div>
    <p></p>
</div> 