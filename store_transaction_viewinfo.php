<?php

// Global variable for table object
$store_transaction_view = NULL;

//
// Table class for store_transaction_view
//
class cstore_transaction_view extends cTable {
	var $sn;
	var $transaction_date;
	var $State;
	var $store_name;
	var $merchandizer;
	var $category;
	var $product;
	var $quantity;
	var $amount;
	var $monthly_target;
	var $month;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'store_transaction_view';
		$this->TableName = 'store_transaction_view';
		$this->TableType = 'VIEW';

		// Update Table
		$this->UpdateTable = "`store_transaction_view`";
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
		$this->sn = new cField('store_transaction_view', 'store_transaction_view', 'x_sn', 'sn', '`sn`', '`sn`', 3, -1, FALSE, '`sn`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->sn->Sortable = TRUE; // Allow sort
		$this->sn->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['sn'] = &$this->sn;

		// transaction_date
		$this->transaction_date = new cField('store_transaction_view', 'store_transaction_view', 'x_transaction_date', 'transaction_date', '`transaction_date`', '`transaction_date`', 200, 14, FALSE, '`transaction_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->transaction_date->Sortable = TRUE; // Allow sort
		$this->transaction_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectShortDateDMY"));
		$this->fields['transaction_date'] = &$this->transaction_date;

		// State
		$this->State = new cField('store_transaction_view', 'store_transaction_view', 'x_State', 'State', '`State`', '`State`', 200, -1, FALSE, '`State`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->State->Sortable = TRUE; // Allow sort
		$this->State->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->State->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->State->OptionCount = 37;
		$this->fields['State'] = &$this->State;

		// store_name
		$this->store_name = new cField('store_transaction_view', 'store_transaction_view', 'x_store_name', 'store_name', '`store_name`', '`store_name`', 200, -1, FALSE, '`store_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->store_name->Sortable = TRUE; // Allow sort
		$this->fields['store_name'] = &$this->store_name;

		// merchandizer
		$this->merchandizer = new cField('store_transaction_view', 'store_transaction_view', 'x_merchandizer', 'merchandizer', '`merchandizer`', '`merchandizer`', 200, -1, FALSE, '`merchandizer`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->merchandizer->Sortable = TRUE; // Allow sort
		$this->fields['merchandizer'] = &$this->merchandizer;

		// category
		$this->category = new cField('store_transaction_view', 'store_transaction_view', 'x_category', 'category', '`category`', '`category`', 200, -1, FALSE, '`category`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->category->Sortable = TRUE; // Allow sort
		$this->fields['category'] = &$this->category;

		// product
		$this->product = new cField('store_transaction_view', 'store_transaction_view', 'x_product', 'product', '`product`', '`product`', 200, -1, FALSE, '`product`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->product->Sortable = TRUE; // Allow sort
		$this->fields['product'] = &$this->product;

		// quantity
		$this->quantity = new cField('store_transaction_view', 'store_transaction_view', 'x_quantity', 'quantity', '`quantity`', '`quantity`', 3, -1, FALSE, '`quantity`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->quantity->Sortable = TRUE; // Allow sort
		$this->quantity->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['quantity'] = &$this->quantity;

		// amount
		$this->amount = new cField('store_transaction_view', 'store_transaction_view', 'x_amount', 'amount', '`amount`', '`amount`', 131, -1, FALSE, '`amount`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->amount->Sortable = TRUE; // Allow sort
		$this->amount->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['amount'] = &$this->amount;

		// monthly_target
		$this->monthly_target = new cField('store_transaction_view', 'store_transaction_view', 'x_monthly_target', 'monthly_target', '`monthly_target`', '`monthly_target`', 3, -1, FALSE, '`monthly_target`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->monthly_target->Sortable = FALSE; // Allow sort
		$this->monthly_target->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['monthly_target'] = &$this->monthly_target;

		// month
		$this->month = new cField('store_transaction_view', 'store_transaction_view', 'x_month', 'month', '`month`', '`month`', 200, -1, FALSE, '`month`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->month->Sortable = FALSE; // Allow sort
		$this->fields['month'] = &$this->month;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`store_transaction_view`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
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
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "`sn` DESC";
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
			if (array_key_exists('sn', $rs))
				ew_AddFilter($where, ew_QuotedName('sn', $this->DBID) . '=' . ew_QuotedValue($rs['sn'], $this->sn->FldDataType, $this->DBID));
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
		return "`sn` = @sn@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->sn->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->sn->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@sn@", ew_AdjustSql($this->sn->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "store_transaction_viewlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "store_transaction_viewview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "store_transaction_viewedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "store_transaction_viewadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "store_transaction_viewlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("store_transaction_viewview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("store_transaction_viewview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "store_transaction_viewadd.php?" . $this->UrlParm($parm);
		else
			$url = "store_transaction_viewadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("store_transaction_viewedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("store_transaction_viewadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("store_transaction_viewdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "sn:" . ew_VarToJson($this->sn->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->sn->CurrentValue)) {
			$sUrl .= "sn=" . urlencode($this->sn->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
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
			if ($isPost && isset($_POST["sn"]))
				$arKeys[] = $_POST["sn"];
			elseif (isset($_GET["sn"]))
				$arKeys[] = $_GET["sn"];
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
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
			$this->sn->CurrentValue = $key;
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
		$this->transaction_date->setDbValue($rs->fields('transaction_date'));
		$this->State->setDbValue($rs->fields('State'));
		$this->store_name->setDbValue($rs->fields('store_name'));
		$this->merchandizer->setDbValue($rs->fields('merchandizer'));
		$this->category->setDbValue($rs->fields('category'));
		$this->product->setDbValue($rs->fields('product'));
		$this->quantity->setDbValue($rs->fields('quantity'));
		$this->amount->setDbValue($rs->fields('amount'));
		$this->monthly_target->setDbValue($rs->fields('monthly_target'));
		$this->month->setDbValue($rs->fields('month'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
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

		$this->monthly_target->CellCssStyle = "white-space: nowrap;";

		// month
		$this->month->CellCssStyle = "white-space: nowrap;";

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

		// monthly_target
		$this->monthly_target->ViewValue = $this->monthly_target->CurrentValue;
		$this->monthly_target->ViewCustomAttributes = "";

		// month
		$this->month->ViewValue = $this->month->CurrentValue;
		$this->month->ViewCustomAttributes = "";

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

		// monthly_target
		$this->monthly_target->LinkCustomAttributes = "";
		$this->monthly_target->HrefValue = "";
		$this->monthly_target->TooltipValue = "";

		// month
		$this->month->LinkCustomAttributes = "";
		$this->month->HrefValue = "";
		$this->month->TooltipValue = "";

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
		$this->sn->ViewCustomAttributes = "";

		// transaction_date
		$this->transaction_date->EditAttrs["class"] = "form-control";
		$this->transaction_date->EditCustomAttributes = "";
		$this->transaction_date->EditValue = $this->transaction_date->CurrentValue;
		$this->transaction_date->PlaceHolder = ew_RemoveHtml($this->transaction_date->FldCaption());

		// State
		$this->State->EditAttrs["class"] = "form-control";
		$this->State->EditCustomAttributes = "";
		$this->State->EditValue = $this->State->Options(TRUE);

		// store_name
		$this->store_name->EditAttrs["class"] = "form-control";
		$this->store_name->EditCustomAttributes = "";
		$this->store_name->EditValue = $this->store_name->CurrentValue;
		$this->store_name->PlaceHolder = ew_RemoveHtml($this->store_name->FldCaption());

		// merchandizer
		$this->merchandizer->EditAttrs["class"] = "form-control";
		$this->merchandizer->EditCustomAttributes = "";
		$this->merchandizer->EditValue = $this->merchandizer->CurrentValue;
		$this->merchandizer->PlaceHolder = ew_RemoveHtml($this->merchandizer->FldCaption());

		// category
		$this->category->EditAttrs["class"] = "form-control";
		$this->category->EditCustomAttributes = "";
		$this->category->EditValue = $this->category->CurrentValue;
		$this->category->PlaceHolder = ew_RemoveHtml($this->category->FldCaption());

		// product
		$this->product->EditAttrs["class"] = "form-control";
		$this->product->EditCustomAttributes = "";
		$this->product->EditValue = $this->product->CurrentValue;
		$this->product->PlaceHolder = ew_RemoveHtml($this->product->FldCaption());

		// quantity
		$this->quantity->EditAttrs["class"] = "form-control";
		$this->quantity->EditCustomAttributes = "";
		$this->quantity->EditValue = $this->quantity->CurrentValue;
		$this->quantity->PlaceHolder = ew_RemoveHtml($this->quantity->FldCaption());

		// amount
		$this->amount->EditAttrs["class"] = "form-control";
		$this->amount->EditCustomAttributes = "";
		$this->amount->EditValue = $this->amount->CurrentValue;
		$this->amount->PlaceHolder = ew_RemoveHtml($this->amount->FldCaption());
		if (strval($this->amount->EditValue) <> "" && is_numeric($this->amount->EditValue)) $this->amount->EditValue = ew_FormatNumber($this->amount->EditValue, -2, -2, -2, -1);

		// monthly_target
		$this->monthly_target->EditAttrs["class"] = "form-control";
		$this->monthly_target->EditCustomAttributes = "";
		$this->monthly_target->EditValue = $this->monthly_target->CurrentValue;
		$this->monthly_target->PlaceHolder = ew_RemoveHtml($this->monthly_target->FldCaption());

		// month
		$this->month->EditAttrs["class"] = "form-control";
		$this->month->EditCustomAttributes = "";
		$this->month->EditValue = $this->month->CurrentValue;
		$this->month->PlaceHolder = ew_RemoveHtml($this->month->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
			if (is_numeric($this->quantity->CurrentValue))
				$this->quantity->Total += $this->quantity->CurrentValue; // Accumulate total
			if (is_numeric($this->amount->CurrentValue))
				$this->amount->Total += $this->amount->CurrentValue; // Accumulate total
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
			$this->quantity->CurrentValue = $this->quantity->Total;
			$this->quantity->ViewValue = $this->quantity->CurrentValue;
			$this->quantity->ViewCustomAttributes = "";
			$this->quantity->HrefValue = ""; // Clear href value
			$this->amount->CurrentValue = $this->amount->Total;
			$this->amount->ViewValue = $this->amount->CurrentValue;
			$this->amount->ViewValue = ew_FormatNumber($this->amount->ViewValue, 2, -2, -2, -1);
			$this->amount->ViewCustomAttributes = "";
			$this->amount->HrefValue = ""; // Clear href value

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
					if ($this->transaction_date->Exportable) $Doc->ExportCaption($this->transaction_date);
					if ($this->State->Exportable) $Doc->ExportCaption($this->State);
					if ($this->store_name->Exportable) $Doc->ExportCaption($this->store_name);
					if ($this->merchandizer->Exportable) $Doc->ExportCaption($this->merchandizer);
					if ($this->category->Exportable) $Doc->ExportCaption($this->category);
					if ($this->product->Exportable) $Doc->ExportCaption($this->product);
					if ($this->quantity->Exportable) $Doc->ExportCaption($this->quantity);
					if ($this->amount->Exportable) $Doc->ExportCaption($this->amount);
				} else {
					if ($this->sn->Exportable) $Doc->ExportCaption($this->sn);
					if ($this->transaction_date->Exportable) $Doc->ExportCaption($this->transaction_date);
					if ($this->State->Exportable) $Doc->ExportCaption($this->State);
					if ($this->store_name->Exportable) $Doc->ExportCaption($this->store_name);
					if ($this->merchandizer->Exportable) $Doc->ExportCaption($this->merchandizer);
					if ($this->category->Exportable) $Doc->ExportCaption($this->category);
					if ($this->product->Exportable) $Doc->ExportCaption($this->product);
					if ($this->quantity->Exportable) $Doc->ExportCaption($this->quantity);
					if ($this->amount->Exportable) $Doc->ExportCaption($this->amount);
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
				$this->AggregateListRowValues(); // Aggregate row values

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->sn->Exportable) $Doc->ExportField($this->sn);
						if ($this->transaction_date->Exportable) $Doc->ExportField($this->transaction_date);
						if ($this->State->Exportable) $Doc->ExportField($this->State);
						if ($this->store_name->Exportable) $Doc->ExportField($this->store_name);
						if ($this->merchandizer->Exportable) $Doc->ExportField($this->merchandizer);
						if ($this->category->Exportable) $Doc->ExportField($this->category);
						if ($this->product->Exportable) $Doc->ExportField($this->product);
						if ($this->quantity->Exportable) $Doc->ExportField($this->quantity);
						if ($this->amount->Exportable) $Doc->ExportField($this->amount);
					} else {
						if ($this->sn->Exportable) $Doc->ExportField($this->sn);
						if ($this->transaction_date->Exportable) $Doc->ExportField($this->transaction_date);
						if ($this->State->Exportable) $Doc->ExportField($this->State);
						if ($this->store_name->Exportable) $Doc->ExportField($this->store_name);
						if ($this->merchandizer->Exportable) $Doc->ExportField($this->merchandizer);
						if ($this->category->Exportable) $Doc->ExportField($this->category);
						if ($this->product->Exportable) $Doc->ExportField($this->product);
						if ($this->quantity->Exportable) $Doc->ExportField($this->quantity);
						if ($this->amount->Exportable) $Doc->ExportField($this->amount);
					}
					$Doc->EndExportRow($RowCnt);
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}

		// Export aggregates (horizontal format only)
		if ($Doc->Horizontal) {
			$this->RowType = EW_ROWTYPE_AGGREGATE;
			$this->ResetAttrs();
			$this->AggregateListRow();
			if (!$Doc->ExportCustom) {
				$Doc->BeginExportRow(-1);
				if ($this->sn->Exportable) $Doc->ExportAggregate($this->sn, '');
				if ($this->transaction_date->Exportable) $Doc->ExportAggregate($this->transaction_date, '');
				if ($this->State->Exportable) $Doc->ExportAggregate($this->State, '');
				if ($this->store_name->Exportable) $Doc->ExportAggregate($this->store_name, '');
				if ($this->merchandizer->Exportable) $Doc->ExportAggregate($this->merchandizer, '');
				if ($this->category->Exportable) $Doc->ExportAggregate($this->category, '');
				if ($this->product->Exportable) $Doc->ExportAggregate($this->product, '');
				if ($this->quantity->Exportable) $Doc->ExportAggregate($this->quantity, 'TOTAL');
				if ($this->amount->Exportable) $Doc->ExportAggregate($this->amount, 'TOTAL');
				$Doc->EndExportRow();
			}
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
