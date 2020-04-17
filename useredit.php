<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$user_edit = NULL; // Initialize page object first

class cuser_edit extends cuser {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{B6A7A568-E606-4B9C-A82F-C80372444DA0}';

	// Table name
	var $TableName = 'user';

	// Page object name
	var $PageObjName = 'user_edit';

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

		// Table object (user)
		if (!isset($GLOBALS["user"]) || get_class($GLOBALS["user"]) == "cuser") {
			$GLOBALS["user"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["user"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'user', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("userlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
			if (strval($Security->CurrentUserID()) == "") {
				$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
				$this->Page_Terminate(ew_GetUrl("userlist.php"));
			}
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
		$this->firstname->SetVisibility();
		$this->lastname->SetVisibility();
		$this->_email->SetVisibility();
		$this->password->SetVisibility();
		$this->user_type->SetVisibility();
		$this->user_code->SetVisibility();
		$this->device_token->SetVisibility();
		$this->user_level->SetVisibility();
		$this->profile->SetVisibility();
		$this->store_location->SetVisibility();

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
		global $EW_EXPORT, $user;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($user);
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
					if ($pageName == "userview.php")
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
					$this->Page_Terminate("userlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "userlist.php")
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
		if (!$this->firstname->FldIsDetailKey) {
			$this->firstname->setFormValue($objForm->GetValue("x_firstname"));
		}
		if (!$this->lastname->FldIsDetailKey) {
			$this->lastname->setFormValue($objForm->GetValue("x_lastname"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->password->FldIsDetailKey) {
			$this->password->setFormValue($objForm->GetValue("x_password"));
		}
		if (!$this->user_type->FldIsDetailKey) {
			$this->user_type->setFormValue($objForm->GetValue("x_user_type"));
		}
		if (!$this->user_code->FldIsDetailKey) {
			$this->user_code->setFormValue($objForm->GetValue("x_user_code"));
		}
		if (!$this->device_token->FldIsDetailKey) {
			$this->device_token->setFormValue($objForm->GetValue("x_device_token"));
		}
		if (!$this->user_level->FldIsDetailKey) {
			$this->user_level->setFormValue($objForm->GetValue("x_user_level"));
		}
		if (!$this->profile->FldIsDetailKey) {
			$this->profile->setFormValue($objForm->GetValue("x_profile"));
		}
		if (!$this->store_location->FldIsDetailKey) {
			$this->store_location->setFormValue($objForm->GetValue("x_store_location"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->sn->CurrentValue = $this->sn->FormValue;
		$this->firstname->CurrentValue = $this->firstname->FormValue;
		$this->lastname->CurrentValue = $this->lastname->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->password->CurrentValue = $this->password->FormValue;
		$this->user_type->CurrentValue = $this->user_type->FormValue;
		$this->user_code->CurrentValue = $this->user_code->FormValue;
		$this->device_token->CurrentValue = $this->device_token->FormValue;
		$this->user_level->CurrentValue = $this->user_level->FormValue;
		$this->profile->CurrentValue = $this->profile->FormValue;
		$this->store_location->CurrentValue = $this->store_location->FormValue;
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

		// Check if valid user id
		if ($res) {
			$res = $this->ShowOptionLink('edit');
			if (!$res) {
				$sUserIdMsg = ew_DeniedMsg();
				$this->setFailureMessage($sUserIdMsg);
			}
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
		$this->firstname->setDbValue($row['firstname']);
		$this->lastname->setDbValue($row['lastname']);
		$this->_email->setDbValue($row['email']);
		$this->password->setDbValue($row['password']);
		$this->user_type->setDbValue($row['user_type']);
		$this->user_code->setDbValue($row['user_code']);
		$this->device_token->setDbValue($row['device_token']);
		$this->user_level->setDbValue($row['user_level']);
		$this->profile->setDbValue($row['profile']);
		$this->store_location->setDbValue($row['store_location']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['sn'] = NULL;
		$row['firstname'] = NULL;
		$row['lastname'] = NULL;
		$row['email'] = NULL;
		$row['password'] = NULL;
		$row['user_type'] = NULL;
		$row['user_code'] = NULL;
		$row['device_token'] = NULL;
		$row['user_level'] = NULL;
		$row['profile'] = NULL;
		$row['store_location'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->sn->DbValue = $row['sn'];
		$this->firstname->DbValue = $row['firstname'];
		$this->lastname->DbValue = $row['lastname'];
		$this->_email->DbValue = $row['email'];
		$this->password->DbValue = $row['password'];
		$this->user_type->DbValue = $row['user_type'];
		$this->user_code->DbValue = $row['user_code'];
		$this->device_token->DbValue = $row['device_token'];
		$this->user_level->DbValue = $row['user_level'];
		$this->profile->DbValue = $row['profile'];
		$this->store_location->DbValue = $row['store_location'];
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
		// firstname
		// lastname
		// email
		// password
		// user_type
		// user_code
		// device_token
		// user_level
		// profile
		// store_location

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// sn
		$this->sn->ViewValue = $this->sn->CurrentValue;
		$this->sn->ViewCustomAttributes = "";

		// firstname
		$this->firstname->ViewValue = $this->firstname->CurrentValue;
		$this->firstname->ViewCustomAttributes = "";

		// lastname
		$this->lastname->ViewValue = $this->lastname->CurrentValue;
		$this->lastname->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// password
		$this->password->ViewValue = $this->password->CurrentValue;
		$this->password->ViewCustomAttributes = "";

		// user_type
		$this->user_type->ViewValue = $this->user_type->CurrentValue;
		$this->user_type->ViewCustomAttributes = "";

		// user_code
		$this->user_code->ViewValue = $this->user_code->CurrentValue;
		$this->user_code->ViewCustomAttributes = "";

		// device_token
		$this->device_token->ViewValue = $this->device_token->CurrentValue;
		$this->device_token->ViewCustomAttributes = "";

		// user_level
		if ($Security->CanAdmin()) { // System admin
		if (strval($this->user_level->CurrentValue) <> "") {
			$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->user_level->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
		$sWhereWrk = "";
		$this->user_level->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->user_level, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->user_level->ViewValue = $this->user_level->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->user_level->ViewValue = $this->user_level->CurrentValue;
			}
		} else {
			$this->user_level->ViewValue = NULL;
		}
		} else {
			$this->user_level->ViewValue = $Language->Phrase("PasswordMask");
		}
		$this->user_level->ViewCustomAttributes = "";

		// profile
		$this->profile->ViewValue = $this->profile->CurrentValue;
		$this->profile->ViewCustomAttributes = "";

		// store_location
		if (strval($this->store_location->CurrentValue) <> "") {
			$sFilterWrk = "`sn`" . ew_SearchString("=", $this->store_location->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `sn`, `store_name` AS `DispFld`, `State` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `stores`";
		$sWhereWrk = "";
		$this->store_location->LookupFilters = array("dx1" => '`store_name`', "dx2" => '`State`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->store_location, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->store_location->ViewValue = $this->store_location->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->store_location->ViewValue = $this->store_location->CurrentValue;
			}
		} else {
			$this->store_location->ViewValue = NULL;
		}
		$this->store_location->ViewCustomAttributes = "";

			// sn
			$this->sn->LinkCustomAttributes = "";
			$this->sn->HrefValue = "";
			$this->sn->TooltipValue = "";

			// firstname
			$this->firstname->LinkCustomAttributes = "";
			$this->firstname->HrefValue = "";
			$this->firstname->TooltipValue = "";

			// lastname
			$this->lastname->LinkCustomAttributes = "";
			$this->lastname->HrefValue = "";
			$this->lastname->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// password
			$this->password->LinkCustomAttributes = "";
			$this->password->HrefValue = "";
			$this->password->TooltipValue = "";

			// user_type
			$this->user_type->LinkCustomAttributes = "";
			$this->user_type->HrefValue = "";
			$this->user_type->TooltipValue = "";

			// user_code
			$this->user_code->LinkCustomAttributes = "";
			$this->user_code->HrefValue = "";
			$this->user_code->TooltipValue = "";

			// device_token
			$this->device_token->LinkCustomAttributes = "";
			$this->device_token->HrefValue = "";
			$this->device_token->TooltipValue = "";

			// user_level
			$this->user_level->LinkCustomAttributes = "";
			$this->user_level->HrefValue = "";
			$this->user_level->TooltipValue = "";

			// profile
			$this->profile->LinkCustomAttributes = "";
			$this->profile->HrefValue = "";
			$this->profile->TooltipValue = "";

			// store_location
			$this->store_location->LinkCustomAttributes = "";
			$this->store_location->HrefValue = "";
			$this->store_location->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// sn
			$this->sn->EditAttrs["class"] = "form-control";
			$this->sn->EditCustomAttributes = "";
			$this->sn->EditValue = $this->sn->CurrentValue;
			$this->sn->ViewCustomAttributes = "";

			// firstname
			$this->firstname->EditAttrs["class"] = "form-control";
			$this->firstname->EditCustomAttributes = "";
			$this->firstname->EditValue = ew_HtmlEncode($this->firstname->CurrentValue);
			$this->firstname->PlaceHolder = ew_RemoveHtml($this->firstname->FldCaption());

			// lastname
			$this->lastname->EditAttrs["class"] = "form-control";
			$this->lastname->EditCustomAttributes = "";
			$this->lastname->EditValue = ew_HtmlEncode($this->lastname->CurrentValue);
			$this->lastname->PlaceHolder = ew_RemoveHtml($this->lastname->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// password
			$this->password->EditAttrs["class"] = "form-control";
			$this->password->EditCustomAttributes = "";
			$this->password->EditValue = ew_HtmlEncode($this->password->CurrentValue);
			$this->password->PlaceHolder = ew_RemoveHtml($this->password->FldCaption());

			// user_type
			$this->user_type->EditAttrs["class"] = "form-control";
			$this->user_type->EditCustomAttributes = "";
			$this->user_type->EditValue = ew_HtmlEncode($this->user_type->CurrentValue);
			$this->user_type->PlaceHolder = ew_RemoveHtml($this->user_type->FldCaption());

			// user_code
			$this->user_code->EditAttrs["class"] = "form-control";
			$this->user_code->EditCustomAttributes = "";
			$this->user_code->EditValue = ew_HtmlEncode($this->user_code->CurrentValue);
			$this->user_code->PlaceHolder = ew_RemoveHtml($this->user_code->FldCaption());

			// device_token
			$this->device_token->EditAttrs["class"] = "form-control";
			$this->device_token->EditCustomAttributes = "";
			$this->device_token->EditValue = ew_HtmlEncode($this->device_token->CurrentValue);
			$this->device_token->PlaceHolder = ew_RemoveHtml($this->device_token->FldCaption());

			// user_level
			$this->user_level->EditAttrs["class"] = "form-control";
			$this->user_level->EditCustomAttributes = "";
			if (!$Security->CanAdmin()) { // System admin
				$this->user_level->EditValue = $Language->Phrase("PasswordMask");
			} else {
			if (trim(strval($this->user_level->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->user_level->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `userlevels`";
			$sWhereWrk = "";
			$this->user_level->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->user_level, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->user_level->EditValue = $arwrk;
			}

			// profile
			$this->profile->EditAttrs["class"] = "form-control";
			$this->profile->EditCustomAttributes = "";
			$this->profile->EditValue = ew_HtmlEncode($this->profile->CurrentValue);
			$this->profile->PlaceHolder = ew_RemoveHtml($this->profile->FldCaption());

			// store_location
			$this->store_location->EditCustomAttributes = "";
			if (trim(strval($this->store_location->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`sn`" . ew_SearchString("=", $this->store_location->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `sn`, `store_name` AS `DispFld`, `State` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `stores`";
			$sWhereWrk = "";
			$this->store_location->LookupFilters = array("dx1" => '`store_name`', "dx2" => '`State`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->store_location, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$this->store_location->ViewValue = $this->store_location->DisplayValue($arwrk);
			} else {
				$this->store_location->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->store_location->EditValue = $arwrk;

			// Edit refer script
			// sn

			$this->sn->LinkCustomAttributes = "";
			$this->sn->HrefValue = "";

			// firstname
			$this->firstname->LinkCustomAttributes = "";
			$this->firstname->HrefValue = "";

			// lastname
			$this->lastname->LinkCustomAttributes = "";
			$this->lastname->HrefValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";

			// password
			$this->password->LinkCustomAttributes = "";
			$this->password->HrefValue = "";

			// user_type
			$this->user_type->LinkCustomAttributes = "";
			$this->user_type->HrefValue = "";

			// user_code
			$this->user_code->LinkCustomAttributes = "";
			$this->user_code->HrefValue = "";

			// device_token
			$this->device_token->LinkCustomAttributes = "";
			$this->device_token->HrefValue = "";

			// user_level
			$this->user_level->LinkCustomAttributes = "";
			$this->user_level->HrefValue = "";

			// profile
			$this->profile->LinkCustomAttributes = "";
			$this->profile->HrefValue = "";

			// store_location
			$this->store_location->LinkCustomAttributes = "";
			$this->store_location->HrefValue = "";
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
		if (!$this->firstname->FldIsDetailKey && !is_null($this->firstname->FormValue) && $this->firstname->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->firstname->FldCaption(), $this->firstname->ReqErrMsg));
		}
		if (!$this->lastname->FldIsDetailKey && !is_null($this->lastname->FormValue) && $this->lastname->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->lastname->FldCaption(), $this->lastname->ReqErrMsg));
		}
		if (!$this->_email->FldIsDetailKey && !is_null($this->_email->FormValue) && $this->_email->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->_email->FldCaption(), $this->_email->ReqErrMsg));
		}
		if (!$this->password->FldIsDetailKey && !is_null($this->password->FormValue) && $this->password->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->password->FldCaption(), $this->password->ReqErrMsg));
		}
		if (!$this->user_type->FldIsDetailKey && !is_null($this->user_type->FormValue) && $this->user_type->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->user_type->FldCaption(), $this->user_type->ReqErrMsg));
		}
		if (!$this->user_code->FldIsDetailKey && !is_null($this->user_code->FormValue) && $this->user_code->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->user_code->FldCaption(), $this->user_code->ReqErrMsg));
		}
		if (!$this->device_token->FldIsDetailKey && !is_null($this->device_token->FormValue) && $this->device_token->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->device_token->FldCaption(), $this->device_token->ReqErrMsg));
		}
		if (!$this->user_level->FldIsDetailKey && !is_null($this->user_level->FormValue) && $this->user_level->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->user_level->FldCaption(), $this->user_level->ReqErrMsg));
		}
		if (!$this->profile->FldIsDetailKey && !is_null($this->profile->FormValue) && $this->profile->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->profile->FldCaption(), $this->profile->ReqErrMsg));
		}
		if (!$this->store_location->FldIsDetailKey && !is_null($this->store_location->FormValue) && $this->store_location->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->store_location->FldCaption(), $this->store_location->ReqErrMsg));
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

			// firstname
			$this->firstname->SetDbValueDef($rsnew, $this->firstname->CurrentValue, "", $this->firstname->ReadOnly);

			// lastname
			$this->lastname->SetDbValueDef($rsnew, $this->lastname->CurrentValue, "", $this->lastname->ReadOnly);

			// email
			$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, "", $this->_email->ReadOnly);

			// password
			$this->password->SetDbValueDef($rsnew, $this->password->CurrentValue, "", $this->password->ReadOnly || (EW_ENCRYPTED_PASSWORD && $rs->fields('password') == $this->password->CurrentValue));

			// user_type
			$this->user_type->SetDbValueDef($rsnew, $this->user_type->CurrentValue, "", $this->user_type->ReadOnly);

			// user_code
			$this->user_code->SetDbValueDef($rsnew, $this->user_code->CurrentValue, "", $this->user_code->ReadOnly);

			// device_token
			$this->device_token->SetDbValueDef($rsnew, $this->device_token->CurrentValue, "", $this->device_token->ReadOnly);

			// user_level
			if ($Security->CanAdmin()) { // System admin
			$this->user_level->SetDbValueDef($rsnew, $this->user_level->CurrentValue, 0, $this->user_level->ReadOnly);
			}

			// profile
			$this->profile->SetDbValueDef($rsnew, $this->profile->CurrentValue, "", $this->profile->ReadOnly);

			// store_location
			$this->store_location->SetDbValueDef($rsnew, $this->store_location->CurrentValue, 0, $this->store_location->ReadOnly);

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

	// Show link optionally based on User ID
	function ShowOptionLink($id = "") {
		global $Security;
		if ($Security->IsLoggedIn() && !$Security->IsAdmin() && !$this->UserIDAllow($id))
			return $Security->IsValidUserID($this->sn->CurrentValue);
		return TRUE;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("userlist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_user_level":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `userlevelid` AS `LinkFld`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
			$sWhereWrk = "";
			$this->user_level->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`userlevelid` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->user_level, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_store_location":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `sn` AS `LinkFld`, `store_name` AS `DispFld`, `State` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `stores`";
			$sWhereWrk = "{filter}";
			$this->store_location->LookupFilters = array("dx1" => '`store_name`', "dx2" => '`State`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`sn` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->store_location, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($user_edit)) $user_edit = new cuser_edit();

// Page init
$user_edit->Page_Init();

// Page main
$user_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$user_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fuseredit = new ew_Form("fuseredit", "edit");

// Validate form
fuseredit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_firstname");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user->firstname->FldCaption(), $user->firstname->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_lastname");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user->lastname->FldCaption(), $user->lastname->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user->_email->FldCaption(), $user->_email->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_password");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user->password->FldCaption(), $user->password->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_user_type");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user->user_type->FldCaption(), $user->user_type->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_user_code");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user->user_code->FldCaption(), $user->user_code->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_device_token");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user->device_token->FldCaption(), $user->device_token->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_user_level");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user->user_level->FldCaption(), $user->user_level->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_profile");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user->profile->FldCaption(), $user->profile->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_store_location");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user->store_location->FldCaption(), $user->store_location->ReqErrMsg)) ?>");

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
fuseredit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fuseredit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fuseredit.Lists["x_user_level"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"userlevels"};
fuseredit.Lists["x_user_level"].Data = "<?php echo $user_edit->user_level->LookupFilterQuery(FALSE, "edit") ?>";
fuseredit.Lists["x_store_location"] = {"LinkField":"x_sn","Ajax":true,"AutoFill":false,"DisplayFields":["x_store_name","x_State","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"stores"};
fuseredit.Lists["x_store_location"].Data = "<?php echo $user_edit->store_location->LookupFilterQuery(FALSE, "edit") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $user_edit->ShowPageHeader(); ?>
<?php
$user_edit->ShowMessage();
?>
<form name="fuseredit" id="fuseredit" class="<?php echo $user_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($user_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $user_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="user">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($user_edit->IsModal) ?>">
<!-- Fields to prevent google autofill -->
<input class="hidden" type="text" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<input class="hidden" type="password" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($user->sn->Visible) { // sn ?>
	<div id="r_sn" class="form-group">
		<label id="elh_user_sn" class="<?php echo $user_edit->LeftColumnClass ?>"><?php echo $user->sn->FldCaption() ?></label>
		<div class="<?php echo $user_edit->RightColumnClass ?>"><div<?php echo $user->sn->CellAttributes() ?>>
<span id="el_user_sn">
<span<?php echo $user->sn->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $user->sn->EditValue ?></p></span>
</span>
<input type="hidden" data-table="user" data-field="x_sn" name="x_sn" id="x_sn" value="<?php echo ew_HtmlEncode($user->sn->CurrentValue) ?>">
<?php echo $user->sn->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->firstname->Visible) { // firstname ?>
	<div id="r_firstname" class="form-group">
		<label id="elh_user_firstname" for="x_firstname" class="<?php echo $user_edit->LeftColumnClass ?>"><?php echo $user->firstname->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $user_edit->RightColumnClass ?>"><div<?php echo $user->firstname->CellAttributes() ?>>
<span id="el_user_firstname">
<input type="text" data-table="user" data-field="x_firstname" name="x_firstname" id="x_firstname" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($user->firstname->getPlaceHolder()) ?>" value="<?php echo $user->firstname->EditValue ?>"<?php echo $user->firstname->EditAttributes() ?>>
</span>
<?php echo $user->firstname->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->lastname->Visible) { // lastname ?>
	<div id="r_lastname" class="form-group">
		<label id="elh_user_lastname" for="x_lastname" class="<?php echo $user_edit->LeftColumnClass ?>"><?php echo $user->lastname->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $user_edit->RightColumnClass ?>"><div<?php echo $user->lastname->CellAttributes() ?>>
<span id="el_user_lastname">
<input type="text" data-table="user" data-field="x_lastname" name="x_lastname" id="x_lastname" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($user->lastname->getPlaceHolder()) ?>" value="<?php echo $user->lastname->EditValue ?>"<?php echo $user->lastname->EditAttributes() ?>>
</span>
<?php echo $user->lastname->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_user__email" for="x__email" class="<?php echo $user_edit->LeftColumnClass ?>"><?php echo $user->_email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $user_edit->RightColumnClass ?>"><div<?php echo $user->_email->CellAttributes() ?>>
<span id="el_user__email">
<input type="text" data-table="user" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($user->_email->getPlaceHolder()) ?>" value="<?php echo $user->_email->EditValue ?>"<?php echo $user->_email->EditAttributes() ?>>
</span>
<?php echo $user->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->password->Visible) { // password ?>
	<div id="r_password" class="form-group">
		<label id="elh_user_password" for="x_password" class="<?php echo $user_edit->LeftColumnClass ?>"><?php echo $user->password->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $user_edit->RightColumnClass ?>"><div<?php echo $user->password->CellAttributes() ?>>
<span id="el_user_password">
<input type="text" data-table="user" data-field="x_password" name="x_password" id="x_password" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($user->password->getPlaceHolder()) ?>" value="<?php echo $user->password->EditValue ?>"<?php echo $user->password->EditAttributes() ?>>
</span>
<?php echo $user->password->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->user_type->Visible) { // user_type ?>
	<div id="r_user_type" class="form-group">
		<label id="elh_user_user_type" for="x_user_type" class="<?php echo $user_edit->LeftColumnClass ?>"><?php echo $user->user_type->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $user_edit->RightColumnClass ?>"><div<?php echo $user->user_type->CellAttributes() ?>>
<span id="el_user_user_type">
<input type="text" data-table="user" data-field="x_user_type" name="x_user_type" id="x_user_type" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($user->user_type->getPlaceHolder()) ?>" value="<?php echo $user->user_type->EditValue ?>"<?php echo $user->user_type->EditAttributes() ?>>
</span>
<?php echo $user->user_type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->user_code->Visible) { // user_code ?>
	<div id="r_user_code" class="form-group">
		<label id="elh_user_user_code" for="x_user_code" class="<?php echo $user_edit->LeftColumnClass ?>"><?php echo $user->user_code->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $user_edit->RightColumnClass ?>"><div<?php echo $user->user_code->CellAttributes() ?>>
<span id="el_user_user_code">
<input type="text" data-table="user" data-field="x_user_code" name="x_user_code" id="x_user_code" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($user->user_code->getPlaceHolder()) ?>" value="<?php echo $user->user_code->EditValue ?>"<?php echo $user->user_code->EditAttributes() ?>>
</span>
<?php echo $user->user_code->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->device_token->Visible) { // device_token ?>
	<div id="r_device_token" class="form-group">
		<label id="elh_user_device_token" for="x_device_token" class="<?php echo $user_edit->LeftColumnClass ?>"><?php echo $user->device_token->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $user_edit->RightColumnClass ?>"><div<?php echo $user->device_token->CellAttributes() ?>>
<span id="el_user_device_token">
<textarea data-table="user" data-field="x_device_token" name="x_device_token" id="x_device_token" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($user->device_token->getPlaceHolder()) ?>"<?php echo $user->device_token->EditAttributes() ?>><?php echo $user->device_token->EditValue ?></textarea>
</span>
<?php echo $user->device_token->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->user_level->Visible) { // user_level ?>
	<div id="r_user_level" class="form-group">
		<label id="elh_user_user_level" for="x_user_level" class="<?php echo $user_edit->LeftColumnClass ?>"><?php echo $user->user_level->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $user_edit->RightColumnClass ?>"><div<?php echo $user->user_level->CellAttributes() ?>>
<?php if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin ?>
<span id="el_user_user_level">
<p class="form-control-static"><?php echo $user->user_level->EditValue ?></p>
</span>
<?php } else { ?>
<span id="el_user_user_level">
<select data-table="user" data-field="x_user_level" data-value-separator="<?php echo $user->user_level->DisplayValueSeparatorAttribute() ?>" id="x_user_level" name="x_user_level"<?php echo $user->user_level->EditAttributes() ?>>
<?php echo $user->user_level->SelectOptionListHtml("x_user_level") ?>
</select>
</span>
<?php } ?>
<?php echo $user->user_level->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->profile->Visible) { // profile ?>
	<div id="r_profile" class="form-group">
		<label id="elh_user_profile" for="x_profile" class="<?php echo $user_edit->LeftColumnClass ?>"><?php echo $user->profile->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $user_edit->RightColumnClass ?>"><div<?php echo $user->profile->CellAttributes() ?>>
<span id="el_user_profile">
<textarea data-table="user" data-field="x_profile" name="x_profile" id="x_profile" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($user->profile->getPlaceHolder()) ?>"<?php echo $user->profile->EditAttributes() ?>><?php echo $user->profile->EditValue ?></textarea>
</span>
<?php echo $user->profile->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->store_location->Visible) { // store_location ?>
	<div id="r_store_location" class="form-group">
		<label id="elh_user_store_location" for="x_store_location" class="<?php echo $user_edit->LeftColumnClass ?>"><?php echo $user->store_location->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $user_edit->RightColumnClass ?>"><div<?php echo $user->store_location->CellAttributes() ?>>
<span id="el_user_store_location">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_store_location"><?php echo (strval($user->store_location->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $user->store_location->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($user->store_location->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_store_location',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="user" data-field="x_store_location" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $user->store_location->DisplayValueSeparatorAttribute() ?>" name="x_store_location" id="x_store_location" value="<?php echo $user->store_location->CurrentValue ?>"<?php echo $user->store_location->EditAttributes() ?>>
</span>
<?php echo $user->store_location->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$user_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $user_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $user_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fuseredit.Init();
</script>
<?php
$user_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$user_edit->Page_Terminate();
?>
