<?php
 session_start();
 include './db_connect.php';
 // include 'mailgun.php';

 
  // include 'class.phpmailer.php';
  // include 'mailtemplate.php'; 
   $order_id=$_POST['order_id'];
   $order_date=$_POST['order_date'];
   $subject=$_POST['subject'];
   $message=$_POST['message'];
   


   // $invoice = $_FILES['invoice']['name'];

   // $ext = pathinfo($invoice, PATHINFO_EXTENSION);
   // $uploaddir = 'images/invoice/';
   // $temp =  $_FILES["invoice"]["name"];
   // $newfilename = "invoice_".date('ymdhis').".".$ext;
    // echo $newfilename; exit;

   // $uploadfile = $uploaddir . $newfilename;

   $error=array();

   $filesave = array();
    $extension=array("jpeg","jpg","png","gif","pdf","doc","docx");
    $i=0;
    foreach($_FILES["files"]["tmp_name"] as $key=>$tmp_name)
            {
                $file_name=$_FILES["files"]["name"][$key];
                $file_tmp=$_FILES["files"]["tmp_name"][$key];
                $ext=pathinfo($file_name,PATHINFO_EXTENSION);
                $newfilename = "invoice_".$i.date('ymdhis').".".$ext;
                if(in_array($ext,$extension))
                {
                    if(!file_exists("images/invoice/".$newfilename))
                    {
                        move_uploaded_file($file_tmp=$_FILES["files"]["tmp_name"][$key],"images/invoice/".$newfilename);
                        array_push($filesave,$newfilename);
                    }
                    else
                    {
                        $filename=basename($file_name,$ext);
                        $newFileName=$filename.time().".".$ext;
                        array_push($filesave,$newFileName);
                        move_uploaded_file($file_tmp=$_FILES["files"]["tmp_name"][$key],"images/invoice/".$newFileName);
                    }
                }
                else
                {
                    array_push($error,"$file_name, ");
                }
                $i++;
            }

   // echo $order_id."<br>".$order_date."<br>".$subject."<br>".$message; exit;

    $str_array_file = implode(", ",$filesave);
  

     $insert_query = 'INSERT INTO `tbl_complain`(order_id, order_date, subject, message, invoice) VALUES ("'.$order_id.'","'.$order_date.'","'.$subject.'","'.$message.'","'.$str_array_file.'")';

     // echo $insert_query; exit;
     $insert = mysqli_query($conn,$insert_query);
  

    if($insert){ 

      $last_insert_id = $conn->insert_id;

      foreach ($filesave as $row) {

         $file_query = 'INSERT INTO `tbl_complain_attachement`(complain_id, file) VALUES ("'.$last_insert_id.'","'.$row.'")';

     
          $file_insert = mysqli_query($conn,$file_query);
       
      }
      
      
      // echo "submit data";
      $_SESSION['complain_message'] =  "We have received your request we will revert back to you shortly!";
    
      header("Location: complain.php");
    }
    else{
      
      // echo "Error";
      
      header("Location: complain.php");
    }
  


  ///////////////



?>

