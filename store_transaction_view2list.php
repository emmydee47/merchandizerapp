<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "store_transaction_view2info.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$store_transaction_view2_list = NULL; // Initialize page object first

class cstore_transaction_view2_list extends cstore_transaction_view2 {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{B6A7A568-E606-4B9C-A82F-C80372444DA0}';

	// Table name
	var $TableName = 'store_transaction_view2';

	// Page object name
	var $PageObjName = 'store_transaction_view2_list';

	// Grid form hidden field names
	var $FormName = 'fstore_transaction_view2list';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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

		// Table object (store_transaction_view2)
		if (!isset($GLOBALS["store_transaction_view2"]) || get_class($GLOBALS["store_transaction_view2"]) == "cstore_transaction_view2") {
			$GLOBALS["store_transaction_view2"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["store_transaction_view2"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "store_transaction_view2add.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "store_transaction_view2delete.php";
		$this->MultiUpdateUrl = "store_transaction_view2update.php";

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'store_transaction_view2', TRUE);

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

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fstore_transaction_view2listsrch";

		// List actions
		$this->ListActions = new cListActions();
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
		if (!$Security->CanList()) {
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
		// Get export parameters

		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} elseif (@$_GET["cmd"] == "json") {
			$this->Export = $_GET["cmd"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();
		$this->sn->SetVisibility();
		$this->sn->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->receipt_number->SetVisibility();
		$this->transaction_date->SetVisibility();
		$this->transaction_month->SetVisibility();
		$this->store_name->SetVisibility();
		$this->category->SetVisibility();
		$this->product_name->SetVisibility();
		$this->quantity->SetVisibility();
		$this->amount->SetVisibility();

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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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
		global $EW_EXPORT, $store_transaction_view2;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($store_transaction_view2);
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
			ew_SaveDebugMsg();
			header("Location: " . $url);
		}
		exit();
	}

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $AutoHidePager = EW_AUTO_HIDE_PAGER;
	var $AutoHidePageSizeSelector = EW_AUTO_HIDE_PAGE_SIZE_SELECTOR;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security, $EW_EXPORT;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Set up records per page
			$this->SetupDisplayRecs();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values

			// Process filter list
			$this->ProcessFilterList();
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->Command <> "json" && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetupSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($this->Command <> "json" && $this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		if ($this->Command <> "json")
			$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif ($this->Command <> "json") {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter
		if ($this->Command == "json") {
			$this->UseSessionForListSQL = FALSE; // Do not use session for ListSQL
			$this->CurrentFilter = $sFilter;
		} else {
			$this->setSessionWhere($sFilter);
			$this->CurrentFilter = "";
		}

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array_keys($EW_EXPORT))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->ListRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Set up number of records displayed per page
	function SetupDisplayRecs() {
		$sWrk = @$_GET[EW_TABLE_REC_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayRecs = intval($sWrk);
			} else {
				if (strtolower($sWrk) == "all") { // Display all records
					$this->DisplayRecs = -1;
				} else {
					$this->DisplayRecs = 20; // Non-numeric, load default
				}
			}
			$this->setRecordsPerPage($this->DisplayRecs); // Save to Session

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->sn->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->sn->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "fstore_transaction_view2listsrch") : "";
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->sn->AdvancedSearch->ToJson(), ","); // Field sn
		$sFilterList = ew_Concat($sFilterList, $this->receipt_number->AdvancedSearch->ToJson(), ","); // Field receipt_number
		$sFilterList = ew_Concat($sFilterList, $this->transaction_date->AdvancedSearch->ToJson(), ","); // Field transaction_date
		$sFilterList = ew_Concat($sFilterList, $this->transaction_month->AdvancedSearch->ToJson(), ","); // Field transaction_month
		$sFilterList = ew_Concat($sFilterList, $this->store_name->AdvancedSearch->ToJson(), ","); // Field store_name
		$sFilterList = ew_Concat($sFilterList, $this->category->AdvancedSearch->ToJson(), ","); // Field category
		$sFilterList = ew_Concat($sFilterList, $this->product_name->AdvancedSearch->ToJson(), ","); // Field product_name
		$sFilterList = ew_Concat($sFilterList, $this->quantity->AdvancedSearch->ToJson(), ","); // Field quantity
		$sFilterList = ew_Concat($sFilterList, $this->amount->AdvancedSearch->ToJson(), ","); // Field amount
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}
		$sFilterList = preg_replace('/,$/', "", $sFilterList);

		// Return filter list in json
		if ($sFilterList <> "")
			$sFilterList = "\"data\":{" . $sFilterList . "}";
		if ($sSavedFilterList <> "") {
			if ($sFilterList <> "")
				$sFilterList .= ",";
			$sFilterList .= "\"filters\":" . $sSavedFilterList;
		}
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Process filter list
	function ProcessFilterList() {
		global $UserProfile;
		if (@$_POST["ajax"] == "savefilters") { // Save filter request (Ajax)
			$filters = @$_POST["filters"];
			$UserProfile->SetSearchFilters(CurrentUserName(), "fstore_transaction_view2listsrch", $filters);

			// Clean output buffer
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			echo ew_ArrayToJson(array(array("success" => TRUE))); // Success
			$this->Page_Terminate();
			exit();
		} elseif (@$_POST["cmd"] == "resetfilter") {
			$this->RestoreFilterList();
		}
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(@$_POST["filter"], TRUE);
		$this->Command = "search";

		// Field sn
		$this->sn->AdvancedSearch->SearchValue = @$filter["x_sn"];
		$this->sn->AdvancedSearch->SearchOperator = @$filter["z_sn"];
		$this->sn->AdvancedSearch->SearchCondition = @$filter["v_sn"];
		$this->sn->AdvancedSearch->SearchValue2 = @$filter["y_sn"];
		$this->sn->AdvancedSearch->SearchOperator2 = @$filter["w_sn"];
		$this->sn->AdvancedSearch->Save();

		// Field receipt_number
		$this->receipt_number->AdvancedSearch->SearchValue = @$filter["x_receipt_number"];
		$this->receipt_number->AdvancedSearch->SearchOperator = @$filter["z_receipt_number"];
		$this->receipt_number->AdvancedSearch->SearchCondition = @$filter["v_receipt_number"];
		$this->receipt_number->AdvancedSearch->SearchValue2 = @$filter["y_receipt_number"];
		$this->receipt_number->AdvancedSearch->SearchOperator2 = @$filter["w_receipt_number"];
		$this->receipt_number->AdvancedSearch->Save();

		// Field transaction_date
		$this->transaction_date->AdvancedSearch->SearchValue = @$filter["x_transaction_date"];
		$this->transaction_date->AdvancedSearch->SearchOperator = @$filter["z_transaction_date"];
		$this->transaction_date->AdvancedSearch->SearchCondition = @$filter["v_transaction_date"];
		$this->transaction_date->AdvancedSearch->SearchValue2 = @$filter["y_transaction_date"];
		$this->transaction_date->AdvancedSearch->SearchOperator2 = @$filter["w_transaction_date"];
		$this->transaction_date->AdvancedSearch->Save();

		// Field transaction_month
		$this->transaction_month->AdvancedSearch->SearchValue = @$filter["x_transaction_month"];
		$this->transaction_month->AdvancedSearch->SearchOperator = @$filter["z_transaction_month"];
		$this->transaction_month->AdvancedSearch->SearchCondition = @$filter["v_transaction_month"];
		$this->transaction_month->AdvancedSearch->SearchValue2 = @$filter["y_transaction_month"];
		$this->transaction_month->AdvancedSearch->SearchOperator2 = @$filter["w_transaction_month"];
		$this->transaction_month->AdvancedSearch->Save();

		// Field store_name
		$this->store_name->AdvancedSearch->SearchValue = @$filter["x_store_name"];
		$this->store_name->AdvancedSearch->SearchOperator = @$filter["z_store_name"];
		$this->store_name->AdvancedSearch->SearchCondition = @$filter["v_store_name"];
		$this->store_name->AdvancedSearch->SearchValue2 = @$filter["y_store_name"];
		$this->store_name->AdvancedSearch->SearchOperator2 = @$filter["w_store_name"];
		$this->store_name->AdvancedSearch->Save();

		// Field category
		$this->category->AdvancedSearch->SearchValue = @$filter["x_category"];
		$this->category->AdvancedSearch->SearchOperator = @$filter["z_category"];
		$this->category->AdvancedSearch->SearchCondition = @$filter["v_category"];
		$this->category->AdvancedSearch->SearchValue2 = @$filter["y_category"];
		$this->category->AdvancedSearch->SearchOperator2 = @$filter["w_category"];
		$this->category->AdvancedSearch->Save();

		// Field product_name
		$this->product_name->AdvancedSearch->SearchValue = @$filter["x_product_name"];
		$this->product_name->AdvancedSearch->SearchOperator = @$filter["z_product_name"];
		$this->product_name->AdvancedSearch->SearchCondition = @$filter["v_product_name"];
		$this->product_name->AdvancedSearch->SearchValue2 = @$filter["y_product_name"];
		$this->product_name->AdvancedSearch->SearchOperator2 = @$filter["w_product_name"];
		$this->product_name->AdvancedSearch->Save();

		// Field quantity
		$this->quantity->AdvancedSearch->SearchValue = @$filter["x_quantity"];
		$this->quantity->AdvancedSearch->SearchOperator = @$filter["z_quantity"];
		$this->quantity->AdvancedSearch->SearchCondition = @$filter["v_quantity"];
		$this->quantity->AdvancedSearch->SearchValue2 = @$filter["y_quantity"];
		$this->quantity->AdvancedSearch->SearchOperator2 = @$filter["w_quantity"];
		$this->quantity->AdvancedSearch->Save();

		// Field amount
		$this->amount->AdvancedSearch->SearchValue = @$filter["x_amount"];
		$this->amount->AdvancedSearch->SearchOperator = @$filter["z_amount"];
		$this->amount->AdvancedSearch->SearchCondition = @$filter["v_amount"];
		$this->amount->AdvancedSearch->SearchValue2 = @$filter["y_amount"];
		$this->amount->AdvancedSearch->SearchOperator2 = @$filter["w_amount"];
		$this->amount->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->sn, $Default, FALSE); // sn
		$this->BuildSearchSql($sWhere, $this->receipt_number, $Default, FALSE); // receipt_number
		$this->BuildSearchSql($sWhere, $this->transaction_date, $Default, FALSE); // transaction_date
		$this->BuildSearchSql($sWhere, $this->transaction_month, $Default, FALSE); // transaction_month
		$this->BuildSearchSql($sWhere, $this->store_name, $Default, FALSE); // store_name
		$this->BuildSearchSql($sWhere, $this->category, $Default, FALSE); // category
		$this->BuildSearchSql($sWhere, $this->product_name, $Default, FALSE); // product_name
		$this->BuildSearchSql($sWhere, $this->quantity, $Default, FALSE); // quantity
		$this->BuildSearchSql($sWhere, $this->amount, $Default, FALSE); // amount

		// Set up search parm
		if (!$Default && $sWhere <> "" && in_array($this->Command, array("", "reset", "resetall"))) {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->sn->AdvancedSearch->Save(); // sn
			$this->receipt_number->AdvancedSearch->Save(); // receipt_number
			$this->transaction_date->AdvancedSearch->Save(); // transaction_date
			$this->transaction_month->AdvancedSearch->Save(); // transaction_month
			$this->store_name->AdvancedSearch->Save(); // store_name
			$this->category->AdvancedSearch->Save(); // category
			$this->product_name->AdvancedSearch->Save(); // product_name
			$this->quantity->AdvancedSearch->Save(); // quantity
			$this->amount->AdvancedSearch->Save(); // amount
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = $Fld->FldParm();
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1)
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal, $this->DBID) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2, $this->DBID) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2, $this->DBID);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE || $Fld->FldDataType == EW_DATATYPE_TIME) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->receipt_number, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->transaction_date, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->transaction_month, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->store_name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->category, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->product_name, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSQL(&$Where, &$Fld, $arKeywords, $type) {
		global $EW_BASIC_SEARCH_IGNORE_PATTERN;
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if ($EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace($EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .= "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;

		// Get search SQL
		if ($sSearchKeyword <> "") {
			$ar = $this->BasicSearch->KeywordList($Default);

			// Search keyword in any fields
			if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
				foreach ($ar as $sKeyword) {
					if ($sKeyword <> "") {
						if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
						$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
					}
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
			}
			if (!$Default && in_array($this->Command, array("", "reset", "resetall"))) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		if ($this->sn->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->receipt_number->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->transaction_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->transaction_month->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->store_name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->category->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->product_name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->quantity->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->amount->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->sn->AdvancedSearch->UnsetSession();
		$this->receipt_number->AdvancedSearch->UnsetSession();
		$this->transaction_date->AdvancedSearch->UnsetSession();
		$this->transaction_month->AdvancedSearch->UnsetSession();
		$this->store_name->AdvancedSearch->UnsetSession();
		$this->category->AdvancedSearch->UnsetSession();
		$this->product_name->AdvancedSearch->UnsetSession();
		$this->quantity->AdvancedSearch->UnsetSession();
		$this->amount->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->sn->AdvancedSearch->Load();
		$this->receipt_number->AdvancedSearch->Load();
		$this->transaction_date->AdvancedSearch->Load();
		$this->transaction_month->AdvancedSearch->Load();
		$this->store_name->AdvancedSearch->Load();
		$this->category->AdvancedSearch->Load();
		$this->product_name->AdvancedSearch->Load();
		$this->quantity->AdvancedSearch->Load();
		$this->amount->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->sn); // sn
			$this->UpdateSort($this->receipt_number); // receipt_number
			$this->UpdateSort($this->transaction_date); // transaction_date
			$this->UpdateSort($this->transaction_month); // transaction_month
			$this->UpdateSort($this->store_name); // store_name
			$this->UpdateSort($this->category); // category
			$this->UpdateSort($this->product_name); // product_name
			$this->UpdateSort($this->quantity); // quantity
			$this->UpdateSort($this->amount); // amount
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
				$this->sn->setSort("DESC");
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->sn->setSort("");
				$this->receipt_number->setSort("");
				$this->transaction_date->setSort("");
				$this->transaction_month->setSort("");
				$this->store_name->setSort("");
				$this->category->setSort("");
				$this->product_name->setSort("");
				$this->quantity->setSort("");
				$this->amount->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssClass = "text-nowrap";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Call ListOptions_Rendering event
		$this->ListOptions_Rendering();

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->sn->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fstore_transaction_view2listsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fstore_transaction_view2listsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fstore_transaction_view2list}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fstore_transaction_view2listsrch\">" . $Language->Phrase("SearchLink") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Advanced search button
		$item = &$this->SearchOptions->Add("advancedsearch");
		$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"store_transaction_view2srch.php\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
		$item->Visible = TRUE;

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "" && $this->Command == "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// sn

		$this->sn->AdvancedSearch->SearchValue = @$_GET["x_sn"];
		if ($this->sn->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->sn->AdvancedSearch->SearchOperator = @$_GET["z_sn"];

		// receipt_number
		$this->receipt_number->AdvancedSearch->SearchValue = @$_GET["x_receipt_number"];
		if ($this->receipt_number->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->receipt_number->AdvancedSearch->SearchOperator = @$_GET["z_receipt_number"];

		// transaction_date
		$this->transaction_date->AdvancedSearch->SearchValue = @$_GET["x_transaction_date"];
		if ($this->transaction_date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->transaction_date->AdvancedSearch->SearchOperator = @$_GET["z_transaction_date"];
		$this->transaction_date->AdvancedSearch->SearchCondition = @$_GET["v_transaction_date"];
		$this->transaction_date->AdvancedSearch->SearchValue2 = @$_GET["y_transaction_date"];
		if ($this->transaction_date->AdvancedSearch->SearchValue2 <> "" && $this->Command == "") $this->Command = "search";
		$this->transaction_date->AdvancedSearch->SearchOperator2 = @$_GET["w_transaction_date"];

		// transaction_month
		$this->transaction_month->AdvancedSearch->SearchValue = @$_GET["x_transaction_month"];
		if ($this->transaction_month->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->transaction_month->AdvancedSearch->SearchOperator = @$_GET["z_transaction_month"];

		// store_name
		$this->store_name->AdvancedSearch->SearchValue = @$_GET["x_store_name"];
		if ($this->store_name->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->store_name->AdvancedSearch->SearchOperator = @$_GET["z_store_name"];

		// category
		$this->category->AdvancedSearch->SearchValue = @$_GET["x_category"];
		if ($this->category->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->category->AdvancedSearch->SearchOperator = @$_GET["z_category"];

		// product_name
		$this->product_name->AdvancedSearch->SearchValue = @$_GET["x_product_name"];
		if ($this->product_name->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->product_name->AdvancedSearch->SearchOperator = @$_GET["z_product_name"];

		// quantity
		$this->quantity->AdvancedSearch->SearchValue = @$_GET["x_quantity"];
		if ($this->quantity->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->quantity->AdvancedSearch->SearchOperator = @$_GET["z_quantity"];

		// amount
		$this->amount->AdvancedSearch->SearchValue = @$_GET["x_amount"];
		if ($this->amount->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->amount->AdvancedSearch->SearchOperator = @$_GET["z_amount"];
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->ListSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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
		$this->receipt_number->setDbValue($row['receipt_number']);
		$this->transaction_date->setDbValue($row['transaction_date']);
		$this->transaction_month->setDbValue($row['transaction_month']);
		$this->store_name->setDbValue($row['store_name']);
		$this->category->setDbValue($row['category']);
		$this->product_name->setDbValue($row['product_name']);
		$this->quantity->setDbValue($row['quantity']);
		$this->amount->setDbValue($row['amount']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['sn'] = NULL;
		$row['receipt_number'] = NULL;
		$row['transaction_date'] = NULL;
		$row['transaction_month'] = NULL;
		$row['store_name'] = NULL;
		$row['category'] = NULL;
		$row['product_name'] = NULL;
		$row['quantity'] = NULL;
		$row['amount'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->sn->DbValue = $row['sn'];
		$this->receipt_number->DbValue = $row['receipt_number'];
		$this->transaction_date->DbValue = $row['transaction_date'];
		$this->transaction_month->DbValue = $row['transaction_month'];
		$this->store_name->DbValue = $row['store_name'];
		$this->category->DbValue = $row['category'];
		$this->product_name->DbValue = $row['product_name'];
		$this->quantity->DbValue = $row['quantity'];
		$this->amount->DbValue = $row['amount'];
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Convert decimal values if posted back
		if ($this->amount->FormValue == $this->amount->CurrentValue && is_numeric(ew_StrToFloat($this->amount->CurrentValue)))
			$this->amount->CurrentValue = ew_StrToFloat($this->amount->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// sn
		// receipt_number
		// transaction_date
		// transaction_month
		// store_name
		// category
		// product_name
		// quantity
		// amount

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// sn
		$this->sn->ViewValue = $this->sn->CurrentValue;
		$this->sn->ViewCustomAttributes = "";

		// receipt_number
		$this->receipt_number->ViewValue = $this->receipt_number->CurrentValue;
		$this->receipt_number->ViewCustomAttributes = "";

		// transaction_date
		$this->transaction_date->ViewValue = $this->transaction_date->CurrentValue;
		$this->transaction_date->ViewValue = ew_FormatDateTime($this->transaction_date->ViewValue, 5);
		$this->transaction_date->ViewCustomAttributes = "";

		// transaction_month
		$this->transaction_month->ViewValue = $this->transaction_month->CurrentValue;
		$this->transaction_month->ViewCustomAttributes = "";

		// store_name
		$this->store_name->ViewValue = $this->store_name->CurrentValue;
		$this->store_name->ViewCustomAttributes = "";

		// category
		$this->category->ViewValue = $this->category->CurrentValue;
		$this->category->ViewCustomAttributes = "";

		// product_name
		$this->product_name->ViewValue = $this->product_name->CurrentValue;
		$this->product_name->ViewCustomAttributes = "";

		// quantity
		$this->quantity->ViewValue = $this->quantity->CurrentValue;
		$this->quantity->ViewCustomAttributes = "";

		// amount
		$this->amount->ViewValue = $this->amount->CurrentValue;
		$this->amount->ViewValue = ew_FormatNumber($this->amount->ViewValue, 2, -2, -2, -2);
		$this->amount->CellCssStyle .= "text-align: right;";
		$this->amount->ViewCustomAttributes = "";

			// sn
			$this->sn->LinkCustomAttributes = "";
			$this->sn->HrefValue = "";
			$this->sn->TooltipValue = "";

			// receipt_number
			$this->receipt_number->LinkCustomAttributes = "";
			$this->receipt_number->HrefValue = "";
			$this->receipt_number->TooltipValue = "";

			// transaction_date
			$this->transaction_date->LinkCustomAttributes = "";
			$this->transaction_date->HrefValue = "";
			$this->transaction_date->TooltipValue = "";

			// transaction_month
			$this->transaction_month->LinkCustomAttributes = "";
			$this->transaction_month->HrefValue = "";
			$this->transaction_month->TooltipValue = "";

			// store_name
			$this->store_name->LinkCustomAttributes = "";
			$this->store_name->HrefValue = "";
			$this->store_name->TooltipValue = "";

			// category
			$this->category->LinkCustomAttributes = "";
			$this->category->HrefValue = "";
			$this->category->TooltipValue = "";

			// product_name
			$this->product_name->LinkCustomAttributes = "";
			$this->product_name->HrefValue = "";
			$this->product_name->TooltipValue = "";

			// quantity
			$this->quantity->LinkCustomAttributes = "";
			$this->quantity->HrefValue = "";
			$this->quantity->TooltipValue = "";

			// amount
			$this->amount->LinkCustomAttributes = "";
			$this->amount->HrefValue = "";
			$this->amount->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->sn->AdvancedSearch->Load();
		$this->receipt_number->AdvancedSearch->Load();
		$this->transaction_date->AdvancedSearch->Load();
		$this->transaction_month->AdvancedSearch->Load();
		$this->store_name->AdvancedSearch->Load();
		$this->category->AdvancedSearch->Load();
		$this->product_name->AdvancedSearch->Load();
		$this->quantity->AdvancedSearch->Load();
		$this->amount->AdvancedSearch->Load();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = FALSE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = FALSE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = TRUE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_store_transaction_view2\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_store_transaction_view2',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fstore_transaction_view2list,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = FALSE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = $this->UseSelectLimit;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->ListRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetupStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED && $this->Export <> "pdf")
			echo ew_DebugMsg();

		// Output data
		$Doc->Export();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendering event
	function ListOptions_Rendering() {

		//$GLOBALS["xxx_grid"]->DetailAdd = (...condition...); // Set to TRUE or FALSE conditionally
		//$GLOBALS["xxx_grid"]->DetailEdit = (...condition...); // Set to TRUE or FALSE conditionally
		//$GLOBALS["xxx_grid"]->DetailView = (...condition...); // Set to TRUE or FALSE conditionally

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example:
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($store_transaction_view2_list)) $store_transaction_view2_list = new cstore_transaction_view2_list();

// Page init
$store_transaction_view2_list->Page_Init();

// Page main
$store_transaction_view2_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$store_transaction_view2_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($store_transaction_view2->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fstore_transaction_view2list = new ew_Form("fstore_transaction_view2list", "list");
fstore_transaction_view2list.FormKeyCountName = '<?php echo $store_transaction_view2_list->FormKeyCountName ?>';

// Form_CustomValidate event
fstore_transaction_view2list.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fstore_transaction_view2list.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fstore_transaction_view2listsrch = new ew_Form("fstore_transaction_view2listsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($store_transaction_view2->Export == "") { ?>
<div class="ewToolbar">
<?php if ($store_transaction_view2_list->TotalRecs > 0 && $store_transaction_view2_list->ExportOptions->Visible()) { ?>
<?php $store_transaction_view2_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($store_transaction_view2_list->SearchOptions->Visible()) { ?>
<?php $store_transaction_view2_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($store_transaction_view2_list->FilterOptions->Visible()) { ?>
<?php $store_transaction_view2_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $store_transaction_view2_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($store_transaction_view2_list->TotalRecs <= 0)
			$store_transaction_view2_list->TotalRecs = $store_transaction_view2->ListRecordCount();
	} else {
		if (!$store_transaction_view2_list->Recordset && ($store_transaction_view2_list->Recordset = $store_transaction_view2_list->LoadRecordset()))
			$store_transaction_view2_list->TotalRecs = $store_transaction_view2_list->Recordset->RecordCount();
	}
	$store_transaction_view2_list->StartRec = 1;
	if ($store_transaction_view2_list->DisplayRecs <= 0 || ($store_transaction_view2->Export <> "" && $store_transaction_view2->ExportAll)) // Display all records
		$store_transaction_view2_list->DisplayRecs = $store_transaction_view2_list->TotalRecs;
	if (!($store_transaction_view2->Export <> "" && $store_transaction_view2->ExportAll))
		$store_transaction_view2_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$store_transaction_view2_list->Recordset = $store_transaction_view2_list->LoadRecordset($store_transaction_view2_list->StartRec-1, $store_transaction_view2_list->DisplayRecs);

	// Set no record found message
	if ($store_transaction_view2->CurrentAction == "" && $store_transaction_view2_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$store_transaction_view2_list->setWarningMessage(ew_DeniedMsg());
		if ($store_transaction_view2_list->SearchWhere == "0=101")
			$store_transaction_view2_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$store_transaction_view2_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$store_transaction_view2_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($store_transaction_view2->Export == "" && $store_transaction_view2->CurrentAction == "") { ?>
<form name="fstore_transaction_view2listsrch" id="fstore_transaction_view2listsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($store_transaction_view2_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fstore_transaction_view2listsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="store_transaction_view2">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($store_transaction_view2_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($store_transaction_view2_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $store_transaction_view2_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($store_transaction_view2_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($store_transaction_view2_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($store_transaction_view2_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($store_transaction_view2_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("SearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $store_transaction_view2_list->ShowPageHeader(); ?>
<?php
$store_transaction_view2_list->ShowMessage();
?>
<?php if ($store_transaction_view2_list->TotalRecs > 0 || $store_transaction_view2->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($store_transaction_view2_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> store_transaction_view2">
<form name="fstore_transaction_view2list" id="fstore_transaction_view2list" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($store_transaction_view2_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $store_transaction_view2_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="store_transaction_view2">
<div id="gmp_store_transaction_view2" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($store_transaction_view2_list->TotalRecs > 0 || $store_transaction_view2->CurrentAction == "gridedit") { ?>
<table id="tbl_store_transaction_view2list" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$store_transaction_view2_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$store_transaction_view2_list->RenderListOptions();

// Render list options (header, left)
$store_transaction_view2_list->ListOptions->Render("header", "left");
?>
<?php if ($store_transaction_view2->sn->Visible) { // sn ?>
	<?php if ($store_transaction_view2->SortUrl($store_transaction_view2->sn) == "") { ?>
		<th data-name="sn" class="<?php echo $store_transaction_view2->sn->HeaderCellClass() ?>"><div id="elh_store_transaction_view2_sn" class="store_transaction_view2_sn"><div class="ewTableHeaderCaption"><?php echo $store_transaction_view2->sn->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sn" class="<?php echo $store_transaction_view2->sn->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $store_transaction_view2->SortUrl($store_transaction_view2->sn) ?>',1);"><div id="elh_store_transaction_view2_sn" class="store_transaction_view2_sn">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $store_transaction_view2->sn->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($store_transaction_view2->sn->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($store_transaction_view2->sn->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($store_transaction_view2->receipt_number->Visible) { // receipt_number ?>
	<?php if ($store_transaction_view2->SortUrl($store_transaction_view2->receipt_number) == "") { ?>
		<th data-name="receipt_number" class="<?php echo $store_transaction_view2->receipt_number->HeaderCellClass() ?>"><div id="elh_store_transaction_view2_receipt_number" class="store_transaction_view2_receipt_number"><div class="ewTableHeaderCaption"><?php echo $store_transaction_view2->receipt_number->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="receipt_number" class="<?php echo $store_transaction_view2->receipt_number->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $store_transaction_view2->SortUrl($store_transaction_view2->receipt_number) ?>',1);"><div id="elh_store_transaction_view2_receipt_number" class="store_transaction_view2_receipt_number">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $store_transaction_view2->receipt_number->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($store_transaction_view2->receipt_number->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($store_transaction_view2->receipt_number->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($store_transaction_view2->transaction_date->Visible) { // transaction_date ?>
	<?php if ($store_transaction_view2->SortUrl($store_transaction_view2->transaction_date) == "") { ?>
		<th data-name="transaction_date" class="<?php echo $store_transaction_view2->transaction_date->HeaderCellClass() ?>"><div id="elh_store_transaction_view2_transaction_date" class="store_transaction_view2_transaction_date"><div class="ewTableHeaderCaption"><?php echo $store_transaction_view2->transaction_date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="transaction_date" class="<?php echo $store_transaction_view2->transaction_date->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $store_transaction_view2->SortUrl($store_transaction_view2->transaction_date) ?>',1);"><div id="elh_store_transaction_view2_transaction_date" class="store_transaction_view2_transaction_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $store_transaction_view2->transaction_date->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($store_transaction_view2->transaction_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($store_transaction_view2->transaction_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($store_transaction_view2->transaction_month->Visible) { // transaction_month ?>
	<?php if ($store_transaction_view2->SortUrl($store_transaction_view2->transaction_month) == "") { ?>
		<th data-name="transaction_month" class="<?php echo $store_transaction_view2->transaction_month->HeaderCellClass() ?>"><div id="elh_store_transaction_view2_transaction_month" class="store_transaction_view2_transaction_month"><div class="ewTableHeaderCaption"><?php echo $store_transaction_view2->transaction_month->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="transaction_month" class="<?php echo $store_transaction_view2->transaction_month->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $store_transaction_view2->SortUrl($store_transaction_view2->transaction_month) ?>',1);"><div id="elh_store_transaction_view2_transaction_month" class="store_transaction_view2_transaction_month">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $store_transaction_view2->transaction_month->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($store_transaction_view2->transaction_month->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($store_transaction_view2->transaction_month->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($store_transaction_view2->store_name->Visible) { // store_name ?>
	<?php if ($store_transaction_view2->SortUrl($store_transaction_view2->store_name) == "") { ?>
		<th data-name="store_name" class="<?php echo $store_transaction_view2->store_name->HeaderCellClass() ?>"><div id="elh_store_transaction_view2_store_name" class="store_transaction_view2_store_name"><div class="ewTableHeaderCaption"><?php echo $store_transaction_view2->store_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="store_name" class="<?php echo $store_transaction_view2->store_name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $store_transaction_view2->SortUrl($store_transaction_view2->store_name) ?>',1);"><div id="elh_store_transaction_view2_store_name" class="store_transaction_view2_store_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $store_transaction_view2->store_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($store_transaction_view2->store_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($store_transaction_view2->store_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($store_transaction_view2->category->Visible) { // category ?>
	<?php if ($store_transaction_view2->SortUrl($store_transaction_view2->category) == "") { ?>
		<th data-name="category" class="<?php echo $store_transaction_view2->category->HeaderCellClass() ?>"><div id="elh_store_transaction_view2_category" class="store_transaction_view2_category"><div class="ewTableHeaderCaption"><?php echo $store_transaction_view2->category->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="category" class="<?php echo $store_transaction_view2->category->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $store_transaction_view2->SortUrl($store_transaction_view2->category) ?>',1);"><div id="elh_store_transaction_view2_category" class="store_transaction_view2_category">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $store_transaction_view2->category->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($store_transaction_view2->category->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($store_transaction_view2->category->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($store_transaction_view2->product_name->Visible) { // product_name ?>
	<?php if ($store_transaction_view2->SortUrl($store_transaction_view2->product_name) == "") { ?>
		<th data-name="product_name" class="<?php echo $store_transaction_view2->product_name->HeaderCellClass() ?>"><div id="elh_store_transaction_view2_product_name" class="store_transaction_view2_product_name"><div class="ewTableHeaderCaption"><?php echo $store_transaction_view2->product_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="product_name" class="<?php echo $store_transaction_view2->product_name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $store_transaction_view2->SortUrl($store_transaction_view2->product_name) ?>',1);"><div id="elh_store_transaction_view2_product_name" class="store_transaction_view2_product_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $store_transaction_view2->product_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($store_transaction_view2->product_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($store_transaction_view2->product_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($store_transaction_view2->quantity->Visible) { // quantity ?>
	<?php if ($store_transaction_view2->SortUrl($store_transaction_view2->quantity) == "") { ?>
		<th data-name="quantity" class="<?php echo $store_transaction_view2->quantity->HeaderCellClass() ?>"><div id="elh_store_transaction_view2_quantity" class="store_transaction_view2_quantity"><div class="ewTableHeaderCaption"><?php echo $store_transaction_view2->quantity->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="quantity" class="<?php echo $store_transaction_view2->quantity->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $store_transaction_view2->SortUrl($store_transaction_view2->quantity) ?>',1);"><div id="elh_store_transaction_view2_quantity" class="store_transaction_view2_quantity">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $store_transaction_view2->quantity->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($store_transaction_view2->quantity->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($store_transaction_view2->quantity->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($store_transaction_view2->amount->Visible) { // amount ?>
	<?php if ($store_transaction_view2->SortUrl($store_transaction_view2->amount) == "") { ?>
		<th data-name="amount" class="<?php echo $store_transaction_view2->amount->HeaderCellClass() ?>"><div id="elh_store_transaction_view2_amount" class="store_transaction_view2_amount"><div class="ewTableHeaderCaption"><?php echo $store_transaction_view2->amount->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="amount" class="<?php echo $store_transaction_view2->amount->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $store_transaction_view2->SortUrl($store_transaction_view2->amount) ?>',1);"><div id="elh_store_transaction_view2_amount" class="store_transaction_view2_amount">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $store_transaction_view2->amount->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($store_transaction_view2->amount->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($store_transaction_view2->amount->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$store_transaction_view2_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($store_transaction_view2->ExportAll && $store_transaction_view2->Export <> "") {
	$store_transaction_view2_list->StopRec = $store_transaction_view2_list->TotalRecs;
} else {

	// Set the last record to display
	if ($store_transaction_view2_list->TotalRecs > $store_transaction_view2_list->StartRec + $store_transaction_view2_list->DisplayRecs - 1)
		$store_transaction_view2_list->StopRec = $store_transaction_view2_list->StartRec + $store_transaction_view2_list->DisplayRecs - 1;
	else
		$store_transaction_view2_list->StopRec = $store_transaction_view2_list->TotalRecs;
}
$store_transaction_view2_list->RecCnt = $store_transaction_view2_list->StartRec - 1;
if ($store_transaction_view2_list->Recordset && !$store_transaction_view2_list->Recordset->EOF) {
	$store_transaction_view2_list->Recordset->MoveFirst();
	$bSelectLimit = $store_transaction_view2_list->UseSelectLimit;
	if (!$bSelectLimit && $store_transaction_view2_list->StartRec > 1)
		$store_transaction_view2_list->Recordset->Move($store_transaction_view2_list->StartRec - 1);
} elseif (!$store_transaction_view2->AllowAddDeleteRow && $store_transaction_view2_list->StopRec == 0) {
	$store_transaction_view2_list->StopRec = $store_transaction_view2->GridAddRowCount;
}

// Initialize aggregate
$store_transaction_view2->RowType = EW_ROWTYPE_AGGREGATEINIT;
$store_transaction_view2->ResetAttrs();
$store_transaction_view2_list->RenderRow();
while ($store_transaction_view2_list->RecCnt < $store_transaction_view2_list->StopRec) {
	$store_transaction_view2_list->RecCnt++;
	if (intval($store_transaction_view2_list->RecCnt) >= intval($store_transaction_view2_list->StartRec)) {
		$store_transaction_view2_list->RowCnt++;

		// Set up key count
		$store_transaction_view2_list->KeyCount = $store_transaction_view2_list->RowIndex;

		// Init row class and style
		$store_transaction_view2->ResetAttrs();
		$store_transaction_view2->CssClass = "";
		if ($store_transaction_view2->CurrentAction == "gridadd") {
		} else {
			$store_transaction_view2_list->LoadRowValues($store_transaction_view2_list->Recordset); // Load row values
		}
		$store_transaction_view2->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$store_transaction_view2->RowAttrs = array_merge($store_transaction_view2->RowAttrs, array('data-rowindex'=>$store_transaction_view2_list->RowCnt, 'id'=>'r' . $store_transaction_view2_list->RowCnt . '_store_transaction_view2', 'data-rowtype'=>$store_transaction_view2->RowType));

		// Render row
		$store_transaction_view2_list->RenderRow();

		// Render list options
		$store_transaction_view2_list->RenderListOptions();
?>
	<tr<?php echo $store_transaction_view2->RowAttributes() ?>>
<?php

// Render list options (body, left)
$store_transaction_view2_list->ListOptions->Render("body", "left", $store_transaction_view2_list->RowCnt);
?>
	<?php if ($store_transaction_view2->sn->Visible) { // sn ?>
		<td data-name="sn"<?php echo $store_transaction_view2->sn->CellAttributes() ?>>
<span id="el<?php echo $store_transaction_view2_list->RowCnt ?>_store_transaction_view2_sn" class="store_transaction_view2_sn">
<span<?php echo $store_transaction_view2->sn->ViewAttributes() ?>>
<?php echo $store_transaction_view2->sn->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($store_transaction_view2->receipt_number->Visible) { // receipt_number ?>
		<td data-name="receipt_number"<?php echo $store_transaction_view2->receipt_number->CellAttributes() ?>>
<span id="el<?php echo $store_transaction_view2_list->RowCnt ?>_store_transaction_view2_receipt_number" class="store_transaction_view2_receipt_number">
<span<?php echo $store_transaction_view2->receipt_number->ViewAttributes() ?>>
<?php echo $store_transaction_view2->receipt_number->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($store_transaction_view2->transaction_date->Visible) { // transaction_date ?>
		<td data-name="transaction_date"<?php echo $store_transaction_view2->transaction_date->CellAttributes() ?>>
<span id="el<?php echo $store_transaction_view2_list->RowCnt ?>_store_transaction_view2_transaction_date" class="store_transaction_view2_transaction_date">
<span<?php echo $store_transaction_view2->transaction_date->ViewAttributes() ?>>
<?php echo $store_transaction_view2->transaction_date->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($store_transaction_view2->transaction_month->Visible) { // transaction_month ?>
		<td data-name="transaction_month"<?php echo $store_transaction_view2->transaction_month->CellAttributes() ?>>
<span id="el<?php echo $store_transaction_view2_list->RowCnt ?>_store_transaction_view2_transaction_month" class="store_transaction_view2_transaction_month">
<span<?php echo $store_transaction_view2->transaction_month->ViewAttributes() ?>>
<?php echo $store_transaction_view2->transaction_month->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($store_transaction_view2->store_name->Visible) { // store_name ?>
		<td data-name="store_name"<?php echo $store_transaction_view2->store_name->CellAttributes() ?>>
<span id="el<?php echo $store_transaction_view2_list->RowCnt ?>_store_transaction_view2_store_name" class="store_transaction_view2_store_name">
<span<?php echo $store_transaction_view2->store_name->ViewAttributes() ?>>
<?php echo $store_transaction_view2->store_name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($store_transaction_view2->category->Visible) { // category ?>
		<td data-name="category"<?php echo $store_transaction_view2->category->CellAttributes() ?>>
<span id="el<?php echo $store_transaction_view2_list->RowCnt ?>_store_transaction_view2_category" class="store_transaction_view2_category">
<span<?php echo $store_transaction_view2->category->ViewAttributes() ?>>
<?php echo $store_transaction_view2->category->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($store_transaction_view2->product_name->Visible) { // product_name ?>
		<td data-name="product_name"<?php echo $store_transaction_view2->product_name->CellAttributes() ?>>
<span id="el<?php echo $store_transaction_view2_list->RowCnt ?>_store_transaction_view2_product_name" class="store_transaction_view2_product_name">
<span<?php echo $store_transaction_view2->product_name->ViewAttributes() ?>>
<?php echo $store_transaction_view2->product_name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($store_transaction_view2->quantity->Visible) { // quantity ?>
		<td data-name="quantity"<?php echo $store_transaction_view2->quantity->CellAttributes() ?>>
<span id="el<?php echo $store_transaction_view2_list->RowCnt ?>_store_transaction_view2_quantity" class="store_transaction_view2_quantity">
<span<?php echo $store_transaction_view2->quantity->ViewAttributes() ?>>
<?php echo $store_transaction_view2->quantity->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($store_transaction_view2->amount->Visible) { // amount ?>
		<td data-name="amount"<?php echo $store_transaction_view2->amount->CellAttributes() ?>>
<span id="el<?php echo $store_transaction_view2_list->RowCnt ?>_store_transaction_view2_amount" class="store_transaction_view2_amount">
<span<?php echo $store_transaction_view2->amount->ViewAttributes() ?>>
<?php echo $store_transaction_view2->amount->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$store_transaction_view2_list->ListOptions->Render("body", "right", $store_transaction_view2_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($store_transaction_view2->CurrentAction <> "gridadd")
		$store_transaction_view2_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($store_transaction_view2->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($store_transaction_view2_list->Recordset)
	$store_transaction_view2_list->Recordset->Close();
?>
<?php if ($store_transaction_view2->Export == "") { ?>
<div class="box-footer ewGridLowerPanel">
<?php if ($store_transaction_view2->CurrentAction <> "gridadd" && $store_transaction_view2->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($store_transaction_view2_list->Pager)) $store_transaction_view2_list->Pager = new cPrevNextPager($store_transaction_view2_list->StartRec, $store_transaction_view2_list->DisplayRecs, $store_transaction_view2_list->TotalRecs, $store_transaction_view2_list->AutoHidePager) ?>
<?php if ($store_transaction_view2_list->Pager->RecordCount > 0 && $store_transaction_view2_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($store_transaction_view2_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $store_transaction_view2_list->PageUrl() ?>start=<?php echo $store_transaction_view2_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($store_transaction_view2_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $store_transaction_view2_list->PageUrl() ?>start=<?php echo $store_transaction_view2_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $store_transaction_view2_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($store_transaction_view2_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $store_transaction_view2_list->PageUrl() ?>start=<?php echo $store_transaction_view2_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($store_transaction_view2_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $store_transaction_view2_list->PageUrl() ?>start=<?php echo $store_transaction_view2_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $store_transaction_view2_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $store_transaction_view2_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $store_transaction_view2_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $store_transaction_view2_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($store_transaction_view2_list->TotalRecs > 0 && (!$store_transaction_view2_list->AutoHidePageSizeSelector || $store_transaction_view2_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="store_transaction_view2">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($store_transaction_view2_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($store_transaction_view2_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($store_transaction_view2_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($store_transaction_view2->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($store_transaction_view2_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($store_transaction_view2_list->TotalRecs == 0 && $store_transaction_view2->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($store_transaction_view2_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($store_transaction_view2->Export == "") { ?>
<script type="text/javascript">
fstore_transaction_view2listsrch.FilterList = <?php echo $store_transaction_view2_list->GetFilterList() ?>;
fstore_transaction_view2listsrch.Init();
fstore_transaction_view2list.Init();
</script>
<?php } ?>
<?php
$store_transaction_view2_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($store_transaction_view2->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$store_transaction_view2_list->Page_Terminate();
?>
