<?php
session_start();
include "./includes/smispagesetup.php";
include "./includes/examfunctions.php";
include "./includes/upload_functions.php";
include "./includes/student_image.php";
$smissubtitle = "<A href=/smis/application_system.php> Self Sponsored Subsystem</a>";
 $regNo=getParameter('regNo');
 $regNo=strtoupper(trim($regNo));  

   
   CCSetSession('docwidth',400);

 $StudentIDCardBtn=getParameter('StudentIDCardBtn'); 
/* if($StudentIDCardBtn) {
// 	echo set_docheader($smissubtitle);
	echo setStudentIDheader($smissubtitle);
	echo getStudentPersonalInformation();
 	echo docClosingTagsID();
	} else { 
	*/
  	echo set_headercontents($smissubtitle);
	echo getStudentNo(); 
 	if($StudentIDCardBtn) {
	echo getStudentPersonalInformation();
	} // if($StudentIDCardBtn) {
	echo set_footercontents();
 	echo ClosingTags();

 
function getStudentNo() {
 $currentURL = $_SERVER['PHP_SELF'] ;
global $db;
$regNo = strtoupper(trim(getParameter('regNo')));  
 $StudentIDCardBtn=getParameter('StudentIDCardBtn'); 
 $vretlogin = "";
  $vretlogin = "\n<table border=\"1\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\">\n";
  $vretlogin .= "<FORM action=\"".$currentURL."\" name=\"studentIDCard\"";
  $vretlogin .= " enctype=\"multipart/form-data\" method=\"post\"> \n";
  $vretlogin .= "<tr align=\"center\" ><td  class=\"dentry_label\">Student ID Card \n";
  $vretlogin .= " Generation </td></tr> \n ";
  $vretlogin .= "  <tr align=\"center\"> <td > \n";
  $vretlogin .= " <table border=\"1\" align=\"top\" cellspacing=\"0\" cellpadding=\"0\">\n";
  $vretlogin .= " <tr align=\"center\"> <td class=\"dentry_label\">Registration Number :  ";
  $vretlogin .= "<INPUT type=\"text\" name=\"regNo\" size=\"20\" value=\"".$regNo."\">";
  $vretlogin .= "</td> </tr> \n";
  $vretlogin .= "  <tr align=\"center\"> <td>\n ";
  $vretlogin .= " <INPUT name=\"StudentIDCardBtn\" type=\"submit\" value=\"Continue ";
  $vretlogin .= "with Student ID Card Generation\"  class=\"inputbutton\">&nbsp;&nbsp; ";
$checkReIssueQuery = " Query  <BR> " ;

 if($StudentIDCardBtn) {
	$smisSystemDate = date('d-M-Y');
 if (getRolePrivs('SMIS_ID_PROCESSOR_POST')) {
	 $studentType=2;
	$checkReIssueQuery =" Select All
  		MUTHONI.STUDENTS_ID_REQUESTS_VIEW.REGISTRATION_NUMBER,
  		MUTHONI.STUDENTS_ID_REQUESTS_VIEW.REQUEST_ID,
  		MUTHONI.STUDENTS_ID_REQUESTS_VIEW.REQUEST_DATE,
  		MUTHONI.STUDENTS_ID_REQUESTS_VIEW.REQUEST_TYPE,
  		TO_CHAR(MUTHONI.STUDENTS_ID_REQUESTS_VIEW.VALID_FROM, 'DD-MON-YYYY') AS VALID_FROM, 
  		TO_CHAR(MUTHONI.STUDENTS_ID_REQUESTS_VIEW.VALID_TO, 'DD-MON-YYYY') AS VALID_TO 
		From  MUTHONI.STUDENTS_ID_REQUESTS_VIEW
		Where MUTHONI.STUDENTS_ID_REQUESTS_VIEW.DEGREE_TYPE IN ('Masters','Ph.D','PGDIP','PGDip')  AND  MUTHONI.STUDENTS_ID_REQUESTS_VIEW.REGISTRATION_NUMBER = '$regNo' And
  		TO_DATE(TO_CHAR(NVL(MUTHONI.STUDENTS_ID_REQUESTS_VIEW.VALID_FROM,'01-JAN-1900'), 'DD-MON-YYYY'),
  		'DD-MON-YYYY') <= TO_DATE('$smisSystemDate', 'DD-MON-YYYY') And
  		TO_DATE(TO_CHAR(NVL(MUTHONI.STUDENTS_ID_REQUESTS_VIEW.VALID_TO,'01-JAN-1900'), 'DD-MON-YYYY'),
  		'DD-MON-YYYY') >= TO_DATE('$smisSystemDate', 'DD-MON-YYYY') " ;
 }else {
	 $studentType=1;
	$checkReIssueQuery =" Select All
  		MUTHONI.STUDENTS_ID_REQUESTS_VIEW.REGISTRATION_NUMBER,
  		MUTHONI.STUDENTS_ID_REQUESTS_VIEW.REQUEST_ID,
  		MUTHONI.STUDENTS_ID_REQUESTS_VIEW.REQUEST_DATE,
  		MUTHONI.STUDENTS_ID_REQUESTS_VIEW.REQUEST_TYPE,
  		TO_CHAR(MUTHONI.STUDENTS_ID_REQUESTS_VIEW.VALID_FROM, 'DD-MON-YYYY') AS VALID_FROM, 
  		TO_CHAR(MUTHONI.STUDENTS_ID_REQUESTS_VIEW.VALID_TO, 'DD-MON-YYYY') AS VALID_TO 
		From  MUTHONI.STUDENTS_ID_REQUESTS_VIEW
		Where MUTHONI.STUDENTS_ID_REQUESTS_VIEW.DEGREE_TYPE IN ('Bachelor','Dip','Certificate')  AND  MUTHONI.STUDENTS_ID_REQUESTS_VIEW.REGISTRATION_NUMBER = '$regNo' And
  		TO_DATE(TO_CHAR(NVL(MUTHONI.STUDENTS_ID_REQUESTS_VIEW.VALID_FROM,'01-JAN-1900'), 'DD-MON-YYYY'),
  		'DD-MON-YYYY') <= TO_DATE('$smisSystemDate', 'DD-MON-YYYY') And
  		TO_DATE(TO_CHAR(NVL(MUTHONI.STUDENTS_ID_REQUESTS_VIEW.VALID_TO,'01-JAN-1900'), 'DD-MON-YYYY'),
  		'DD-MON-YYYY') >= TO_DATE('$smisSystemDate', 'DD-MON-YYYY') " ; 
 }
// print $checkReIssueQuery;
	$checkReIssuetDetails = $db->GetRow($checkReIssueQuery);
	if  ($checkReIssuetDetails) {	
    	$requestID = $checkReIssuetDetails['REQUEST_ID'];
    	$requestDate = $checkReIssuetDetails['REQUEST_DATE'];
    	$currentReqType = $checkReIssuetDetails['REQUEST_TYPE'];
		$loggedOnUser = CCGetUserID();
		$loggedOnUser = strtoupper(trim($loggedOnUser));  
		$htmlParam = "realm=print&requestID=".$requestID; 
		$htmlParam .= "&requestDate=".$requestDate."&regNo=".$regNo; 
		$htmlParam .= "&oracleUser=".$loggedOnUser."&studentType=".$studentType;

        $photoCaptured = IsBiometricCaptured($regNo, 'PHOTO');
        $signaturedCaptured = IsBiometricCaptured($regNo, 'SIGNATURE');
        if ($photoCaptured && $signaturedCaptured) {
            //show the bsris links else fall back to the default one, we will add an id for bsris in the html params
            $htmlParam .= "&source=bsris";
        }else{
            $htmlParam .= "&source=smis";
        }


		//print "kiplagat02";
 		if (getRolePrivs('SMIS_ID_ADMIN')) {
		/* if  (($loggedOnUser == "MUTHONI" )  or ($loggedOnUser == "MURAGURI" ) 
		or ($loggedOnUser == "MUTHONI" ) or ($loggedOnUser == "HKAMAU" ) ) {
		*/

  		$vretlogin .= "<A href=\"http://smis.uonbi.ac.ke/ids/student_id_card.php?".$htmlParam."\">";
		$vretlogin .= " Re-Print Student ID </A> $requestType " ;  
		} // if (getRolePrivs('SMIS_ID_ADMIN')) {
	} // if  ($checkReIssuetDetails) {
	} // if($StudentIDCardBtn) {

  $vretlogin .= " </td> </tr> \n";
  $vretlogin .= "  <tr align=\"center\"> <td>\n ";
//  $vretlogin .= "  <BR> $checkReIssueQuery <BR> \n ";
  $vretlogin .= "<a href=\"/smis/application_system.php\">Back to Self Sponsored Subsystem  </td> </tr> \n";
  $vretlogin .= "</table></td></tr>";
  $vretlogin .= "</FORM></table>\n";
  return $vretlogin;
}

function getStudentPersonalInformation() {
global $db;
$regNo=strtoupper(trim(getParameter('regNo')));  
$courseReg = "";
 if ((getRolePrivs('SMIS_ADMISSIONS_ADMIN')) or (getRolePrivs('SMIS_ID_PROCESSOR_UNDER')) ) {
 $SQLStatement="SELECT ALL 
 	MUTHONI.UON_STUDENTS.REGISTRATION_NUMBER,
	MUTHONI.UON_STUDENTS.SURNAME, MUTHONI.UON_STUDENTS.OTHER_NAMES, 
	NVL(MUTHONI.STUDENTS_INFORMATION.NATIONAL_ID,
	MUTHONI.UON_STUDENTS.NATIONAL_ID) AS NATIONALID, 
	MUTHONI.DEGREE_PROGRAMMES.DEGREE_CODE, 
	MUTHONI.DEGREE_PROGRAMMES.DEGREE_NAME, 
	MUTHONI.GROUPS.GROUP_NAME ,
	MUTHONI.DEGREE_PROGRAMMES.BILLABLE, 
	MUTHONI.DEGREE_PROGRAMMES.FACUL_FAC_CODE, 
	MUTHONI.FACULTIES.FACULTY_NAME, 
	MUTHONI.DEGREE_PROGRAMMES.BILLING_START_YEAR,
	MUTHONI.STUDENTS_INFORMATION.LIBRARY_BARCODE,
	DECODE(MUTHONI.UON_STUDENTS.STC_STUDENT_CATEGORY_ID,
	'001', 'I', '002', 'I', '003', 'II', '004', 'II', 'II') AS MODULE, 
	NVL(MUTHONI.STUDENTS_INFORMATION.DEFAULT_GROUP, 
	DECODE(DECODE(MUTHONI.UON_STUDENTS.STC_STUDENT_CATEGORY_ID,
	'001', 'I', '002', 'I', '003', 'II', '004', 'II', 'II'),
	'I', '10', 'II', '99', '99')) AS STUDENT_GROUP
	FROM MUTHONI.UON_STUDENTS, MUTHONI.STUDENTS_INFORMATION, 
	MUTHONI.DEGREE_PROGRAMMES , MUTHONI.FACULTIES ,
	MUTHONI.GROUPS
	WHERE MUTHONI.UON_STUDENTS.REGISTRATION_NUMBER='$regNo'
	AND  ((MUTHONI.UON_STUDENTS.REGISTRATION_NUMBER=
	MUTHONI.STUDENTS_INFORMATION.REGISTRATION_NUMBER(+))
	AND (NVL(MUTHONI.STUDENTS_INFORMATION.DEFAULT_GROUP, 
	DECODE(DECODE(MUTHONI.UON_STUDENTS.STC_STUDENT_CATEGORY_ID,
	'001', 'I', '002', 'I', '003', 'II', '004', 'II', 'II'),
	'I', '10', 'II', '99', '99')) = MUTHONI.GROUPS.GROUP_CODE)
	AND (MUTHONI.UON_STUDENTS.D_PROG_DEGREE_CODE=
	MUTHONI.DEGREE_PROGRAMMES.DEGREE_CODE)
	AND  (MUTHONI.DEGREE_PROGRAMMES.FACUL_FAC_CODE=
	MUTHONI.FACULTIES.FAC_CODE))";
	}
 	$studentDetails = $db->GetRow($SQLStatement);
	if ($studentDetails){
		$studentNames=$studentDetails['SURNAME']." ".$studentDetails['OTHER_NAMES'];
		$degCode=$studentDetails['DEGREE_CODE'];
		$nationalID=$studentDetails['NATIONALID'];
		$libraryBarcode=$studentDetails['LIBRARY_BARCODE'];
		$barcodeDigits = 4 ;
		if (strlen(trim($libraryBarcode)) < $barcodeDigits ) {
		$SQLStatement2=" SELECT MUTHONI.LIBRARY_BARCODE_SEQ.NEXTVAL 
				FROM DUAL";
		$libraryBarcode = $db->GetOne($SQLStatement2);

 		if (is_numeric($libraryBarcode)) {
		$libraryBarcode = $libraryBarcode."7";
		$SQLRowExist=" SELECT ALL 
		MUTHONI.STUDENTS_INFORMATION.REGISTRATION_NUMBER,
		MUTHONI.STUDENTS_INFORMATION.LIBRARY_BARCODE
		FROM MUTHONI.STUDENTS_INFORMATION
		WHERE MUTHONI.STUDENTS_INFORMATION.REGISTRATION_NUMBER='$regNo'";
		$recordExist = $db->GetRow($SQLRowExist);
		if ($recordExist){
		$SQLUpdateRecord=" UPDATE MUTHONI.STUDENTS_INFORMATION
		SET STUDENTS_INFORMATION.LIBRARY_BARCODE = '$libraryBarcode'
		WHERE MUTHONI.STUDENTS_INFORMATION.REGISTRATION_NUMBER='$regNo'";
		 $executeResult = $db->Execute($SQLUpdateRecord);
		} else { // if ($recordExist){
		$SQLInsertRecord =" INSERT INTO  MUTHONI.STUDENTS_INFORMATION 
   			(REGISTRATION_NUMBER, LIBRARY_BARCODE) 
			VALUES ('$regNo','$libraryBarcode')  " ;
		 $executeResult = $db->Execute($SQLInsertRecord);
		}// else if ($recordExist){
		} // if (is_numeric($libraryBarcode)) {
	
		} // if (!strlen(trim($libraryBarcode))) {
//		$libraryBarcode = "99172330";
		$StudentCategory=$studentDetails[MODULE];
		$studentGroup=$studentDetails[GROUP_NAME];
		$programmeName=$studentDetails[DEGREE_NAME];
		$facultyName=$studentDetails[FACULTY_NAME];
		if (strlen(trim($studentGroup)) > 0 ) 
		$StudentCategory.="(".$studentGroup.")";// 
	$dateOfIssue=date('d-M-Y');
	$expiryDateID=strtoupper(date('Y-m-d'));
	$expiryDateID = strtotime($expiryDateID);  
	$expiryDateID = ( $expiryDateID + (364 * 3600 * 24) );  
	$expiryDate=date("d-M-Y",($expiryDateID));

        $studentSignSource = getStudentSignSource($regNo);
        $studentPhotoSource = getStudentPhotoSource($regNo);

        /* end of Watermark  */
$courseReg .="<DIV class=\"idfrontpage\"><!�[if IE 7]>";
$courseReg .= "<br style=\"height:0; line-height:0\"><![endif]�> \n";
$courseReg .="<DIV class=\"studentid\"><!�[if IE 7]>";
$courseReg .="<br style=\"height:0; line-height:0\"><![endif]�> \n";
/* end of Watermark */
$courseReg .="<DIV class=\"studentid\"> \n";
  	$courseReg .= "\n<table border=\"0\" align=\"left\" cellspacing=\"0\" cellpadding=\"0\" >\n";
  	$courseReg .= "<tr align=\"left\" ><td  class=\"dentry_label\">";
$imageSource = "/smis/students_id/uon_id_front_main.jpg";
$courseReg .="<p class=\"centeredImage\"> <img src=".$imageSource;
$courseReg .=" alt=\"Student Passport\" height=\"320\" width=\"500\" ";
$courseReg .=" class=\"ImageBorder\" > </p>";
// D61/7384/2006     ";
  	$courseReg .= "</td></tr> \n ";
  	$courseReg .= "</table>";
$courseReg .="</DIV>\n";
/* end of Watermark */
$courseReg .="<DIV class=\"student_id_front\"> \n";
  	$courseReg .= "\n<table border=\"0\" align=\"left\" cellspacing=\"0\" cellpadding=\"0\">\n";
  	$courseReg .= "  <tr align=\"center\"> <td > \n";
        
  $courseReg .="<p class=\"centeredImage\"> ";
if (strlen(trim($studentPhotoSource)) < 2 )   {
	$courseReg .= " Photo Missing " ;
} else { // if (strlen(trim($studentPhotoexist)) < 2 )   {
  $courseReg .="<img src=".$studentPhotoSource;
  $courseReg .=" alt=\"Student Passport\" height=\"120\" width=\"100\" ";
  $courseReg .=" class=\"ImageBorder\" > ";
} // if (strlen(trim($studentPhotoexist)) < 2 )   {
  $courseReg .=" </p> </td> ";
  $courseReg .= " <td width=\"355\"> \n";
  $courseReg .= " <table border=\"0\" align=\"top\" cellspacing=\"0\" ";
  $courseReg .= " cellpadding=\"0\" width=\"355\">\n";
  $courseReg .= " <tr > <td align=\"center\" >";
  $courseReg .="<p class=\"ID_HEAD\" > ";
  $courseReg .=" UNDERGRADUATE STUDENT ID CARD</p>  ";
  $courseReg .= " </td> </tr> \n";
  $courseReg .= " <tr > <td align=\"left\" > \n";
  $courseReg .="<p class=\"ID_NAMES\" > ".$studentNames ."  </p> \n ";
  $courseReg .="<p class=\"ID_NAMES\" > Reg. No:".$regNo ."  </p> \n ";
  $courseReg .="<p class=\"ID_NAMES\" > ID/PP NO:".$nationalID ."  </p> \n ";
  $courseReg .="<p class=\"ID_NAMES\" > ".$programmeName ."  </p> \n ";
  $courseReg .="<p class=\"ID_NAMES\" > ".$facultyName ."  </p> \n ";
  $courseReg .= " </td> </tr> \n";
  	$courseReg .= "</table></td></tr>";

  $courseReg .= " <tr > <td align=\"center\"> &nbsp; &nbsp; </td> ";
  $courseReg .= "<td align=\"center\">  &nbsp; &nbsp; </td> </tr> \n";
/*  $courseReg .= " <tr > <td align=\"center\"> &nbsp; &nbsp; </td> ";
  $courseReg .= "<td align=\"center\">  &nbsp; &nbsp; </td> </tr> \n";
  $courseReg .= " <tr > <td align=\"center\"> &nbsp; &nbsp; </td> ";
  $courseReg .= "<td align=\"center\">  &nbsp; &nbsp; </td> </tr> \n";
  $courseReg .= " <tr > <td align=\"center\"> Holder's Sign </td> ";
  $courseReg .= "<td align=\"center\">  Academic Registrar Sign </td> </tr> \n";
  $courseReg .= " <tr > <td align=\"right\" colspan=2 >";
  $courseReg .= "ISO 9001:2008 Certified </td> </tr> \n";
*/
  	$courseReg .= "</table>\n";
	} else {
  	$courseReg = "\n<table border=\"1\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\">\n";
  	$courseReg .= "<tr align=\"center\" ><td  class=\"dentry_label\">Wrong Student ";
  	$courseReg .= " Registration Number. Try Again </td></tr> \n ";
   	$courseReg .= "<tr align=\"center\" ><td  >".getStudentNo()."</td></tr> \n ";
 	$courseReg .= "</table>\n";
	}
$courseReg .="</DIV>\n";
$courseReg .="</DIV>\n";
$courseReg .="</DIV>\n";

$courseReg .="<DIV class=\"idbackpage\" STYLE=\"page-break-before: always\"> \n";
$courseReg .="<DIV class=\"studentid\"> \n";
  	$courseReg .= "\n<table border=\"0\" align=\"left\" cellspacing=\"0\" cellpadding=\"0\" >\n";
  	$courseReg .= "<tr align=\"left\" ><td  class=\"dentry_label\">";
$imageSource = "/smis/students_id/fountain_logo.jpg";
$courseReg .="<p class=\"centeredImage\"> <img src=".$imageSource;
$courseReg .=" alt=\"Student Passport\" height=\"320\" width=\"500\" ";
// D61/7384/2006     ";
$courseReg .=" class=\"ImageBorder\" > </p>";
  	$courseReg .= "</td></tr> \n ";
  	$courseReg .= "</table>";
$courseReg .="</DIV>\n";
$courseReg .="<DIV class=\"studentid\"> \n";
  	$courseReg .= "\n<table border=\"0\" align=\"left\" cellspacing=\"0\" cellpadding=\"0\" ";
  	$courseReg .= ">\n";
  	$courseReg .= "<tr align=\"left\" ><td colspan=\"2\" nowrap> &nbsp; &nbsp;</td></tr> \n ";
  	$courseReg .= "<tr align=\"left\" > <td nowrap> &nbsp; &nbsp;</td>  <td nowrap>";
  	$courseReg .= "&nbsp; </td></tr> \n ";
  	$courseReg .= "<tr align=\"left\" ><td nowrap> &nbsp; &nbsp;</td> <td nowrap> ";
  	$courseReg .= "Issued $dateOfIssue </td></tr> \n ";
  	$courseReg .= "<tr align=\"left\" ><td nowrap> &nbsp; &nbsp;</td> <td nowrap> ";
  	$courseReg .= "Expires $expiryDate </td></tr> \n ";
  	$courseReg .= "</table>";
$courseReg .="</DIV>\n";
$courseReg .="<DIV class=\"studentid\"> \n";

$courseReg .="<DIV class=\"idRegBarcode\"> \n";
if ((strlen(trim($libraryBarcode)) >= $barcodeDigits ) 
		&& (is_numeric($libraryBarcode)) ) {
  	$imageSource = "\"./Barcode/barcode_img.php?num=".$libraryBarcode;
  	$imageSource .= "&type=code128&imgtype=png\" ";
$courseReg .="<p class=\"centeredImage\" align=\"center\" > <img src=".$imageSource;
//$courseReg .=" alt=\"Student Number\" ";
$courseReg .=" class=\"ImageBorder\" > </p>";
} //if ((strlen(trim($libraryBarcode)) >= 8)
  	$imageSource = "\"./Barcode/barcode_img.php?num=".$regNo;
  	$imageSource .= "&type=code128&imgtype=png\" ";
$courseReg .="<p class=\"centeredImage\" align=\"center\" > <img src=".$imageSource;
//$courseReg .=" alt=\"Student Number\" ";
$courseReg .=" class=\"ImageBorder\" > </p>";
$courseReg .="</DIV>\n";
/*
  	$courseReg .= "\n<table border=\"0\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\" ";
  	$courseReg .= "width=\"480\" >\n";
	$emptyRows = 5;
	for($j=0; $j < $emptyRows ; $j++) {
  	$courseReg .= "<tr align=\"left\" ><td colspan=\"2\" nowrap> &nbsp; &nbsp;</td></tr> \n ";
	}
  	$courseReg .= "<tr ><td colspan=\"2\" nowrap > &nbsp; &nbsp;";
  	$courseReg .= "<img src=\"./Barcode/barcode_img.php?num=".$regNo;
  	$courseReg .= "&type=code128&imgtype=png\" ";
  	$courseReg .= "title=\"".$regNo ."\"> \n ";
 	$courseReg .= "</td></tr> \n ";
  	$courseReg .= "</table>";
*/
$courseReg .="</DIV>\n";
$courseReg .="</DIV>\n";
	return $courseReg;
}
