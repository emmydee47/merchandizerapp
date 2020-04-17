<?php

// Global variable for table object
$transactions = NULL;

//
// Table class for transactions
//
class ctransactions extends cTable {
	var $sn;
	var $receipt_number;
	var $transaction_date;
	var $store;
	var $user;
	var $category;
	var $product;
	var $amount;
	var $quantity;
	var $status;
	var $customer;
	var $customer_address;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'transactions';
		$this->TableName = 'transactions';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`transactions`";
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
		$this->BasicSearch->TypeDefault = "OR";

		// sn
		$this->sn = new cField('transactions', 'transactions', 'x_sn', 'sn', '`sn`', '`sn`', 3, -1, FALSE, '`sn`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->sn->Sortable = TRUE; // Allow sort
		$this->sn->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['sn'] = &$this->sn;

		// receipt_number
		$this->receipt_number = new cField('transactions', 'transactions', 'x_receipt_number', 'receipt_number', '`receipt_number`', '`receipt_number`', 200, -1, FALSE, '`receipt_number`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->receipt_number->Sortable = TRUE; // Allow sort
		$this->fields['receipt_number'] = &$this->receipt_number;

		// transaction_date
		$this->transaction_date = new cField('transactions', 'transactions', 'x_transaction_date', 'transaction_date', '`transaction_date`', '`transaction_date`', 200, 9, FALSE, '`transaction_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->transaction_date->Sortable = TRUE; // Allow sort
		$this->transaction_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectDateYMD"));
		$this->fields['transaction_date'] = &$this->transaction_date;

		// store
		$this->store = new cField('transactions', 'transactions', 'x_store', 'store', '\'\'', '\'\'', 201, -1, FALSE, '`EV__store`', TRUE, TRUE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->store->FldIsCustom = TRUE; // Custom field
		$this->store->Sortable = TRUE; // Allow sort
		$this->store->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->store->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['store'] = &$this->store;

		// user
		$this->user = new cField('transactions', 'transactions', 'x_user', 'user', '`user`', '`user`', 3, -1, FALSE, '`user`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->user->Sortable = TRUE; // Allow sort
		$this->user->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->user->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->user->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['user'] = &$this->user;

		// category
		$this->category = new cField('transactions', 'transactions', 'x_category', 'category', '\'\'', '\'\'', 201, -1, FALSE, '\'\'', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->category->FldIsCustom = TRUE; // Custom field
		$this->category->Sortable = TRUE; // Allow sort
		$this->category->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->category->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['category'] = &$this->category;

		// product
		$this->product = new cField('transactions', 'transactions', 'x_product', 'product', '`product`', '`product`', 3, -1, FALSE, '`EV__product`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'SELECT');
		$this->product->Sortable = TRUE; // Allow sort
		$this->product->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->product->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['product'] = &$this->product;

		// amount
		$this->amount = new cField('transactions', 'transactions', 'x_amount', 'amount', '`amount`', '`amount`', 131, -1, FALSE, '`amount`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->amount->Sortable = TRUE; // Allow sort
		$this->amount->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['amount'] = &$this->amount;

		// quantity
		$this->quantity = new cField('transactions', 'transactions', 'x_quantity', 'quantity', '`quantity`', '`quantity`', 3, -1, FALSE, '`quantity`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->quantity->Sortable = TRUE; // Allow sort
		$this->quantity->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['quantity'] = &$this->quantity;

		// status
		$this->status = new cField('transactions', 'transactions', 'x_status', 'status', '\'\'', '\'\'', 201, -1, FALSE, '\'\'', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->status->FldIsCustom = TRUE; // Custom field
		$this->status->Sortable = TRUE; // Allow sort
		$this->status->OptionCount = 3;
		$this->fields['status'] = &$this->status;

		// customer
		$this->customer = new cField('transactions', 'transactions', 'x_customer', 'customer', '`customer`', '`customer`', 200, -1, FALSE, '`customer`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->customer->Sortable = FALSE; // Allow sort
		$this->fields['customer'] = &$this->customer;

		// customer_address
		$this->customer_address = new cField('transactions', 'transactions', 'x_customer_address', 'customer_address', '`customer_address`', '`customer_address`', 200, -1, FALSE, '`customer_address`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->customer_address->Sortable = FALSE; // Allow sort
		$this->fields['customer_address'] = &$this->customer_address;
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
			$sSortFieldList = ($ofld->FldVirtualExpression <> "") ? $ofld->FldVirtualExpression : $sSortField;
			$this->setSessionOrderByList($sSortFieldList . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Session ORDER BY for List page
	function getSessionOrderByList() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST];
	}

	function setSessionOrderByList($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST] = $v;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`transactions`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT *, '' AS `store`, '' AS `category`, '' AS `status` FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlSelectList = "";

	function getSqlSelectList() { // Select for List page
		$select = "";
		$select = "SELECT * FROM (" .
			"SELECT *, '' AS `store`, '' AS `category`, '' AS `status`, (SELECT `store_name` FROM `stores` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`store_name` = `transactions`.`store` LIMIT 1) AS `EV__store`, (SELECT CONCAT(`product_name`,'" . ew_ValueSeparator(1, $this->product) . "',`product_id`) FROM `products` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`sn` = `transactions`.`product` LIMIT 1) AS `EV__product` FROM `transactions`" .
			") `EW_TMP_TABLE`";
		return ($this->_SqlSelectList <> "") ? $this->_SqlSelectList : $select;
	}

	function SqlSelectList() { // For backward compatibility
		return $this->getSqlSelectList();
	}

	function setSqlSelectList($v) {
		$this->_SqlSelectList = $v;
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
		global $Security;

		// Add User ID filter
		if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
			$sFilter = $this->AddUserIDFilter($sFilter);
		}
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = $this->UserIDAllowSecurity;
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
		if ($this->UseVirtualFields()) {
			$sSelect = $this->getSqlSelectList();
			$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderByList() : "";
		} else {
			$sSelect = $this->getSqlSelect();
			$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderBy() : "";
		}
		return ew_BuildSelectSql($sSelect, $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = ($this->UseVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Check if virtual fields is used in SQL
	function UseVirtualFields() {
		$sWhere = $this->UseSessionForListSQL ? $this->getSessionWhere() : $this->CurrentFilter;
		$sOrderBy = $this->UseSessionForListSQL ? $this->getSessionOrderByList() : "";
		if ($sWhere <> "")
			$sWhere = " " . str_replace(array("(",")"), array("",""), $sWhere) . " ";
		if ($sOrderBy <> "")
			$sOrderBy = " " . str_replace(array("(",")"), array("",""), $sOrderBy) . " ";
		if ($this->BasicSearch->getKeyword() <> "")
			return TRUE;
		if (strpos($sOrderBy, " " . $this->store->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->product->AdvancedSearch->SearchValue <> "" ||
			$this->product->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->product->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->product->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		return FALSE;
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
			return "transactionslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "transactionsview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "transactionsedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "transactionsadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "transactionslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("transactionsview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("transactionsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "transactionsadd.php?" . $this->UrlParm($parm);
		else
			$url = "transactionsadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("transactionsedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("transactionsadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("transactionsdelete.php", $this->UrlParm());
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
		$this->receipt_number->setDbValue($rs->fields('receipt_number'));
		$this->transaction_date->setDbValue($rs->fields('transaction_date'));
		$this->store->setDbValue($rs->fields('store'));
		$this->user->setDbValue($rs->fields('user'));
		$this->category->setDbValue($rs->fields('category'));
		$this->product->setDbValue($rs->fields('product'));
		$this->amount->setDbValue($rs->fields('amount'));
		$this->quantity->setDbValue($rs->fields('quantity'));
		$this->status->setDbValue($rs->fields('status'));
		$this->customer->setDbValue($rs->fields('customer'));
		$this->customer_address->setDbValue($rs->fields('customer_address'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
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

		$this->customer->CellCssStyle = "white-space: nowrap;";

		// customer_address
		$this->customer_address->CellCssStyle = "white-space: nowrap;";

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

		// customer
		$this->customer->ViewValue = $this->customer->CurrentValue;
		$this->customer->ViewCustomAttributes = "";

		// customer_address
		$this->customer_address->ViewValue = $this->customer_address->CurrentValue;
		$this->customer_address->ViewCustomAttributes = "";

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

		// customer
		$this->customer->LinkCustomAttributes = "";
		$this->customer->HrefValue = "";
		$this->customer->TooltipValue = "";

		// customer_address
		$this->customer_address->LinkCustomAttributes = "";
		$this->customer_address->HrefValue = "";
		$this->customer_address->TooltipValue = "";

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

		// receipt_number
		$this->receipt_number->EditAttrs["class"] = "form-control";
		$this->receipt_number->EditCustomAttributes = "";
		$this->receipt_number->EditValue = $this->receipt_number->CurrentValue;
		$this->receipt_number->PlaceHolder = ew_RemoveHtml($this->receipt_number->FldCaption());

		// transaction_date
		// store

		$this->store->EditAttrs["class"] = "form-control";
		$this->store->EditCustomAttributes = "";

		// user
		// category

		$this->category->EditAttrs["class"] = "form-control";
		$this->category->EditCustomAttributes = "";

		// product
		$this->product->EditAttrs["class"] = "form-control";
		$this->product->EditCustomAttributes = "";

		// amount
		$this->amount->EditAttrs["class"] = "form-control";
		$this->amount->EditCustomAttributes = "";
		$this->amount->EditValue = $this->amount->CurrentValue;
		$this->amount->PlaceHolder = ew_RemoveHtml($this->amount->FldCaption());
		if (strval($this->amount->EditValue) <> "" && is_numeric($this->amount->EditValue)) $this->amount->EditValue = ew_FormatNumber($this->amount->EditValue, -2, -2, -2, -1);

		// quantity
		$this->quantity->EditAttrs["class"] = "form-control";
		$this->quantity->EditCustomAttributes = "";
		$this->quantity->EditValue = $this->quantity->CurrentValue;
		$this->quantity->PlaceHolder = ew_RemoveHtml($this->quantity->FldCaption());

		// status
		$this->status->EditCustomAttributes = "";
		$this->status->EditValue = $this->status->Options(FALSE);

		// customer
		$this->customer->EditAttrs["class"] = "form-control";
		$this->customer->EditCustomAttributes = "";
		$this->customer->EditValue = $this->customer->CurrentValue;
		$this->customer->PlaceHolder = ew_RemoveHtml($this->customer->FldCaption());

		// customer_address
		$this->customer_address->EditAttrs["class"] = "form-control";
		$this->customer_address->EditCustomAttributes = "";
		$this->customer_address->EditValue = $this->customer_address->CurrentValue;
		$this->customer_address->PlaceHolder = ew_RemoveHtml($this->customer_address->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
			if (is_numeric($this->amount->CurrentValue))
				$this->amount->Total += $this->amount->CurrentValue; // Accumulate total
			if (is_numeric($this->quantity->CurrentValue))
				$this->quantity->Total += $this->quantity->CurrentValue; // Accumulate total
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
			$this->amount->CurrentValue = $this->amount->Total;
			$this->amount->ViewValue = $this->amount->CurrentValue;
			$this->amount->ViewValue = ew_FormatNumber($this->amount->ViewValue, 2, -2, -2, -1);
			$this->amount->ViewCustomAttributes = "";
			$this->amount->HrefValue = ""; // Clear href value
			$this->quantity->CurrentValue = $this->quantity->Total;
			$this->quantity->ViewValue = $this->quantity->CurrentValue;
			$this->quantity->ViewCustomAttributes = "";
			$this->quantity->HrefValue = ""; // Clear href value

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
					if ($this->receipt_number->Exportable) $Doc->ExportCaption($this->receipt_number);
					if ($this->transaction_date->Exportable) $Doc->ExportCaption($this->transaction_date);
					if ($this->store->Exportable) $Doc->ExportCaption($this->store);
					if ($this->user->Exportable) $Doc->ExportCaption($this->user);
					if ($this->category->Exportable) $Doc->ExportCaption($this->category);
					if ($this->product->Exportable) $Doc->ExportCaption($this->product);
					if ($this->amount->Exportable) $Doc->ExportCaption($this->amount);
					if ($this->quantity->Exportable) $Doc->ExportCaption($this->quantity);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
				} else {
					if ($this->sn->Exportable) $Doc->ExportCaption($this->sn);
					if ($this->receipt_number->Exportable) $Doc->ExportCaption($this->receipt_number);
					if ($this->transaction_date->Exportable) $Doc->ExportCaption($this->transaction_date);
					if ($this->store->Exportable) $Doc->ExportCaption($this->store);
					if ($this->user->Exportable) $Doc->ExportCaption($this->user);
					if ($this->category->Exportable) $Doc->ExportCaption($this->category);
					if ($this->product->Exportable) $Doc->ExportCaption($this->product);
					if ($this->amount->Exportable) $Doc->ExportCaption($this->amount);
					if ($this->quantity->Exportable) $Doc->ExportCaption($this->quantity);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
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
						if ($this->receipt_number->Exportable) $Doc->ExportField($this->receipt_number);
						if ($this->transaction_date->Exportable) $Doc->ExportField($this->transaction_date);
						if ($this->store->Exportable) $Doc->ExportField($this->store);
						if ($this->user->Exportable) $Doc->ExportField($this->user);
						if ($this->category->Exportable) $Doc->ExportField($this->category);
						if ($this->product->Exportable) $Doc->ExportField($this->product);
						if ($this->amount->Exportable) $Doc->ExportField($this->amount);
						if ($this->quantity->Exportable) $Doc->ExportField($this->quantity);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
					} else {
						if ($this->sn->Exportable) $Doc->ExportField($this->sn);
						if ($this->receipt_number->Exportable) $Doc->ExportField($this->receipt_number);
						if ($this->transaction_date->Exportable) $Doc->ExportField($this->transaction_date);
						if ($this->store->Exportable) $Doc->ExportField($this->store);
						if ($this->user->Exportable) $Doc->ExportField($this->user);
						if ($this->category->Exportable) $Doc->ExportField($this->category);
						if ($this->product->Exportable) $Doc->ExportField($this->product);
						if ($this->amount->Exportable) $Doc->ExportField($this->amount);
						if ($this->quantity->Exportable) $Doc->ExportField($this->quantity);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
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
				if ($this->receipt_number->Exportable) $Doc->ExportAggregate($this->receipt_number, '');
				if ($this->transaction_date->Exportable) $Doc->ExportAggregate($this->transaction_date, '');
				if ($this->store->Exportable) $Doc->ExportAggregate($this->store, '');
				if ($this->user->Exportable) $Doc->ExportAggregate($this->user, '');
				if ($this->category->Exportable) $Doc->ExportAggregate($this->category, '');
				if ($this->product->Exportable) $Doc->ExportAggregate($this->product, '');
				if ($this->amount->Exportable) $Doc->ExportAggregate($this->amount, 'TOTAL');
				if ($this->quantity->Exportable) $Doc->ExportAggregate($this->quantity, 'TOTAL');
				if ($this->status->Exportable) $Doc->ExportAggregate($this->status, '');
				$Doc->EndExportRow();
			}
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Add User ID filter
	function AddUserIDFilter($sFilter) {
		global $Security;
		$sFilterWrk = "";
		$id = (CurrentPageID() == "list") ? $this->CurrentAction : CurrentPageID();
		if (!$this->UserIDAllow($id) && !$Security->IsAdmin()) {
			$sFilterWrk = $Security->UserIDList();
			if ($sFilterWrk <> "")
				$sFilterWrk = '`user` IN (' . $sFilterWrk . ')';
		}

		// Call User ID Filtering event
		$this->UserID_Filtering($sFilterWrk);
		ew_AddFilter($sFilter, $sFilterWrk);
		return $sFilter;
	}

	// User ID subquery
	function GetUserIDSubquery(&$fld, &$masterfld) {
		global $UserTableConn;
		$sWrk = "";
		$sSql = "SELECT " . $masterfld->FldExpression . " FROM `transactions`";
		$sFilter = $this->AddUserIDFilter("");
		if ($sFilter <> "") $sSql .= " WHERE " . $sFilter;

		// Use subquery
		if (EW_USE_SUBQUERY_FOR_MASTER_USER_ID) {
			$sWrk = $sSql;
		} else {

			// List all values
			if ($rs = $UserTableConn->Execute($sSql)) {
				while (!$rs->EOF) {
					if ($sWrk <> "") $sWrk .= ",";
					$sWrk .= ew_QuotedValue($rs->fields[0], $masterfld->FldDataType, EW_USER_TABLE_DBID);
					$rs->MoveNext();
				}
				$rs->Close();
			}
		}
		if ($sWrk <> "") {
			$sWrk = $fld->FldExpression . " IN (" . $sWrk . ")";
		}
		return $sWrk;
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
		 if(CurrentUserLevel()==0){
		$userid = CurrentUserID();
		ew_AddFilter($filter, "user = ".$userid);
		}
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
