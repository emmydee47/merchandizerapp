<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "location_targetinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$location_target_list = NULL; // Initialize page object first

class clocation_target_list extends clocation_target {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{B6A7A568-E606-4B9C-A82F-C80372444DA0}';

	// Table name
	var $TableName = 'location_target';

	// Page object name
	var $PageObjName = 'location_target_list';

	// Grid form hidden field names
	var $FormName = 'flocation_targetlist';
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

		// Table object (location_target)
		if (!isset($GLOBALS["location_target"]) || get_class($GLOBALS["location_target"]) == "clocation_target") {
			$GLOBALS["location_target"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["location_target"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "location_targetadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "location_targetdelete.php";
		$this->MultiUpdateUrl = "location_targetupdate.php";

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'location_target', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption flocation_targetlistsrch";

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
		// Create form object

		$objForm = new cFormObj();

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
		$this->date->SetVisibility();
		$this->date->Visible = !$this->IsAddOrEdit();
		$this->location->SetVisibility();
		$this->month->SetVisibility();
		$this->product_category->SetVisibility();
		$this->target_value->SetVisibility();

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
		global $EW_EXPORT, $location_target;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($location_target);
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

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to grid edit mode
				if ($this->CurrentAction == "gridedit")
					$this->GridEditMode();

				// Switch to grid add mode
				if ($this->CurrentAction == "gridadd")
					$this->GridAddMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$this->CurrentAction = $_POST["a_list"]; // Get action

					// Grid Update
					if (($this->CurrentAction == "gridupdate" || $this->CurrentAction == "gridoverwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridedit") {
						if ($this->ValidateGridForm()) {
							$bGridUpdate = $this->GridUpdate();
						} else {
							$bGridUpdate = FALSE;
							$this->setFailureMessage($gsFormError);
						}
						if (!$bGridUpdate) {
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridedit"; // Stay in Grid Edit mode
						}
					}

					// Grid Insert
					if ($this->CurrentAction == "gridinsert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridadd") {
						if ($this->ValidateGridForm()) {
							$bGridInsert = $this->GridInsert();
						} else {
							$bGridInsert = FALSE;
							$this->setFailureMessage($gsFormError);
						}
						if (!$bGridInsert) {
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridadd"; // Stay in Grid Add mode
						}
					}
				}
			}

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

			// Show grid delete link for grid add / grid edit
			if ($this->AllowAddDeleteRow) {
				if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
					$item = $this->ListOptions->GetItem("griddelete");
					if ($item) $item->Visible = TRUE;
				}
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Process filter list
			$this->ProcessFilterList();

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

	// Exit inline mode
	function ClearInlineMode() {
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Grid Add mode
	function GridAddMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridadd"; // Enabled grid add
	}

	// Switch to Grid Edit mode
	function GridEditMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridedit"; // Enable grid edit
	}

	// Perform update to grid
	function GridUpdate() {
		global $Language, $objForm, $gsFormError;
		$bGridUpdate = TRUE;

		// Get old recordset
		$this->CurrentFilter = $this->BuildKeyFilter();
		if ($this->CurrentFilter == "")
			$this->CurrentFilter = "0=1";
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			$rsold = $rs->GetRows();
			$rs->Close();
		}

		// Call Grid Updating event
		if (!$this->Grid_Updating($rsold)) {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("GridEditCancelled")); // Set grid edit cancelled message
			return FALSE;
		}

		// Begin transaction
		$conn->BeginTrans();
		$sKey = "";

		// Update row index and get row key
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Update all rows based on key
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
			$objForm->Index = $rowindex;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			$rowaction = strval($objForm->GetValue($this->FormActionName));

			// Load all values and keys
			if ($rowaction <> "insertdelete") { // Skip insert then deleted rows
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
					$bGridUpdate = $this->SetupKeyValues($rowkey); // Set up key values
				} else {
					$bGridUpdate = TRUE;
				}

				// Skip empty row
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// No action required
				// Validate form and insert/update/delete record

				} elseif ($bGridUpdate) {
					if ($rowaction == "delete") {
						$this->CurrentFilter = $this->KeyFilter();
						$bGridUpdate = $this->DeleteRows(); // Delete this row
					} else if (!$this->ValidateForm()) {
						$bGridUpdate = FALSE; // Form error, reset action
						$this->setFailureMessage($gsFormError);
					} else {
						if ($rowaction == "insert") {
							$bGridUpdate = $this->AddRow(); // Insert this row
						} else {
							if ($rowkey <> "") {
								$this->SendEmail = FALSE; // Do not send email on update success
								$bGridUpdate = $this->EditRow(); // Update this row
							}
						} // End update
					}
				}
				if ($bGridUpdate) {
					if ($sKey <> "") $sKey .= ", ";
					$sKey .= $rowkey;
				} else {
					break;
				}
			}
		}
		if ($bGridUpdate) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Updated event
			$this->Grid_Updated($rsold, $rsnew);
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up update success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
		}
		return $bGridUpdate;
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

	// Perform Grid Add
	function GridInsert() {
		global $Language, $objForm, $gsFormError;
		$rowindex = 1;
		$bGridInsert = FALSE;
		$conn = &$this->Connection();

		// Call Grid Inserting event
		if (!$this->Grid_Inserting()) {
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("GridAddCancelled")); // Set grid add cancelled message
			}
			return FALSE;
		}

		// Begin transaction
		$conn->BeginTrans();

		// Init key filter
		$sWrkFilter = "";
		$addcnt = 0;
		$sKey = "";

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Insert all rows
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "" && $rowaction <> "insert")
				continue; // Skip
			$this->LoadFormValues(); // Get form values
			if (!$this->EmptyRow()) {
				$addcnt++;
				$this->SendEmail = FALSE; // Do not send email on insert success

				// Validate form
				if (!$this->ValidateForm()) {
					$bGridInsert = FALSE; // Form error, reset action
					$this->setFailureMessage($gsFormError);
				} else {
					$bGridInsert = $this->AddRow($this->OldRecordset); // Insert this row
				}
				if ($bGridInsert) {
					if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
					$sKey .= $this->sn->CurrentValue;

					// Add filter for this record
					$sFilter = $this->KeyFilter();
					if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
					$sWrkFilter .= $sFilter;
				} else {
					break;
				}
			}
		}
		if ($addcnt == 0) { // No record inserted
			$this->setFailureMessage($Language->Phrase("NoAddRecord"));
			$bGridInsert = FALSE;
		}
		if ($bGridInsert) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			$this->CurrentFilter = $sWrkFilter;
			$sSql = $this->SQL();
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Inserted event
			$this->Grid_Inserted($rsnew);
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("InsertSuccess")); // Set up insert success message
			$this->ClearInlineMode(); // Clear grid add mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("InsertFailed")); // Set insert failed message
			}
		}
		return $bGridInsert;
	}

	// Check if empty row
	function EmptyRow() {
		global $objForm;
		if ($objForm->HasValue("x_location") && $objForm->HasValue("o_location") && $this->location->CurrentValue <> $this->location->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_month") && $objForm->HasValue("o_month") && $this->month->CurrentValue <> $this->month->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_product_category") && $objForm->HasValue("o_product_category") && $this->product_category->CurrentValue <> $this->product_category->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_target_value") && $objForm->HasValue("o_target_value") && $this->target_value->CurrentValue <> $this->target_value->OldValue)
			return FALSE;
		return TRUE;
	}

	// Validate grid form
	function ValidateGridForm() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Validate all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else if (!$this->ValidateForm()) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	// Get all form values of the grid
	function GetGridFormValues() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;
		$rows = array();

		// Loop through all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else {
					$rows[] = $this->GetFieldValues("FormValue"); // Return row as array
				}
			}
		}
		return $rows; // Return as array of array
	}

	// Restore form values for current row
	function RestoreCurrentRowFormValues($idx) {
		global $objForm;

		// Get row based on current index
		$objForm->Index = $idx;
		$this->LoadFormValues(); // Load form values
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "flocation_targetlistsrch") : "";
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->sn->AdvancedSearch->ToJson(), ","); // Field sn
		$sFilterList = ew_Concat($sFilterList, $this->date->AdvancedSearch->ToJson(), ","); // Field date
		$sFilterList = ew_Concat($sFilterList, $this->location->AdvancedSearch->ToJson(), ","); // Field location
		$sFilterList = ew_Concat($sFilterList, $this->month->AdvancedSearch->ToJson(), ","); // Field month
		$sFilterList = ew_Concat($sFilterList, $this->product_category->AdvancedSearch->ToJson(), ","); // Field product_category
		$sFilterList = ew_Concat($sFilterList, $this->target_value->AdvancedSearch->ToJson(), ","); // Field target_value
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "flocation_targetlistsrch", $filters);

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

		// Field date
		$this->date->AdvancedSearch->SearchValue = @$filter["x_date"];
		$this->date->AdvancedSearch->SearchOperator = @$filter["z_date"];
		$this->date->AdvancedSearch->SearchCondition = @$filter["v_date"];
		$this->date->AdvancedSearch->SearchValue2 = @$filter["y_date"];
		$this->date->AdvancedSearch->SearchOperator2 = @$filter["w_date"];
		$this->date->AdvancedSearch->Save();

		// Field location
		$this->location->AdvancedSearch->SearchValue = @$filter["x_location"];
		$this->location->AdvancedSearch->SearchOperator = @$filter["z_location"];
		$this->location->AdvancedSearch->SearchCondition = @$filter["v_location"];
		$this->location->AdvancedSearch->SearchValue2 = @$filter["y_location"];
		$this->location->AdvancedSearch->SearchOperator2 = @$filter["w_location"];
		$this->location->AdvancedSearch->Save();

		// Field month
		$this->month->AdvancedSearch->SearchValue = @$filter["x_month"];
		$this->month->AdvancedSearch->SearchOperator = @$filter["z_month"];
		$this->month->AdvancedSearch->SearchCondition = @$filter["v_month"];
		$this->month->AdvancedSearch->SearchValue2 = @$filter["y_month"];
		$this->month->AdvancedSearch->SearchOperator2 = @$filter["w_month"];
		$this->month->AdvancedSearch->Save();

		// Field product_category
		$this->product_category->AdvancedSearch->SearchValue = @$filter["x_product_category"];
		$this->product_category->AdvancedSearch->SearchOperator = @$filter["z_product_category"];
		$this->product_category->AdvancedSearch->SearchCondition = @$filter["v_product_category"];
		$this->product_category->AdvancedSearch->SearchValue2 = @$filter["y_product_category"];
		$this->product_category->AdvancedSearch->SearchOperator2 = @$filter["w_product_category"];
		$this->product_category->AdvancedSearch->Save();

		// Field target_value
		$this->target_value->AdvancedSearch->SearchValue = @$filter["x_target_value"];
		$this->target_value->AdvancedSearch->SearchOperator = @$filter["z_target_value"];
		$this->target_value->AdvancedSearch->SearchCondition = @$filter["v_target_value"];
		$this->target_value->AdvancedSearch->SearchValue2 = @$filter["y_target_value"];
		$this->target_value->AdvancedSearch->SearchOperator2 = @$filter["w_target_value"];
		$this->target_value->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->month, $arKeywords, $type);
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
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->sn); // sn
			$this->UpdateSort($this->date); // date
			$this->UpdateSort($this->location); // location
			$this->UpdateSort($this->month); // month
			$this->UpdateSort($this->product_category); // product_category
			$this->UpdateSort($this->target_value); // target_value
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
				$this->date->setSort("");
				$this->location->setSort("");
				$this->month->setSort("");
				$this->product_category->setSort("");
				$this->target_value->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "griddelete"
		if ($this->AllowAddDeleteRow) {
			$item = &$this->ListOptions->Add("griddelete");
			$item->CssClass = "text-nowrap";
			$item->OnLeft = FALSE;
			$item->Visible = FALSE; // Default hidden
		}

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanView();
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = FALSE;

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

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "delete"
		if ($this->AllowAddDeleteRow) {
			if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$option = &$this->ListOptions;
				$option->UseButtonGroup = TRUE; // Use button group for grid delete button
				$option->UseImageAndText = TRUE; // Use image and text for grid delete button
				$oListOpt = &$option->Items["griddelete"];
				if (!$Security->CanDelete() && is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
					$oListOpt->Body = "&nbsp;";
				} else {
					$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" onclick=\"return ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->Phrase("DeleteLink") . "</a>";
				}
			}
		}

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		$viewcaption = ew_HtmlTitle($Language->Phrase("ViewLink"));
		if ($Security->CanView()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		$copycaption = ew_HtmlTitle($Language->Phrase("CopyLink"));
		if ($Security->CanAdd()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

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
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->sn->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$item = &$option->Add("gridadd");
		$item->Body = "<a class=\"ewAddEdit ewGridAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" href=\"" . ew_HtmlEncode($this->GridAddUrl) . "\">" . $Language->Phrase("GridAddLink") . "</a>";
		$item->Visible = ($this->GridAddUrl <> "" && $Security->CanAdd());

		// Add grid edit
		$option = $options["addedit"];
		$item = &$option->Add("gridedit");
		$item->Body = "<a class=\"ewAddEdit ewGridEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GridEditUrl) . "\">" . $Language->Phrase("GridEditLink") . "</a>";
		$item->Visible = ($this->GridEditUrl <> "" && $Security->CanEdit());
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"flocation_targetlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"flocation_targetlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "gridedit") { // Not grid add/edit mode
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.flocation_targetlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		} else { // Grid add/edit mode

			// Hide all options first
			foreach ($options as &$option)
				$option->HideAllOptions();
			if ($this->CurrentAction == "gridadd") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = $Security->CanAdd();
				}
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;

				// Add grid insert
				$item = &$option->Add("gridinsert");
				$item->Body = "<a class=\"ewAction ewGridInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("GridInsertLink") . "</a>";

				// Add grid cancel
				$item = &$option->Add("gridcancel");
				$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
				$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
			if ($this->CurrentAction == "gridedit") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = $Security->CanAdd();
				}
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;
					$item = &$option->Add("gridsave");
					$item->Body = "<a class=\"ewAction ewGridSave\" title=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("GridSaveLink") . "</a>";
					$item = &$option->Add("gridcancel");
					$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
					$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"flocation_targetlistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

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

	// Load default values
	function LoadDefaultValues() {
		$this->sn->CurrentValue = NULL;
		$this->sn->OldValue = $this->sn->CurrentValue;
		$this->date->CurrentValue = NULL;
		$this->date->OldValue = $this->date->CurrentValue;
		$this->location->CurrentValue = NULL;
		$this->location->OldValue = $this->location->CurrentValue;
		$this->month->CurrentValue = NULL;
		$this->month->OldValue = $this->month->CurrentValue;
		$this->product_category->CurrentValue = NULL;
		$this->product_category->OldValue = $this->product_category->CurrentValue;
		$this->target_value->CurrentValue = NULL;
		$this->target_value->OldValue = $this->target_value->CurrentValue;
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "" && $this->Command == "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->sn->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->sn->setFormValue($objForm->GetValue("x_sn"));
		if (!$this->date->FldIsDetailKey) {
			$this->date->setFormValue($objForm->GetValue("x_date"));
			$this->date->CurrentValue = ew_UnFormatDateTime($this->date->CurrentValue, 0);
		}
		$this->date->setOldValue($objForm->GetValue("o_date"));
		if (!$this->location->FldIsDetailKey) {
			$this->location->setFormValue($objForm->GetValue("x_location"));
		}
		$this->location->setOldValue($objForm->GetValue("o_location"));
		if (!$this->month->FldIsDetailKey) {
			$this->month->setFormValue($objForm->GetValue("x_month"));
		}
		$this->month->setOldValue($objForm->GetValue("o_month"));
		if (!$this->product_category->FldIsDetailKey) {
			$this->product_category->setFormValue($objForm->GetValue("x_product_category"));
		}
		$this->product_category->setOldValue($objForm->GetValue("o_product_category"));
		if (!$this->target_value->FldIsDetailKey) {
			$this->target_value->setFormValue($objForm->GetValue("x_target_value"));
		}
		$this->target_value->setOldValue($objForm->GetValue("o_target_value"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->sn->CurrentValue = $this->sn->FormValue;
		$this->date->CurrentValue = $this->date->FormValue;
		$this->date->CurrentValue = ew_UnFormatDateTime($this->date->CurrentValue, 0);
		$this->location->CurrentValue = $this->location->FormValue;
		$this->month->CurrentValue = $this->month->FormValue;
		$this->product_category->CurrentValue = $this->product_category->FormValue;
		$this->target_value->CurrentValue = $this->target_value->FormValue;
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
		$this->date->setDbValue($row['date']);
		$this->location->setDbValue($row['location']);
		$this->month->setDbValue($row['month']);
		$this->product_category->setDbValue($row['product_category']);
		$this->target_value->setDbValue($row['target_value']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['sn'] = $this->sn->CurrentValue;
		$row['date'] = $this->date->CurrentValue;
		$row['location'] = $this->location->CurrentValue;
		$row['month'] = $this->month->CurrentValue;
		$row['product_category'] = $this->product_category->CurrentValue;
		$row['target_value'] = $this->target_value->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->sn->DbValue = $row['sn'];
		$this->date->DbValue = $row['date'];
		$this->location->DbValue = $row['location'];
		$this->month->DbValue = $row['month'];
		$this->product_category->DbValue = $row['product_category'];
		$this->target_value->DbValue = $row['target_value'];
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

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// sn
		// date
		// location
		// month
		// product_category
		// target_value

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// sn
		$this->sn->ViewValue = $this->sn->CurrentValue;
		$this->sn->ViewCustomAttributes = "";

		// date
		$this->date->ViewValue = $this->date->CurrentValue;
		$this->date->ViewValue = ew_FormatDateTime($this->date->ViewValue, 0);
		$this->date->ViewCustomAttributes = "";

		// location
		if (strval($this->location->CurrentValue) <> "") {
			$sFilterWrk = "`sn`" . ew_SearchString("=", $this->location->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `sn`, `store_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `stores`";
		$sWhereWrk = "";
		$this->location->LookupFilters = array("dx1" => '`store_name`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->location, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->location->ViewValue = $this->location->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->location->ViewValue = $this->location->CurrentValue;
			}
		} else {
			$this->location->ViewValue = NULL;
		}
		$this->location->ViewCustomAttributes = "";

		// month
		if (strval($this->month->CurrentValue) <> "") {
			$this->month->ViewValue = $this->month->OptionCaption($this->month->CurrentValue);
		} else {
			$this->month->ViewValue = NULL;
		}
		$this->month->ViewCustomAttributes = "";

		// product_category
		if (strval($this->product_category->CurrentValue) <> "") {
			$sFilterWrk = "`sn`" . ew_SearchString("=", $this->product_category->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `sn`, `category` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `product_categories`";
		$sWhereWrk = "";
		$this->product_category->LookupFilters = array();
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

		// target_value
		$this->target_value->ViewValue = $this->target_value->CurrentValue;
		$this->target_value->ViewCustomAttributes = "";

			// sn
			$this->sn->LinkCustomAttributes = "";
			$this->sn->HrefValue = "";
			$this->sn->TooltipValue = "";

			// date
			$this->date->LinkCustomAttributes = "";
			$this->date->HrefValue = "";
			$this->date->TooltipValue = "";

			// location
			$this->location->LinkCustomAttributes = "";
			$this->location->HrefValue = "";
			$this->location->TooltipValue = "";

			// month
			$this->month->LinkCustomAttributes = "";
			$this->month->HrefValue = "";
			$this->month->TooltipValue = "";

			// product_category
			$this->product_category->LinkCustomAttributes = "";
			$this->product_category->HrefValue = "";
			$this->product_category->TooltipValue = "";

			// target_value
			$this->target_value->LinkCustomAttributes = "";
			$this->target_value->HrefValue = "";
			$this->target_value->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// sn
			// date
			// location

			$this->location->EditCustomAttributes = "";
			if (trim(strval($this->location->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`sn`" . ew_SearchString("=", $this->location->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `sn`, `store_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `stores`";
			$sWhereWrk = "";
			$this->location->LookupFilters = array("dx1" => '`store_name`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->location, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->location->ViewValue = $this->location->DisplayValue($arwrk);
			} else {
				$this->location->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->location->EditValue = $arwrk;

			// month
			$this->month->EditAttrs["class"] = "form-control";
			$this->month->EditCustomAttributes = "";
			$this->month->EditValue = $this->month->Options(TRUE);

			// product_category
			$this->product_category->EditAttrs["class"] = "form-control";
			$this->product_category->EditCustomAttributes = "";
			if (trim(strval($this->product_category->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`sn`" . ew_SearchString("=", $this->product_category->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `sn`, `category` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `product_categories`";
			$sWhereWrk = "";
			$this->product_category->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->product_category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->product_category->EditValue = $arwrk;

			// target_value
			$this->target_value->EditAttrs["class"] = "form-control";
			$this->target_value->EditCustomAttributes = "";
			$this->target_value->EditValue = ew_HtmlEncode($this->target_value->CurrentValue);
			$this->target_value->PlaceHolder = ew_RemoveHtml($this->target_value->FldCaption());

			// Add refer script
			// sn

			$this->sn->LinkCustomAttributes = "";
			$this->sn->HrefValue = "";

			// date
			$this->date->LinkCustomAttributes = "";
			$this->date->HrefValue = "";

			// location
			$this->location->LinkCustomAttributes = "";
			$this->location->HrefValue = "";

			// month
			$this->month->LinkCustomAttributes = "";
			$this->month->HrefValue = "";

			// product_category
			$this->product_category->LinkCustomAttributes = "";
			$this->product_category->HrefValue = "";

			// target_value
			$this->target_value->LinkCustomAttributes = "";
			$this->target_value->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// sn
			$this->sn->EditAttrs["class"] = "form-control";
			$this->sn->EditCustomAttributes = "";
			$this->sn->EditValue = $this->sn->CurrentValue;
			$this->sn->ViewCustomAttributes = "";

			// date
			// location

			$this->location->EditCustomAttributes = "";
			if (trim(strval($this->location->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`sn`" . ew_SearchString("=", $this->location->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `sn`, `store_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `stores`";
			$sWhereWrk = "";
			$this->location->LookupFilters = array("dx1" => '`store_name`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->location, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->location->ViewValue = $this->location->DisplayValue($arwrk);
			} else {
				$this->location->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->location->EditValue = $arwrk;

			// month
			$this->month->EditAttrs["class"] = "form-control";
			$this->month->EditCustomAttributes = "";
			$this->month->EditValue = $this->month->Options(TRUE);

			// product_category
			$this->product_category->EditAttrs["class"] = "form-control";
			$this->product_category->EditCustomAttributes = "";
			if (trim(strval($this->product_category->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`sn`" . ew_SearchString("=", $this->product_category->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `sn`, `category` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `product_categories`";
			$sWhereWrk = "";
			$this->product_category->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->product_category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->product_category->EditValue = $arwrk;

			// target_value
			$this->target_value->EditAttrs["class"] = "form-control";
			$this->target_value->EditCustomAttributes = "";
			$this->target_value->EditValue = ew_HtmlEncode($this->target_value->CurrentValue);
			$this->target_value->PlaceHolder = ew_RemoveHtml($this->target_value->FldCaption());

			// Edit refer script
			// sn

			$this->sn->LinkCustomAttributes = "";
			$this->sn->HrefValue = "";

			// date
			$this->date->LinkCustomAttributes = "";
			$this->date->HrefValue = "";

			// location
			$this->location->LinkCustomAttributes = "";
			$this->location->HrefValue = "";

			// month
			$this->month->LinkCustomAttributes = "";
			$this->month->HrefValue = "";

			// product_category
			$this->product_category->LinkCustomAttributes = "";
			$this->product_category->HrefValue = "";

			// target_value
			$this->target_value->LinkCustomAttributes = "";
			$this->target_value->HrefValue = "";
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
		if (!$this->location->FldIsDetailKey && !is_null($this->location->FormValue) && $this->location->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->location->FldCaption(), $this->location->ReqErrMsg));
		}
		if (!$this->month->FldIsDetailKey && !is_null($this->month->FormValue) && $this->month->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->month->FldCaption(), $this->month->ReqErrMsg));
		}
		if (!$this->product_category->FldIsDetailKey && !is_null($this->product_category->FormValue) && $this->product_category->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_category->FldCaption(), $this->product_category->ReqErrMsg));
		}
		if (!$this->target_value->FldIsDetailKey && !is_null($this->target_value->FormValue) && $this->target_value->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->target_value->FldCaption(), $this->target_value->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->target_value->FormValue)) {
			ew_AddMessage($gsFormError, $this->target_value->FldErrMsg());
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

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;
		}
		$rows = ($rs) ? $rs->GetRows() : array();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['sn'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		}
		if (!$DeleteRows) {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
		} else {
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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

			// date
			$this->date->SetDbValueDef($rsnew, ew_CurrentDate(), ew_CurrentDate());
			$rsnew['date'] = &$this->date->DbValue;

			// location
			$this->location->SetDbValueDef($rsnew, $this->location->CurrentValue, 0, $this->location->ReadOnly);

			// month
			$this->month->SetDbValueDef($rsnew, $this->month->CurrentValue, "", $this->month->ReadOnly);

			// product_category
			$this->product_category->SetDbValueDef($rsnew, $this->product_category->CurrentValue, 0, $this->product_category->ReadOnly);

			// target_value
			$this->target_value->SetDbValueDef($rsnew, $this->target_value->CurrentValue, 0, $this->target_value->ReadOnly);

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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// date
		$this->date->SetDbValueDef($rsnew, ew_CurrentDate(), ew_CurrentDate());
		$rsnew['date'] = &$this->date->DbValue;

		// location
		$this->location->SetDbValueDef($rsnew, $this->location->CurrentValue, 0, FALSE);

		// month
		$this->month->SetDbValueDef($rsnew, $this->month->CurrentValue, "", FALSE);

		// product_category
		$this->product_category->SetDbValueDef($rsnew, $this->product_category->CurrentValue, 0, FALSE);

		// target_value
		$this->target_value->SetDbValueDef($rsnew, $this->target_value->CurrentValue, 0, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
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
		$item->Body = "<button id=\"emf_location_target\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_location_target',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.flocation_targetlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
		case "x_location":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `sn` AS `LinkFld`, `store_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `stores`";
			$sWhereWrk = "{filter}";
			$this->location->LookupFilters = array("dx1" => '`store_name`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`sn` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->location, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_product_category":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `sn` AS `LinkFld`, `category` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `product_categories`";
			$sWhereWrk = "";
			$this->product_category->LookupFilters = array();
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
if (!isset($location_target_list)) $location_target_list = new clocation_target_list();

// Page init
$location_target_list->Page_Init();

// Page main
$location_target_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$location_target_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($location_target->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = flocation_targetlist = new ew_Form("flocation_targetlist", "list");
flocation_targetlist.FormKeyCountName = '<?php echo $location_target_list->FormKeyCountName ?>';

// Validate form
flocation_targetlist.Validate = function() {
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
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_location");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $location_target->location->FldCaption(), $location_target->location->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_month");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $location_target->month->FldCaption(), $location_target->month->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_product_category");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $location_target->product_category->FldCaption(), $location_target->product_category->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_target_value");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $location_target->target_value->FldCaption(), $location_target->target_value->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_target_value");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($location_target->target_value->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	if (gridinsert && addcnt == 0) { // No row added
		ew_Alert(ewLanguage.Phrase("NoAddRecord"));
		return false;
	}
	return true;
}

// Check empty row
flocation_targetlist.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "location", false)) return false;
	if (ew_ValueChanged(fobj, infix, "month", false)) return false;
	if (ew_ValueChanged(fobj, infix, "product_category", false)) return false;
	if (ew_ValueChanged(fobj, infix, "target_value", false)) return false;
	return true;
}

// Form_CustomValidate event
flocation_targetlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
flocation_targetlist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
flocation_targetlist.Lists["x_location"] = {"LinkField":"x_sn","Ajax":true,"AutoFill":false,"DisplayFields":["x_store_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"stores"};
flocation_targetlist.Lists["x_location"].Data = "<?php echo $location_target_list->location->LookupFilterQuery(FALSE, "list") ?>";
flocation_targetlist.Lists["x_month"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
flocation_targetlist.Lists["x_month"].Options = <?php echo json_encode($location_target_list->month->Options()) ?>;
flocation_targetlist.Lists["x_product_category"] = {"LinkField":"x_sn","Ajax":true,"AutoFill":false,"DisplayFields":["x_category","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"product_categories"};
flocation_targetlist.Lists["x_product_category"].Data = "<?php echo $location_target_list->product_category->LookupFilterQuery(FALSE, "list") ?>";

// Form object for search
var CurrentSearchForm = flocation_targetlistsrch = new ew_Form("flocation_targetlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($location_target->Export == "") { ?>
<div class="ewToolbar">
<?php if ($location_target_list->TotalRecs > 0 && $location_target_list->ExportOptions->Visible()) { ?>
<?php $location_target_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($location_target_list->SearchOptions->Visible()) { ?>
<?php $location_target_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($location_target_list->FilterOptions->Visible()) { ?>
<?php $location_target_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
if ($location_target->CurrentAction == "gridadd") {
	$location_target->CurrentFilter = "0=1";
	$location_target_list->StartRec = 1;
	$location_target_list->DisplayRecs = $location_target->GridAddRowCount;
	$location_target_list->TotalRecs = $location_target_list->DisplayRecs;
	$location_target_list->StopRec = $location_target_list->DisplayRecs;
} else {
	$bSelectLimit = $location_target_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($location_target_list->TotalRecs <= 0)
			$location_target_list->TotalRecs = $location_target->ListRecordCount();
	} else {
		if (!$location_target_list->Recordset && ($location_target_list->Recordset = $location_target_list->LoadRecordset()))
			$location_target_list->TotalRecs = $location_target_list->Recordset->RecordCount();
	}
	$location_target_list->StartRec = 1;
	if ($location_target_list->DisplayRecs <= 0 || ($location_target->Export <> "" && $location_target->ExportAll)) // Display all records
		$location_target_list->DisplayRecs = $location_target_list->TotalRecs;
	if (!($location_target->Export <> "" && $location_target->ExportAll))
		$location_target_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$location_target_list->Recordset = $location_target_list->LoadRecordset($location_target_list->StartRec-1, $location_target_list->DisplayRecs);

	// Set no record found message
	if ($location_target->CurrentAction == "" && $location_target_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$location_target_list->setWarningMessage(ew_DeniedMsg());
		if ($location_target_list->SearchWhere == "0=101")
			$location_target_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$location_target_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$location_target_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($location_target->Export == "" && $location_target->CurrentAction == "") { ?>
<form name="flocation_targetlistsrch" id="flocation_targetlistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($location_target_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="flocation_targetlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="location_target">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($location_target_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($location_target_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $location_target_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($location_target_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($location_target_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($location_target_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($location_target_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $location_target_list->ShowPageHeader(); ?>
<?php
$location_target_list->ShowMessage();
?>
<?php if ($location_target_list->TotalRecs > 0 || $location_target->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($location_target_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> location_target">
<form name="flocation_targetlist" id="flocation_targetlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($location_target_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $location_target_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="location_target">
<div id="gmp_location_target" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($location_target_list->TotalRecs > 0 || $location_target->CurrentAction == "gridedit") { ?>
<table id="tbl_location_targetlist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$location_target_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$location_target_list->RenderListOptions();

// Render list options (header, left)
$location_target_list->ListOptions->Render("header", "left");
?>
<?php if ($location_target->sn->Visible) { // sn ?>
	<?php if ($location_target->SortUrl($location_target->sn) == "") { ?>
		<th data-name="sn" class="<?php echo $location_target->sn->HeaderCellClass() ?>"><div id="elh_location_target_sn" class="location_target_sn"><div class="ewTableHeaderCaption"><?php echo $location_target->sn->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sn" class="<?php echo $location_target->sn->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $location_target->SortUrl($location_target->sn) ?>',1);"><div id="elh_location_target_sn" class="location_target_sn">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $location_target->sn->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($location_target->sn->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($location_target->sn->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($location_target->date->Visible) { // date ?>
	<?php if ($location_target->SortUrl($location_target->date) == "") { ?>
		<th data-name="date" class="<?php echo $location_target->date->HeaderCellClass() ?>"><div id="elh_location_target_date" class="location_target_date"><div class="ewTableHeaderCaption"><?php echo $location_target->date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="date" class="<?php echo $location_target->date->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $location_target->SortUrl($location_target->date) ?>',1);"><div id="elh_location_target_date" class="location_target_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $location_target->date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($location_target->date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($location_target->date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($location_target->location->Visible) { // location ?>
	<?php if ($location_target->SortUrl($location_target->location) == "") { ?>
		<th data-name="location" class="<?php echo $location_target->location->HeaderCellClass() ?>"><div id="elh_location_target_location" class="location_target_location"><div class="ewTableHeaderCaption"><?php echo $location_target->location->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="location" class="<?php echo $location_target->location->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $location_target->SortUrl($location_target->location) ?>',1);"><div id="elh_location_target_location" class="location_target_location">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $location_target->location->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($location_target->location->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($location_target->location->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($location_target->month->Visible) { // month ?>
	<?php if ($location_target->SortUrl($location_target->month) == "") { ?>
		<th data-name="month" class="<?php echo $location_target->month->HeaderCellClass() ?>"><div id="elh_location_target_month" class="location_target_month"><div class="ewTableHeaderCaption"><?php echo $location_target->month->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="month" class="<?php echo $location_target->month->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $location_target->SortUrl($location_target->month) ?>',1);"><div id="elh_location_target_month" class="location_target_month">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $location_target->month->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($location_target->month->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($location_target->month->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($location_target->product_category->Visible) { // product_category ?>
	<?php if ($location_target->SortUrl($location_target->product_category) == "") { ?>
		<th data-name="product_category" class="<?php echo $location_target->product_category->HeaderCellClass() ?>"><div id="elh_location_target_product_category" class="location_target_product_category"><div class="ewTableHeaderCaption"><?php echo $location_target->product_category->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="product_category" class="<?php echo $location_target->product_category->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $location_target->SortUrl($location_target->product_category) ?>',1);"><div id="elh_location_target_product_category" class="location_target_product_category">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $location_target->product_category->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($location_target->product_category->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($location_target->product_category->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($location_target->target_value->Visible) { // target_value ?>
	<?php if ($location_target->SortUrl($location_target->target_value) == "") { ?>
		<th data-name="target_value" class="<?php echo $location_target->target_value->HeaderCellClass() ?>"><div id="elh_location_target_target_value" class="location_target_target_value"><div class="ewTableHeaderCaption"><?php echo $location_target->target_value->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="target_value" class="<?php echo $location_target->target_value->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $location_target->SortUrl($location_target->target_value) ?>',1);"><div id="elh_location_target_target_value" class="location_target_target_value">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $location_target->target_value->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($location_target->target_value->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($location_target->target_value->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$location_target_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($location_target->ExportAll && $location_target->Export <> "") {
	$location_target_list->StopRec = $location_target_list->TotalRecs;
} else {

	// Set the last record to display
	if ($location_target_list->TotalRecs > $location_target_list->StartRec + $location_target_list->DisplayRecs - 1)
		$location_target_list->StopRec = $location_target_list->StartRec + $location_target_list->DisplayRecs - 1;
	else
		$location_target_list->StopRec = $location_target_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($location_target_list->FormKeyCountName) && ($location_target->CurrentAction == "gridadd" || $location_target->CurrentAction == "gridedit" || $location_target->CurrentAction == "F")) {
		$location_target_list->KeyCount = $objForm->GetValue($location_target_list->FormKeyCountName);
		$location_target_list->StopRec = $location_target_list->StartRec + $location_target_list->KeyCount - 1;
	}
}
$location_target_list->RecCnt = $location_target_list->StartRec - 1;
if ($location_target_list->Recordset && !$location_target_list->Recordset->EOF) {
	$location_target_list->Recordset->MoveFirst();
	$bSelectLimit = $location_target_list->UseSelectLimit;
	if (!$bSelectLimit && $location_target_list->StartRec > 1)
		$location_target_list->Recordset->Move($location_target_list->StartRec - 1);
} elseif (!$location_target->AllowAddDeleteRow && $location_target_list->StopRec == 0) {
	$location_target_list->StopRec = $location_target->GridAddRowCount;
}

// Initialize aggregate
$location_target->RowType = EW_ROWTYPE_AGGREGATEINIT;
$location_target->ResetAttrs();
$location_target_list->RenderRow();
if ($location_target->CurrentAction == "gridadd")
	$location_target_list->RowIndex = 0;
if ($location_target->CurrentAction == "gridedit")
	$location_target_list->RowIndex = 0;
while ($location_target_list->RecCnt < $location_target_list->StopRec) {
	$location_target_list->RecCnt++;
	if (intval($location_target_list->RecCnt) >= intval($location_target_list->StartRec)) {
		$location_target_list->RowCnt++;
		if ($location_target->CurrentAction == "gridadd" || $location_target->CurrentAction == "gridedit" || $location_target->CurrentAction == "F") {
			$location_target_list->RowIndex++;
			$objForm->Index = $location_target_list->RowIndex;
			if ($objForm->HasValue($location_target_list->FormActionName))
				$location_target_list->RowAction = strval($objForm->GetValue($location_target_list->FormActionName));
			elseif ($location_target->CurrentAction == "gridadd")
				$location_target_list->RowAction = "insert";
			else
				$location_target_list->RowAction = "";
		}

		// Set up key count
		$location_target_list->KeyCount = $location_target_list->RowIndex;

		// Init row class and style
		$location_target->ResetAttrs();
		$location_target->CssClass = "";
		if ($location_target->CurrentAction == "gridadd") {
			$location_target_list->LoadRowValues(); // Load default values
		} else {
			$location_target_list->LoadRowValues($location_target_list->Recordset); // Load row values
		}
		$location_target->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($location_target->CurrentAction == "gridadd") // Grid add
			$location_target->RowType = EW_ROWTYPE_ADD; // Render add
		if ($location_target->CurrentAction == "gridadd" && $location_target->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$location_target_list->RestoreCurrentRowFormValues($location_target_list->RowIndex); // Restore form values
		if ($location_target->CurrentAction == "gridedit") { // Grid edit
			if ($location_target->EventCancelled) {
				$location_target_list->RestoreCurrentRowFormValues($location_target_list->RowIndex); // Restore form values
			}
			if ($location_target_list->RowAction == "insert")
				$location_target->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$location_target->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($location_target->CurrentAction == "gridedit" && ($location_target->RowType == EW_ROWTYPE_EDIT || $location_target->RowType == EW_ROWTYPE_ADD) && $location_target->EventCancelled) // Update failed
			$location_target_list->RestoreCurrentRowFormValues($location_target_list->RowIndex); // Restore form values
		if ($location_target->RowType == EW_ROWTYPE_EDIT) // Edit row
			$location_target_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$location_target->RowAttrs = array_merge($location_target->RowAttrs, array('data-rowindex'=>$location_target_list->RowCnt, 'id'=>'r' . $location_target_list->RowCnt . '_location_target', 'data-rowtype'=>$location_target->RowType));

		// Render row
		$location_target_list->RenderRow();

		// Render list options
		$location_target_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($location_target_list->RowAction <> "delete" && $location_target_list->RowAction <> "insertdelete" && !($location_target_list->RowAction == "insert" && $location_target->CurrentAction == "F" && $location_target_list->EmptyRow())) {
?>
	<tr<?php echo $location_target->RowAttributes() ?>>
<?php

// Render list options (body, left)
$location_target_list->ListOptions->Render("body", "left", $location_target_list->RowCnt);
?>
	<?php if ($location_target->sn->Visible) { // sn ?>
		<td data-name="sn"<?php echo $location_target->sn->CellAttributes() ?>>
<?php if ($location_target->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="location_target" data-field="x_sn" name="o<?php echo $location_target_list->RowIndex ?>_sn" id="o<?php echo $location_target_list->RowIndex ?>_sn" value="<?php echo ew_HtmlEncode($location_target->sn->OldValue) ?>">
<?php } ?>
<?php if ($location_target->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $location_target_list->RowCnt ?>_location_target_sn" class="form-group location_target_sn">
<span<?php echo $location_target->sn->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $location_target->sn->EditValue ?></p></span>
</span>
<input type="hidden" data-table="location_target" data-field="x_sn" name="x<?php echo $location_target_list->RowIndex ?>_sn" id="x<?php echo $location_target_list->RowIndex ?>_sn" value="<?php echo ew_HtmlEncode($location_target->sn->CurrentValue) ?>">
<?php } ?>
<?php if ($location_target->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $location_target_list->RowCnt ?>_location_target_sn" class="location_target_sn">
<span<?php echo $location_target->sn->ViewAttributes() ?>>
<?php echo $location_target->sn->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($location_target->date->Visible) { // date ?>
		<td data-name="date"<?php echo $location_target->date->CellAttributes() ?>>
<?php if ($location_target->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="location_target" data-field="x_date" name="o<?php echo $location_target_list->RowIndex ?>_date" id="o<?php echo $location_target_list->RowIndex ?>_date" value="<?php echo ew_HtmlEncode($location_target->date->OldValue) ?>">
<?php } ?>
<?php if ($location_target->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($location_target->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $location_target_list->RowCnt ?>_location_target_date" class="location_target_date">
<span<?php echo $location_target->date->ViewAttributes() ?>>
<?php echo $location_target->date->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($location_target->location->Visible) { // location ?>
		<td data-name="location"<?php echo $location_target->location->CellAttributes() ?>>
<?php if ($location_target->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $location_target_list->RowCnt ?>_location_target_location" class="form-group location_target_location">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $location_target_list->RowIndex ?>_location"><?php echo (strval($location_target->location->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $location_target->location->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($location_target->location->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $location_target_list->RowIndex ?>_location',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="location_target" data-field="x_location" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $location_target->location->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $location_target_list->RowIndex ?>_location" id="x<?php echo $location_target_list->RowIndex ?>_location" value="<?php echo $location_target->location->CurrentValue ?>"<?php echo $location_target->location->EditAttributes() ?>>
</span>
<input type="hidden" data-table="location_target" data-field="x_location" name="o<?php echo $location_target_list->RowIndex ?>_location" id="o<?php echo $location_target_list->RowIndex ?>_location" value="<?php echo ew_HtmlEncode($location_target->location->OldValue) ?>">
<?php } ?>
<?php if ($location_target->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $location_target_list->RowCnt ?>_location_target_location" class="form-group location_target_location">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $location_target_list->RowIndex ?>_location"><?php echo (strval($location_target->location->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $location_target->location->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($location_target->location->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $location_target_list->RowIndex ?>_location',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="location_target" data-field="x_location" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $location_target->location->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $location_target_list->RowIndex ?>_location" id="x<?php echo $location_target_list->RowIndex ?>_location" value="<?php echo $location_target->location->CurrentValue ?>"<?php echo $location_target->location->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($location_target->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $location_target_list->RowCnt ?>_location_target_location" class="location_target_location">
<span<?php echo $location_target->location->ViewAttributes() ?>>
<?php echo $location_target->location->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($location_target->month->Visible) { // month ?>
		<td data-name="month"<?php echo $location_target->month->CellAttributes() ?>>
<?php if ($location_target->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $location_target_list->RowCnt ?>_location_target_month" class="form-group location_target_month">
<select data-table="location_target" data-field="x_month" data-value-separator="<?php echo $location_target->month->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $location_target_list->RowIndex ?>_month" name="x<?php echo $location_target_list->RowIndex ?>_month"<?php echo $location_target->month->EditAttributes() ?>>
<?php echo $location_target->month->SelectOptionListHtml("x<?php echo $location_target_list->RowIndex ?>_month") ?>
</select>
</span>
<input type="hidden" data-table="location_target" data-field="x_month" name="o<?php echo $location_target_list->RowIndex ?>_month" id="o<?php echo $location_target_list->RowIndex ?>_month" value="<?php echo ew_HtmlEncode($location_target->month->OldValue) ?>">
<?php } ?>
<?php if ($location_target->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $location_target_list->RowCnt ?>_location_target_month" class="form-group location_target_month">
<select data-table="location_target" data-field="x_month" data-value-separator="<?php echo $location_target->month->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $location_target_list->RowIndex ?>_month" name="x<?php echo $location_target_list->RowIndex ?>_month"<?php echo $location_target->month->EditAttributes() ?>>
<?php echo $location_target->month->SelectOptionListHtml("x<?php echo $location_target_list->RowIndex ?>_month") ?>
</select>
</span>
<?php } ?>
<?php if ($location_target->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $location_target_list->RowCnt ?>_location_target_month" class="location_target_month">
<span<?php echo $location_target->month->ViewAttributes() ?>>
<?php echo $location_target->month->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($location_target->product_category->Visible) { // product_category ?>
		<td data-name="product_category"<?php echo $location_target->product_category->CellAttributes() ?>>
<?php if ($location_target->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $location_target_list->RowCnt ?>_location_target_product_category" class="form-group location_target_product_category">
<select data-table="location_target" data-field="x_product_category" data-value-separator="<?php echo $location_target->product_category->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $location_target_list->RowIndex ?>_product_category" name="x<?php echo $location_target_list->RowIndex ?>_product_category"<?php echo $location_target->product_category->EditAttributes() ?>>
<?php echo $location_target->product_category->SelectOptionListHtml("x<?php echo $location_target_list->RowIndex ?>_product_category") ?>
</select>
</span>
<input type="hidden" data-table="location_target" data-field="x_product_category" name="o<?php echo $location_target_list->RowIndex ?>_product_category" id="o<?php echo $location_target_list->RowIndex ?>_product_category" value="<?php echo ew_HtmlEncode($location_target->product_category->OldValue) ?>">
<?php } ?>
<?php if ($location_target->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $location_target_list->RowCnt ?>_location_target_product_category" class="form-group location_target_product_category">
<select data-table="location_target" data-field="x_product_category" data-value-separator="<?php echo $location_target->product_category->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $location_target_list->RowIndex ?>_product_category" name="x<?php echo $location_target_list->RowIndex ?>_product_category"<?php echo $location_target->product_category->EditAttributes() ?>>
<?php echo $location_target->product_category->SelectOptionListHtml("x<?php echo $location_target_list->RowIndex ?>_product_category") ?>
</select>
</span>
<?php } ?>
<?php if ($location_target->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $location_target_list->RowCnt ?>_location_target_product_category" class="location_target_product_category">
<span<?php echo $location_target->product_category->ViewAttributes() ?>>
<?php echo $location_target->product_category->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($location_target->target_value->Visible) { // target_value ?>
		<td data-name="target_value"<?php echo $location_target->target_value->CellAttributes() ?>>
<?php if ($location_target->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $location_target_list->RowCnt ?>_location_target_target_value" class="form-group location_target_target_value">
<input type="text" data-table="location_target" data-field="x_target_value" name="x<?php echo $location_target_list->RowIndex ?>_target_value" id="x<?php echo $location_target_list->RowIndex ?>_target_value" size="30" placeholder="<?php echo ew_HtmlEncode($location_target->target_value->getPlaceHolder()) ?>" value="<?php echo $location_target->target_value->EditValue ?>"<?php echo $location_target->target_value->EditAttributes() ?>>
</span>
<input type="hidden" data-table="location_target" data-field="x_target_value" name="o<?php echo $location_target_list->RowIndex ?>_target_value" id="o<?php echo $location_target_list->RowIndex ?>_target_value" value="<?php echo ew_HtmlEncode($location_target->target_value->OldValue) ?>">
<?php } ?>
<?php if ($location_target->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $location_target_list->RowCnt ?>_location_target_target_value" class="form-group location_target_target_value">
<input type="text" data-table="location_target" data-field="x_target_value" name="x<?php echo $location_target_list->RowIndex ?>_target_value" id="x<?php echo $location_target_list->RowIndex ?>_target_value" size="30" placeholder="<?php echo ew_HtmlEncode($location_target->target_value->getPlaceHolder()) ?>" value="<?php echo $location_target->target_value->EditValue ?>"<?php echo $location_target->target_value->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($location_target->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $location_target_list->RowCnt ?>_location_target_target_value" class="location_target_target_value">
<span<?php echo $location_target->target_value->ViewAttributes() ?>>
<?php echo $location_target->target_value->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$location_target_list->ListOptions->Render("body", "right", $location_target_list->RowCnt);
?>
	</tr>
<?php if ($location_target->RowType == EW_ROWTYPE_ADD || $location_target->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
flocation_targetlist.UpdateOpts(<?php echo $location_target_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($location_target->CurrentAction <> "gridadd")
		if (!$location_target_list->Recordset->EOF) $location_target_list->Recordset->MoveNext();
}
?>
<?php
	if ($location_target->CurrentAction == "gridadd" || $location_target->CurrentAction == "gridedit") {
		$location_target_list->RowIndex = '$rowindex$';
		$location_target_list->LoadRowValues();

		// Set row properties
		$location_target->ResetAttrs();
		$location_target->RowAttrs = array_merge($location_target->RowAttrs, array('data-rowindex'=>$location_target_list->RowIndex, 'id'=>'r0_location_target', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($location_target->RowAttrs["class"], "ewTemplate");
		$location_target->RowType = EW_ROWTYPE_ADD;

		// Render row
		$location_target_list->RenderRow();

		// Render list options
		$location_target_list->RenderListOptions();
		$location_target_list->StartRowCnt = 0;
?>
	<tr<?php echo $location_target->RowAttributes() ?>>
<?php

// Render list options (body, left)
$location_target_list->ListOptions->Render("body", "left", $location_target_list->RowIndex);
?>
	<?php if ($location_target->sn->Visible) { // sn ?>
		<td data-name="sn">
<input type="hidden" data-table="location_target" data-field="x_sn" name="o<?php echo $location_target_list->RowIndex ?>_sn" id="o<?php echo $location_target_list->RowIndex ?>_sn" value="<?php echo ew_HtmlEncode($location_target->sn->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($location_target->date->Visible) { // date ?>
		<td data-name="date">
<input type="hidden" data-table="location_target" data-field="x_date" name="o<?php echo $location_target_list->RowIndex ?>_date" id="o<?php echo $location_target_list->RowIndex ?>_date" value="<?php echo ew_HtmlEncode($location_target->date->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($location_target->location->Visible) { // location ?>
		<td data-name="location">
<span id="el$rowindex$_location_target_location" class="form-group location_target_location">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $location_target_list->RowIndex ?>_location"><?php echo (strval($location_target->location->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $location_target->location->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($location_target->location->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $location_target_list->RowIndex ?>_location',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="location_target" data-field="x_location" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $location_target->location->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $location_target_list->RowIndex ?>_location" id="x<?php echo $location_target_list->RowIndex ?>_location" value="<?php echo $location_target->location->CurrentValue ?>"<?php echo $location_target->location->EditAttributes() ?>>
</span>
<input type="hidden" data-table="location_target" data-field="x_location" name="o<?php echo $location_target_list->RowIndex ?>_location" id="o<?php echo $location_target_list->RowIndex ?>_location" value="<?php echo ew_HtmlEncode($location_target->location->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($location_target->month->Visible) { // month ?>
		<td data-name="month">
<span id="el$rowindex$_location_target_month" class="form-group location_target_month">
<select data-table="location_target" data-field="x_month" data-value-separator="<?php echo $location_target->month->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $location_target_list->RowIndex ?>_month" name="x<?php echo $location_target_list->RowIndex ?>_month"<?php echo $location_target->month->EditAttributes() ?>>
<?php echo $location_target->month->SelectOptionListHtml("x<?php echo $location_target_list->RowIndex ?>_month") ?>
</select>
</span>
<input type="hidden" data-table="location_target" data-field="x_month" name="o<?php echo $location_target_list->RowIndex ?>_month" id="o<?php echo $location_target_list->RowIndex ?>_month" value="<?php echo ew_HtmlEncode($location_target->month->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($location_target->product_category->Visible) { // product_category ?>
		<td data-name="product_category">
<span id="el$rowindex$_location_target_product_category" class="form-group location_target_product_category">
<select data-table="location_target" data-field="x_product_category" data-value-separator="<?php echo $location_target->product_category->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $location_target_list->RowIndex ?>_product_category" name="x<?php echo $location_target_list->RowIndex ?>_product_category"<?php echo $location_target->product_category->EditAttributes() ?>>
<?php echo $location_target->product_category->SelectOptionListHtml("x<?php echo $location_target_list->RowIndex ?>_product_category") ?>
</select>
</span>
<input type="hidden" data-table="location_target" data-field="x_product_category" name="o<?php echo $location_target_list->RowIndex ?>_product_category" id="o<?php echo $location_target_list->RowIndex ?>_product_category" value="<?php echo ew_HtmlEncode($location_target->product_category->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($location_target->target_value->Visible) { // target_value ?>
		<td data-name="target_value">
<span id="el$rowindex$_location_target_target_value" class="form-group location_target_target_value">
<input type="text" data-table="location_target" data-field="x_target_value" name="x<?php echo $location_target_list->RowIndex ?>_target_value" id="x<?php echo $location_target_list->RowIndex ?>_target_value" size="30" placeholder="<?php echo ew_HtmlEncode($location_target->target_value->getPlaceHolder()) ?>" value="<?php echo $location_target->target_value->EditValue ?>"<?php echo $location_target->target_value->EditAttributes() ?>>
</span>
<input type="hidden" data-table="location_target" data-field="x_target_value" name="o<?php echo $location_target_list->RowIndex ?>_target_value" id="o<?php echo $location_target_list->RowIndex ?>_target_value" value="<?php echo ew_HtmlEncode($location_target->target_value->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$location_target_list->ListOptions->Render("body", "right", $location_target_list->RowCnt);
?>
<script type="text/javascript">
flocation_targetlist.UpdateOpts(<?php echo $location_target_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($location_target->CurrentAction == "gridadd") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $location_target_list->FormKeyCountName ?>" id="<?php echo $location_target_list->FormKeyCountName ?>" value="<?php echo $location_target_list->KeyCount ?>">
<?php echo $location_target_list->MultiSelectKey ?>
<?php } ?>
<?php if ($location_target->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $location_target_list->FormKeyCountName ?>" id="<?php echo $location_target_list->FormKeyCountName ?>" value="<?php echo $location_target_list->KeyCount ?>">
<?php echo $location_target_list->MultiSelectKey ?>
<?php } ?>
<?php if ($location_target->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($location_target_list->Recordset)
	$location_target_list->Recordset->Close();
?>
<?php if ($location_target->Export == "") { ?>
<div class="box-footer ewGridLowerPanel">
<?php if ($location_target->CurrentAction <> "gridadd" && $location_target->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($location_target_list->Pager)) $location_target_list->Pager = new cPrevNextPager($location_target_list->StartRec, $location_target_list->DisplayRecs, $location_target_list->TotalRecs, $location_target_list->AutoHidePager) ?>
<?php if ($location_target_list->Pager->RecordCount > 0 && $location_target_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($location_target_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $location_target_list->PageUrl() ?>start=<?php echo $location_target_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($location_target_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $location_target_list->PageUrl() ?>start=<?php echo $location_target_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $location_target_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($location_target_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $location_target_list->PageUrl() ?>start=<?php echo $location_target_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($location_target_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $location_target_list->PageUrl() ?>start=<?php echo $location_target_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $location_target_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $location_target_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $location_target_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $location_target_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($location_target_list->TotalRecs > 0 && (!$location_target_list->AutoHidePageSizeSelector || $location_target_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="location_target">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($location_target_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($location_target_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($location_target_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($location_target->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($location_target_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($location_target_list->TotalRecs == 0 && $location_target->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($location_target_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($location_target->Export == "") { ?>
<script type="text/javascript">
flocation_targetlistsrch.FilterList = <?php echo $location_target_list->GetFilterList() ?>;
flocation_targetlistsrch.Init();
flocation_targetlist.Init();
</script>
<?php } ?>
<?php
$location_target_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($location_target->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$location_target_list->Page_Terminate();
?>
