<?php

// Global variable for table object
$location_target_view = NULL;

//
// Table class for location_target_view
//
class clocation_target_view extends cTable {
	var $sn;
	var $State;
	var $store_name;
	var $month;
	var $product_category;
	var $target_value;
	var $qty_sold;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'location_target_view';
		$this->TableName = 'location_target_view';
		$this->TableType = 'CUSTOMVIEW';

		// Update Table
		$this->UpdateTable = "location_target INNER JOIN stores ON stores.sn = location_target.location";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->ExportWordColumnWidth = NULL; // Cell width (PHPWord only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// sn
		$this->sn = new cField('location_target_view', 'location_target_view', 'x_sn', 'sn', 'stores.sn', 'stores.sn', 3, -1, FALSE, 'stores.sn', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->sn->Sortable = TRUE; // Allow sort
		$this->sn->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['sn'] = &$this->sn;

		// State
		$this->State = new cField('location_target_view', 'location_target_view', 'x_State', 'State', 'stores.State', 'stores.State', 200, -1, FALSE, 'stores.State', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->State->Sortable = TRUE; // Allow sort
		$this->State->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->State->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->State->OptionCount = 37;
		$this->fields['State'] = &$this->State;

		// store_name
		$this->store_name = new cField('location_target_view', 'location_target_view', 'x_store_name', 'store_name', 'stores.store_name', 'stores.store_name', 200, -1, FALSE, 'stores.store_name', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->store_name->Sortable = TRUE; // Allow sort
		$this->fields['store_name'] = &$this->store_name;

		// month
		$this->month = new cField('location_target_view', 'location_target_view', 'x_month', 'month', 'location_target.month', 'location_target.month', 200, -1, FALSE, 'location_target.month', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->month->Sortable = TRUE; // Allow sort
		$this->month->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->month->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->month->OptionCount = 12;
		$this->fields['month'] = &$this->month;

		// product_category
		$this->product_category = new cField('location_target_view', 'location_target_view', 'x_product_category', 'product_category', 'location_target.product_category', 'location_target.product_category', 3, -1, FALSE, 'location_target.product_category', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->product_category->Sortable = TRUE; // Allow sort
		$this->product_category->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->product_category->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->product_category->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['product_category'] = &$this->product_category;

		// target_value
		$this->target_value = new cField('location_target_view', 'location_target_view', 'x_target_value', 'target_value', 'location_target.target_value', 'location_target.target_value', 3, -1, FALSE, 'location_target.target_value', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->target_value->Sortable = TRUE; // Allow sort
		$this->target_value->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['target_value'] = &$this->target_value;

		// qty_sold
		$this->qty_sold = new cField('location_target_view', 'location_target_view', 'x_qty_sold', 'qty_sold', '\'\'', '\'\'', 201, -1, FALSE, '\'\'', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->qty_sold->FldIsCustom = TRUE; // Custom field
		$this->qty_sold->Sortable = TRUE; // Allow sort
		$this->fields['qty_sold'] = &$this->qty_sold;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Column CSS classes
	var $LeftColumnClass = "col-sm-2 control-label ewLabel";
	var $RightColumnClass = "col-sm-10";
	var $OffsetColumnClass = "col-sm-10 col-sm-offset-2";

	// Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
	function SetLeftColumnClass($class) {
		if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
			$this->LeftColumnClass = $class . " control-label ewLabel";
			$this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - intval($match[2]));
			$this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace($match[1], $match[1] + "-offset", $this->LeftColumnClass);
		}
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "location_target INNER JOIN stores ON stores.sn = location_target.location";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT stores.sn, stores.State, stores.store_name, location_target.month, location_target.product_category, location_target.target_value, '' AS `qty_sold` FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	var $UseSessionForListSQL = TRUE;

	function ListSQL() {
		$sFilter = $this->UseSessionForListSQL ? $this->getSessionWhere() : "";
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSelect = $this->getSqlSelect();
		$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderBy() : "";
		return ew_BuildSelectSql($sSelect, $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function ListRecordCount() {
		$sSql = $this->ListSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {

			// Get insert id if necessary
			$this->sn->setDbValue($conn->Insert_ID());
			$rs['sn'] = $this->sn->DbValue;
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$bDelete = TRUE;
		$conn = &$this->Connection();
		if ($bDelete)
			$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "location_target_viewlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "location_target_viewview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "location_target_viewedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "location_target_viewadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "location_target_viewlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("location_target_viewview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("location_target_viewview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "location_target_viewadd.php?" . $this->UrlParm($parm);
		else
			$url = "location_target_viewadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("location_target_viewedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("location_target_viewadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("location_target_viewdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = $_POST["key_m"];
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = $_GET["key_m"];
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsPost();

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->sn->setDbValue($rs->fields('sn'));
		$this->State->setDbValue($rs->fields('State'));
		$this->store_name->setDbValue($rs->fields('store_name'));
		$this->month->setDbValue($rs->fields('month'));
		$this->product_category->setDbValue($rs->fields('product_category'));
		$this->target_value->setDbValue($rs->fields('target_value'));
		$this->qty_sold->setDbValue($rs->fields('qty_sold'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// sn
		// State
		// store_name
		// month
		// product_category
		// target_value
		// qty_sold
		// sn

		$this->sn->ViewValue = $this->sn->CurrentValue;
		$this->sn->ViewCustomAttributes = "";

		// State
		if (strval($this->State->CurrentValue) <> "") {
			$this->State->ViewValue = $this->State->OptionCaption($this->State->CurrentValue);
		} else {
			$this->State->ViewValue = NULL;
		}
		$this->State->ViewCustomAttributes = "";

		// store_name
		$this->store_name->ViewValue = $this->store_name->CurrentValue;
		$this->store_name->ViewCustomAttributes = "";

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

		// qty_sold
		$this->qty_sold->ViewValue = $this->qty_sold->CurrentValue;
		$this->qty_sold->ViewCustomAttributes = "";

		// sn
		$this->sn->LinkCustomAttributes = "";
		$this->sn->HrefValue = "";
		$this->sn->TooltipValue = "";

		// State
		$this->State->LinkCustomAttributes = "";
		$this->State->HrefValue = "";
		$this->State->TooltipValue = "";

		// store_name
		$this->store_name->LinkCustomAttributes = "";
		$this->store_name->HrefValue = "";
		$this->store_name->TooltipValue = "";

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

		// qty_sold
		$this->qty_sold->LinkCustomAttributes = "";
		$this->qty_sold->HrefValue = "";
		$this->qty_sold->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();

		// Save data for Custom Template
		$this->Rows[] = $this->CustomTemplateFieldValues();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// sn
		$this->sn->EditAttrs["class"] = "form-control";
		$this->sn->EditCustomAttributes = "";
		$this->sn->EditValue = $this->sn->CurrentValue;
		$this->sn->PlaceHolder = ew_RemoveHtml($this->sn->FldCaption());

		// State
		$this->State->EditAttrs["class"] = "form-control";
		$this->State->EditCustomAttributes = "";
		$this->State->EditValue = $this->State->Options(TRUE);

		// store_name
		$this->store_name->EditAttrs["class"] = "form-control";
		$this->store_name->EditCustomAttributes = "";
		$this->store_name->EditValue = $this->store_name->CurrentValue;
		$this->store_name->PlaceHolder = ew_RemoveHtml($this->store_name->FldCaption());

		// month
		$this->month->EditAttrs["class"] = "form-control";
		$this->month->EditCustomAttributes = "";
		$this->month->EditValue = $this->month->Options(TRUE);

		// product_category
		$this->product_category->EditAttrs["class"] = "form-control";
		$this->product_category->EditCustomAttributes = "";

		// target_value
		$this->target_value->EditAttrs["class"] = "form-control";
		$this->target_value->EditCustomAttributes = "";
		$this->target_value->EditValue = $this->target_value->CurrentValue;
		$this->target_value->PlaceHolder = ew_RemoveHtml($this->target_value->FldCaption());

		// qty_sold
		$this->qty_sold->EditAttrs["class"] = "form-control";
		$this->qty_sold->EditCustomAttributes = "";
		$this->qty_sold->EditValue = $this->qty_sold->CurrentValue;
		$this->qty_sold->PlaceHolder = ew_RemoveHtml($this->qty_sold->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->sn->Exportable) $Doc->ExportCaption($this->sn);
					if ($this->State->Exportable) $Doc->ExportCaption($this->State);
					if ($this->store_name->Exportable) $Doc->ExportCaption($this->store_name);
					if ($this->month->Exportable) $Doc->ExportCaption($this->month);
					if ($this->product_category->Exportable) $Doc->ExportCaption($this->product_category);
					if ($this->target_value->Exportable) $Doc->ExportCaption($this->target_value);
					if ($this->qty_sold->Exportable) $Doc->ExportCaption($this->qty_sold);
				} else {
					if ($this->sn->Exportable) $Doc->ExportCaption($this->sn);
					if ($this->State->Exportable) $Doc->ExportCaption($this->State);
					if ($this->store_name->Exportable) $Doc->ExportCaption($this->store_name);
					if ($this->month->Exportable) $Doc->ExportCaption($this->month);
					if ($this->product_category->Exportable) $Doc->ExportCaption($this->product_category);
					if ($this->target_value->Exportable) $Doc->ExportCaption($this->target_value);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->sn->Exportable) $Doc->ExportField($this->sn);
						if ($this->State->Exportable) $Doc->ExportField($this->State);
						if ($this->store_name->Exportable) $Doc->ExportField($this->store_name);
						if ($this->month->Exportable) $Doc->ExportField($this->month);
						if ($this->product_category->Exportable) $Doc->ExportField($this->product_category);
						if ($this->target_value->Exportable) $Doc->ExportField($this->target_value);
						if ($this->qty_sold->Exportable) $Doc->ExportField($this->qty_sold);
					} else {
						if ($this->sn->Exportable) $Doc->ExportField($this->sn);
						if ($this->State->Exportable) $Doc->ExportField($this->State);
						if ($this->store_name->Exportable) $Doc->ExportField($this->store_name);
						if ($this->month->Exportable) $Doc->ExportField($this->month);
						if ($this->product_category->Exportable) $Doc->ExportField($this->product_category);
						if ($this->target_value->Exportable) $Doc->ExportField($this->target_value);
					}
					$Doc->EndExportRow($RowCnt);
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
