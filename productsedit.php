<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "productsinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$products_edit = NULL; // Initialize page object first

class cproducts_edit extends cproducts {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{B6A7A568-E606-4B9C-A82F-C80372444DA0}';

	// Table name
	var $TableName = 'products';

	// Page object name
	var $PageObjName = 'products_edit';

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

		// Table object (products)
		if (!isset($GLOBALS["products"]) || get_class($GLOBALS["products"]) == "cproducts") {
			$GLOBALS["products"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["products"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'products', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("productslist.php"));
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
		$this->product_category->SetVisibility();
		$this->product_id->SetVisibility();
		$this->product_name->SetVisibility();
		$this->product_description->SetVisibility();
		$this->price->SetVisibility();
		$this->image->SetVisibility();

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
		global $EW_EXPORT, $products;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($products);
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
					if ($pageName == "productsview.php")
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
					$this->Page_Terminate("productslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "productslist.php")
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
		$this->image->Upload->Index = $objForm->Index;
		$this->image->Upload->UploadFile();
		$this->image->CurrentValue = $this->image->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->sn->FldIsDetailKey)
			$this->sn->setFormValue($objForm->GetValue("x_sn"));
		if (!$this->product_category->FldIsDetailKey) {
			$this->product_category->setFormValue($objForm->GetValue("x_product_category"));
		}
		if (!$this->product_id->FldIsDetailKey) {
			$this->product_id->setFormValue($objForm->GetValue("x_product_id"));
		}
		if (!$this->product_name->FldIsDetailKey) {
			$this->product_name->setFormValue($objForm->GetValue("x_product_name"));
		}
		if (!$this->product_description->FldIsDetailKey) {
			$this->product_description->setFormValue($objForm->GetValue("x_product_description"));
		}
		if (!$this->price->FldIsDetailKey) {
			$this->price->setFormValue($objForm->GetValue("x_price"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->sn->CurrentValue = $this->sn->FormValue;
		$this->product_category->CurrentValue = $this->product_category->FormValue;
		$this->product_id->CurrentValue = $this->product_id->FormValue;
		$this->product_name->CurrentValue = $this->product_name->FormValue;
		$this->product_description->CurrentValue = $this->product_description->FormValue;
		$this->price->CurrentValue = $this->price->FormValue;
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
		$this->product_category->setDbValue($row['product_category']);
		$this->product_id->setDbValue($row['product_id']);
		$this->product_name->setDbValue($row['product_name']);
		$this->product_description->setDbValue($row['product_description']);
		$this->price->setDbValue($row['price']);
		$this->image->Upload->DbValue = $row['image'];
		$this->image->CurrentValue = $this->image->Upload->DbValue;
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['sn'] = NULL;
		$row['product_category'] = NULL;
		$row['product_id'] = NULL;
		$row['product_name'] = NULL;
		$row['product_description'] = NULL;
		$row['price'] = NULL;
		$row['image'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->sn->DbValue = $row['sn'];
		$this->product_category->DbValue = $row['product_category'];
		$this->product_id->DbValue = $row['product_id'];
		$this->product_name->DbValue = $row['product_name'];
		$this->product_description->DbValue = $row['product_description'];
		$this->price->DbValue = $row['price'];
		$this->image->Upload->DbValue = $row['image'];
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

		if ($this->price->FormValue == $this->price->CurrentValue && is_numeric(ew_StrToFloat($this->price->CurrentValue)))
			$this->price->CurrentValue = ew_StrToFloat($this->price->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// sn
		// product_category
		// product_id
		// product_name
		// product_description
		// price
		// image

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// sn
		$this->sn->ViewValue = $this->sn->CurrentValue;
		$this->sn->ViewCustomAttributes = "";

		// product_category
		if (strval($this->product_category->CurrentValue) <> "") {
			$sFilterWrk = "`sn`" . ew_SearchString("=", $this->product_category->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `sn`, `category` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `product_categories`";
		$sWhereWrk = "";
		$this->product_category->LookupFilters = array("dx1" => '`category`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->product_category, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->product_category->ViewValue = $this->product_category->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->product_category->ViewValue = $this->product_category->CurrentValue;
			}
		} else {
			$this->product_category->ViewValue = NULL;
		}
		$this->product_category->ViewCustomAttributes = "";

		// product_id
		$this->product_id->ViewValue = $this->product_id->CurrentValue;
		$this->product_id->ViewCustomAttributes = "";

		// product_name
		$this->product_name->ViewValue = $this->product_name->CurrentValue;
		$this->product_name->ViewCustomAttributes = "";

		// product_description
		$this->product_description->ViewValue = $this->product_description->CurrentValue;
		$this->product_description->ViewCustomAttributes = "";

		// price
		$this->price->ViewValue = $this->price->CurrentValue;
		$this->price->ViewCustomAttributes = "";

		// image
		if (!ew_Empty($this->image->Upload->DbValue)) {
			$this->image->ImageWidth = 80;
			$this->image->ImageHeight = 80;
			$this->image->ImageAlt = $this->image->FldAlt();
			$this->image->ViewValue = $this->image->Upload->DbValue;
		} else {
			$this->image->ViewValue = "";
		}
		$this->image->ViewCustomAttributes = "";

			// sn
			$this->sn->LinkCustomAttributes = "";
			$this->sn->HrefValue = "";
			$this->sn->TooltipValue = "";

			// product_category
			$this->product_category->LinkCustomAttributes = "";
			$this->product_category->HrefValue = "";
			$this->product_category->TooltipValue = "";

			// product_id
			$this->product_id->LinkCustomAttributes = "";
			$this->product_id->HrefValue = "";
			$this->product_id->TooltipValue = "";

			// product_name
			$this->product_name->LinkCustomAttributes = "";
			$this->product_name->HrefValue = "";
			$this->product_name->TooltipValue = "";

			// product_description
			$this->product_description->LinkCustomAttributes = "";
			$this->product_description->HrefValue = "";
			$this->product_description->TooltipValue = "";

			// price
			$this->price->LinkCustomAttributes = "";
			$this->price->HrefValue = "";
			$this->price->TooltipValue = "";

			// image
			$this->image->LinkCustomAttributes = "";
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->HrefValue = ew_GetFileUploadUrl($this->image, $this->image->Upload->DbValue); // Add prefix/suffix
				$this->image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->image->HrefValue = ew_FullUrl($this->image->HrefValue, "href");
			} else {
				$this->image->HrefValue = "";
			}
			$this->image->HrefValue2 = $this->image->UploadPath . $this->image->Upload->DbValue;
			$this->image->TooltipValue = "";
			if ($this->image->UseColorbox) {
				if (ew_Empty($this->image->TooltipValue))
					$this->image->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->image->LinkAttrs["data-rel"] = "products_x_image";
				ew_AppendClass($this->image->LinkAttrs["class"], "ewLightbox");
			}
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// sn
			$this->sn->EditAttrs["class"] = "form-control";
			$this->sn->EditCustomAttributes = "";
			$this->sn->EditValue = $this->sn->CurrentValue;
			$this->sn->ViewCustomAttributes = "";

			// product_category
			$this->product_category->EditCustomAttributes = "";
			if (trim(strval($this->product_category->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`sn`" . ew_SearchString("=", $this->product_category->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `sn`, `category` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `product_categories`";
			$sWhereWrk = "";
			$this->product_category->LookupFilters = array("dx1" => '`category`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->product_category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->product_category->ViewValue = $this->product_category->DisplayValue($arwrk);
			} else {
				$this->product_category->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->product_category->EditValue = $arwrk;

			// product_id
			$this->product_id->EditAttrs["class"] = "form-control";
			$this->product_id->EditCustomAttributes = "";
			$this->product_id->EditValue = ew_HtmlEncode($this->product_id->CurrentValue);
			$this->product_id->PlaceHolder = ew_RemoveHtml($this->product_id->FldCaption());

			// product_name
			$this->product_name->EditAttrs["class"] = "form-control";
			$this->product_name->EditCustomAttributes = "";
			$this->product_name->EditValue = ew_HtmlEncode($this->product_name->CurrentValue);
			$this->product_name->PlaceHolder = ew_RemoveHtml($this->product_name->FldCaption());

			// product_description
			$this->product_description->EditAttrs["class"] = "form-control";
			$this->product_description->EditCustomAttributes = "";
			$this->product_description->EditValue = ew_HtmlEncode($this->product_description->CurrentValue);
			$this->product_description->PlaceHolder = ew_RemoveHtml($this->product_description->FldCaption());

			// price
			$this->price->EditAttrs["class"] = "form-control";
			$this->price->EditCustomAttributes = "";
			$this->price->EditValue = ew_HtmlEncode($this->price->CurrentValue);
			$this->price->PlaceHolder = ew_RemoveHtml($this->price->FldCaption());
			if (strval($this->price->EditValue) <> "" && is_numeric($this->price->EditValue)) $this->price->EditValue = ew_FormatNumber($this->price->EditValue, -2, -1, -2, 0);

			// image
			$this->image->EditAttrs["class"] = "form-control";
			$this->image->EditCustomAttributes = "";
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->ImageWidth = 80;
				$this->image->ImageHeight = 80;
				$this->image->ImageAlt = $this->image->FldAlt();
				$this->image->EditValue = $this->image->Upload->DbValue;
			} else {
				$this->image->EditValue = "";
			}
			if (!ew_Empty($this->image->CurrentValue))
				$this->image->Upload->FileName = $this->image->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->image);

			// Edit refer script
			// sn

			$this->sn->LinkCustomAttributes = "";
			$this->sn->HrefValue = "";

			// product_category
			$this->product_category->LinkCustomAttributes = "";
			$this->product_category->HrefValue = "";

			// product_id
			$this->product_id->LinkCustomAttributes = "";
			$this->product_id->HrefValue = "";

			// product_name
			$this->product_name->LinkCustomAttributes = "";
			$this->product_name->HrefValue = "";

			// product_description
			$this->product_description->LinkCustomAttributes = "";
			$this->product_description->HrefValue = "";

			// price
			$this->price->LinkCustomAttributes = "";
			$this->price->HrefValue = "";

			// image
			$this->image->LinkCustomAttributes = "";
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->HrefValue = ew_GetFileUploadUrl($this->image, $this->image->Upload->DbValue); // Add prefix/suffix
				$this->image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->image->HrefValue = ew_FullUrl($this->image->HrefValue, "href");
			} else {
				$this->image->HrefValue = "";
			}
			$this->image->HrefValue2 = $this->image->UploadPath . $this->image->Upload->DbValue;
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
		if (!$this->product_category->FldIsDetailKey && !is_null($this->product_category->FormValue) && $this->product_category->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_category->FldCaption(), $this->product_category->ReqErrMsg));
		}
		if (!$this->product_id->FldIsDetailKey && !is_null($this->product_id->FormValue) && $this->product_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_id->FldCaption(), $this->product_id->ReqErrMsg));
		}
		if (!$this->product_name->FldIsDetailKey && !is_null($this->product_name->FormValue) && $this->product_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_name->FldCaption(), $this->product_name->ReqErrMsg));
		}
		if (!$this->product_description->FldIsDetailKey && !is_null($this->product_description->FormValue) && $this->product_description->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_description->FldCaption(), $this->product_description->ReqErrMsg));
		}
		if (!$this->price->FldIsDetailKey && !is_null($this->price->FormValue) && $this->price->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->price->FldCaption(), $this->price->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->price->FormValue)) {
			ew_AddMessage($gsFormError, $this->price->FldErrMsg());
		}
		if ($this->image->Upload->FileName == "" && !$this->image->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->image->FldCaption(), $this->image->ReqErrMsg));
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

			// product_category
			$this->product_category->SetDbValueDef($rsnew, $this->product_category->CurrentValue, 0, $this->product_category->ReadOnly);

			// product_id
			$this->product_id->SetDbValueDef($rsnew, $this->product_id->CurrentValue, "", $this->product_id->ReadOnly);

			// product_name
			$this->product_name->SetDbValueDef($rsnew, $this->product_name->CurrentValue, "", $this->product_name->ReadOnly);

			// product_description
			$this->product_description->SetDbValueDef($rsnew, $this->product_description->CurrentValue, "", $this->product_description->ReadOnly);

			// price
			$this->price->SetDbValueDef($rsnew, $this->price->CurrentValue, 0, $this->price->ReadOnly);

			// image
			if ($this->image->Visible && !$this->image->ReadOnly && !$this->image->Upload->KeepFile) {
				$this->image->Upload->DbValue = $rsold['image']; // Get original value
				if ($this->image->Upload->FileName == "") {
					$rsnew['image'] = NULL;
				} else {
					$rsnew['image'] = $this->image->Upload->FileName;
				}
			}
			if ($this->image->Visible && !$this->image->Upload->KeepFile) {
				if (!ew_Empty($this->image->Upload->Value)) {
					$rsnew['image'] = ew_UploadFileNameEx($this->image->PhysicalUploadPath(), $rsnew['image']); // Get new file name
				}
			}

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
					if ($this->image->Visible && !$this->image->Upload->KeepFile) {
						if (!ew_Empty($this->image->Upload->Value)) {
							if (!$this->image->Upload->SaveToFile($rsnew['image'], TRUE)) {
								$this->setFailureMessage($Language->Phrase("UploadErrMsg7"));
								return FALSE;
							}
						}
					}
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

		// image
		ew_CleanUploadTempPath($this->image, $this->image->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("productslist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_product_category":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `sn` AS `LinkFld`, `category` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `product_categories`";
			$sWhereWrk = "{filter}";
			$this->product_category->LookupFilters = array("dx1" => '`category`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`sn` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->product_category, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($products_edit)) $products_edit = new cproducts_edit();

// Page init
$products_edit->Page_Init();

// Page main
$products_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$products_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fproductsedit = new ew_Form("fproductsedit", "edit");

// Validate form
fproductsedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_product_category");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $products->product_category->FldCaption(), $products->product_category->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_product_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $products->product_id->FldCaption(), $products->product_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_product_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $products->product_name->FldCaption(), $products->product_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_product_description");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $products->product_description->FldCaption(), $products->product_description->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_price");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $products->price->FldCaption(), $products->price->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_price");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($products->price->FldErrMsg()) ?>");
			felm = this.GetElements("x" + infix + "_image");
			elm = this.GetElements("fn_x" + infix + "_image");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $products->image->FldCaption(), $products->image->ReqErrMsg)) ?>");

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
fproductsedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fproductsedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fproductsedit.Lists["x_product_category"] = {"LinkField":"x_sn","Ajax":true,"AutoFill":false,"DisplayFields":["x_category","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"product_categories"};
fproductsedit.Lists["x_product_category"].Data = "<?php echo $products_edit->product_category->LookupFilterQuery(FALSE, "edit") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $products_edit->ShowPageHeader(); ?>
<?php
$products_edit->ShowMessage();
?>
<form name="fproductsedit" id="fproductsedit" class="<?php echo $products_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($products_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $products_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="products">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($products_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($products->sn->Visible) { // sn ?>
	<div id="r_sn" class="form-group">
		<label id="elh_products_sn" class="<?php echo $products_edit->LeftColumnClass ?>"><?php echo $products->sn->FldCaption() ?></label>
		<div class="<?php echo $products_edit->RightColumnClass ?>"><div<?php echo $products->sn->CellAttributes() ?>>
<span id="el_products_sn">
<span<?php echo $products->sn->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $products->sn->EditValue ?></p></span>
</span>
<input type="hidden" data-table="products" data-field="x_sn" name="x_sn" id="x_sn" value="<?php echo ew_HtmlEncode($products->sn->CurrentValue) ?>">
<?php echo $products->sn->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->product_category->Visible) { // product_category ?>
	<div id="r_product_category" class="form-group">
		<label id="elh_products_product_category" for="x_product_category" class="<?php echo $products_edit->LeftColumnClass ?>"><?php echo $products->product_category->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_edit->RightColumnClass ?>"><div<?php echo $products->product_category->CellAttributes() ?>>
<span id="el_products_product_category">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_product_category"><?php echo (strval($products->product_category->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $products->product_category->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($products->product_category->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_product_category',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="products" data-field="x_product_category" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $products->product_category->DisplayValueSeparatorAttribute() ?>" name="x_product_category" id="x_product_category" value="<?php echo $products->product_category->CurrentValue ?>"<?php echo $products->product_category->EditAttributes() ?>>
</span>
<?php echo $products->product_category->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->product_id->Visible) { // product_id ?>
	<div id="r_product_id" class="form-group">
		<label id="elh_products_product_id" for="x_product_id" class="<?php echo $products_edit->LeftColumnClass ?>"><?php echo $products->product_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_edit->RightColumnClass ?>"><div<?php echo $products->product_id->CellAttributes() ?>>
<span id="el_products_product_id">
<input type="text" data-table="products" data-field="x_product_id" name="x_product_id" id="x_product_id" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($products->product_id->getPlaceHolder()) ?>" value="<?php echo $products->product_id->EditValue ?>"<?php echo $products->product_id->EditAttributes() ?>>
</span>
<?php echo $products->product_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->product_name->Visible) { // product_name ?>
	<div id="r_product_name" class="form-group">
		<label id="elh_products_product_name" for="x_product_name" class="<?php echo $products_edit->LeftColumnClass ?>"><?php echo $products->product_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_edit->RightColumnClass ?>"><div<?php echo $products->product_name->CellAttributes() ?>>
<span id="el_products_product_name">
<input type="text" data-table="products" data-field="x_product_name" name="x_product_name" id="x_product_name" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($products->product_name->getPlaceHolder()) ?>" value="<?php echo $products->product_name->EditValue ?>"<?php echo $products->product_name->EditAttributes() ?>>
</span>
<?php echo $products->product_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->product_description->Visible) { // product_description ?>
	<div id="r_product_description" class="form-group">
		<label id="elh_products_product_description" for="x_product_description" class="<?php echo $products_edit->LeftColumnClass ?>"><?php echo $products->product_description->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_edit->RightColumnClass ?>"><div<?php echo $products->product_description->CellAttributes() ?>>
<span id="el_products_product_description">
<textarea data-table="products" data-field="x_product_description" name="x_product_description" id="x_product_description" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($products->product_description->getPlaceHolder()) ?>"<?php echo $products->product_description->EditAttributes() ?>><?php echo $products->product_description->EditValue ?></textarea>
</span>
<?php echo $products->product_description->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->price->Visible) { // price ?>
	<div id="r_price" class="form-group">
		<label id="elh_products_price" for="x_price" class="<?php echo $products_edit->LeftColumnClass ?>"><?php echo $products->price->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_edit->RightColumnClass ?>"><div<?php echo $products->price->CellAttributes() ?>>
<span id="el_products_price">
<input type="text" data-table="products" data-field="x_price" name="x_price" id="x_price" size="30" placeholder="<?php echo ew_HtmlEncode($products->price->getPlaceHolder()) ?>" value="<?php echo $products->price->EditValue ?>"<?php echo $products->price->EditAttributes() ?>>
</span>
<?php echo $products->price->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->image->Visible) { // image ?>
	<div id="r_image" class="form-group">
		<label id="elh_products_image" class="<?php echo $products_edit->LeftColumnClass ?>"><?php echo $products->image->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_edit->RightColumnClass ?>"><div<?php echo $products->image->CellAttributes() ?>>
<span id="el_products_image">
<div id="fd_x_image">
<span title="<?php echo $products->image->FldTitle() ? $products->image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($products->image->ReadOnly || $products->image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="products" data-field="x_image" name="x_image" id="x_image"<?php echo $products->image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_image" id= "fn_x_image" value="<?php echo $products->image->Upload->FileName ?>">
<?php if (@$_POST["fa_x_image"] == "0") { ?>
<input type="hidden" name="fa_x_image" id= "fa_x_image" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_image" id= "fa_x_image" value="1">
<?php } ?>
<input type="hidden" name="fs_x_image" id= "fs_x_image" value="200">
<input type="hidden" name="fx_x_image" id= "fx_x_image" value="<?php echo $products->image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_image" id= "fm_x_image" value="<?php echo $products->image->UploadMaxFileSize ?>">
</div>
<table id="ft_x_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $products->image->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$products_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $products_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $products_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fproductsedit.Init();
</script>
<?php
$products_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$products_edit->Page_Terminate();
?>
