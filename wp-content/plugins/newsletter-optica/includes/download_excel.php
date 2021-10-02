<?php

function print_excel()
  {

    global $wpdb;

    // # check the URL in order to perform the downloading
        if (!isset($_GET['excel']) || !isset($_GET['download_excel']) || !isset($_GET['from_date']) || !isset($_GET['to_date']) ) {
            return false;
        }

    $from_date = $_GET['from_date'];    
    $to_date = $_GET['to_date'];

    // # check the XLSXWriter class is already loaded or not. If it is not loaded yet, we will load it.
    if (!class_exists('XLSXWriter')) {
        include_once('xlsxwriter.class.php');
    }

    // # set the destination file
    $fileLocation = "users_newsletter_from" . $from_date . "to". $to_date .".xlsx";

    $user_newsletter = $wpdb->get_results("SELECT wnc.email, wnc.phone_number from " . 
                                            $wpdb->prefix . "mpj_news_contacts wnc 
                                            where wnc.activate = 1 and 
                                            date(wnc.created_at) between " . " '$from_date'  " . " and " . " '$to_date' " . " ");

    $datos = array();  
    
    $columns = array("Email","Numero de Celular");

    array_push($datos, $columns);

    // # prepare the data set
    if( count($user_newsletter > 0) ):
        foreach($user_newsletter as $key => $value):

            $datos_user =  array($value->email, $value->phone_number);
            array_push($datos, $datos_user);

        endforeach;
    endif;

    $data = $datos;

    // # call the class and generate the excel file from the $data
    $writer = new XLSXWriter();
    $writer->writeSheet($data);
    $writer->writeToFile($fileLocation);

    // # prompt download popup
    header('Content-Description: File Transfer');
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment; filename=" . basename($fileLocation));
    header("Content-Transfer-Encoding: binary");
    header("Expires: 0");
    header("Pragma: public");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header('Content-Length: ' . filesize($fileLocation));

    ob_clean();
    flush();

    readfile($fileLocation);
    unlink($fileLocation);
    exit;


  }