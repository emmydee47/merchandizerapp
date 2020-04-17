<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "merchandizer_locationinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$merchandizer_location_edit = NULL; // Initialize page object first

class cmerchandizer_location_edit extends cmerchandizer_location {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{B6A7A568-E606-4B9C-A82F-C80372444DA0}';

	// Table name
	var $TableName = 'merchandizer_location';

	// Page object name
	var $PageObjName = 'merchandizer_location_edit';

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

		// Table object (merchandizer_location)
		if (!isset($GLOBALS["merchandizer_location"]) || get_class($GLOBALS["merchandizer_location"]) == "cmerchandizer_location") {
			$GLOBALS["merchandizer_location"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["merchandizer_location"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'merchandizer_location', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("merchandizer_locationlist.php"));
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
		$this->user_id->SetVisibility();
		$this->longitude->SetVisibility();
		$this->latitude->SetVisibility();
		$this->location->SetVisibility();
		$this->date->SetVisibility();

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
		global $EW_EXPORT, $merchandizer_location;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($merchandizer_location);
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
					if ($pageName == "merchandizer_locationview.php")
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
					$this->Page_Terminate("merchandizer_locationlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "merchandizer_locationlist.php")
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
		if (!$this->user_id->FldIsDetailKey) {
			$this->user_id->setFormValue($objForm->GetValue("x_user_id"));
		}
		if (!$this->longitude->FldIsDetailKey) {
			$this->longitude->setFormValue($objForm->GetValue("x_longitude"));
		}
		if (!$this->latitude->FldIsDetailKey) {
			$this->latitude->setFormValue($objForm->GetValue("x_latitude"));
		}
		if (!$this->location->FldIsDetailKey) {
			$this->location->setFormValue($objForm->GetValue("x_location"));
		}
		if (!$this->date->FldIsDetailKey) {
			$this->date->setFormValue($objForm->GetValue("x_date"));
			$this->date->CurrentValue = ew_UnFormatDateTime($this->date->CurrentValue, 0);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->sn->CurrentValue = $this->sn->FormValue;
		$this->user_id->CurrentValue = $this->user_id->FormValue;
		$this->longitude->CurrentValue = $this->longitude->FormValue;
		$this->latitude->CurrentValue = $this->latitude->FormValue;
		$this->location->CurrentValue = $this->location->FormValue;
		$this->date->CurrentValue = $this->date->FormValue;
		$this->date->CurrentValue = ew_UnFormatDateTime($this->date->CurrentValue, 0);
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
		$this->user_id->setDbValue($row['user_id']);
		$this->longitude->setDbValue($row['longitude']);
		$this->latitude->setDbValue($row['latitude']);
		$this->location->setDbValue($row['location']);
		$this->date->setDbValue($row['date']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['sn'] = NULL;
		$row['user_id'] = NULL;
		$row['longitude'] = NULL;
		$row['latitude'] = NULL;
		$row['location'] = NULL;
		$row['date'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->sn->DbValue = $row['sn'];
		$this->user_id->DbValue = $row['user_id'];
		$this->longitude->DbValue = $row['longitude'];
		$this->latitude->DbValue = $row['latitude'];
		$this->location->DbValue = $row['location'];
		$this->date->DbValue = $row['date'];
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
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// sn
		// user_id
		// longitude
		// latitude
		// location
		// date

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// sn
		$this->sn->ViewValue = $this->sn->CurrentValue;
		$this->sn->ViewCustomAttributes = "";

		// user_id
		$this->user_id->ViewValue = $this->user_id->CurrentValue;
		$this->user_id->ViewCustomAttributes = "";

		// longitude
		$this->longitude->ViewValue = $this->longitude->CurrentValue;
		$this->longitude->ViewCustomAttributes = "";

		// latitude
		$this->latitude->ViewValue = $this->latitude->CurrentValue;
		$this->latitude->ViewCustomAttributes = "";

		// location
		$this->location->ViewValue = $this->location->CurrentValue;
		$this->location->ViewCustomAttributes = "";

		// date
		$this->date->ViewValue = $this->date->CurrentValue;
		$this->date->ViewValue = ew_FormatDateTime($this->date->ViewValue, 0);
		$this->date->ViewCustomAttributes = "";

			// sn
			$this->sn->LinkCustomAttributes = "";
			$this->sn->HrefValue = "";
			$this->sn->TooltipValue = "";

			// user_id
			$this->user_id->LinkCustomAttributes = "";
			$this->user_id->HrefValue = "";
			$this->user_id->TooltipValue = "";

			// longitude
			$this->longitude->LinkCustomAttributes = "";
			$this->longitude->HrefValue = "";
			$this->longitude->TooltipValue = "";

			// latitude
			$this->latitude->LinkCustomAttributes = "";
			$this->latitude->HrefValue = "";
			$this->latitude->TooltipValue = "";

			// location
			$this->location->LinkCustomAttributes = "";
			$this->location->HrefValue = "";
			$this->location->TooltipValue = "";

			// date
			$this->date->LinkCustomAttributes = "";
			$this->date->HrefValue = "";
			$this->date->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// sn
			$this->sn->EditAttrs["class"] = "form-control";
			$this->sn->EditCustomAttributes = "";
			$this->sn->EditValue = $this->sn->CurrentValue;
			$this->sn->ViewCustomAttributes = "";

			// user_id
			$this->user_id->EditAttrs["class"] = "form-control";
			$this->user_id->EditCustomAttributes = "";
			$this->user_id->EditValue = ew_HtmlEncode($this->user_id->CurrentValue);
			$this->user_id->PlaceHolder = ew_RemoveHtml($this->user_id->FldCaption());

			// longitude
			$this->longitude->EditAttrs["class"] = "form-control";
			$this->longitude->EditCustomAttributes = "";
			$this->longitude->EditValue = ew_HtmlEncode($this->longitude->CurrentValue);
			$this->longitude->PlaceHolder = ew_RemoveHtml($this->longitude->FldCaption());

			// latitude
			$this->latitude->EditAttrs["class"] = "form-control";
			$this->latitude->EditCustomAttributes = "";
			$this->latitude->EditValue = ew_HtmlEncode($this->latitude->CurrentValue);
			$this->latitude->PlaceHolder = ew_RemoveHtml($this->latitude->FldCaption());

			// location
			$this->location->EditAttrs["class"] = "form-control";
			$this->location->EditCustomAttributes = "";
			$this->location->EditValue = ew_HtmlEncode($this->location->CurrentValue);
			$this->location->PlaceHolder = ew_RemoveHtml($this->location->FldCaption());

			// date
			$this->date->EditAttrs["class"] = "form-control";
			$this->date->EditCustomAttributes = "";
			$this->date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date->CurrentValue, 8));
			$this->date->PlaceHolder = ew_RemoveHtml($this->date->FldCaption());

			// Edit refer script
			// sn

			$this->sn->LinkCustomAttributes = "";
			$this->sn->HrefValue = "";

			// user_id
			$this->user_id->LinkCustomAttributes = "";
			$this->user_id->HrefValue = "";

			// longitude
			$this->longitude->LinkCustomAttributes = "";
			$this->longitude->HrefValue = "";

			// latitude
			$this->latitude->LinkCustomAttributes = "";
			$this->latitude->HrefValue = "";

			// location
			$this->location->LinkCustomAttributes = "";
			$this->location->HrefValue = "";

			// date
			$this->date->LinkCustomAttributes = "";
			$this->date->HrefValue = "";
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
		if (!$this->user_id->FldIsDetailKey && !is_null($this->user_id->FormValue) && $this->user_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->user_id->FldCaption(), $this->user_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->user_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->user_id->FldErrMsg());
		}
		if (!$this->longitude->FldIsDetailKey && !is_null($this->longitude->FormValue) && $this->longitude->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->longitude->FldCaption(), $this->longitude->ReqErrMsg));
		}
		if (!$this->latitude->FldIsDetailKey && !is_null($this->latitude->FormValue) && $this->latitude->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->latitude->FldCaption(), $this->latitude->ReqErrMsg));
		}
		if (!$this->location->FldIsDetailKey && !is_null($this->location->FormValue) && $this->location->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->location->FldCaption(), $this->location->ReqErrMsg));
		}
		if (!$this->date->FldIsDetailKey && !is_null($this->date->FormValue) && $this->date->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->date->FldCaption(), $this->date->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->date->FormValue)) {
			ew_AddMessage($gsFormError, $this->date->FldErrMsg());
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

			// user_id
			$this->user_id->SetDbValueDef($rsnew, $this->user_id->CurrentValue, 0, $this->user_id->ReadOnly);

			// longitude
			$this->longitude->SetDbValueDef($rsnew, $this->longitude->CurrentValue, "", $this->longitude->ReadOnly);

			// latitude
			$this->latitude->SetDbValueDef($rsnew, $this->latitude->CurrentValue, "", $this->latitude->ReadOnly);

			// location
			$this->location->SetDbValueDef($rsnew, $this->location->CurrentValue, "", $this->location->ReadOnly);

			// date
			$this->date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date->CurrentValue, 0), ew_CurrentDate(), $this->date->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("merchandizer_locationlist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
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
if (!isset($merchandizer_location_edit)) $merchandizer_location_edit = new cmerchandizer_location_edit();

// Page init
$merchandizer_location_edit->Page_Init();

// Page main
$merchandizer_location_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$merchandizer_location_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fmerchandizer_locationedit = new ew_Form("fmerchandizer_locationedit", "edit");

// Validate form
fmerchandizer_locationedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_user_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $merchandizer_location->user_id->FldCaption(), $merchandizer_location->user_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_user_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($merchandizer_location->user_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_longitude");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $merchandizer_location->longitude->FldCaption(), $merchandizer_location->longitude->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_latitude");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $merchandizer_location->latitude->FldCaption(), $merchandizer_location->latitude->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_location");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $merchandizer_location->location->FldCaption(), $merchandizer_location->location->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_date");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $merchandizer_location->date->FldCaption(), $merchandizer_location->date->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($merchandizer_location->date->FldErrMsg()) ?>");

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
fmerchandizer_locationedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fmerchandizer_locationedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $merchandizer_location_edit->ShowPageHeader(); ?>
<?php
$merchandizer_location_edit->ShowMessage();
?>
<form name="fmerchandizer_locationedit" id="fmerchandizer_locationedit" class="<?php echo $merchandizer_location_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($merchandizer_location_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $merchandizer_location_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="merchandizer_location">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($merchandizer_location_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($merchandizer_location->sn->Visible) { // sn ?>
	<div id="r_sn" class="form-group">
		<label id="elh_merchandizer_location_sn" class="<?php echo $merchandizer_location_edit->LeftColumnClass ?>"><?php echo $merchandizer_location->sn->FldCaption() ?></label>
		<div class="<?php echo $merchandizer_location_edit->RightColumnClass ?>"><div<?php echo $merchandizer_location->sn->CellAttributes() ?>>
<span id="el_merchandizer_location_sn">
<span<?php echo $merchandizer_location->sn->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $merchandizer_location->sn->EditValue ?></p></span>
</span>
<input type="hidden" data-table="merchandizer_location" data-field="x_sn" name="x_sn" id="x_sn" value="<?php echo ew_HtmlEncode($merchandizer_location->sn->CurrentValue) ?>">
<?php echo $merchandizer_location->sn->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($merchandizer_location->user_id->Visible) { // user_id ?>
	<div id="r_user_id" class="form-group">
		<label id="elh_merchandizer_location_user_id" for="x_user_id" class="<?php echo $merchandizer_location_edit->LeftColumnClass ?>"><?php echo $merchandizer_location->user_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $merchandizer_location_edit->RightColumnClass ?>"><div<?php echo $merchandizer_location->user_id->CellAttributes() ?>>
<span id="el_merchandizer_location_user_id">
<input type="text" data-table="merchandizer_location" data-field="x_user_id" name="x_user_id" id="x_user_id" size="30" placeholder="<?php echo ew_HtmlEncode($merchandizer_location->user_id->getPlaceHolder()) ?>" value="<?php echo $merchandizer_location->user_id->EditValue ?>"<?php echo $merchandizer_location->user_id->EditAttributes() ?>>
</span>
<?php echo $merchandizer_location->user_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($merchandizer_location->longitude->Visible) { // longitude ?>
	<div id="r_longitude" class="form-group">
		<label id="elh_merchandizer_location_longitude" for="x_longitude" class="<?php echo $merchandizer_location_edit->LeftColumnClass ?>"><?php echo $merchandizer_location->longitude->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $merchandizer_location_edit->RightColumnClass ?>"><div<?php echo $merchandizer_location->longitude->CellAttributes() ?>>
<span id="el_merchandizer_location_longitude">
<input type="text" data-table="merchandizer_location" data-field="x_longitude" name="x_longitude" id="x_longitude" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($merchandizer_location->longitude->getPlaceHolder()) ?>" value="<?php echo $merchandizer_location->longitude->EditValue ?>"<?php echo $merchandizer_location->longitude->EditAttributes() ?>>
</span>
<?php echo $merchandizer_location->longitude->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($merchandizer_location->latitude->Visible) { // latitude ?>
	<div id="r_latitude" class="form-group">
		<label id="elh_merchandizer_location_latitude" for="x_latitude" class="<?php echo $merchandizer_location_edit->LeftColumnClass ?>"><?php echo $merchandizer_location->latitude->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $merchandizer_location_edit->RightColumnClass ?>"><div<?php echo $merchandizer_location->latitude->CellAttributes() ?>>
<span id="el_merchandizer_location_latitude">
<input type="text" data-table="merchandizer_location" data-field="x_latitude" name="x_latitude" id="x_latitude" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($merchandizer_location->latitude->getPlaceHolder()) ?>" value="<?php echo $merchandizer_location->latitude->EditValue ?>"<?php echo $merchandizer_location->latitude->EditAttributes() ?>>
</span>
<?php echo $merchandizer_location->latitude->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($merchandizer_location->location->Visible) { // location ?>
	<div id="r_location" class="form-group">
		<label id="elh_merchandizer_location_location" for="x_location" class="<?php echo $merchandizer_location_edit->LeftColumnClass ?>"><?php echo $merchandizer_location->location->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $merchandizer_location_edit->RightColumnClass ?>"><div<?php echo $merchandizer_location->location->CellAttributes() ?>>
<span id="el_merchandizer_location_location">
<input type="text" data-table="merchandizer_location" data-field="x_location" name="x_location" id="x_location" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($merchandizer_location->location->getPlaceHolder()) ?>" value="<?php echo $merchandizer_location->location->EditValue ?>"<?php echo $merchandizer_location->location->EditAttributes() ?>>
</span>
<?php echo $merchandizer_location->location->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($merchandizer_location->date->Visible) { // date ?>
	<div id="r_date" class="form-group">
		<label id="elh_merchandizer_location_date" for="x_date" class="<?php echo $merchandizer_location_edit->LeftColumnClass ?>"><?php echo $merchandizer_location->date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $merchandizer_location_edit->RightColumnClass ?>"><div<?php echo $merchandizer_location->date->CellAttributes() ?>>
<span id="el_merchandizer_location_date">
<input type="text" data-table="merchandizer_location" data-field="x_date" name="x_date" id="x_date" placeholder="<?php echo ew_HtmlEncode($merchandizer_location->date->getPlaceHolder()) ?>" value="<?php echo $merchandizer_location->date->EditValue ?>"<?php echo $merchandizer_location->date->EditAttributes() ?>>
<?php if (!$merchandizer_location->date->ReadOnly && !$merchandizer_location->date->Disabled && !isset($merchandizer_location->date->EditAttrs["readonly"]) && !isset($merchandizer_location->date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fmerchandizer_locationedit", "x_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $merchandizer_location->date->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$merchandizer_location_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $merchandizer_location_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $merchandizer_location_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fmerchandizer_locationedit.Init();
</script>
<?php
$merchandizer_location_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$merchandizer_location_edit->Page_Terminate();
?>
