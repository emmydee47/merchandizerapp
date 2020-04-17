<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "transactionsinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$transactions_search = NULL; // Initialize page object first

class ctransactions_search extends ctransactions {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = '{B6A7A568-E606-4B9C-A82F-C80372444DA0}';

	// Table name
	var $TableName = 'transactions';

	// Page object name
	var $PageObjName = 'transactions_search';

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

		// Table object (transactions)
		if (!isset($GLOBALS["transactions"]) || get_class($GLOBALS["transactions"]) == "ctransactions") {
			$GLOBALS["transactions"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["transactions"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'transactions', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("transactionslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
			if (strval($Security->CurrentUserID()) == "") {
				$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
				$this->Page_Terminate(ew_GetUrl("transactionslist.php"));
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
		$this->receipt_number->SetVisibility();
		$this->transaction_date->SetVisibility();
		$this->store->SetVisibility();
		$this->user->SetVisibility();
		$this->category->SetVisibility();
		$this->product->SetVisibility();
		$this->amount->SetVisibility();
		$this->quantity->SetVisibility();
		$this->status->SetVisibility();

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
		global $EW_EXPORT, $transactions;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($transactions);
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
					if ($pageName == "transactionsview.php")
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
						$sSrchStr = "transactionslist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->receipt_number); // receipt_number
		$this->BuildSearchUrl($sSrchUrl, $this->transaction_date); // transaction_date
		$this->BuildSearchUrl($sSrchUrl, $this->store); // store
		$this->BuildSearchUrl($sSrchUrl, $this->user); // user
		$this->BuildSearchUrl($sSrchUrl, $this->category); // category
		$this->BuildSearchUrl($sSrchUrl, $this->product); // product
		$this->BuildSearchUrl($sSrchUrl, $this->amount); // amount
		$this->BuildSearchUrl($sSrchUrl, $this->quantity); // quantity
		$this->BuildSearchUrl($sSrchUrl, $this->status); // status
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

		// receipt_number
		$this->receipt_number->AdvancedSearch->SearchValue = $objForm->GetValue("x_receipt_number");
		$this->receipt_number->AdvancedSearch->SearchOperator = $objForm->GetValue("z_receipt_number");

		// transaction_date
		$this->transaction_date->AdvancedSearch->SearchValue = $objForm->GetValue("x_transaction_date");
		$this->transaction_date->AdvancedSearch->SearchOperator = $objForm->GetValue("z_transaction_date");
		$this->transaction_date->AdvancedSearch->SearchCondition = $objForm->GetValue("v_transaction_date");
		$this->transaction_date->AdvancedSearch->SearchValue2 = $objForm->GetValue("y_transaction_date");
		$this->transaction_date->AdvancedSearch->SearchOperator2 = $objForm->GetValue("w_transaction_date");

		// store
		$this->store->AdvancedSearch->SearchValue = $objForm->GetValue("x_store");
		$this->store->AdvancedSearch->SearchOperator = $objForm->GetValue("z_store");

		// user
		$this->user->AdvancedSearch->SearchValue = $objForm->GetValue("x_user");
		$this->user->AdvancedSearch->SearchOperator = $objForm->GetValue("z_user");

		// category
		$this->category->AdvancedSearch->SearchValue = $objForm->GetValue("x_category");
		$this->category->AdvancedSearch->SearchOperator = $objForm->GetValue("z_category");

		// product
		$this->product->AdvancedSearch->SearchValue = $objForm->GetValue("x_product");
		$this->product->AdvancedSearch->SearchOperator = $objForm->GetValue("z_product");

		// amount
		$this->amount->AdvancedSearch->SearchValue = $objForm->GetValue("x_amount");
		$this->amount->AdvancedSearch->SearchOperator = $objForm->GetValue("z_amount");

		// quantity
		$this->quantity->AdvancedSearch->SearchValue = $objForm->GetValue("x_quantity");
		$this->quantity->AdvancedSearch->SearchOperator = $objForm->GetValue("z_quantity");

		// status
		$this->status->AdvancedSearch->SearchValue = $objForm->GetValue("x_status");
		$this->status->AdvancedSearch->SearchOperator = $objForm->GetValue("z_status");
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
		// receipt_number
		// transaction_date
		// store
		// user
		// category
		// product
		// amount
		// quantity
		// status
		// customer
		// customer_address

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// sn
		$this->sn->ViewValue = $this->sn->CurrentValue;
		$this->sn->ViewCustomAttributes = "";

		// receipt_number
		$this->receipt_number->ViewValue = $this->receipt_number->CurrentValue;
		$this->receipt_number->ViewCustomAttributes = "";

		// transaction_date
		$this->transaction_date->ViewValue = $this->transaction_date->CurrentValue;
		$this->transaction_date->ViewValue = ew_FormatDateTime($this->transaction_date->ViewValue, 9);
		$this->transaction_date->ViewCustomAttributes = "";

		// store
		if ($this->store->VirtualValue <> "") {
			$this->store->ViewValue = $this->store->VirtualValue;
		} else {
		if (strval($this->store->CurrentValue) <> "") {
			$sFilterWrk = "`store_name`" . ew_SearchString("=", $this->store->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `store_name`, `store_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `stores`";
		$sWhereWrk = "";
		$this->store->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->store, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->store->ViewValue = $this->store->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->store->ViewValue = $this->store->CurrentValue;
			}
		} else {
			$this->store->ViewValue = NULL;
		}
		}
		$this->store->ViewCustomAttributes = "";

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

		// category
		if (strval($this->category->CurrentValue) <> "") {
			$sFilterWrk = "`sn`" . ew_SearchString("=", $this->category->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `sn`, `category` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `product_categories`";
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
		if ($this->product->VirtualValue <> "") {
			$this->product->ViewValue = $this->product->VirtualValue;
		} else {
		if (strval($this->product->CurrentValue) <> "") {
			$sFilterWrk = "`sn`" . ew_SearchString("=", $this->product->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `sn`, `product_name` AS `DispFld`, `product_id` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `products`";
		$sWhereWrk = "";
		$this->product->LookupFilters = array("dx1" => '`product_name`', "dx2" => '`product_id`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->product, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->product->ViewValue = $this->product->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->product->ViewValue = $this->product->CurrentValue;
			}
		} else {
			$this->product->ViewValue = NULL;
		}
		}
		$this->product->ViewCustomAttributes = "";

		// amount
		$this->amount->ViewValue = $this->amount->CurrentValue;
		$this->amount->ViewValue = ew_FormatNumber($this->amount->ViewValue, 2, -2, -2, -1);
		$this->amount->ViewCustomAttributes = "";

		// quantity
		$this->quantity->ViewValue = $this->quantity->CurrentValue;
		$this->quantity->ViewCustomAttributes = "";

		// status
		if (strval($this->status->CurrentValue) <> "") {
			$this->status->ViewValue = $this->status->OptionCaption($this->status->CurrentValue);
		} else {
			$this->status->ViewValue = NULL;
		}
		$this->status->ViewCustomAttributes = "";

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

			// store
			$this->store->LinkCustomAttributes = "";
			$this->store->HrefValue = "";
			$this->store->TooltipValue = "";
			if ($this->Export == "")
				$this->store->ViewValue = $this->HighlightValue($this->store);

			// user
			$this->user->LinkCustomAttributes = "";
			$this->user->HrefValue = "";
			$this->user->TooltipValue = "";

			// category
			$this->category->LinkCustomAttributes = "";
			$this->category->HrefValue = "";
			$this->category->TooltipValue = "";

			// product
			$this->product->LinkCustomAttributes = "";
			$this->product->HrefValue = "";
			$this->product->TooltipValue = "";
			if ($this->Export == "")
				$this->product->ViewValue = $this->HighlightValue($this->product);

			// amount
			$this->amount->LinkCustomAttributes = "";
			$this->amount->HrefValue = "";
			$this->amount->TooltipValue = "";

			// quantity
			$this->quantity->LinkCustomAttributes = "";
			$this->quantity->HrefValue = "";
			$this->quantity->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// sn
			$this->sn->EditAttrs["class"] = "form-control";
			$this->sn->EditCustomAttributes = "";
			$this->sn->EditValue = ew_HtmlEncode($this->sn->AdvancedSearch->SearchValue);
			$this->sn->PlaceHolder = ew_RemoveHtml($this->sn->FldCaption());

			// receipt_number
			$this->receipt_number->EditAttrs["class"] = "form-control";
			$this->receipt_number->EditCustomAttributes = "";
			$this->receipt_number->EditValue = ew_HtmlEncode($this->receipt_number->AdvancedSearch->SearchValue);
			$this->receipt_number->PlaceHolder = ew_RemoveHtml($this->receipt_number->FldCaption());

			// transaction_date
			$this->transaction_date->EditAttrs["class"] = "form-control";
			$this->transaction_date->EditCustomAttributes = "";
			$this->transaction_date->EditValue = ew_HtmlEncode($this->transaction_date->AdvancedSearch->SearchValue);
			$this->transaction_date->PlaceHolder = ew_RemoveHtml($this->transaction_date->FldCaption());
			$this->transaction_date->EditAttrs["class"] = "form-control";
			$this->transaction_date->EditCustomAttributes = "";
			$this->transaction_date->EditValue2 = ew_HtmlEncode($this->transaction_date->AdvancedSearch->SearchValue2);
			$this->transaction_date->PlaceHolder = ew_RemoveHtml($this->transaction_date->FldCaption());

			// store
			$this->store->EditAttrs["class"] = "form-control";
			$this->store->EditCustomAttributes = "";
			if (trim(strval($this->store->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`store_name`" . ew_SearchString("=", $this->store->AdvancedSearch->SearchValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `store_name`, `store_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `stores`";
			$sWhereWrk = "";
			$this->store->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->store, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->store->EditValue = $arwrk;

			// user
			$this->user->EditAttrs["class"] = "form-control";
			$this->user->EditCustomAttributes = "";
			if (!$Security->IsAdmin() && $Security->IsLoggedIn() && !$this->UserIDAllow("search")) { // Non system admin
				$this->user->AdvancedSearch->SearchValue = CurrentUserID();
			if (strval($this->user->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`sn`" . ew_SearchString("=", $this->user->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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
					$this->user->EditValue = $this->user->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->user->EditValue = $this->user->AdvancedSearch->SearchValue;
				}
			} else {
				$this->user->EditValue = NULL;
			}
			$this->user->ViewCustomAttributes = "";
			} else {
			if (trim(strval($this->user->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`sn`" . ew_SearchString("=", $this->user->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `sn`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `user`";
			$sWhereWrk = "";
			$this->user->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			if (!$GLOBALS["transactions"]->UserIDAllow("search")) $sWhereWrk = $GLOBALS["user"]->AddUserIDFilter($sWhereWrk);
			$this->Lookup_Selecting($this->user, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->user->EditValue = $arwrk;
			}

			// category
			$this->category->EditCustomAttributes = "";
			if (trim(strval($this->category->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`sn`" . ew_SearchString("=", $this->category->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `sn`, `category` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `product_categories`";
			$sWhereWrk = "";
			$this->category->LookupFilters = array("dx1" => '`category`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->category->AdvancedSearch->ViewValue = $this->category->DisplayValue($arwrk);
			} else {
				$this->category->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->category->EditValue = $arwrk;

			// product
			$this->product->EditAttrs["class"] = "form-control";
			$this->product->EditCustomAttributes = "";
			$this->product->EditValue = ew_HtmlEncode($this->product->AdvancedSearch->SearchValue);
			$this->product->PlaceHolder = ew_RemoveHtml($this->product->FldCaption());

			// amount
			$this->amount->EditAttrs["class"] = "form-control";
			$this->amount->EditCustomAttributes = "";
			$this->amount->EditValue = ew_HtmlEncode($this->amount->AdvancedSearch->SearchValue);
			$this->amount->PlaceHolder = ew_RemoveHtml($this->amount->FldCaption());

			// quantity
			$this->quantity->EditAttrs["class"] = "form-control";
			$this->quantity->EditCustomAttributes = "";
			$this->quantity->EditValue = ew_HtmlEncode($this->quantity->AdvancedSearch->SearchValue);
			$this->quantity->PlaceHolder = ew_RemoveHtml($this->quantity->FldCaption());

			// status
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = $this->status->Options(FALSE);
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
		if (!ew_CheckDate($this->transaction_date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->transaction_date->FldErrMsg());
		}
		if (!ew_CheckDate($this->transaction_date->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->transaction_date->FldErrMsg());
		}
		if (!ew_CheckNumber($this->amount->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->amount->FldErrMsg());
		}
		if (!ew_CheckInteger($this->quantity->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->quantity->FldErrMsg());
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
		$this->receipt_number->AdvancedSearch->Load();
		$this->transaction_date->AdvancedSearch->Load();
		$this->store->AdvancedSearch->Load();
		$this->user->AdvancedSearch->Load();
		$this->category->AdvancedSearch->Load();
		$this->product->AdvancedSearch->Load();
		$this->amount->AdvancedSearch->Load();
		$this->quantity->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("transactionslist.php"), "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_store":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `store_name` AS `LinkFld`, `store_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `stores`";
			$sWhereWrk = "";
			$this->store->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`store_name` IN ({filter_value})', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->store, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_user":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `sn` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `user`";
			$sWhereWrk = "";
			$this->user->LookupFilters = array();
			if (!$GLOBALS["transactions"]->UserIDAllow("search")) $sWhereWrk = $GLOBALS["user"]->AddUserIDFilter($sWhereWrk);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`sn` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->user, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_category":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `sn` AS `LinkFld`, `category` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `product_categories`";
			$sWhereWrk = "{filter}";
			$this->category->LookupFilters = array("dx1" => '`category`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`sn` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_product":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `sn` AS `LinkFld`, `product_name` AS `DispFld`, `product_id` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `products`";
			$sWhereWrk = "{filter}";
			$this->product->LookupFilters = array("dx1" => '`product_name`', "dx2" => '`product_id`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`sn` IN ({filter_value})', "t0" => "3", "fn0" => "");
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
if (!isset($transactions_search)) $transactions_search = new ctransactions_search();

// Page init
$transactions_search->Page_Init();

// Page main
$transactions_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$transactions_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($transactions_search->IsModal) { ?>
var CurrentAdvancedSearchForm = ftransactionssearch = new ew_Form("ftransactionssearch", "search");
<?php } else { ?>
var CurrentForm = ftransactionssearch = new ew_Form("ftransactionssearch", "search");
<?php } ?>

// Form_CustomValidate event
ftransactionssearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
ftransactionssearch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
ftransactionssearch.Lists["x_store"] = {"LinkField":"x_store_name","Ajax":true,"AutoFill":false,"DisplayFields":["x_store_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"stores"};
ftransactionssearch.Lists["x_store"].Data = "<?php echo $transactions_search->store->LookupFilterQuery(FALSE, "search") ?>";
ftransactionssearch.Lists["x_user"] = {"LinkField":"x_sn","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"user"};
ftransactionssearch.Lists["x_user"].Data = "<?php echo $transactions_search->user->LookupFilterQuery(FALSE, "search") ?>";
ftransactionssearch.Lists["x_category"] = {"LinkField":"x_sn","Ajax":true,"AutoFill":false,"DisplayFields":["x_category","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"product_categories"};
ftransactionssearch.Lists["x_category"].Data = "<?php echo $transactions_search->category->LookupFilterQuery(FALSE, "search") ?>";
ftransactionssearch.Lists["x_product"] = {"LinkField":"x_sn","Ajax":true,"AutoFill":false,"DisplayFields":["x_product_name","x_product_id","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"products"};
ftransactionssearch.Lists["x_product"].Data = "<?php echo $transactions_search->product->LookupFilterQuery(FALSE, "search") ?>";
ftransactionssearch.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftransactionssearch.Lists["x_status"].Options = <?php echo json_encode($transactions_search->status->Options()) ?>;

// Form object for search
// Validate function for search

ftransactionssearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_sn");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($transactions->sn->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_transaction_date");
	if (elm && !ew_CheckDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($transactions->transaction_date->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_amount");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($transactions->amount->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_quantity");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($transactions->quantity->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $transactions_search->ShowPageHeader(); ?>
<?php
$transactions_search->ShowMessage();
?>
<form name="ftransactionssearch" id="ftransactionssearch" class="<?php echo $transactions_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($transactions_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $transactions_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="transactions">
<input type="hidden" name="a_search" id="a_search" value="S">
<input type="hidden" name="modal" value="<?php echo intval($transactions_search->IsModal) ?>">
<div class="ewSearchDiv"><!-- page* -->
<?php if ($transactions->sn->Visible) { // sn ?>
	<div id="r_sn" class="form-group">
		<label for="x_sn" class="<?php echo $transactions_search->LeftColumnClass ?>"><span id="elh_transactions_sn"><?php echo $transactions->sn->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_sn" id="z_sn" value="="></p>
		</label>
		<div class="<?php echo $transactions_search->RightColumnClass ?>"><div<?php echo $transactions->sn->CellAttributes() ?>>
			<span id="el_transactions_sn">
<input type="text" data-table="transactions" data-field="x_sn" name="x_sn" id="x_sn" placeholder="<?php echo ew_HtmlEncode($transactions->sn->getPlaceHolder()) ?>" value="<?php echo $transactions->sn->EditValue ?>"<?php echo $transactions->sn->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($transactions->receipt_number->Visible) { // receipt_number ?>
	<div id="r_receipt_number" class="form-group">
		<label for="x_receipt_number" class="<?php echo $transactions_search->LeftColumnClass ?>"><span id="elh_transactions_receipt_number"><?php echo $transactions->receipt_number->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_receipt_number" id="z_receipt_number" value="LIKE"></p>
		</label>
		<div class="<?php echo $transactions_search->RightColumnClass ?>"><div<?php echo $transactions->receipt_number->CellAttributes() ?>>
			<span id="el_transactions_receipt_number">
<input type="text" data-table="transactions" data-field="x_receipt_number" name="x_receipt_number" id="x_receipt_number" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($transactions->receipt_number->getPlaceHolder()) ?>" value="<?php echo $transactions->receipt_number->EditValue ?>"<?php echo $transactions->receipt_number->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($transactions->transaction_date->Visible) { // transaction_date ?>
	<div id="r_transaction_date" class="form-group">
		<label for="x_transaction_date" class="<?php echo $transactions_search->LeftColumnClass ?>"><span id="elh_transactions_transaction_date"><?php echo $transactions->transaction_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase(">=") ?><input type="hidden" name="z_transaction_date" id="z_transaction_date" value=">="></p>
		</label>
		<div class="<?php echo $transactions_search->RightColumnClass ?>"><div<?php echo $transactions->transaction_date->CellAttributes() ?>>
			<span id="el_transactions_transaction_date">
<input type="text" data-table="transactions" data-field="x_transaction_date" name="x_transaction_date" id="x_transaction_date" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($transactions->transaction_date->getPlaceHolder()) ?>" value="<?php echo $transactions->transaction_date->EditValue ?>"<?php echo $transactions->transaction_date->EditAttributes() ?>>
<?php if (!$transactions->transaction_date->ReadOnly && !$transactions->transaction_date->Disabled && !isset($transactions->transaction_date->EditAttrs["readonly"]) && !isset($transactions->transaction_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("ftransactionssearch", "x_transaction_date", {"ignoreReadonly":true,"useCurrent":false,"format":9});
</script>
<?php } ?>
</span>
			<span class="ewSearchCond btw0_transaction_date"><label class="radio-inline ewRadio" style="white-space: nowrap;"><input type="radio" name="v_transaction_date" value="AND"<?php if ($transactions->transaction_date->AdvancedSearch->SearchCondition <> "OR") echo " checked" ?>><?php echo $Language->Phrase("AND") ?></label><label class="radio-inline ewRadio" style="white-space: nowrap;"><input type="radio" name="v_transaction_date" value="OR"<?php if ($transactions->transaction_date->AdvancedSearch->SearchCondition == "OR") echo " checked" ?>><?php echo $Language->Phrase("OR") ?></label>&nbsp;</span>
			<p class="form-control-static ewSearchOperator btw0_transaction_date"><?php echo $Language->Phrase("<=") ?><input type="hidden" name="w_transaction_date" id="w_transaction_date" value="<="></p>
			<span id="e2_transactions_transaction_date">
<input type="text" data-table="transactions" data-field="x_transaction_date" name="y_transaction_date" id="y_transaction_date" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($transactions->transaction_date->getPlaceHolder()) ?>" value="<?php echo $transactions->transaction_date->EditValue2 ?>"<?php echo $transactions->transaction_date->EditAttributes() ?>>
<?php if (!$transactions->transaction_date->ReadOnly && !$transactions->transaction_date->Disabled && !isset($transactions->transaction_date->EditAttrs["readonly"]) && !isset($transactions->transaction_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("ftransactionssearch", "y_transaction_date", {"ignoreReadonly":true,"useCurrent":false,"format":9});
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($transactions->store->Visible) { // store ?>
	<div id="r_store" class="form-group">
		<label for="x_store" class="<?php echo $transactions_search->LeftColumnClass ?>"><span id="elh_transactions_store"><?php echo $transactions->store->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_store" id="z_store" value="LIKE"></p>
		</label>
		<div class="<?php echo $transactions_search->RightColumnClass ?>"><div<?php echo $transactions->store->CellAttributes() ?>>
			<span id="el_transactions_store">
<select data-table="transactions" data-field="x_store" data-value-separator="<?php echo $transactions->store->DisplayValueSeparatorAttribute() ?>" id="x_store" name="x_store"<?php echo $transactions->store->EditAttributes() ?>>
<?php echo $transactions->store->SelectOptionListHtml("x_store") ?>
</select>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($transactions->user->Visible) { // user ?>
	<div id="r_user" class="form-group">
		<label for="x_user" class="<?php echo $transactions_search->LeftColumnClass ?>"><span id="elh_transactions_user"><?php echo $transactions->user->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_user" id="z_user" value="="></p>
		</label>
		<div class="<?php echo $transactions_search->RightColumnClass ?>"><div<?php echo $transactions->user->CellAttributes() ?>>
			<span id="el_transactions_user">
<?php if (!$Security->IsAdmin() && $Security->IsLoggedIn() && !$transactions->UserIDAllow("search")) { // Non system admin ?>
<span<?php echo $transactions->user->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $transactions->user->EditValue ?></p></span>
<input type="hidden" data-table="transactions" data-field="x_user" name="x_user" id="x_user" value="<?php echo ew_HtmlEncode($transactions->user->AdvancedSearch->SearchValue) ?>">
<?php } else { ?>
<select data-table="transactions" data-field="x_user" data-value-separator="<?php echo $transactions->user->DisplayValueSeparatorAttribute() ?>" id="x_user" name="x_user"<?php echo $transactions->user->EditAttributes() ?>>
<?php echo $transactions->user->SelectOptionListHtml("x_user") ?>
</select>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($transactions->category->Visible) { // category ?>
	<div id="r_category" class="form-group">
		<label for="x_category" class="<?php echo $transactions_search->LeftColumnClass ?>"><span id="elh_transactions_category"><?php echo $transactions->category->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_category" id="z_category" value="LIKE"></p>
		</label>
		<div class="<?php echo $transactions_search->RightColumnClass ?>"><div<?php echo $transactions->category->CellAttributes() ?>>
			<span id="el_transactions_category">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_category"><?php echo (strval($transactions->category->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $transactions->category->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($transactions->category->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_category',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="transactions" data-field="x_category" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $transactions->category->DisplayValueSeparatorAttribute() ?>" name="x_category" id="x_category" value="<?php echo $transactions->category->AdvancedSearch->SearchValue ?>"<?php echo $transactions->category->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($transactions->product->Visible) { // product ?>
	<div id="r_product" class="form-group">
		<label for="x_product" class="<?php echo $transactions_search->LeftColumnClass ?>"><span id="elh_transactions_product"><?php echo $transactions->product->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_product" id="z_product" value="="></p>
		</label>
		<div class="<?php echo $transactions_search->RightColumnClass ?>"><div<?php echo $transactions->product->CellAttributes() ?>>
			<span id="el_transactions_product">
<input type="text" data-table="transactions" data-field="x_product" name="x_product" id="x_product" size="30" placeholder="<?php echo ew_HtmlEncode($transactions->product->getPlaceHolder()) ?>" value="<?php echo $transactions->product->EditValue ?>"<?php echo $transactions->product->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($transactions->amount->Visible) { // amount ?>
	<div id="r_amount" class="form-group">
		<label for="x_amount" class="<?php echo $transactions_search->LeftColumnClass ?>"><span id="elh_transactions_amount"><?php echo $transactions->amount->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_amount" id="z_amount" value="="></p>
		</label>
		<div class="<?php echo $transactions_search->RightColumnClass ?>"><div<?php echo $transactions->amount->CellAttributes() ?>>
			<span id="el_transactions_amount">
<input type="text" data-table="transactions" data-field="x_amount" name="x_amount" id="x_amount" size="30" placeholder="<?php echo ew_HtmlEncode($transactions->amount->getPlaceHolder()) ?>" value="<?php echo $transactions->amount->EditValue ?>"<?php echo $transactions->amount->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($transactions->quantity->Visible) { // quantity ?>
	<div id="r_quantity" class="form-group">
		<label for="x_quantity" class="<?php echo $transactions_search->LeftColumnClass ?>"><span id="elh_transactions_quantity"><?php echo $transactions->quantity->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_quantity" id="z_quantity" value="="></p>
		</label>
		<div class="<?php echo $transactions_search->RightColumnClass ?>"><div<?php echo $transactions->quantity->CellAttributes() ?>>
			<span id="el_transactions_quantity">
<input type="text" data-table="transactions" data-field="x_quantity" name="x_quantity" id="x_quantity" size="30" placeholder="<?php echo ew_HtmlEncode($transactions->quantity->getPlaceHolder()) ?>" value="<?php echo $transactions->quantity->EditValue ?>"<?php echo $transactions->quantity->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($transactions->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label class="<?php echo $transactions_search->LeftColumnClass ?>"><span id="elh_transactions_status"><?php echo $transactions->status->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_status" id="z_status" value="LIKE"></p>
		</label>
		<div class="<?php echo $transactions_search->RightColumnClass ?>"><div<?php echo $transactions->status->CellAttributes() ?>>
			<span id="el_transactions_status">
<div id="tp_x_status" class="ewTemplate"><input type="radio" data-table="transactions" data-field="x_status" data-value-separator="<?php echo $transactions->status->DisplayValueSeparatorAttribute() ?>" name="x_status" id="x_status" value="{value}"<?php echo $transactions->status->EditAttributes() ?>></div>
<div id="dsl_x_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $transactions->status->RadioButtonListHtml(FALSE, "x_status") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$transactions_search->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $transactions_search->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
ftransactionssearch.Init();
</script>
<?php
$transactions_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$transactions_search->Page_Terminate();
?>
