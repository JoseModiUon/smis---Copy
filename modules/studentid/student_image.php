<?php
function getImagePathExist($regNo, $photoPath, $photoExt, $source = 'smis')
{
    global $db;
    $photoFileName = "";
    $fullPhotoFileName = "";
    $photoFileName = $photoPath . str_replace("/", "_", $regNo) . "." . $photoExt;

    if ($source == 'smis') {
        $fullPhotoFileName = $_SERVER['DOCUMENT_ROOT'] . $photoFileName;
        if (!(file_exists($_SERVER['DOCUMENT_ROOT'] . $photoFileName))) {
            $photoFileName = str_replace("_2001." . $photoExt, "_01." . $photoExt, $photoFileName);
            $photoFileName = str_replace("_2002." . $photoExt, "_02." . $photoExt, $photoFileName);
            $photoFileName = str_replace("_2003." . $photoExt, "_03." . $photoExt, $photoFileName);
            $photoFileName = str_replace("_2004." . $photoExt, "_04." . $photoExt, $photoFileName);
            $photoFileName = str_replace("_2005." . $photoExt, "_05." . $photoExt, $photoFileName);
            $photoFileName = str_replace("_2006." . $photoExt, "_06." . $photoExt, $photoFileName);
            $photoFileName = str_replace("_2007." . $photoExt, "_07." . $photoExt, $photoFileName);
            $photoFileName = str_replace("_2008." . $photoExt, "_08." . $photoExt, $photoFileName);
            $photoFileName = str_replace("_2009." . $photoExt, "_09." . $photoExt, $photoFileName);
            $photoFileName = str_replace("_2010." . $photoExt, "_10." . $photoExt, $photoFileName);
        }
        if (!(file_exists($_SERVER['DOCUMENT_ROOT'] . $photoFileName))) {
            $photoFileName = "";
        }
    }
    return $photoFileName;
}

function getImageFromDatabase($regNo, $imageTable)
{
    $mysqlDB = GetMySQLDB();
    $photoFileName = "";
    if ($mysqlDB) {
        $imageExistQuery = "Select  $imageTable.registration_number
	From  $imageTable
	Where  $imageTable.registration_number = '$regNo'";
        $imageExist = $mysqlDB->GetRow($imageExistQuery);
        if ($imageExist) {
            $photoFileName = "uploadedimage.php?Regno=" . $regNo;
        } // if ($imageExist) {
    } // if ($mysqlDB) {
    return $photoFileName;
}//function getImageFromDatabase

function getStudentPhotoSource($regNo)
{
    $studentPhotoSource = getImageSource($regNo);
    return $studentPhotoSource;
}//function getStudentPhotoSource

/**
 * Return the full path of the photo
 * @param $regNo
 * @return mixed|string
 */
function getImageSource($regNo)
{
    $imageSource = "";
    $photoFileName = "";
    $photoExt = "jpg";
    $source = 'smis';
    $isPhotoCaptured = IsBiometricCaptured($regNo, 'PHOTO');

    if ($isPhotoCaptured) {
        //http://bsris.uonbi.ac.ke/BSRISPHOTO/A22_47547_2017.JPG
        $source = 'bsris';
        $newPhotoHomePath = "//bsris.uonbi.ac.ke/BSRISPHOTO/";
        $photoFileName = $newPhotoHomePath . str_replace("/", "_", $regNo) . "." . $photoExt;
    } else {
        $newPhotoHomePath = "/smis/students_id/students_photos/";

    $photoFileName = getImagePathExist($regNo, $newPhotoHomePath, $photoExt, $source);
    }

    if ($source == 'smis') {
        if (!(file_exists($_SERVER['DOCUMENT_ROOT'] . $photoFileName))) {
            $imageSource = "uploadedimage.php?Regno=" . $regNo;
            $imageSource = "";
        } else {
            $imageSource = $photoFileName;
        }
    } elseif ($source == 'bsris') {
        $imageSource = $photoFileName;
    }
    return $imageSource;
}

/**
 * Return the full path of the signature
 * @param $regNo
 * @return mixed|string
 */
function getStudentSignSource($regNo)
{

    $imageSource = "";
    $photoFileName = "";
    $photoExt = "gif";
    $source = 'smis';
    $isPhotoCaptured = IsBiometricCaptured($regNo, 'PHOTO');



    if ($isPhotoCaptured) {
        //http://bsris.uonbi.ac.ke/BSRISSIGNATURE/A22_47547_2017.GIF
        $source = 'bsris';
        $newPhotoHomePath = "//bsris.uonbi.ac.ke/BSRISSIGNATURE/";
        $photoFileName = $newPhotoHomePath . str_replace("/", "_", $regNo) . "." . $photoExt;
    } else {
        $newPhotoHomePath = "/smis/students_id/students_signs/";
        $photoFileName = getImagePathExist($regNo, $newPhotoHomePath, $photoExt);
    }

    if ($source == 'smis') {
        if (!(file_exists($_SERVER['DOCUMENT_ROOT'] . $photoFileName))) {
            $imageSource = "uploadedimage.php?Regno=" . $regNo;
            $imageSource = "";
        } else {
            $imageSource = $photoFileName;
        }
    } elseif ($source == 'bsris') {
        $imageSource = $photoFileName;
    }
    
    return $imageSource;
}

function getUploadFileExt($htmlParameterName)
{
    global $_FILES;
    $separatorPos = 0;
    $fileExt = "";

    $uploadName = $_FILES[$htmlParameterName]['name'];
    $separatorPos = strrpos($uploadName, ".");
    if ((strlen(trim($separatorPos))) && (is_numeric(trim($separatorPos)))) {
        $separatorPos = $separatorPos;
    } else {
        $separatorPos = 0;
    } // if ((strlen(trim($separatorPos))) && (is_numeric(trim($separatorPos)))) {
    if ($separatorPos > 0) {
        $fileExt = substr($uploadName, $separatorPos);
    } // if ($separatorPos > 0) {

    return $fileExt;
}

/**
 * Let us check if the signature or the photo is captured 01-SEP-2017 Sammy B
 * @param $reg_no
 * @param $field PHOTO|SIGNATURE
 * @return bool
 */
function IsBiometricCaptured($reg_no, $field)
{
//return false;
    //$reg_no = 'E37/84285/2017';
    global $db;
    $item_captured = false;
    $captured = 'N';
    switch (strtoupper($field)) {
        case 'PHOTO':
            $sql = <<<PHOTO_QUERY
SELECT
BSRIS.BRSIS_STUDENTS.PHCAPTURED
FROM
BSRIS.BRSIS_STUDENTS
WHERE BSRIS.BRSIS_STUDENTS.REGISTRATION_NUMBER = '$reg_no'
PHOTO_QUERY;
            break;
        case 'SIGNATURE':
        case 'SIGN':
            $sql = <<<SIGNATURE_QUERY
SELECT
BSRIS.BRSIS_STUDENTS.SGCAPTURED
FROM
BSRIS.BRSIS_STUDENTS
WHERE BSRIS.BRSIS_STUDENTS.REGISTRATION_NUMBER = '$reg_no'
SIGNATURE_QUERY;

            break;
        default:
            $sql = 'EMPTY';
            break;
    }

    if ($sql != 'EMPTY') {
        $captured =$db->GetOne($sql);
    }

    if (strlen($captured) < 1 || $captured == 'N') {
        $item_captured = false;
    } else if ($captured == 'Y') {
        $item_captured = true;
    }
    return $item_captured;
}
