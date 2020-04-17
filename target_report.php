<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php $EW_ROOT_RELATIVE_PATH = ""; ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$target_report_php = NULL; // Initialize page object first

class ctarget_report_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = '{B6A7A568-E606-4B9C-A82F-C80372444DA0}';

	// Table name
	var $TableName = 'target_report.php';

	// Page object name
	var $PageObjName = 'target_report_php';

	// Page headings
	var $Heading = '';
	var $Subheading = '';

	// Page heading
	function PageHeading() {
		global $Language;
		if ($this->Heading <> "")
			return $this->Heading;
		if (method_exists($this, "TableCaption"))
			return $this->TableCaption();
		return "";
	}

	// Page subheading
	function PageSubheading() {
		global $Language;
		if ($this->Subheading <> "")
			return $this->Subheading;
		return "";
	}

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'custom', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'target_report.php', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"]))
			$GLOBALS["gTimer"] = new cTimer();

		// Debug message
		ew_LoadDebugMsg();

		// Open connection
		if (!isset($conn))
			$conn = ew_Connect();

		// User table object (user)
		if (!isset($UserTable)) {
			$UserTable = new cuser();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanReport()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 
		// Global Page Loading event (in userfn*.php)

		Page_Loading();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		// Close connection

		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			ew_SaveDebugMsg();
			header("Location: " . $url);
		}
		exit();
	}

	//
	// Page main
	//
	function Page_Main() {

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("custom", "target_report_php", $url, "", "target_report_php", TRUE);
		$this->Heading = $Language->TablePhrase("target_report_php", "TblCaption"); 
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($target_report_php)) $target_report_php = new ctarget_report_php();

// Page init
$target_report_php->Page_Init();

// Page main
$target_report_php->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();
?>
<?php include_once "header.php" ?>
<?php include_once "header.php" ?>
<?php

$month="";
$year="";

if(isset($_REQUEST['datepicker']) && $_REQUEST['datepicker'] !="" )
{
	$date = $_REQUEST['datepicker'];
	$date = explode("/",$date);
	
	$month = $date[0];
	$year = $date[1];
}else{
	$month = date("F");
	$year = date("Y");
}



function getStates()
{
	$query = "SELECT state FROM stores GROUP BY state";
$myRows = ew_ExecuteRows($query);

return $myRows;

}

function getLocations($state)
{
	$query = "SELECT sn, store_name FROM stores WHERE state='".$state."'";
$myRows = ew_ExecuteRows($query);
return $myRows;
}

function getCategories()
{
  	$query = "SELECT sn, category FROM product_categories LIMIT 1,50000";
$myRows = ew_ExecuteRows($query);

return $myRows;
}

function getProductQuantitySold($category, $month, $year, $location)
{
  	$query = "SELECT SUM(quantity) AS qty FROM transactions INNER JOIN products ON
  	transactions.product=products.sn INNER JOIN user ON transactions.user=user.sn
  	 WHERE products.product_category=".$category." AND 
	   YEAR(transactions.transaction_date)='".$year."'
  	 AND MonthName(transactions.transaction_date) ='".$month."' AND user.store_location=".$location;

	  //echo $query."<br />";
 $qty = ew_ExecuteScalar($query);
return $qty;
}

function getTargetQty($category, $month, $year, $location)
{
  	$query = "SELECT target_value FROM location_target
  	 WHERE product_category=".$category." AND
  	 YEAR(location_target.date)='".$year."'
  	 AND MonthName(location_target.date) ='".$month."' AND location_target.location=".$location;

  
	$qty = ew_ExecuteScalar($query);
	return $qty;
}

$targets_array = array();
$locations_array = array();
$statesarray = getStates();
$select_states_array = $statesarray;

if(isset($_REQUEST['locations']) && $_REQUEST['locations']!="" && $_REQUEST['locations']!="All"){
	$statesarray = array();
	$statesarray[] = array("state"=>$_REQUEST['locations']);
}

$print_location = false;

foreach($statesarray as $state)
{
  $locations = getLocations($state['state']);
	foreach($locations as $location)
	{
		$categories = getCategories();
		  foreach($categories as $category)
		  {
			  $qty_sold = getProductQuantitySold($category['sn'], $month, $year, $location['sn']);
			  $target_qty = getTargetQty($category['sn'], $month, $year, $location['sn']);
			  $targets_array[] = array("category_sn" => $category['sn'], "category_name" =>$category['category'],
			  "quantity_sold" => $qty_sold, "target"=>$target_qty);
		  }
		$locations_array[] = array("location_state"=>$state, "location_sn" => $location['sn'], "location_name" => $location['store_name'],
			"target_array" => $targets_array);
			$targets_array = array();
	}

}

?>

<div class="panel panel-default ewGrid" style="border-radius: 8px; width:100%; padding:20px;">
<div id="gmp_admin" class="table-responsive ewGridMiddlePanel" style="width:70%;" >
<form method="get" id="frmsearch" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="<%= EW_TOKEN_NAME %>" value="<%= Page.Token %>">

<div class="row" style="border-bottom:1px solid #cfcfcf;"><div class="col-md-3"><h4>Target Report For <?php echo $month.", ".$year; ?></h4></div>
<div class="col-md-2" style="padding:5px;">
<input type='text' placeholder="Select month and year" class="form-control" id="datepicker" name="datepicker" style="margin:5px;" />
</div>
<div class="col-md-2" ></div>
<div class="col-md-2" style="padding:5px;">
<select id="locations" name="locations" class="form-control" style="margin:5px;">
	<option>Select state</option>
	<option value="All">All Locations</option>
	<?php
	foreach($select_states_array as $state)
	{
	 ?>
   <option value="<?php echo $state['state']; ?>"><?php echo $state['state']; ?></option>
  <?php
	}

	?>
</select>
</div>
<div class="col-md-3" style="padding:5px;"><span data-phrase="ExportToExcel" id="exp2excel" class="icon-excel ewIcon" data-caption="Export to Excel"></span></div>
</div>
</form>
<?php

foreach($statesarray as $state)
{

	?>
<div id="data-report">
 <h3><?php echo $state['state']; ?></h3>
 <?php 
 foreach($locations_array as $locationvals)
   {
	   if($locationvals['location_state']==$state){ 
	   ?>
<table cellspacing="0" class="ewTable ewTableSeparate" style="border-left:1px solid #cfcfcf; border-right:1px solid #cfcfcf;">
	<thead>
	<tr class="ewTableHeader">
	<td>Outlet</td><td>Category</td><td>Qty Sold</td><td>Target</td></tr>
	</thead>
		<tbody id="qst-tbody">
			<?php
				$target_vals = $locationvals['target_array'];
				$rowspans = count($target_vals);

				foreach($target_vals as $target_val){
			?>
			<tr class="ui-state-default">

				
				<?php if($print_location==false){?>
						<td rowspan="<?php echo $rowspans; ?>" valign="top" style="width:20%;"><?php echo  $locationvals['location_name']; ?></td>
						<?php
						$print_location=true;	
				}

				?>
				<td><?php echo  $target_val['category_name']; ?></td>

				<td><?php echo  $target_val['quantity_sold']; ?></td>
				<td><?php echo  $target_val['target']; ?></td>

				
			</tr>
				<?php 
				} 
			
				?>

		</tbody>
</table>
<?php
$print_location=false;
echo "<br />";
}
}

}
?>
</div>
</div>
</div>

<script type="text/javascript">
		$(document).ready(function() {
			
			$('#datepicker').datetimepicker({
				viewMode: 'years',
				format: 'MMMM/YYYY'
			});

			$('#datepicker').on('input',function(e){
			
				//$('#frmsearch').submit();
				 });
			
			$('#locations').change(function(){
				$('#frmsearch').submit();
				
			})

			 $("#exp2excel").click(function () {
			   
			  // var outputFile = window.prompt("Name your file") || 'export';
			  // outputFile = outputFile.replace('.csv','') + '.csv'             
			   // CSV
			  // exportTableToCSV.apply(this, [$('.ewTable'), outputFile]);
			   window.open('data:application/vnd.ms-excel,' +  encodeURIComponent($('#data-report').html()));
			 });
		});
	</script>
		<script src="phpjs/excel_functions.js"></script>

<?php

function debugCode($message)
{
	 $fp = fopen("debug.txt", "a");
		fwrite($fp,$message);
		fclose($fp);
}
?>
<?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
<?php include_once "footer.php" ?>
<?php
$target_report_php->Page_Terminate();
?>
