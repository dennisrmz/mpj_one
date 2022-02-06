<?php
 /************************** Filtro En CheckOut ******************************/

 function mpj_custom_validate_stock($fields, $errors)
 {
    
    $receta = $_POST['additional_wooccm0'];
    $flag_receta = $_POST['billing_wooccm11'];
    
    if($flag_receta == 1){

        if(empty($receta)){
            $errors->add('validation', 'Receta vacia, por favor cargue una imagen de su receta oftalmologica, donde se pueden ver los datos que especifico de su receta');
        }

    }
    
 }