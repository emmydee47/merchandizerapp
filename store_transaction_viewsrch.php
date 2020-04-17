<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "store_transaction_viewinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$store_transaction_view_search = NULL; // Initialize page object first

class cstore_transaction_view_search extends cstore_transaction_view {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = '{B6A7A568-E606-4B9C-A82F-C80372444DA0}';

	// Table name
	var $TableName = 'store_transaction_view';

	// Page object name
	var $PageObjName = 'store_transaction_view_search';

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

		// Table object (store_transaction_view)
		if (!isset($GLOBALS["store_transaction_view"]) || get_class($GLOBALS["store_transaction_view"]) == "cstore_transaction_view") {
			$GLOBALS["store_transaction_view"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["store_transaction_view"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'store_transaction_view', TRUE);

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
		if (!$Security->CanSearch()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("store_transaction_viewlist.php"));
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
		$this->State->SetVisibility();
		$this->store_name->SetVisibility();
		$this->merchandizer->SetVisibility();
		$this->category->SetVisibility();
		$this->product->SetVisibility();
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
		global $EW_EXPORT, $store_transaction_view;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($store_transaction_view);
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
					if ($pageName == "store_transaction_viewview.php")
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
	var $FormClassName = "form-horizontal ewForm ewSearchForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsSearchError;
		global $gbSkipHeaderFooter;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		if ($this->IsPageRequest()) { // Validate request

			// Get action
			$this->CurrentAction = $objForm->GetValue("a_search");
			switch ($this->CurrentAction) {
				case "S": // Get search criteria

					// Build search string for advanced search, remove blank field
					$this->LoadSearchValues(); // Get search values
					if ($this->ValidateSearch()) {
						$sSrchStr = $this->BuildAdvancedSearch();
					} else {
						$sSrchStr = "";
						$this->setFailureMessage($gsSearchError);
					}
					if ($sSrchStr <> "") {
						$sSrchStr = $this->UrlParm($sSrchStr);
						$sSrchStr = "store_transaction_viewlist.php" . "?" . $sSrchStr;
						$this->Page_Terminate($sSrchStr); // Go to list page
					}
			}
		}

		// Restore search settings from Session
		if ($gsSearchError == "")
			$this->LoadAdvancedSearch();

		// Render row for search
		$this->RowType = EW_ROWTYPE_SEARCH;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Build advanced search
	function BuildAdvancedSearch() {
		$sSrchUrl = "";
		$this->BuildSearchUrl($sSrchUrl, $this->sn); // sn
		$this->BuildSearchUrl($sSrchUrl, $this->transaction_date); // transaction_date
		$this->BuildSearchUrl($sSrchUrl, $this->State); // State
		$this->BuildSearchUrl($sSrchUrl, $this->store_name); // store_name
		$this->BuildSearchUrl($sSrchUrl, $this->merchandizer); // merchandizer
		$this->BuildSearchUrl($sSrchUrl, $this->category); // category
		$this->BuildSearchUrl($sSrchUrl, $this->product); // product
		$this->BuildSearchUrl($sSrchUrl, $this->quantity); // quantity
		$this->BuildSearchUrl($sSrchUrl, $this->amount); // amount
		if ($sSrchUrl <> "") $sSrchUrl .= "&";
		$sSrchUrl .= "cmd=search";
		return $sSrchUrl;
	}

	// Build search URL
	function BuildSearchUrl(&$Url, &$Fld, $OprOnly=FALSE) {
		global $objForm;
		$sWrk = "";
		$FldParm = $Fld->FldParm();
		$FldVal = $objForm->GetValue("x_$FldParm");
		$FldOpr = $objForm->GetValue("z_$FldParm");
		$FldCond = $objForm->GetValue("v_$FldParm");
		$FldVal2 = $objForm->GetValue("y_$FldParm");
		$FldOpr2 = $objForm->GetValue("w_$FldParm");
		$FldVal = $FldVal;
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		$FldVal2 = $FldVal2;
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		$lFldDataType = ($Fld->FldIsVirtual) ? EW_DATATYPE_STRING : $Fld->FldDataType;
		if ($FldOpr == "BETWEEN") {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal) && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal <> "" && $FldVal2 <> "" && $IsValidValue) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			}
		} else {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal));
			if ($FldVal <> "" && $IsValidValue && ew_IsValidOpr($FldOpr, $lFldDataType)) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			} elseif ($FldOpr == "IS NULL" || $FldOpr == "IS NOT NULL" || ($FldOpr <> "" && $OprOnly && ew_IsValidOpr($FldOpr, $lFldDataType))) {
				$sWrk = "z_" . $FldParm . "=" . urlencode($FldOpr);
			}
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal2 <> "" && $IsValidValue && ew_IsValidOpr($FldOpr2, $lFldDataType)) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&w_" . $FldParm . "=" . urlencode($FldOpr2);
			} elseif ($FldOpr2 == "IS NULL" || $FldOpr2 == "IS NOT NULL" || ($FldOpr2 <> "" && $OprOnly && ew_IsValidOpr($FldOpr2, $lFldDataType))) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "w_" . $FldParm . "=" . urlencode($FldOpr2);
			}
		}
		if ($sWrk <> "") {
			if ($Url <> "") $Url .= "&";
			$Url .= $sWrk;
		}
	}

	function SearchValueIsNumeric($Fld, $Value) {
		if (ew_IsFloatFormat($Fld->FldType)) $Value = ew_StrToFloat($Value);
		return is_numeric($Value);
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// sn

		$this->sn->AdvancedSearch->SearchValue = $objForm->GetValue("x_sn");
		$this->sn->AdvancedSearch->SearchOperator = $objForm->GetValue("z_sn");

		// transaction_date
		$this->transaction_date->AdvancedSearch->SearchValue = $objForm->GetValue("x_transaction_date");
		$this->transaction_date->AdvancedSearch->SearchOperator = $objForm->GetValue("z_transaction_date");
		$this->transaction_date->AdvancedSearch->SearchCondition = $objForm->GetValue("v_transaction_date");
		$this->transaction_date->AdvancedSearch->SearchValue2 = $objForm->GetValue("y_transaction_date");
		$this->transaction_date->AdvancedSearch->SearchOperator2 = $objForm->GetValue("w_transaction_date");

		// State
		$this->State->AdvancedSearch->SearchValue = $objForm->GetValue("x_State");
		$this->State->AdvancedSearch->SearchOperator = $objForm->GetValue("z_State");

		// store_name
		$this->store_name->AdvancedSearch->SearchValue = $objForm->GetValue("x_store_name");
		$this->store_name->AdvancedSearch->SearchOperator = $objForm->GetValue("z_store_name");

		// merchandizer
		$this->merchandizer->AdvancedSearch->SearchValue = $objForm->GetValue("x_merchandizer");
		$this->merchandizer->AdvancedSearch->SearchOperator = $objForm->GetValue("z_merchandizer");

		// category
		$this->category->AdvancedSearch->SearchValue = $objForm->GetValue("x_category");
		$this->category->AdvancedSearch->SearchOperator = $objForm->GetValue("z_category");

		// product
		$this->product->AdvancedSearch->SearchValue = $objForm->GetValue("x_product");
		$this->product->AdvancedSearch->SearchOperator = $objForm->GetValue("z_product");

		// quantity
		$this->quantity->AdvancedSearch->SearchValue = $objForm->GetValue("x_quantity");
		$this->quantity->AdvancedSearch->SearchOperator = $objForm->GetValue("z_quantity");

		// amount
		$this->amount->AdvancedSearch->SearchValue = $objForm->GetValue("x_amount");
		$this->amount->AdvancedSearch->SearchOperator = $objForm->GetValue("z_amount");
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
		// State
		// store_name
		// merchandizer
		// category
		// product
		// quantity
		// amount
		// monthly_target
		// month

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// sn
		$this->sn->ViewValue = $this->sn->CurrentValue;
		$this->sn->ViewCustomAttributes = "";

		// transaction_date
		$this->transaction_date->ViewValue = $this->transaction_date->CurrentValue;
		$this->transaction_date->ViewValue = ew_FormatDateTime($this->transaction_date->ViewValue, 14);
		$this->transaction_date->ViewCustomAttributes = "";

		// State
		if (strval($this->State->CurrentValue) <> "") {
			$this->State->ViewValue = $this->State->OptionCaption($this->State->CurrentValue);
		} else {
			$this->State->ViewValue = NULL;
		}
		$this->State->ViewCustomAttributes = "";

		// store_name
		$this->store_name->ViewValue = $this->store_name->CurrentValue;
		if (strval($this->store_name->CurrentValue) <> "") {
			$sFilterWrk = "`store_name`" . ew_SearchString("=", $this->store_name->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `store_name`, `store_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `stores`";
		$sWhereWrk = "";
		$this->store_name->LookupFilters = array("dx1" => '`store_name`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->store_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->store_name->ViewValue = $this->store_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->store_name->ViewValue = $this->store_name->CurrentValue;
			}
		} else {
			$this->store_name->ViewValue = NULL;
		}
		$this->store_name->ViewCustomAttributes = "";

		// merchandizer
		$this->merchandizer->ViewValue = $this->merchandizer->CurrentValue;
		if (strval($this->merchandizer->CurrentValue) <> "") {
			$sFilterWrk = "`firstname`" . ew_SearchString("=", $this->merchandizer->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `firstname`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `user`";
		$sWhereWrk = "";
		$this->merchandizer->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->merchandizer, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->merchandizer->ViewValue = $this->merchandizer->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->merchandizer->ViewValue = $this->merchandizer->CurrentValue;
			}
		} else {
			$this->merchandizer->ViewValue = NULL;
		}
		$this->merchandizer->ViewCustomAttributes = "";

		// category
		$this->category->ViewValue = $this->category->CurrentValue;
		if (strval($this->category->CurrentValue) <> "") {
			$sFilterWrk = "`category`" . ew_SearchString("=", $this->category->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `category`, `category` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `product_categories`";
		$sWhereWrk = "";
		$this->category->LookupFilters = array("dx1" => '`category`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->category, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->category->ViewValue = $this->category->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->category->ViewValue = $this->category->CurrentValue;
			}
		} else {
			$this->category->ViewValue = NULL;
		}
		$this->category->ViewCustomAttributes = "";

		// product
		$this->product->ViewValue = $this->product->CurrentValue;
		if (strval($this->product->CurrentValue) <> "") {
			$sFilterWrk = "`product_name`" . ew_SearchString("=", $this->product->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `product_name`, `product_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `products`";
		$sWhereWrk = "";
		$this->product->LookupFilters = array("dx1" => '`product_name`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->product, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->product->ViewValue = $this->product->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->product->ViewValue = $this->product->CurrentValue;
			}
		} else {
			$this->product->ViewValue = NULL;
		}
		$this->product->ViewCustomAttributes = "";

		// quantity
		$this->quantity->ViewValue = $this->quantity->CurrentValue;
		$this->quantity->ViewCustomAttributes = "";

		// amount
		$this->amount->ViewValue = $this->amount->CurrentValue;
		$this->amount->ViewValue = ew_FormatNumber($this->amount->ViewValue, 2, -2, -2, -1);
		$this->amount->ViewCustomAttributes = "";

			// sn
			$this->sn->LinkCustomAttributes = "";
			$this->sn->HrefValue = "";
			$this->sn->TooltipValue = "";

			// transaction_date
			$this->transaction_date->LinkCustomAttributes = "";
			$this->transaction_date->HrefValue = "";
			$this->transaction_date->TooltipValue = "";

			// State
			$this->State->LinkCustomAttributes = "";
			$this->State->HrefValue = "";
			$this->State->TooltipValue = "";

			// store_name
			$this->store_name->LinkCustomAttributes = "";
			$this->store_name->HrefValue = "";
			$this->store_name->TooltipValue = "";

			// merchandizer
			$this->merchandizer->LinkCustomAttributes = "";
			$this->merchandizer->HrefValue = "";
			$this->merchandizer->TooltipValue = "";

			// category
			$this->category->LinkCustomAttributes = "";
			$this->category->HrefValue = "";
			$this->category->TooltipValue = "";

			// product
			$this->product->LinkCustomAttributes = "";
			$this->product->HrefValue = "";
			$this->product->TooltipValue = "";

			// quantity
			$this->quantity->LinkCustomAttributes = "";
			$this->quantity->HrefValue = "";
			$this->quantity->TooltipValue = "";

			// amount
			$this->amount->LinkCustomAttributes = "";
			$this->amount->HrefValue = "";
			$this->amount->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// sn
			$this->sn->EditAttrs["class"] = "form-control";
			$this->sn->EditCustomAttributes = "";
			$this->sn->EditValue = ew_HtmlEncode($this->sn->AdvancedSearch->SearchValue);
			$this->sn->PlaceHolder = ew_RemoveHtml($this->sn->FldCaption());

			// transaction_date
			$this->transaction_date->EditAttrs["class"] = "form-control";
			$this->transaction_date->EditCustomAttributes = "";
			$this->transaction_date->EditValue = ew_HtmlEncode($this->transaction_date->AdvancedSearch->SearchValue);
			$this->transaction_date->PlaceHolder = ew_RemoveHtml($this->transaction_date->FldCaption());
			$this->transaction_date->EditAttrs["class"] = "form-control";
			$this->transaction_date->EditCustomAttributes = "";
			$this->transaction_date->EditValue2 = ew_HtmlEncode($this->transaction_date->AdvancedSearch->SearchValue2);
			$this->transaction_date->PlaceHolder = ew_RemoveHtml($this->transaction_date->FldCaption());

			// State
			$this->State->EditAttrs["class"] = "form-control";
			$this->State->EditCustomAttributes = "";
			$this->State->EditValue = $this->State->Options(TRUE);

			// store_name
			$this->store_name->EditAttrs["class"] = "form-control";
			$this->store_name->EditCustomAttributes = "";
			$this->store_name->EditValue = ew_HtmlEncode($this->store_name->AdvancedSearch->SearchValue);
			if (strval($this->store_name->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`store_name`" . ew_SearchString("=", $this->store_name->AdvancedSearch->SearchValue, EW_DATATYPE_STRING, "");
			$sSqlWrk = "SELECT `store_name`, `store_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `stores`";
			$sWhereWrk = "";
			$this->store_name->LookupFilters = array("dx1" => '`store_name`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->store_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->store_name->EditValue = $this->store_name->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->store_name->EditValue = ew_HtmlEncode($this->store_name->AdvancedSearch->SearchValue);
				}
			} else {
				$this->store_name->EditValue = NULL;
			}
			$this->store_name->PlaceHolder = ew_RemoveHtml($this->store_name->FldCaption());

			// merchandizer
			$this->merchandizer->EditAttrs["class"] = "form-control";
			$this->merchandizer->EditCustomAttributes = "";
			$this->merchandizer->EditValue = ew_HtmlEncode($this->merchandizer->AdvancedSearch->SearchValue);
			if (strval($this->merchandizer->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`firstname`" . ew_SearchString("=", $this->merchandizer->AdvancedSearch->SearchValue, EW_DATATYPE_STRING, "");
			$sSqlWrk = "SELECT `firstname`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `user`";
			$sWhereWrk = "";
			$this->merchandizer->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->merchandizer, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->merchandizer->EditValue = $this->merchandizer->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->merchandizer->EditValue = ew_HtmlEncode($this->merchandizer->AdvancedSearch->SearchValue);
				}
			} else {
				$this->merchandizer->EditValue = NULL;
			}
			$this->merchandizer->PlaceHolder = ew_RemoveHtml($this->merchandizer->FldCaption());

			// category
			$this->category->EditAttrs["class"] = "form-control";
			$this->category->EditCustomAttributes = "";
			$this->category->EditValue = ew_HtmlEncode($this->category->AdvancedSearch->SearchValue);
			if (strval($this->category->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`category`" . ew_SearchString("=", $this->category->AdvancedSearch->SearchValue, EW_DATATYPE_STRING, "");
			$sSqlWrk = "SELECT `category`, `category` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `product_categories`";
			$sWhereWrk = "";
			$this->category->LookupFilters = array("dx1" => '`category`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->category->EditValue = $this->category->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->category->EditValue = ew_HtmlEncode($this->category->AdvancedSearch->SearchValue);
				}
			} else {
				$this->category->EditValue = NULL;
			}
			$this->category->PlaceHolder = ew_RemoveHtml($this->category->FldCaption());

			// product
			$this->product->EditAttrs["class"] = "form-control";
			$this->product->EditCustomAttributes = "";
			$this->product->EditValue = ew_HtmlEncode($this->product->AdvancedSearch->SearchValue);
			if (strval($this->product->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`product_name`" . ew_SearchString("=", $this->product->AdvancedSearch->SearchValue, EW_DATATYPE_STRING, "");
			$sSqlWrk = "SELECT `product_name`, `product_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `products`";
			$sWhereWrk = "";
			$this->product->LookupFilters = array("dx1" => '`product_name`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->product, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->product->EditValue = $this->product->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->product->EditValue = ew_HtmlEncode($this->product->AdvancedSearch->SearchValue);
				}
			} else {
				$this->product->EditValue = NULL;
			}
			$this->product->PlaceHolder = ew_RemoveHtml($this->product->FldCaption());

			// quantity
			$this->quantity->EditAttrs["class"] = "form-control";
			$this->quantity->EditCustomAttributes = "";
			$this->quantity->EditValue = ew_HtmlEncode($this->quantity->AdvancedSearch->SearchValue);
			$this->quantity->PlaceHolder = ew_RemoveHtml($this->quantity->FldCaption());

			// amount
			$this->amount->EditAttrs["class"] = "form-control";
			$this->amount->EditCustomAttributes = "";
			$this->amount->EditValue = ew_HtmlEncode($this->amount->AdvancedSearch->SearchValue);
			$this->amount->PlaceHolder = ew_RemoveHtml($this->amount->FldCaption());
		}
		if ($this->RowType == EW_ROWTYPE_ADD || $this->RowType == EW_ROWTYPE_EDIT || $this->RowType == EW_ROWTYPE_SEARCH) // Add/Edit/Search row
			$this->SetupFieldTitles();

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
		if (!ew_CheckInteger($this->sn->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->sn->FldErrMsg());
		}
		if (!ew_CheckShortEuroDate($this->transaction_date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->transaction_date->FldErrMsg());
		}
		if (!ew_CheckShortEuroDate($this->transaction_date->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->transaction_date->FldErrMsg());
		}
		if (!ew_CheckInteger($this->quantity->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->quantity->FldErrMsg());
		}
		if (!ew_CheckNumber($this->amount->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->amount->FldErrMsg());
		}

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
		$this->transaction_date->AdvancedSearch->Load();
		$this->State->AdvancedSearch->Load();
		$this->store_name->AdvancedSearch->Load();
		$this->merchandizer->AdvancedSearch->Load();
		$this->category->AdvancedSearch->Load();
		$this->product->AdvancedSearch->Load();
		$this->quantity->AdvancedSearch->Load();
		$this->amount->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("store_transaction_viewlist.php"), "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_store_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `store_name` AS `LinkFld`, `store_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `stores`";
			$sWhereWrk = "{filter}";
			$this->store_name->LookupFilters = array("dx1" => '`store_name`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`store_name` IN ({filter_value})', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->store_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_merchandizer":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `firstname` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `user`";
			$sWhereWrk = "{filter}";
			$this->merchandizer->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
			if (!$GLOBALS["store_transaction_view"]->UserIDAllow("search")) $sWhereWrk = $GLOBALS["user"]->AddUserIDFilter($sWhereWrk);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`firstname` IN ({filter_value})', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->merchandizer, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_category":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `category` AS `LinkFld`, `category` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `product_categories`";
			$sWhereWrk = "{filter}";
			$this->category->LookupFilters = array("dx1" => '`category`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`category` IN ({filter_value})', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_product":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `product_name` AS `LinkFld`, `product_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `products`";
			$sWhereWrk = "{filter}";
			$this->product->LookupFilters = array("dx1" => '`product_name`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`product_name` IN ({filter_value})', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->product, $sWhereWrk); // Call Lookup Selecting
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
		case "x_store_name":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `store_name`, `store_name` AS `DispFld` FROM `stores`";
			$sWhereWrk = "`store_name` LIKE '{query_value}%'";
			$this->store_name->LookupFilters = array("dx1" => '`store_name`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->store_name, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_merchandizer":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `firstname`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld` FROM `user`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(`firstname`,'" . ew_ValueSeparator(1, $this->merchandizer) . "',`lastname`) LIKE '{query_value}%'";
			$this->merchandizer->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
			if (!$GLOBALS["store_transaction_view"]->UserIDAllow("search")) $sWhereWrk = $GLOBALS["user"]->AddUserIDFilter($sWhereWrk);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->merchandizer, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_category":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `category`, `category` AS `DispFld` FROM `product_categories`";
			$sWhereWrk = "`category` LIKE '{query_value}%'";
			$this->category->LookupFilters = array("dx1" => '`category`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_product":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `product_name`, `product_name` AS `DispFld` FROM `products`";
			$sWhereWrk = "`product_name` LIKE '{query_value}%'";
			$this->product->LookupFilters = array("dx1" => '`product_name`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->product, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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
if (!isset($store_transaction_view_search)) $store_transaction_view_search = new cstore_transaction_view_search();

// Page init
$store_transaction_view_search->Page_Init();

// Page main
$store_transaction_view_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$store_transaction_view_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($store_transaction_view_search->IsModal) { ?>
var CurrentAdvancedSearchForm = fstore_transaction_viewsearch = new ew_Form("fstore_transaction_viewsearch", "search");
<?php } else { ?>
var CurrentForm = fstore_transaction_viewsearch = new ew_Form("fstore_transaction_viewsearch", "search");
<?php } ?>

// Form_CustomValidate event
fstore_transaction_viewsearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fstore_transaction_viewsearch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fstore_transaction_viewsearch.Lists["x_State"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fstore_transaction_viewsearch.Lists["x_State"].Options = <?php echo json_encode($store_transaction_view_search->State->Options()) ?>;
fstore_transaction_viewsearch.Lists["x_store_name"] = {"LinkField":"x_store_name","Ajax":true,"AutoFill":false,"DisplayFields":["x_store_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"stores"};
fstore_transaction_viewsearch.Lists["x_store_name"].Data = "<?php echo $store_transaction_view_search->store_name->LookupFilterQuery(FALSE, "search") ?>";
fstore_transaction_viewsearch.AutoSuggests["x_store_name"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $store_transaction_view_search->store_name->LookupFilterQuery(TRUE, "search"))) ?>;
fstore_transaction_viewsearch.Lists["x_merchandizer"] = {"LinkField":"x_firstname","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"user"};
fstore_transaction_viewsearch.Lists["x_merchandizer"].Data = "<?php echo $store_transaction_view_search->merchandizer->LookupFilterQuery(FALSE, "search") ?>";
fstore_transaction_viewsearch.AutoSuggests["x_merchandizer"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $store_transaction_view_search->merchandizer->LookupFilterQuery(TRUE, "search"))) ?>;
fstore_transaction_viewsearch.Lists["x_category"] = {"LinkField":"x_category","Ajax":true,"AutoFill":false,"DisplayFields":["x_category","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"product_categories"};
fstore_transaction_viewsearch.Lists["x_category"].Data = "<?php echo $store_transaction_view_search->category->LookupFilterQuery(FALSE, "search") ?>";
fstore_transaction_viewsearch.AutoSuggests["x_category"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $store_transaction_view_search->category->LookupFilterQuery(TRUE, "search"))) ?>;
fstore_transaction_viewsearch.Lists["x_product"] = {"LinkField":"x_product_name","Ajax":true,"AutoFill":false,"DisplayFields":["x_product_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"products"};
fstore_transaction_viewsearch.Lists["x_product"].Data = "<?php echo $store_transaction_view_search->product->LookupFilterQuery(FALSE, "search") ?>";
fstore_transaction_viewsearch.AutoSuggests["x_product"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $store_transaction_view_search->product->LookupFilterQuery(TRUE, "search"))) ?>;

// Form object for search
// Validate function for search

fstore_transaction_viewsearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_sn");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($store_transaction_view->sn->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_transaction_date");
	if (elm && !ew_CheckShortEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($store_transaction_view->transaction_date->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_quantity");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($store_transaction_view->quantity->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_amount");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($store_transaction_view->amount->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $store_transaction_view_search->ShowPageHeader(); ?>
<?php
$store_transaction_view_search->ShowMessage();
?>
<form name="fstore_transaction_viewsearch" id="fstore_transaction_viewsearch" class="<?php echo $store_transaction_view_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($store_transaction_view_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $store_transaction_view_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="store_transaction_view">
<input type="hidden" name="a_search" id="a_search" value="S">
<input type="hidden" name="modal" value="<?php echo intval($store_transaction_view_search->IsModal) ?>">
<div class="ewSearchDiv"><!-- page* -->
<?php if ($store_transaction_view->sn->Visible) { // sn ?>
	<div id="r_sn" class="form-group">
		<label for="x_sn" class="<?php echo $store_transaction_view_search->LeftColumnClass ?>"><span id="elh_store_transaction_view_sn"><?php echo $store_transaction_view->sn->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_sn" id="z_sn" value="="></p>
		</label>
		<div class="<?php echo $store_transaction_view_search->RightColumnClass ?>"><div<?php echo $store_transaction_view->sn->CellAttributes() ?>>
			<span id="el_store_transaction_view_sn">
<input type="text" data-table="store_transaction_view" data-field="x_sn" name="x_sn" id="x_sn" placeholder="<?php echo ew_HtmlEncode($store_transaction_view->sn->getPlaceHolder()) ?>" value="<?php echo $store_transaction_view->sn->EditValue ?>"<?php echo $store_transaction_view->sn->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_transaction_view->transaction_date->Visible) { // transaction_date ?>
	<div id="r_transaction_date" class="form-group">
		<label for="x_transaction_date" class="<?php echo $store_transaction_view_search->LeftColumnClass ?>"><span id="elh_store_transaction_view_transaction_date"><?php echo $store_transaction_view->transaction_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase(">=") ?><input type="hidden" name="z_transaction_date" id="z_transaction_date" value=">="></p>
		</label>
		<div class="<?php echo $store_transaction_view_search->RightColumnClass ?>"><div<?php echo $store_transaction_view->transaction_date->CellAttributes() ?>>
			<span id="el_store_transaction_view_transaction_date">
<input type="text" data-table="store_transaction_view" data-field="x_transaction_date" name="x_transaction_date" id="x_transaction_date" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($store_transaction_view->transaction_date->getPlaceHolder()) ?>" value="<?php echo $store_transaction_view->transaction_date->EditValue ?>"<?php echo $store_transaction_view->transaction_date->EditAttributes() ?>>
<?php if (!$store_transaction_view->transaction_date->ReadOnly && !$store_transaction_view->transaction_date->Disabled && !isset($store_transaction_view->transaction_date->EditAttrs["readonly"]) && !isset($store_transaction_view->transaction_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fstore_transaction_viewsearch", "x_transaction_date", {"ignoreReadonly":true,"useCurrent":false,"format":14});
</script>
<?php } ?>
</span>
			<span class="ewSearchCond btw0_transaction_date"><label class="radio-inline ewRadio" style="white-space: nowrap;"><input type="radio" name="v_transaction_date" value="AND"<?php if ($store_transaction_view->transaction_date->AdvancedSearch->SearchCondition <> "OR") echo " checked" ?>><?php echo $Language->Phrase("AND") ?></label><label class="radio-inline ewRadio" style="white-space: nowrap;"><input type="radio" name="v_transaction_date" value="OR"<?php if ($store_transaction_view->transaction_date->AdvancedSearch->SearchCondition == "OR") echo " checked" ?>><?php echo $Language->Phrase("OR") ?></label>&nbsp;</span>
			<p class="form-control-static ewSearchOperator btw0_transaction_date"><?php echo $Language->Phrase("<=") ?><input type="hidden" name="w_transaction_date" id="w_transaction_date" value="<="></p>
			<span id="e2_store_transaction_view_transaction_date">
<input type="text" data-table="store_transaction_view" data-field="x_transaction_date" name="y_transaction_date" id="y_transaction_date" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($store_transaction_view->transaction_date->getPlaceHolder()) ?>" value="<?php echo $store_transaction_view->transaction_date->EditValue2 ?>"<?php echo $store_transaction_view->transaction_date->EditAttributes() ?>>
<?php if (!$store_transaction_view->transaction_date->ReadOnly && !$store_transaction_view->transaction_date->Disabled && !isset($store_transaction_view->transaction_date->EditAttrs["readonly"]) && !isset($store_transaction_view->transaction_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fstore_transaction_viewsearch", "y_transaction_date", {"ignoreReadonly":true,"useCurrent":false,"format":14});
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_transaction_view->State->Visible) { // State ?>
	<div id="r_State" class="form-group">
		<label for="x_State" class="<?php echo $store_transaction_view_search->LeftColumnClass ?>"><span id="elh_store_transaction_view_State"><?php echo $store_transaction_view->State->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_State" id="z_State" value="LIKE"></p>
		</label>
		<div class="<?php echo $store_transaction_view_search->RightColumnClass ?>"><div<?php echo $store_transaction_view->State->CellAttributes() ?>>
			<span id="el_store_transaction_view_State">
<select data-table="store_transaction_view" data-field="x_State" data-value-separator="<?php echo $store_transaction_view->State->DisplayValueSeparatorAttribute() ?>" id="x_State" name="x_State"<?php echo $store_transaction_view->State->EditAttributes() ?>>
<?php echo $store_transaction_view->State->SelectOptionListHtml("x_State") ?>
</select>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_transaction_view->store_name->Visible) { // store_name ?>
	<div id="r_store_name" class="form-group">
		<label class="<?php echo $store_transaction_view_search->LeftColumnClass ?>"><span id="elh_store_transaction_view_store_name"><?php echo $store_transaction_view->store_name->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_store_name" id="z_store_name" value="LIKE"></p>
		</label>
		<div class="<?php echo $store_transaction_view_search->RightColumnClass ?>"><div<?php echo $store_transaction_view->store_name->CellAttributes() ?>>
			<span id="el_store_transaction_view_store_name">
<?php
$wrkonchange = trim(" " . @$store_transaction_view->store_name->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$store_transaction_view->store_name->EditAttrs["onchange"] = "";
?>
<span id="as_x_store_name" style="white-space: nowrap; z-index: 8960">
	<input type="text" name="sv_x_store_name" id="sv_x_store_name" value="<?php echo $store_transaction_view->store_name->EditValue ?>" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($store_transaction_view->store_name->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($store_transaction_view->store_name->getPlaceHolder()) ?>"<?php echo $store_transaction_view->store_name->EditAttributes() ?>>
</span>
<input type="hidden" data-table="store_transaction_view" data-field="x_store_name" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $store_transaction_view->store_name->DisplayValueSeparatorAttribute() ?>" name="x_store_name" id="x_store_name" value="<?php echo ew_HtmlEncode($store_transaction_view->store_name->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fstore_transaction_viewsearch.CreateAutoSuggest({"id":"x_store_name","forceSelect":false});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($store_transaction_view->store_name->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_store_name',m:0,n:10,srch:true});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_transaction_view->merchandizer->Visible) { // merchandizer ?>
	<div id="r_merchandizer" class="form-group">
		<label class="<?php echo $store_transaction_view_search->LeftColumnClass ?>"><span id="elh_store_transaction_view_merchandizer"><?php echo $store_transaction_view->merchandizer->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_merchandizer" id="z_merchandizer" value="LIKE"></p>
		</label>
		<div class="<?php echo $store_transaction_view_search->RightColumnClass ?>"><div<?php echo $store_transaction_view->merchandizer->CellAttributes() ?>>
			<span id="el_store_transaction_view_merchandizer">
<?php
$wrkonchange = trim(" " . @$store_transaction_view->merchandizer->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$store_transaction_view->merchandizer->EditAttrs["onchange"] = "";
?>
<span id="as_x_merchandizer" style="white-space: nowrap; z-index: 8950">
	<input type="text" name="sv_x_merchandizer" id="sv_x_merchandizer" value="<?php echo $store_transaction_view->merchandizer->EditValue ?>" size="30" maxlength="201" placeholder="<?php echo ew_HtmlEncode($store_transaction_view->merchandizer->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($store_transaction_view->merchandizer->getPlaceHolder()) ?>"<?php echo $store_transaction_view->merchandizer->EditAttributes() ?>>
</span>
<input type="hidden" data-table="store_transaction_view" data-field="x_merchandizer" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $store_transaction_view->merchandizer->DisplayValueSeparatorAttribute() ?>" name="x_merchandizer" id="x_merchandizer" value="<?php echo ew_HtmlEncode($store_transaction_view->merchandizer->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fstore_transaction_viewsearch.CreateAutoSuggest({"id":"x_merchandizer","forceSelect":false});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($store_transaction_view->merchandizer->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_merchandizer',m:0,n:10,srch:true});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_transaction_view->category->Visible) { // category ?>
	<div id="r_category" class="form-group">
		<label class="<?php echo $store_transaction_view_search->LeftColumnClass ?>"><span id="elh_store_transaction_view_category"><?php echo $store_transaction_view->category->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_category" id="z_category" value="LIKE"></p>
		</label>
		<div class="<?php echo $store_transaction_view_search->RightColumnClass ?>"><div<?php echo $store_transaction_view->category->CellAttributes() ?>>
			<span id="el_store_transaction_view_category">
<?php
$wrkonchange = trim(" " . @$store_transaction_view->category->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$store_transaction_view->category->EditAttrs["onchange"] = "";
?>
<span id="as_x_category" style="white-space: nowrap; z-index: 8940">
	<input type="text" name="sv_x_category" id="sv_x_category" value="<?php echo $store_transaction_view->category->EditValue ?>" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($store_transaction_view->category->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($store_transaction_view->category->getPlaceHolder()) ?>"<?php echo $store_transaction_view->category->EditAttributes() ?>>
</span>
<input type="hidden" data-table="store_transaction_view" data-field="x_category" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $store_transaction_view->category->DisplayValueSeparatorAttribute() ?>" name="x_category" id="x_category" value="<?php echo ew_HtmlEncode($store_transaction_view->category->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fstore_transaction_viewsearch.CreateAutoSuggest({"id":"x_category","forceSelect":false});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($store_transaction_view->category->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_category',m:0,n:10,srch:true});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_transaction_view->product->Visible) { // product ?>
	<div id="r_product" class="form-group">
		<label class="<?php echo $store_transaction_view_search->LeftColumnClass ?>"><span id="elh_store_transaction_view_product"><?php echo $store_transaction_view->product->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_product" id="z_product" value="LIKE"></p>
		</label>
		<div class="<?php echo $store_transaction_view_search->RightColumnClass ?>"><div<?php echo $store_transaction_view->product->CellAttributes() ?>>
			<span id="el_store_transaction_view_product">
<?php
$wrkonchange = trim(" " . @$store_transaction_view->product->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$store_transaction_view->product->EditAttrs["onchange"] = "";
?>
<span id="as_x_product" style="white-space: nowrap; z-index: 8930">
	<input type="text" name="sv_x_product" id="sv_x_product" value="<?php echo $store_transaction_view->product->EditValue ?>" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($store_transaction_view->product->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($store_transaction_view->product->getPlaceHolder()) ?>"<?php echo $store_transaction_view->product->EditAttributes() ?>>
</span>
<input type="hidden" data-table="store_transaction_view" data-field="x_product" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $store_transaction_view->product->DisplayValueSeparatorAttribute() ?>" name="x_product" id="x_product" value="<?php echo ew_HtmlEncode($store_transaction_view->product->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fstore_transaction_viewsearch.CreateAutoSuggest({"id":"x_product","forceSelect":false});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($store_transaction_view->product->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_product',m:0,n:10,srch:true});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_transaction_view->quantity->Visible) { // quantity ?>
	<div id="r_quantity" class="form-group">
		<label for="x_quantity" class="<?php echo $store_transaction_view_search->LeftColumnClass ?>"><span id="elh_store_transaction_view_quantity"><?php echo $store_transaction_view->quantity->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_quantity" id="z_quantity" value="="></p>
		</label>
		<div class="<?php echo $store_transaction_view_search->RightColumnClass ?>"><div<?php echo $store_transaction_view->quantity->CellAttributes() ?>>
			<span id="el_store_transaction_view_quantity">
<input type="text" data-table="store_transaction_view" data-field="x_quantity" name="x_quantity" id="x_quantity" size="30" placeholder="<?php echo ew_HtmlEncode($store_transaction_view->quantity->getPlaceHolder()) ?>" value="<?php echo $store_transaction_view->quantity->EditValue ?>"<?php echo $store_transaction_view->quantity->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($store_transaction_view->amount->Visible) { // amount ?>
	<div id="r_amount" class="form-group">
		<label for="x_amount" class="<?php echo $store_transaction_view_search->LeftColumnClass ?>"><span id="elh_store_transaction_view_amount"><?php echo $store_transaction_view->amount->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_amount" id="z_amount" value="="></p>
		</label>
		<div class="<?php echo $store_transaction_view_search->RightColumnClass ?>"><div<?php echo $store_transaction_view->amount->CellAttributes() ?>>
			<span id="el_store_transaction_view_amount">
<input type="text" data-table="store_transaction_view" data-field="x_amount" name="x_amount" id="x_amount" size="30" placeholder="<?php echo ew_HtmlEncode($store_transaction_view->amount->getPlaceHolder()) ?>" value="<?php echo $store_transaction_view->amount->EditValue ?>"<?php echo $store_transaction_view->amount->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$store_transaction_view_search->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $store_transaction_view_search->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fstore_transaction_viewsearch.Init();
</script>
<?php
$store_transaction_view_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$store_transaction_view_search->Page_Terminate();
?>
