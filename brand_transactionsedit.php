<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "brand_transactionsinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$brand_transactions_edit = NULL; // Initialize page object first

class cbrand_transactions_edit extends cbrand_transactions {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{B6A7A568-E606-4B9C-A82F-C80372444DA0}';

	// Table name
	var $TableName = 'brand_transactions';

	// Page object name
	var $PageObjName = 'brand_transactions_edit';

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
		if ($this->TableName)
			return $Language->Phrase($this->PageID);
		return "";
	}

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
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
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
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

		// Parent constuctor
		parent::__construct();

		// Table object (brand_transactions)
		if (!isset($GLOBALS["brand_transactions"]) || get_class($GLOBALS["brand_transactions"]) == "cbrand_transactions") {
			$GLOBALS["brand_transactions"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["brand_transactions"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'brand_transactions', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"]))
			$GLOBALS["gTimer"] = new cTimer();

		// Debug message
		ew_LoadDebugMsg();

		// Open connection
		if (!isset($conn))
			$conn = ew_Connect($this->DBID);

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

		// Is modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("brand_transactionslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
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
		// Create form object

		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->sn->SetVisibility();
		$this->sn->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->transaction_date->SetVisibility();
		$this->brand->SetVisibility();
		$this->product->SetVisibility();
		$this->amount->SetVisibility();
		$this->quantity->SetVisibility();
		$this->user->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $brand_transactions;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($brand_transactions);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		// Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();

			// Handle modal response
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "brand_transactionsview.php")
						$row["view"] = "1";
				} else { // List page should not be shown as modal => error
					$row["error"] = $this->getFailureMessage();
				}
				echo ew_ArrayToJson(array($row));
			} else {
				ew_SaveDebugMsg();
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gbSkipHeaderFooter;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		$this->FormClassName = "ewForm ewEditForm form-horizontal";
		$sReturnUrl = "";
		$loaded = FALSE;
		$postBack = FALSE;

		// Set up current action and primary key
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			if ($this->CurrentAction <> "I") // Not reload record, handle as postback
				$postBack = TRUE;

			// Load key from Form
			if ($objForm->HasValue("x_sn")) {
				$this->sn->setFormValue($objForm->GetValue("x_sn"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["sn"])) {
				$this->sn->setQueryStringValue($_GET["sn"]);
				$loadByQuery = TRUE;
			} else {
				$this->sn->CurrentValue = NULL;
			}
		}

		// Load current record
		$loaded = $this->LoadRow();

		// Process form if post back
		if ($postBack) {
			$this->LoadFormValues(); // Get form values
		}

		// Validate form if post back
		if ($postBack) {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}

		// Perform current action
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$loaded) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("brand_transactionslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "brand_transactionslist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetupStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->sn->FldIsDetailKey)
			$this->sn->setFormValue($objForm->GetValue("x_sn"));
		if (!$this->transaction_date->FldIsDetailKey) {
			$this->transaction_date->setFormValue($objForm->GetValue("x_transaction_date"));
		}
		if (!$this->brand->FldIsDetailKey) {
			$this->brand->setFormValue($objForm->GetValue("x_brand"));
		}
		if (!$this->product->FldIsDetailKey) {
			$this->product->setFormValue($objForm->GetValue("x_product"));
		}
		if (!$this->amount->FldIsDetailKey) {
			$this->amount->setFormValue($objForm->GetValue("x_amount"));
		}
		if (!$this->quantity->FldIsDetailKey) {
			$this->quantity->setFormValue($objForm->GetValue("x_quantity"));
		}
		if (!$this->user->FldIsDetailKey) {
			$this->user->setFormValue($objForm->GetValue("x_user"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->sn->CurrentValue = $this->sn->FormValue;
		$this->transaction_date->CurrentValue = $this->transaction_date->FormValue;
		$this->brand->CurrentValue = $this->brand->FormValue;
		$this->product->CurrentValue = $this->product->FormValue;
		$this->amount->CurrentValue = $this->amount->FormValue;
		$this->quantity->CurrentValue = $this->quantity->FormValue;
		$this->user->CurrentValue = $this->user->FormValue;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues($rs = NULL) {
		if ($rs && !$rs->EOF)
			$row = $rs->fields;
		else
			$row = $this->NewRow(); 

		// Call Row Selected event
		$this->Row_Selected($row);
		if (!$rs || $rs->EOF)
			return;
		$this->sn->setDbValue($row['sn']);
		$this->transaction_date->setDbValue($row['transaction_date']);
		$this->brand->setDbValue($row['brand']);
		$this->product->setDbValue($row['product']);
		$this->amount->setDbValue($row['amount']);
		$this->quantity->setDbValue($row['quantity']);
		$this->user->setDbValue($row['user']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['sn'] = NULL;
		$row['transaction_date'] = NULL;
		$row['brand'] = NULL;
		$row['product'] = NULL;
		$row['amount'] = NULL;
		$row['quantity'] = NULL;
		$row['user'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->sn->DbValue = $row['sn'];
		$this->transaction_date->DbValue = $row['transaction_date'];
		$this->brand->DbValue = $row['brand'];
		$this->product->DbValue = $row['product'];
		$this->amount->DbValue = $row['amount'];
		$this->quantity->DbValue = $row['quantity'];
		$this->user->DbValue = $row['user'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("sn")) <> "")
			$this->sn->CurrentValue = $this->getKey("sn"); // sn
		else
			$bValidKey = FALSE;

		// Load old record
		$this->OldRecordset = NULL;
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
		}
		$this->LoadRowValues($this->OldRecordset); // Load row values
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->amount->FormValue == $this->amount->CurrentValue && is_numeric(ew_StrToFloat($this->amount->CurrentValue)))
			$this->amount->CurrentValue = ew_StrToFloat($this->amount->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// sn
		// transaction_date
		// brand
		// product
		// amount
		// quantity
		// user

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// sn
		$this->sn->ViewValue = $this->sn->CurrentValue;
		$this->sn->ViewCustomAttributes = "";

		// transaction_date
		$this->transaction_date->ViewValue = $this->transaction_date->CurrentValue;
		$this->transaction_date->ViewValue = ew_FormatDateTime($this->transaction_date->ViewValue, 9);
		$this->transaction_date->ViewCustomAttributes = "";

		// brand
		if (strval($this->brand->CurrentValue) <> "") {
			$sFilterWrk = "`sn`" . ew_SearchString("=", $this->brand->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `sn`, `brand_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `other_brands`";
		$sWhereWrk = "";
		$this->brand->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->brand, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->brand->ViewValue = $this->brand->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->brand->ViewValue = $this->brand->CurrentValue;
			}
		} else {
			$this->brand->ViewValue = NULL;
		}
		$this->brand->ViewCustomAttributes = "";

		// product
		$this->product->ViewValue = $this->product->CurrentValue;
		$this->product->ViewCustomAttributes = "";

		// amount
		$this->amount->ViewValue = $this->amount->CurrentValue;
		$this->amount->ViewCustomAttributes = "";

		// quantity
		$this->quantity->ViewValue = $this->quantity->CurrentValue;
		$this->quantity->ViewCustomAttributes = "";

		// user
		if (strval($this->user->CurrentValue) <> "") {
			$sFilterWrk = "`sn`" . ew_SearchString("=", $this->user->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `sn`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `user`";
		$sWhereWrk = "";
		$this->user->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->user, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->user->ViewValue = $this->user->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->user->ViewValue = $this->user->CurrentValue;
			}
		} else {
			$this->user->ViewValue = NULL;
		}
		$this->user->ViewCustomAttributes = "";

			// sn
			$this->sn->LinkCustomAttributes = "";
			$this->sn->HrefValue = "";
			$this->sn->TooltipValue = "";

			// transaction_date
			$this->transaction_date->LinkCustomAttributes = "";
			$this->transaction_date->HrefValue = "";
			$this->transaction_date->TooltipValue = "";

			// brand
			$this->brand->LinkCustomAttributes = "";
			$this->brand->HrefValue = "";
			$this->brand->TooltipValue = "";

			// product
			$this->product->LinkCustomAttributes = "";
			$this->product->HrefValue = "";
			$this->product->TooltipValue = "";

			// amount
			$this->amount->LinkCustomAttributes = "";
			$this->amount->HrefValue = "";
			$this->amount->TooltipValue = "";

			// quantity
			$this->quantity->LinkCustomAttributes = "";
			$this->quantity->HrefValue = "";
			$this->quantity->TooltipValue = "";

			// user
			$this->user->LinkCustomAttributes = "";
			$this->user->HrefValue = "";
			$this->user->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// sn
			$this->sn->EditAttrs["class"] = "form-control";
			$this->sn->EditCustomAttributes = "";
			$this->sn->EditValue = $this->sn->CurrentValue;
			$this->sn->ViewCustomAttributes = "";

			// transaction_date
			// brand

			$this->brand->EditAttrs["class"] = "form-control";
			$this->brand->EditCustomAttributes = "";
			if (trim(strval($this->brand->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`sn`" . ew_SearchString("=", $this->brand->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `sn`, `brand_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `other_brands`";
			$sWhereWrk = "";
			$this->brand->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->brand, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->brand->EditValue = $arwrk;

			// product
			$this->product->EditAttrs["class"] = "form-control";
			$this->product->EditCustomAttributes = "";
			$this->product->EditValue = ew_HtmlEncode($this->product->CurrentValue);
			$this->product->PlaceHolder = ew_RemoveHtml($this->product->FldCaption());

			// amount
			$this->amount->EditAttrs["class"] = "form-control";
			$this->amount->EditCustomAttributes = "";
			$this->amount->EditValue = ew_HtmlEncode($this->amount->CurrentValue);
			$this->amount->PlaceHolder = ew_RemoveHtml($this->amount->FldCaption());
			if (strval($this->amount->EditValue) <> "" && is_numeric($this->amount->EditValue)) $this->amount->EditValue = ew_FormatNumber($this->amount->EditValue, -2, -1, -2, 0);

			// quantity
			$this->quantity->EditAttrs["class"] = "form-control";
			$this->quantity->EditCustomAttributes = "";
			$this->quantity->EditValue = ew_HtmlEncode($this->quantity->CurrentValue);
			$this->quantity->PlaceHolder = ew_RemoveHtml($this->quantity->FldCaption());

			// user
			// Edit refer script
			// sn

			$this->sn->LinkCustomAttributes = "";
			$this->sn->HrefValue = "";

			// transaction_date
			$this->transaction_date->LinkCustomAttributes = "";
			$this->transaction_date->HrefValue = "";

			// brand
			$this->brand->LinkCustomAttributes = "";
			$this->brand->HrefValue = "";

			// product
			$this->product->LinkCustomAttributes = "";
			$this->product->HrefValue = "";

			// amount
			$this->amount->LinkCustomAttributes = "";
			$this->amount->HrefValue = "";

			// quantity
			$this->quantity->LinkCustomAttributes = "";
			$this->quantity->HrefValue = "";

			// user
			$this->user->LinkCustomAttributes = "";
			$this->user->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD || $this->RowType == EW_ROWTYPE_EDIT || $this->RowType == EW_ROWTYPE_SEARCH) // Add/Edit/Search row
			$this->SetupFieldTitles();

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->brand->FldIsDetailKey && !is_null($this->brand->FormValue) && $this->brand->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->brand->FldCaption(), $this->brand->ReqErrMsg));
		}
		if (!$this->product->FldIsDetailKey && !is_null($this->product->FormValue) && $this->product->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product->FldCaption(), $this->product->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->product->FormValue)) {
			ew_AddMessage($gsFormError, $this->product->FldErrMsg());
		}
		if (!$this->amount->FldIsDetailKey && !is_null($this->amount->FormValue) && $this->amount->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->amount->FldCaption(), $this->amount->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->amount->FormValue)) {
			ew_AddMessage($gsFormError, $this->amount->FldErrMsg());
		}
		if (!$this->quantity->FldIsDetailKey && !is_null($this->quantity->FormValue) && $this->quantity->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->quantity->FldCaption(), $this->quantity->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->quantity->FormValue)) {
			ew_AddMessage($gsFormError, $this->quantity->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// transaction_date
			$this->transaction_date->SetDbValueDef($rsnew, CurrentStoreID(), "");
			$rsnew['transaction_date'] = &$this->transaction_date->DbValue;

			// brand
			$this->brand->SetDbValueDef($rsnew, $this->brand->CurrentValue, 0, $this->brand->ReadOnly);

			// product
			$this->product->SetDbValueDef($rsnew, $this->product->CurrentValue, 0, $this->product->ReadOnly);

			// amount
			$this->amount->SetDbValueDef($rsnew, $this->amount->CurrentValue, 0, $this->amount->ReadOnly);

			// quantity
			$this->quantity->SetDbValueDef($rsnew, $this->quantity->CurrentValue, 0, $this->quantity->ReadOnly);

			// user
			$this->user->SetDbValueDef($rsnew, CurrentUserID(), 0);
			$rsnew['user'] = &$this->user->DbValue;

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("brand_transactionslist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_brand":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `sn` AS `LinkFld`, `brand_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `other_brands`";
			$sWhereWrk = "";
			$this->brand->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`sn` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->brand, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_user":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `sn` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `user`";
			$sWhereWrk = "";
			$this->user->LookupFilters = array();
			if (!$GLOBALS["brand_transactions"]->UserIDAllow("edit")) $sWhereWrk = $GLOBALS["user"]->AddUserIDFilter($sWhereWrk);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`sn` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->user, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($brand_transactions_edit)) $brand_transactions_edit = new cbrand_transactions_edit();

// Page init
$brand_transactions_edit->Page_Init();

// Page main
$brand_transactions_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$brand_transactions_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fbrand_transactionsedit = new ew_Form("fbrand_transactionsedit", "edit");

// Validate form
fbrand_transactionsedit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_brand");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $brand_transactions->brand->FldCaption(), $brand_transactions->brand->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_product");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $brand_transactions->product->FldCaption(), $brand_transactions->product->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_product");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($brand_transactions->product->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_amount");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $brand_transactions->amount->FldCaption(), $brand_transactions->amount->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_amount");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($brand_transactions->amount->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_quantity");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $brand_transactions->quantity->FldCaption(), $brand_transactions->quantity->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_quantity");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($brand_transactions->quantity->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fbrand_transactionsedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fbrand_transactionsedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fbrand_transactionsedit.Lists["x_brand"] = {"LinkField":"x_sn","Ajax":true,"AutoFill":false,"DisplayFields":["x_brand_name","","",""],"ParentFields":[],"ChildFields":["x_product"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"other_brands"};
fbrand_transactionsedit.Lists["x_brand"].Data = "<?php echo $brand_transactions_edit->brand->LookupFilterQuery(FALSE, "edit") ?>";
fbrand_transactionsedit.Lists["x_user"] = {"LinkField":"x_sn","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"user"};
fbrand_transactionsedit.Lists["x_user"].Data = "<?php echo $brand_transactions_edit->user->LookupFilterQuery(FALSE, "edit") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $brand_transactions_edit->ShowPageHeader(); ?>
<?php
$brand_transactions_edit->ShowMessage();
?>
<form name="fbrand_transactionsedit" id="fbrand_transactionsedit" class="<?php echo $brand_transactions_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($brand_transactions_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $brand_transactions_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="brand_transactions">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($brand_transactions_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($brand_transactions->sn->Visible) { // sn ?>
	<div id="r_sn" class="form-group">
		<label id="elh_brand_transactions_sn" class="<?php echo $brand_transactions_edit->LeftColumnClass ?>"><?php echo $brand_transactions->sn->FldCaption() ?></label>
		<div class="<?php echo $brand_transactions_edit->RightColumnClass ?>"><div<?php echo $brand_transactions->sn->CellAttributes() ?>>
<span id="el_brand_transactions_sn">
<span<?php echo $brand_transactions->sn->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $brand_transactions->sn->EditValue ?></p></span>
</span>
<input type="hidden" data-table="brand_transactions" data-field="x_sn" name="x_sn" id="x_sn" value="<?php echo ew_HtmlEncode($brand_transactions->sn->CurrentValue) ?>">
<?php echo $brand_transactions->sn->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($brand_transactions->brand->Visible) { // brand ?>
	<div id="r_brand" class="form-group">
		<label id="elh_brand_transactions_brand" for="x_brand" class="<?php echo $brand_transactions_edit->LeftColumnClass ?>"><?php echo $brand_transactions->brand->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $brand_transactions_edit->RightColumnClass ?>"><div<?php echo $brand_transactions->brand->CellAttributes() ?>>
<span id="el_brand_transactions_brand">
<select data-table="brand_transactions" data-field="x_brand" data-value-separator="<?php echo $brand_transactions->brand->DisplayValueSeparatorAttribute() ?>" id="x_brand" name="x_brand"<?php echo $brand_transactions->brand->EditAttributes() ?>>
<?php echo $brand_transactions->brand->SelectOptionListHtml("x_brand") ?>
</select>
</span>
<?php echo $brand_transactions->brand->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($brand_transactions->product->Visible) { // product ?>
	<div id="r_product" class="form-group">
		<label id="elh_brand_transactions_product" for="x_product" class="<?php echo $brand_transactions_edit->LeftColumnClass ?>"><?php echo $brand_transactions->product->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $brand_transactions_edit->RightColumnClass ?>"><div<?php echo $brand_transactions->product->CellAttributes() ?>>
<span id="el_brand_transactions_product">
<input type="text" data-table="brand_transactions" data-field="x_product" name="x_product" id="x_product" size="30" placeholder="<?php echo ew_HtmlEncode($brand_transactions->product->getPlaceHolder()) ?>" value="<?php echo $brand_transactions->product->EditValue ?>"<?php echo $brand_transactions->product->EditAttributes() ?>>
</span>
<?php echo $brand_transactions->product->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($brand_transactions->amount->Visible) { // amount ?>
	<div id="r_amount" class="form-group">
		<label id="elh_brand_transactions_amount" for="x_amount" class="<?php echo $brand_transactions_edit->LeftColumnClass ?>"><?php echo $brand_transactions->amount->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $brand_transactions_edit->RightColumnClass ?>"><div<?php echo $brand_transactions->amount->CellAttributes() ?>>
<span id="el_brand_transactions_amount">
<input type="text" data-table="brand_transactions" data-field="x_amount" name="x_amount" id="x_amount" size="30" placeholder="<?php echo ew_HtmlEncode($brand_transactions->amount->getPlaceHolder()) ?>" value="<?php echo $brand_transactions->amount->EditValue ?>"<?php echo $brand_transactions->amount->EditAttributes() ?>>
</span>
<?php echo $brand_transactions->amount->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($brand_transactions->quantity->Visible) { // quantity ?>
	<div id="r_quantity" class="form-group">
		<label id="elh_brand_transactions_quantity" for="x_quantity" class="<?php echo $brand_transactions_edit->LeftColumnClass ?>"><?php echo $brand_transactions->quantity->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $brand_transactions_edit->RightColumnClass ?>"><div<?php echo $brand_transactions->quantity->CellAttributes() ?>>
<span id="el_brand_transactions_quantity">
<input type="text" data-table="brand_transactions" data-field="x_quantity" name="x_quantity" id="x_quantity" size="30" placeholder="<?php echo ew_HtmlEncode($brand_transactions->quantity->getPlaceHolder()) ?>" value="<?php echo $brand_transactions->quantity->EditValue ?>"<?php echo $brand_transactions->quantity->EditAttributes() ?>>
</span>
<?php echo $brand_transactions->quantity->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$brand_transactions_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $brand_transactions_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $brand_transactions_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fbrand_transactionsedit.Init();
</script>
<?php
$brand_transactions_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$brand_transactions_edit->Page_Terminate();
?>
