<?php
if($_POST['action'] == 'provinces'){
    require_once '../classes/reference.class.php';
    $ref_obj = new Reference();
    $ref = $ref_obj->get_province($_POST['filter']);
?>
    <option value="none">--Select--</option>
<?php
    foreach($ref as $row){
?>
        <option value="<?=$row['provCode']?>"><?=$row['provDesc']?></option>
<?php
    }
}
if($_POST['action'] == 'city'){
    require_once '../classes/reference.class.php';
    $ref_obj = new Reference();
    $ref = $ref_obj->get_City($_POST['filter']);
?>
    <option value="none">--Select--</option>
<?php
    foreach($ref as $row){
?>
        <option value="<?=$row['citymunCode']?>"><?=$row['citymunDesc']?></option>
<?php
    }
}

if($_POST['action'] == 'barangay'){
    require_once '../classes/reference.class.php';
    $ref_obj = new Reference();
    $ref = $ref_obj->get_brgy($_POST['filter']);
?>
    <option value="none">--Select--</option>
<?php
    foreach($ref as $row){
?>
        <option value="<?=$row['brgyCode']?>"><?=$row['brgyDesc']?></option>
<?php
    }

}
?>



<!-- Reversed Referencing -->
<?php
if($_POST['action'] == 'city_by_brgy'){
    require_once '../classes/reference.class.php';
    $ref_obj = new Reference();
    $ref = $ref_obj->get_city_by_brgy($_POST['filter']);
?>
    <option value="none">--Select--</option>
<?php
    foreach($ref as $row){
?>
        <option value="<?=$row['citymunCode']?>" data-province="<?=$row['provCode']?>"><?=$row['citymunDesc']?></option>
<?php
    }
}

if($_POST['action'] == 'province_by_city'){
    require_once '../classes/reference.class.php';
    $ref_obj = new Reference();
    $ref = $ref_obj->get_province_by_city($_POST['filter']);
?>
    <option value="none">--Select--</option>
<?php
    foreach($ref as $row){
?>
        <option value="<?=$row['provCode']?>" data-region="<?=$row['regCode']?>"><?=$row['provDesc']?></option>
<?php
    }
}

if($_POST['action'] == 'region_by_province'){
    require_once '../classes/reference.class.php';
    $ref_obj = new Reference();
    $ref = $ref_obj->get_region_by_province($_POST['filter']);
?>
    <option value="none">--Select--</option>
<?php
    foreach($ref as $row){
?>
        <option value="<?=$row['regCode']?>"><?=$row['regDesc']?></option>
<?php
    }
}
?>


<?php
/* if($_POST['action'] == 'region_by_province'){
    require_once '../classes/reference.class.php';

    if (isset($_POST['action']) && $_POST['action'] === 'getSelectedData' && isset($_POST['brgyCode'])) {
        $ref_obj = new Reference();
        $selectedBrgyCode = $_POST['brgyCode'];
        $selectedData = $ref_obj->getSelectedData($selectedBrgyCode);
        $cityData = $ref_obj->get_City_by_Brgy($selectedData['citymunCode']);
        $provinceData = $ref_obj->get_Province_by_City($selectedData['provCode']);
        $regionData = $ref_obj->get_Region_by_Province($selectedData['regCode']);
    
        $response = array(
            'cityData' => $cityData,
            'provinceData' => $provinceData,
            'regionData' => $regionData
        );
    
        echo json_encode($response);
    }
} */
?>    
