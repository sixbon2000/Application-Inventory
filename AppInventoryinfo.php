<?php

// Global variable for table object
$AppInventory = NULL;

//
// Table class for AppInventory
//
class cAppInventory extends cTable {
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;
	var $ID;
	var $Applications;
	var $Associated_Apps2F_Service;
	var $IP_Address;
	var $System_Name;
	var $Support_Team;
	var $Vendor;
	var $Url;
	var $Application_Description;
	var $Affected_Users;
	var $Status;
	var $Location;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'AppInventory';
		$this->TableName = 'AppInventory';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "[AppInventory]";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
		$this->ExportWordColumnWidth = NULL; // Cell width (PHPWord only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = TRUE; // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// ID
		$this->ID = new cField('AppInventory', 'AppInventory', 'x_ID', 'ID', '[ID]', '[ID]', 3, -1, FALSE, '[ID]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->ID->Sortable = FALSE; // Allow sort
		$this->ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ID'] = &$this->ID;

		// Applications
		$this->Applications = new cField('AppInventory', 'AppInventory', 'x_Applications', 'Applications', '[Applications]', '[Applications]', 202, -1, FALSE, '[Applications]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Applications->Sortable = TRUE; // Allow sort
		$this->fields['Applications'] = &$this->Applications;

		// Associated Apps/ Service
		$this->Associated_Apps2F_Service = new cField('AppInventory', 'AppInventory', 'x_Associated_Apps2F_Service', 'Associated Apps/ Service', '[Associated Apps/ Service]', '[Associated Apps/ Service]', 202, -1, FALSE, '[Associated Apps/ Service]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Associated_Apps2F_Service->Sortable = TRUE; // Allow sort
		$this->fields['Associated Apps/ Service'] = &$this->Associated_Apps2F_Service;

		// IP Address
		$this->IP_Address = new cField('AppInventory', 'AppInventory', 'x_IP_Address', 'IP Address', '[IP Address]', '[IP Address]', 202, -1, FALSE, '[IP Address]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->IP_Address->Sortable = TRUE; // Allow sort
		$this->fields['IP Address'] = &$this->IP_Address;

		// System Name
		$this->System_Name = new cField('AppInventory', 'AppInventory', 'x_System_Name', 'System Name', '[System Name]', '[System Name]', 202, -1, FALSE, '[System Name]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->System_Name->Sortable = TRUE; // Allow sort
		$this->fields['System Name'] = &$this->System_Name;

		// Support Team
		$this->Support_Team = new cField('AppInventory', 'AppInventory', 'x_Support_Team', 'Support Team', '[Support Team]', '[Support Team]', 202, -1, FALSE, '[EV__Support_Team]', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'SELECT');
		$this->Support_Team->Sortable = TRUE; // Allow sort
		$this->Support_Team->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->Support_Team->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['Support Team'] = &$this->Support_Team;

		// Vendor
		$this->Vendor = new cField('AppInventory', 'AppInventory', 'x_Vendor', 'Vendor', '[Vendor]', '[Vendor]', 202, -1, FALSE, '[Vendor]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->Vendor->Sortable = TRUE; // Allow sort
		$this->Vendor->OptionCount = 2;
		$this->fields['Vendor'] = &$this->Vendor;

		// Url
		$this->Url = new cField('AppInventory', 'AppInventory', 'x_Url', 'Url', '[Url]', '[Url]', 203, -1, FALSE, '[Url]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->Url->Sortable = TRUE; // Allow sort
		$this->fields['Url'] = &$this->Url;

		// Application Description
		$this->Application_Description = new cField('AppInventory', 'AppInventory', 'x_Application_Description', 'Application Description', '[Application Description]', '[Application Description]', 202, -1, FALSE, '[Application Description]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Application_Description->Sortable = TRUE; // Allow sort
		$this->fields['Application Description'] = &$this->Application_Description;

		// Affected Users
		$this->Affected_Users = new cField('AppInventory', 'AppInventory', 'x_Affected_Users', 'Affected Users', '[Affected Users]', '[Affected Users]', 202, -1, FALSE, '[Affected Users]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Affected_Users->Sortable = TRUE; // Allow sort
		$this->fields['Affected Users'] = &$this->Affected_Users;

		// Status
		$this->Status = new cField('AppInventory', 'AppInventory', 'x_Status', 'Status', '[Status]', '[Status]', 202, -1, FALSE, '[Status]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->Status->Sortable = TRUE; // Allow sort
		$this->Status->OptionCount = 2;
		$this->fields['Status'] = &$this->Status;

		// Location
		$this->Location = new cField('AppInventory', 'AppInventory', 'x_Location', 'Location', '[Location]', '[Location]', 202, -1, FALSE, '[Location]', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Location->Sortable = TRUE; // Allow sort
		$this->fields['Location'] = &$this->Location;
	}

	// Field Visibility
	function GetFieldVisibility($fldparm) {
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
			$this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace($match[1], $match[1] + "-offset", $class);
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "[AppInventory]";
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
	var $_SqlSelectList = "";

	function getSqlSelectList() { // Select for List page
		$select = "";
		$select = "SELECT * FROM (" .
			"SELECT *, (SELECT TOP 1 [Support Team] FROM [Support Teams] [EW_TMP_LOOKUPTABLE] WHERE [EW_TMP_LOOKUPTABLE].[Support Team] = [AppInventory].[Support Team]) AS [EV__Support_Team] FROM [AppInventory]" .
			") [EW_TMP_TABLE]";
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
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "[Applications] ASC";
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
		$filter = $this->CurrentFilter;
		$filter = $this->ApplyUserIDFilters($filter);
		$sort = $this->getSessionOrderBy();
		return $this->GetSQL($filter, $sort);
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
		if ($this->Support_Team->AdvancedSearch->SearchValue <> "" ||
			$this->Support_Team->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->Support_Team->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->Support_Team->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		return FALSE;
	}

	// Try to get record count
	function TryGetRecordCount($sql) {
		$cnt = -1;
		$pattern = "/^SELECT \* FROM/i";
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match($pattern, $sql)) {
			$sql = "SELECT COUNT(*) FROM" . preg_replace($pattern, "", $sql);
		} else {
			$sql = "SELECT COUNT(*) FROM (" . $sql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($filter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $filter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : "SELECT * FROM " . $this->getSqlFrom();
		$groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
		$having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
		$sql = ew_BuildSelectSql($select, $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sql);
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
		$filter = $this->getSessionWhere();
		ew_AddFilter($filter, $this->CurrentFilter);
		$filter = $this->ApplyUserIDFilters($filter);
		$this->Recordset_Selecting($filter);
		$select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : "SELECT * FROM " . $this->getSqlFrom();
		$groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
		$having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
		if ($this->UseVirtualFields())
			$sql = ew_BuildSelectSql($this->getSqlSelectList(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
		else
			$sql = ew_BuildSelectSql($select, $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
		$cnt = $this->TryGetRecordCount($sql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sql)) {
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
		$names = preg_replace('/,+$/', "", $names);
		$values = preg_replace('/,+$/', "", $values);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {

			// Get insert id if necessary
			$this->ID->setDbValue($conn->Insert_ID());
			$rs['ID'] = $this->ID->DbValue;
			if ($this->AuditTrailOnAdd)
				$this->WriteAuditTrailOnAdd($rs);
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
		$sql = preg_replace('/,+$/', "", $sql);
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
		if ($bUpdate && $this->AuditTrailOnEdit) {
			$rsaudit = $rs;
			$fldname = 'ID';
			if (!array_key_exists($fldname, $rsaudit)) $rsaudit[$fldname] = $rsold[$fldname];
			$this->WriteAuditTrailOnEdit($rsold, $rsaudit);
		}
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('ID', $rs))
				ew_AddFilter($where, ew_QuotedName('ID', $this->DBID) . '=' . ew_QuotedValue($rs['ID'], $this->ID->FldDataType, $this->DBID));
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
		if ($bDelete && $this->AuditTrailOnDelete)
			$this->WriteAuditTrailOnDelete($rs);
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "[ID] = @ID@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->ID->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->ID->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@ID@", ew_AdjustSql($this->ID->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "AppInventorylist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "AppInventoryview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "AppInventoryedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "AppInventoryadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "AppInventorylist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("AppInventoryview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("AppInventoryview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "AppInventoryadd.php?" . $this->UrlParm($parm);
		else
			$url = "AppInventoryadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("AppInventoryedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("AppInventoryadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("AppInventorydelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "ID:" . ew_VarToJson($this->ID->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->ID->CurrentValue)) {
			$sUrl .= "ID=" . urlencode($this->ID->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(141, 201, 203, 128, 204, 205))) { // Unsortable data type
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
			if ($isPost && isset($_POST["ID"]))
				$arKeys[] = $_POST["ID"];
			elseif (isset($_GET["ID"]))
				$arKeys[] = $_GET["ID"];
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
			$this->ID->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($filter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $filter;
		//$sql = $this->SQL();

		$sql = $this->GetSQL($filter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->ID->setDbValue($rs->fields('ID'));
		$this->Applications->setDbValue($rs->fields('Applications'));
		$this->Associated_Apps2F_Service->setDbValue($rs->fields('Associated Apps/ Service'));
		$this->IP_Address->setDbValue($rs->fields('IP Address'));
		$this->System_Name->setDbValue($rs->fields('System Name'));
		$this->Support_Team->setDbValue($rs->fields('Support Team'));
		$this->Vendor->setDbValue($rs->fields('Vendor'));
		$this->Url->setDbValue($rs->fields('Url'));
		$this->Application_Description->setDbValue($rs->fields('Application Description'));
		$this->Affected_Users->setDbValue($rs->fields('Affected Users'));
		$this->Status->setDbValue($rs->fields('Status'));
		$this->Location->setDbValue($rs->fields('Location'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// ID
		// Applications
		// Associated Apps/ Service
		// IP Address
		// System Name
		// Support Team
		// Vendor
		// Url
		// Application Description
		// Affected Users
		// Status
		// Location
		// ID

		$this->ID->ViewValue = $this->ID->CurrentValue;
		$this->ID->ViewCustomAttributes = "";

		// Applications
		$this->Applications->ViewValue = $this->Applications->CurrentValue;
		$this->Applications->ViewCustomAttributes = "";

		// Associated Apps/ Service
		$this->Associated_Apps2F_Service->ViewValue = $this->Associated_Apps2F_Service->CurrentValue;
		$this->Associated_Apps2F_Service->ViewCustomAttributes = "";

		// IP Address
		$this->IP_Address->ViewValue = $this->IP_Address->CurrentValue;
		$this->IP_Address->ViewCustomAttributes = "";

		// System Name
		$this->System_Name->ViewValue = $this->System_Name->CurrentValue;
		$this->System_Name->ViewCustomAttributes = "";

		// Support Team
		if ($this->Support_Team->VirtualValue <> "") {
			$this->Support_Team->ViewValue = $this->Support_Team->VirtualValue;
		} else {
		if (strval($this->Support_Team->CurrentValue) <> "") {
			$sFilterWrk = "[Support Team]" . ew_SearchString("=", $this->Support_Team->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT [Support Team], [Support Team] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [Support Teams]";
		$sWhereWrk = "";
		$this->Support_Team->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Support_Team, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [Support Team] ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Support_Team->ViewValue = $this->Support_Team->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Support_Team->ViewValue = $this->Support_Team->CurrentValue;
			}
		} else {
			$this->Support_Team->ViewValue = NULL;
		}
		}
		$this->Support_Team->ViewCustomAttributes = "";

		// Vendor
		if (strval($this->Vendor->CurrentValue) <> "") {
			$this->Vendor->ViewValue = $this->Vendor->OptionCaption($this->Vendor->CurrentValue);
		} else {
			$this->Vendor->ViewValue = NULL;
		}
		$this->Vendor->ViewCustomAttributes = "";

		// Url
		$this->Url->ViewValue = $this->Url->CurrentValue;
		$this->Url->CssStyle = "font-style: italic;";
		$this->Url->ViewCustomAttributes = "";

		// Application Description
		$this->Application_Description->ViewValue = $this->Application_Description->CurrentValue;
		$this->Application_Description->ViewCustomAttributes = "";

		// Affected Users
		$this->Affected_Users->ViewValue = $this->Affected_Users->CurrentValue;
		$this->Affected_Users->ViewCustomAttributes = "";

		// Status
		if (strval($this->Status->CurrentValue) <> "") {
			$this->Status->ViewValue = $this->Status->OptionCaption($this->Status->CurrentValue);
		} else {
			$this->Status->ViewValue = NULL;
		}
		$this->Status->ViewCustomAttributes = "";

		// Location
		$this->Location->ViewValue = $this->Location->CurrentValue;
		$this->Location->ViewCustomAttributes = "";

		// ID
		$this->ID->LinkCustomAttributes = "";
		$this->ID->HrefValue = "";
		$this->ID->TooltipValue = "";

		// Applications
		$this->Applications->LinkCustomAttributes = "";
		$this->Applications->HrefValue = "";
		$this->Applications->TooltipValue = "";

		// Associated Apps/ Service
		$this->Associated_Apps2F_Service->LinkCustomAttributes = "";
		$this->Associated_Apps2F_Service->HrefValue = "";
		$this->Associated_Apps2F_Service->TooltipValue = "";

		// IP Address
		$this->IP_Address->LinkCustomAttributes = "";
		$this->IP_Address->HrefValue = "";
		$this->IP_Address->TooltipValue = "";

		// System Name
		$this->System_Name->LinkCustomAttributes = "";
		$this->System_Name->HrefValue = "";
		$this->System_Name->TooltipValue = "";

		// Support Team
		$this->Support_Team->LinkCustomAttributes = "";
		$this->Support_Team->HrefValue = "";
		$this->Support_Team->TooltipValue = "";

		// Vendor
		$this->Vendor->LinkCustomAttributes = "";
		$this->Vendor->HrefValue = "";
		$this->Vendor->TooltipValue = "";

		// Url
		$this->Url->LinkCustomAttributes = "";
		if (!ew_Empty($this->Url->CurrentValue)) {
			$this->Url->HrefValue = ((!empty($this->Url->ViewValue) && !is_array($this->Url->ViewValue)) ? ew_RemoveHtml($this->Url->ViewValue) : $this->Url->CurrentValue); // Add prefix/suffix
			$this->Url->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->Url->HrefValue = ew_FullUrl($this->Url->HrefValue, "href");
		} else {
			$this->Url->HrefValue = "";
		}
		$this->Url->TooltipValue = "";

		// Application Description
		$this->Application_Description->LinkCustomAttributes = "";
		$this->Application_Description->HrefValue = "";
		$this->Application_Description->TooltipValue = "";

		// Affected Users
		$this->Affected_Users->LinkCustomAttributes = "";
		$this->Affected_Users->HrefValue = "";
		$this->Affected_Users->TooltipValue = "";

		// Status
		$this->Status->LinkCustomAttributes = "";
		$this->Status->HrefValue = "";
		$this->Status->TooltipValue = "";

		// Location
		$this->Location->LinkCustomAttributes = "";
		$this->Location->HrefValue = "";
		$this->Location->TooltipValue = "";

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

		// ID
		$this->ID->EditAttrs["class"] = "form-control";
		$this->ID->EditCustomAttributes = "";
		$this->ID->EditValue = $this->ID->CurrentValue;
		$this->ID->ViewCustomAttributes = "";

		// Applications
		$this->Applications->EditAttrs["class"] = "form-control";
		$this->Applications->EditCustomAttributes = "";
		$this->Applications->EditValue = $this->Applications->CurrentValue;
		$this->Applications->PlaceHolder = ew_RemoveHtml($this->Applications->FldCaption());

		// Associated Apps/ Service
		$this->Associated_Apps2F_Service->EditAttrs["class"] = "form-control";
		$this->Associated_Apps2F_Service->EditCustomAttributes = "";
		$this->Associated_Apps2F_Service->EditValue = $this->Associated_Apps2F_Service->CurrentValue;
		$this->Associated_Apps2F_Service->PlaceHolder = ew_RemoveHtml($this->Associated_Apps2F_Service->FldCaption());

		// IP Address
		$this->IP_Address->EditAttrs["class"] = "form-control";
		$this->IP_Address->EditCustomAttributes = "";
		$this->IP_Address->EditValue = $this->IP_Address->CurrentValue;
		$this->IP_Address->PlaceHolder = ew_RemoveHtml($this->IP_Address->FldCaption());

		// System Name
		$this->System_Name->EditAttrs["class"] = "form-control";
		$this->System_Name->EditCustomAttributes = "";
		$this->System_Name->EditValue = $this->System_Name->CurrentValue;
		$this->System_Name->PlaceHolder = ew_RemoveHtml($this->System_Name->FldCaption());

		// Support Team
		$this->Support_Team->EditCustomAttributes = "";

		// Vendor
		$this->Vendor->EditCustomAttributes = "";
		$this->Vendor->EditValue = $this->Vendor->Options(FALSE);

		// Url
		$this->Url->EditAttrs["class"] = "form-control";
		$this->Url->EditCustomAttributes = "";
		$this->Url->EditValue = $this->Url->CurrentValue;
		$this->Url->PlaceHolder = ew_RemoveHtml($this->Url->FldCaption());

		// Application Description
		$this->Application_Description->EditAttrs["class"] = "form-control";
		$this->Application_Description->EditCustomAttributes = "";
		$this->Application_Description->EditValue = $this->Application_Description->CurrentValue;
		$this->Application_Description->PlaceHolder = ew_RemoveHtml($this->Application_Description->FldCaption());

		// Affected Users
		$this->Affected_Users->EditAttrs["class"] = "form-control";
		$this->Affected_Users->EditCustomAttributes = "";
		$this->Affected_Users->EditValue = $this->Affected_Users->CurrentValue;
		$this->Affected_Users->PlaceHolder = ew_RemoveHtml($this->Affected_Users->FldCaption());

		// Status
		$this->Status->EditCustomAttributes = "";
		$this->Status->EditValue = $this->Status->Options(FALSE);

		// Location
		$this->Location->EditAttrs["class"] = "form-control";
		$this->Location->EditCustomAttributes = "";
		$this->Location->EditValue = $this->Location->CurrentValue;
		$this->Location->PlaceHolder = ew_RemoveHtml($this->Location->FldCaption());

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
					if ($this->ID->Exportable) $Doc->ExportCaption($this->ID);
					if ($this->Applications->Exportable) $Doc->ExportCaption($this->Applications);
					if ($this->Associated_Apps2F_Service->Exportable) $Doc->ExportCaption($this->Associated_Apps2F_Service);
					if ($this->IP_Address->Exportable) $Doc->ExportCaption($this->IP_Address);
					if ($this->System_Name->Exportable) $Doc->ExportCaption($this->System_Name);
					if ($this->Support_Team->Exportable) $Doc->ExportCaption($this->Support_Team);
					if ($this->Vendor->Exportable) $Doc->ExportCaption($this->Vendor);
					if ($this->Url->Exportable) $Doc->ExportCaption($this->Url);
					if ($this->Application_Description->Exportable) $Doc->ExportCaption($this->Application_Description);
					if ($this->Affected_Users->Exportable) $Doc->ExportCaption($this->Affected_Users);
					if ($this->Status->Exportable) $Doc->ExportCaption($this->Status);
					if ($this->Location->Exportable) $Doc->ExportCaption($this->Location);
				} else {
					if ($this->Applications->Exportable) $Doc->ExportCaption($this->Applications);
					if ($this->Associated_Apps2F_Service->Exportable) $Doc->ExportCaption($this->Associated_Apps2F_Service);
					if ($this->IP_Address->Exportable) $Doc->ExportCaption($this->IP_Address);
					if ($this->System_Name->Exportable) $Doc->ExportCaption($this->System_Name);
					if ($this->Support_Team->Exportable) $Doc->ExportCaption($this->Support_Team);
					if ($this->Vendor->Exportable) $Doc->ExportCaption($this->Vendor);
					if ($this->Url->Exportable) $Doc->ExportCaption($this->Url);
					if ($this->Application_Description->Exportable) $Doc->ExportCaption($this->Application_Description);
					if ($this->Affected_Users->Exportable) $Doc->ExportCaption($this->Affected_Users);
					if ($this->Status->Exportable) $Doc->ExportCaption($this->Status);
					if ($this->Location->Exportable) $Doc->ExportCaption($this->Location);
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
						if ($this->ID->Exportable) $Doc->ExportField($this->ID);
						if ($this->Applications->Exportable) $Doc->ExportField($this->Applications);
						if ($this->Associated_Apps2F_Service->Exportable) $Doc->ExportField($this->Associated_Apps2F_Service);
						if ($this->IP_Address->Exportable) $Doc->ExportField($this->IP_Address);
						if ($this->System_Name->Exportable) $Doc->ExportField($this->System_Name);
						if ($this->Support_Team->Exportable) $Doc->ExportField($this->Support_Team);
						if ($this->Vendor->Exportable) $Doc->ExportField($this->Vendor);
						if ($this->Url->Exportable) $Doc->ExportField($this->Url);
						if ($this->Application_Description->Exportable) $Doc->ExportField($this->Application_Description);
						if ($this->Affected_Users->Exportable) $Doc->ExportField($this->Affected_Users);
						if ($this->Status->Exportable) $Doc->ExportField($this->Status);
						if ($this->Location->Exportable) $Doc->ExportField($this->Location);
					} else {
						if ($this->Applications->Exportable) $Doc->ExportField($this->Applications);
						if ($this->Associated_Apps2F_Service->Exportable) $Doc->ExportField($this->Associated_Apps2F_Service);
						if ($this->IP_Address->Exportable) $Doc->ExportField($this->IP_Address);
						if ($this->System_Name->Exportable) $Doc->ExportField($this->System_Name);
						if ($this->Support_Team->Exportable) $Doc->ExportField($this->Support_Team);
						if ($this->Vendor->Exportable) $Doc->ExportField($this->Vendor);
						if ($this->Url->Exportable) $Doc->ExportField($this->Url);
						if ($this->Application_Description->Exportable) $Doc->ExportField($this->Application_Description);
						if ($this->Affected_Users->Exportable) $Doc->ExportField($this->Affected_Users);
						if ($this->Status->Exportable) $Doc->ExportField($this->Status);
						if ($this->Location->Exportable) $Doc->ExportField($this->Location);
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

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'AppInventory';
		$usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 'AppInventory';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['ID'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserName();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$newvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$newvalue = $rs[$fldname];
					else
						$newvalue = "[MEMO]"; // Memo Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$newvalue = "[XML]"; // XML Field
				} else {
					$newvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $usr, "A", $table, $fldname, $key, "", $newvalue);
			}
		}
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		global $Language;
		if (!$this->AuditTrailOnEdit) return;
		$table = 'AppInventory';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['ID'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserName();
		foreach (array_keys($rsnew) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && array_key_exists($fldname, $rsold) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_DATE) { // DateTime field
					$modified = (ew_FormatDateTime($rsold[$fldname], 0) <> ew_FormatDateTime($rsnew[$fldname], 0));
				} else {
					$modified = !ew_CompareValue($rsold[$fldname], $rsnew[$fldname]);
				}
				if ($modified) {
					if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") { // Password Field
						$oldvalue = $Language->Phrase("PasswordMask");
						$newvalue = $Language->Phrase("PasswordMask");
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) { // Memo field
						if (EW_AUDIT_TRAIL_TO_DATABASE) {
							$oldvalue = $rsold[$fldname];
							$newvalue = $rsnew[$fldname];
						} else {
							$oldvalue = "[MEMO]";
							$newvalue = "[MEMO]";
						}
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) { // XML field
						$oldvalue = "[XML]";
						$newvalue = "[XML]";
					} else {
						$oldvalue = $rsold[$fldname];
						$newvalue = $rsnew[$fldname];
					}
					ew_WriteAuditTrail("log", $dt, $id, $usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
				}
			}
		}
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnDelete) return;
		$table = 'AppInventory';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['ID'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$curUser = CurrentUserName();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$oldvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$oldvalue = $rs[$fldname];
					else
						$oldvalue = "[MEMO]"; // Memo field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$oldvalue = "[XML]"; // XML field
				} else {
					$oldvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $curUser, "D", $table, $fldname, $key, $oldvalue, "");
			}
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
