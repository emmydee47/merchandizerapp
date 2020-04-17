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

$brand_transactions_search = NULL; // Initialize page object first

class cbrand_transactions_search extends cbrand_transactions {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = '{B6A7A568-E606-4B9C-A82F-C80372444DA0}';

	// Table name
	var $TableName = 'brand_transactions';

	// Page object name
	var $PageObjName = 'brand_transactions_search';

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
			define("EW_PAGE_ID", 'search', TRUE);

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
		if (!$Security->CanSearch()) {
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
						$sSrchStr = "brand_transactionslist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->brand); // brand
		$this->BuildSearchUrl($sSrchUrl, $this->product); // product
		$this->BuildSearchUrl($sSrchUrl, $this->amount); // amount
		$this->BuildSearchUrl($sSrchUrl, $this->quantity); // quantity
		$this->BuildSearchUrl($sSrchUrl, $this->user); // user
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

		// brand
		$this->brand->AdvancedSearch->SearchValue = $objForm->GetValue("x_brand");
		$this->brand->AdvancedSearch->SearchOperator = $objForm->GetValue("z_brand");

		// product
		$this->product->AdvancedSearch->SearchValue = $objForm->GetValue("x_product");
		$this->product->AdvancedSearch->SearchOperator = $objForm->GetValue("z_product");

		// amount
		$this->amount->AdvancedSearch->SearchValue = $objForm->GetValue("x_amount");
		$this->amount->AdvancedSearch->SearchOperator = $objForm->GetValue("z_amount");

		// quantity
		$this->quantity->AdvancedSearch->SearchValue = $objForm->GetValue("x_quantity");
		$this->quantity->AdvancedSearch->SearchOperator = $objForm->GetValue("z_quantity");

		// user
		$this->user->AdvancedSearch->SearchValue = $objForm->GetValue("x_user");
		$this->user->AdvancedSearch->SearchOperator = $objForm->GetValue("z_user");
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

			// brand
			$this->brand->EditAttrs["class"] = "form-control";
			$this->brand->EditCustomAttributes = "";
			if (trim(strval($this->brand->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`sn`" . ew_SearchString("=", $this->brand->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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

			// user
			$this->user->EditAttrs["class"] = "form-control";
			$this->user->EditCustomAttributes = "";
			if (trim(strval($this->user->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`sn`" . ew_SearchString("=", $this->user->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `sn`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `user`";
			$sWhereWrk = "";
			$this->user->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			if (!$GLOBALS["brand_transactions"]->UserIDAllow("search")) $sWhereWrk = $GLOBALS["user"]->AddUserIDFilter($sWhereWrk);
			$this->Lookup_Selecting($this->user, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->user->EditValue = $arwrk;
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
		if (!ew_CheckInteger($this->product->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->product->FldErrMsg());
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
		$this->transaction_date->AdvancedSearch->Load();
		$this->brand->AdvancedSearch->Load();
		$this->product->AdvancedSearch->Load();
		$this->amount->AdvancedSearch->Load();
		$this->quantity->AdvancedSearch->Load();
		$this->user->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("brand_transactionslist.php"), "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
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
			if (!$GLOBALS["brand_transactions"]->UserIDAllow("search")) $sWhereWrk = $GLOBALS["user"]->AddUserIDFilter($sWhereWrk);
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
if (!isset($brand_transactions_search)) $brand_transactions_search = new cbrand_transactions_search();

// Page init
$brand_transactions_search->Page_Init();

// Page main
$brand_transactions_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$brand_transactions_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($brand_transactions_search->IsModal) { ?>
var CurrentAdvancedSearchForm = fbrand_transactionssearch = new ew_Form("fbrand_transactionssearch", "search");
<?php } else { ?>
var CurrentForm = fbrand_transactionssearch = new ew_Form("fbrand_transactionssearch", "search");
<?php } ?>

// Form_CustomValidate event
fbrand_transactionssearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fbrand_transactionssearch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fbrand_transactionssearch.Lists["x_brand"] = {"LinkField":"x_sn","Ajax":true,"AutoFill":false,"DisplayFields":["x_brand_name","","",""],"ParentFields":[],"ChildFields":["x_product"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"other_brands"};
fbrand_transactionssearch.Lists["x_brand"].Data = "<?php echo $brand_transactions_search->brand->LookupFilterQuery(FALSE, "search") ?>";
fbrand_transactionssearch.Lists["x_user"] = {"LinkField":"x_sn","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"user"};
fbrand_transactionssearch.Lists["x_user"].Data = "<?php echo $brand_transactions_search->user->LookupFilterQuery(FALSE, "search") ?>";

// Form object for search
// Validate function for search

fbrand_transactionssearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_sn");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($brand_transactions->sn->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_transaction_date");
	if (elm && !ew_CheckDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($brand_transactions->transaction_date->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_product");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($brand_transactions->product->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_amount");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($brand_transactions->amount->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_quantity");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($brand_transactions->quantity->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $brand_transactions_search->ShowPageHeader(); ?>
<?php
$brand_transactions_search->ShowMessage();
?>
<form name="fbrand_transactionssearch" id="fbrand_transactionssearch" class="<?php echo $brand_transactions_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($brand_transactions_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $brand_transactions_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="brand_transactions">
<input type="hidden" name="a_search" id="a_search" value="S">
<input type="hidden" name="modal" value="<?php echo intval($brand_transactions_search->IsModal) ?>">
<div class="ewSearchDiv"><!-- page* -->
<?php if ($brand_transactions->sn->Visible) { // sn ?>
	<div id="r_sn" class="form-group">
		<label for="x_sn" class="<?php echo $brand_transactions_search->LeftColumnClass ?>"><span id="elh_brand_transactions_sn"><?php echo $brand_transactions->sn->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_sn" id="z_sn" value="="></p>
		</label>
		<div class="<?php echo $brand_transactions_search->RightColumnClass ?>"><div<?php echo $brand_transactions->sn->CellAttributes() ?>>
			<span id="el_brand_transactions_sn">
<input type="text" data-table="brand_transactions" data-field="x_sn" name="x_sn" id="x_sn" placeholder="<?php echo ew_HtmlEncode($brand_transactions->sn->getPlaceHolder()) ?>" value="<?php echo $brand_transactions->sn->EditValue ?>"<?php echo $brand_transactions->sn->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($brand_transactions->transaction_date->Visible) { // transaction_date ?>
	<div id="r_transaction_date" class="form-group">
		<label for="x_transaction_date" class="<?php echo $brand_transactions_search->LeftColumnClass ?>"><span id="elh_brand_transactions_transaction_date"><?php echo $brand_transactions->transaction_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase(">=") ?><input type="hidden" name="z_transaction_date" id="z_transaction_date" value=">="></p>
		</label>
		<div class="<?php echo $brand_transactions_search->RightColumnClass ?>"><div<?php echo $brand_transactions->transaction_date->CellAttributes() ?>>
			<span id="el_brand_transactions_transaction_date">
<input type="text" data-table="brand_transactions" data-field="x_transaction_date" name="x_transaction_date" id="x_transaction_date" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($brand_transactions->transaction_date->getPlaceHolder()) ?>" value="<?php echo $brand_transactions->transaction_date->EditValue ?>"<?php echo $brand_transactions->transaction_date->EditAttributes() ?>>
</span>
			<span class="ewSearchCond btw0_transaction_date"><label class="radio-inline ewRadio" style="white-space: nowrap;"><input type="radio" name="v_transaction_date" value="AND"<?php if ($brand_transactions->transaction_date->AdvancedSearch->SearchCondition <> "OR") echo " checked" ?>><?php echo $Language->Phrase("AND") ?></label><label class="radio-inline ewRadio" style="white-space: nowrap;"><input type="radio" name="v_transaction_date" value="OR"<?php if ($brand_transactions->transaction_date->AdvancedSearch->SearchCondition == "OR") echo " checked" ?>><?php echo $Language->Phrase("OR") ?></label>&nbsp;</span>
			<p class="form-control-static ewSearchOperator btw0_transaction_date"><?php echo $Language->Phrase("<=") ?><input type="hidden" name="w_transaction_date" id="w_transaction_date" value="<="></p>
			<span id="e2_brand_transactions_transaction_date">
<input type="text" data-table="brand_transactions" data-field="x_transaction_date" name="y_transaction_date" id="y_transaction_date" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($brand_transactions->transaction_date->getPlaceHolder()) ?>" value="<?php echo $brand_transactions->transaction_date->EditValue2 ?>"<?php echo $brand_transactions->transaction_date->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($brand_transactions->brand->Visible) { // brand ?>
	<div id="r_brand" class="form-group">
		<label for="x_brand" class="<?php echo $brand_transactions_search->LeftColumnClass ?>"><span id="elh_brand_transactions_brand"><?php echo $brand_transactions->brand->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_brand" id="z_brand" value="="></p>
		</label>
		<div class="<?php echo $brand_transactions_search->RightColumnClass ?>"><div<?php echo $brand_transactions->brand->CellAttributes() ?>>
			<span id="el_brand_transactions_brand">
<select data-table="brand_transactions" data-field="x_brand" data-value-separator="<?php echo $brand_transactions->brand->DisplayValueSeparatorAttribute() ?>" id="x_brand" name="x_brand"<?php echo $brand_transactions->brand->EditAttributes() ?>>
<?php echo $brand_transactions->brand->SelectOptionListHtml("x_brand") ?>
</select>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($brand_transactions->product->Visible) { // product ?>
	<div id="r_product" class="form-group">
		<label for="x_product" class="<?php echo $brand_transactions_search->LeftColumnClass ?>"><span id="elh_brand_transactions_product"><?php echo $brand_transactions->product->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_product" id="z_product" value="="></p>
		</label>
		<div class="<?php echo $brand_transactions_search->RightColumnClass ?>"><div<?php echo $brand_transactions->product->CellAttributes() ?>>
			<span id="el_brand_transactions_product">
<input type="text" data-table="brand_transactions" data-field="x_product" name="x_product" id="x_product" size="30" placeholder="<?php echo ew_HtmlEncode($brand_transactions->product->getPlaceHolder()) ?>" value="<?php echo $brand_transactions->product->EditValue ?>"<?php echo $brand_transactions->product->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($brand_transactions->amount->Visible) { // amount ?>
	<div id="r_amount" class="form-group">
		<label for="x_amount" class="<?php echo $brand_transactions_search->LeftColumnClass ?>"><span id="elh_brand_transactions_amount"><?php echo $brand_transactions->amount->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_amount" id="z_amount" value="="></p>
		</label>
		<div class="<?php echo $brand_transactions_search->RightColumnClass ?>"><div<?php echo $brand_transactions->amount->CellAttributes() ?>>
			<span id="el_brand_transactions_amount">
<input type="text" data-table="brand_transactions" data-field="x_amount" name="x_amount" id="x_amount" size="30" placeholder="<?php echo ew_HtmlEncode($brand_transactions->amount->getPlaceHolder()) ?>" value="<?php echo $brand_transactions->amount->EditValue ?>"<?php echo $brand_transactions->amount->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($brand_transactions->quantity->Visible) { // quantity ?>
	<div id="r_quantity" class="form-group">
		<label for="x_quantity" class="<?php echo $brand_transactions_search->LeftColumnClass ?>"><span id="elh_brand_transactions_quantity"><?php echo $brand_transactions->quantity->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_quantity" id="z_quantity" value="="></p>
		</label>
		<div class="<?php echo $brand_transactions_search->RightColumnClass ?>"><div<?php echo $brand_transactions->quantity->CellAttributes() ?>>
			<span id="el_brand_transactions_quantity">
<input type="text" data-table="brand_transactions" data-field="x_quantity" name="x_quantity" id="x_quantity" size="30" placeholder="<?php echo ew_HtmlEncode($brand_transactions->quantity->getPlaceHolder()) ?>" value="<?php echo $brand_transactions->quantity->EditValue ?>"<?php echo $brand_transactions->quantity->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($brand_transactions->user->Visible) { // user ?>
	<div id="r_user" class="form-group">
		<label for="x_user" class="<?php echo $brand_transactions_search->LeftColumnClass ?>"><span id="elh_brand_transactions_user"><?php echo $brand_transactions->user->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_user" id="z_user" value="="></p>
		</label>
		<div class="<?php echo $brand_transactions_search->RightColumnClass ?>"><div<?php echo $brand_transactions->user->CellAttributes() ?>>
			<span id="el_brand_transactions_user">
<select data-table="brand_transactions" data-field="x_user" data-value-separator="<?php echo $brand_transactions->user->DisplayValueSeparatorAttribute() ?>" id="x_user" name="x_user"<?php echo $brand_transactions->user->EditAttributes() ?>>
<?php echo $brand_transactions->user->SelectOptionListHtml("x_user") ?>
</select>
</span>
		</div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$brand_transactions_search->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $brand_transactions_search->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fbrand_transactionssearch.Init();
</script>
<?php
$brand_transactions_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$brand_transactions_search->Page_Terminate();
?>
