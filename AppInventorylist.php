<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "AppInventoryinfo.php" ?>
<?php include_once "Usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$AppInventory_list = NULL; // Initialize page object first

class cAppInventory_list extends cAppInventory {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{F928ACD0-3C23-4A28-A63B-8FA9605A2019}';

	// Table name
	var $TableName = 'AppInventory';

	// Page object name
	var $PageObjName = 'AppInventory_list';

	// Grid form hidden field names
	var $FormName = 'fAppInventorylist';
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
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;

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

		// Table object (AppInventory)
		if (!isset($GLOBALS["AppInventory"]) || get_class($GLOBALS["AppInventory"]) == "cAppInventory") {
			$GLOBALS["AppInventory"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["AppInventory"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "AppInventoryadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "AppInventorydelete.php";
		$this->MultiUpdateUrl = "AppInventoryupdate.php";

		// Table object (Users)
		if (!isset($GLOBALS['Users'])) $GLOBALS['Users'] = new cUsers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'AppInventory', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"]))
			$GLOBALS["gTimer"] = new cTimer();

		// Debug message
		ew_LoadDebugMsg();

		// Open connection
		if (!isset($conn))
			$conn = ew_Connect($this->DBID);

		// User table object (Users)
		if (!isset($UserTable)) {
			$UserTable = new cUsers();
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
		$this->FilterOptions->TagClassName = "ewFilterOption fAppInventorylistsrch";

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
		if (IsPasswordExpired())
			$this->Page_Terminate(ew_GetUrl("changepwd.php"));
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 
		// Update last accessed time

		if ($UserProfile->IsValidUser(CurrentUserName(), session_id())) {
		} else {
			echo $Language->Phrase("UserProfileCorrupted");
		}

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
		$this->Applications->SetVisibility();
		$this->Associated_Apps2F_Service->SetVisibility();
		$this->IP_Address->SetVisibility();
		$this->System_Name->SetVisibility();
		$this->Support_Team->SetVisibility();
		$this->Vendor->SetVisibility();
		$this->Affected_Users->SetVisibility();
		$this->Status->SetVisibility();
		$this->Location->SetVisibility();

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
		global $EW_EXPORT, $AppInventory;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($AppInventory);
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
	var $DisplayRecs = 10;
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
			$this->DisplayRecs = 10; // Load default
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
					$this->DisplayRecs = 10; // Non-numeric, load default
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
			$this->ID->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->ID->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Initialize
		$sFilterList = "";
		$sSavedFilterList = "";

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server" && isset($UserProfile))
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fAppInventorylistsrch");
		$sFilterList = ew_Concat($sFilterList, $this->ID->AdvancedSearch->ToJson(), ","); // Field ID
		$sFilterList = ew_Concat($sFilterList, $this->Applications->AdvancedSearch->ToJson(), ","); // Field Applications
		$sFilterList = ew_Concat($sFilterList, $this->Associated_Apps2F_Service->AdvancedSearch->ToJson(), ","); // Field Associated Apps/ Service
		$sFilterList = ew_Concat($sFilterList, $this->IP_Address->AdvancedSearch->ToJson(), ","); // Field IP Address
		$sFilterList = ew_Concat($sFilterList, $this->System_Name->AdvancedSearch->ToJson(), ","); // Field System Name
		$sFilterList = ew_Concat($sFilterList, $this->Support_Team->AdvancedSearch->ToJson(), ","); // Field Support Team
		$sFilterList = ew_Concat($sFilterList, $this->Vendor->AdvancedSearch->ToJson(), ","); // Field Vendor
		$sFilterList = ew_Concat($sFilterList, $this->Url->AdvancedSearch->ToJson(), ","); // Field Url
		$sFilterList = ew_Concat($sFilterList, $this->Application_Description->AdvancedSearch->ToJson(), ","); // Field Application Description
		$sFilterList = ew_Concat($sFilterList, $this->Affected_Users->AdvancedSearch->ToJson(), ","); // Field Affected Users
		$sFilterList = ew_Concat($sFilterList, $this->Status->AdvancedSearch->ToJson(), ","); // Field Status
		$sFilterList = ew_Concat($sFilterList, $this->Location->AdvancedSearch->ToJson(), ","); // Field Location
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fAppInventorylistsrch", $filters);

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

		// Field ID
		$this->ID->AdvancedSearch->SearchValue = @$filter["x_ID"];
		$this->ID->AdvancedSearch->SearchOperator = @$filter["z_ID"];
		$this->ID->AdvancedSearch->SearchCondition = @$filter["v_ID"];
		$this->ID->AdvancedSearch->SearchValue2 = @$filter["y_ID"];
		$this->ID->AdvancedSearch->SearchOperator2 = @$filter["w_ID"];
		$this->ID->AdvancedSearch->Save();

		// Field Applications
		$this->Applications->AdvancedSearch->SearchValue = @$filter["x_Applications"];
		$this->Applications->AdvancedSearch->SearchOperator = @$filter["z_Applications"];
		$this->Applications->AdvancedSearch->SearchCondition = @$filter["v_Applications"];
		$this->Applications->AdvancedSearch->SearchValue2 = @$filter["y_Applications"];
		$this->Applications->AdvancedSearch->SearchOperator2 = @$filter["w_Applications"];
		$this->Applications->AdvancedSearch->Save();

		// Field Associated Apps/ Service
		$this->Associated_Apps2F_Service->AdvancedSearch->SearchValue = @$filter["x_Associated_Apps2F_Service"];
		$this->Associated_Apps2F_Service->AdvancedSearch->SearchOperator = @$filter["z_Associated_Apps2F_Service"];
		$this->Associated_Apps2F_Service->AdvancedSearch->SearchCondition = @$filter["v_Associated_Apps2F_Service"];
		$this->Associated_Apps2F_Service->AdvancedSearch->SearchValue2 = @$filter["y_Associated_Apps2F_Service"];
		$this->Associated_Apps2F_Service->AdvancedSearch->SearchOperator2 = @$filter["w_Associated_Apps2F_Service"];
		$this->Associated_Apps2F_Service->AdvancedSearch->Save();

		// Field IP Address
		$this->IP_Address->AdvancedSearch->SearchValue = @$filter["x_IP_Address"];
		$this->IP_Address->AdvancedSearch->SearchOperator = @$filter["z_IP_Address"];
		$this->IP_Address->AdvancedSearch->SearchCondition = @$filter["v_IP_Address"];
		$this->IP_Address->AdvancedSearch->SearchValue2 = @$filter["y_IP_Address"];
		$this->IP_Address->AdvancedSearch->SearchOperator2 = @$filter["w_IP_Address"];
		$this->IP_Address->AdvancedSearch->Save();

		// Field System Name
		$this->System_Name->AdvancedSearch->SearchValue = @$filter["x_System_Name"];
		$this->System_Name->AdvancedSearch->SearchOperator = @$filter["z_System_Name"];
		$this->System_Name->AdvancedSearch->SearchCondition = @$filter["v_System_Name"];
		$this->System_Name->AdvancedSearch->SearchValue2 = @$filter["y_System_Name"];
		$this->System_Name->AdvancedSearch->SearchOperator2 = @$filter["w_System_Name"];
		$this->System_Name->AdvancedSearch->Save();

		// Field Support Team
		$this->Support_Team->AdvancedSearch->SearchValue = @$filter["x_Support_Team"];
		$this->Support_Team->AdvancedSearch->SearchOperator = @$filter["z_Support_Team"];
		$this->Support_Team->AdvancedSearch->SearchCondition = @$filter["v_Support_Team"];
		$this->Support_Team->AdvancedSearch->SearchValue2 = @$filter["y_Support_Team"];
		$this->Support_Team->AdvancedSearch->SearchOperator2 = @$filter["w_Support_Team"];
		$this->Support_Team->AdvancedSearch->Save();

		// Field Vendor
		$this->Vendor->AdvancedSearch->SearchValue = @$filter["x_Vendor"];
		$this->Vendor->AdvancedSearch->SearchOperator = @$filter["z_Vendor"];
		$this->Vendor->AdvancedSearch->SearchCondition = @$filter["v_Vendor"];
		$this->Vendor->AdvancedSearch->SearchValue2 = @$filter["y_Vendor"];
		$this->Vendor->AdvancedSearch->SearchOperator2 = @$filter["w_Vendor"];
		$this->Vendor->AdvancedSearch->Save();

		// Field Url
		$this->Url->AdvancedSearch->SearchValue = @$filter["x_Url"];
		$this->Url->AdvancedSearch->SearchOperator = @$filter["z_Url"];
		$this->Url->AdvancedSearch->SearchCondition = @$filter["v_Url"];
		$this->Url->AdvancedSearch->SearchValue2 = @$filter["y_Url"];
		$this->Url->AdvancedSearch->SearchOperator2 = @$filter["w_Url"];
		$this->Url->AdvancedSearch->Save();

		// Field Application Description
		$this->Application_Description->AdvancedSearch->SearchValue = @$filter["x_Application_Description"];
		$this->Application_Description->AdvancedSearch->SearchOperator = @$filter["z_Application_Description"];
		$this->Application_Description->AdvancedSearch->SearchCondition = @$filter["v_Application_Description"];
		$this->Application_Description->AdvancedSearch->SearchValue2 = @$filter["y_Application_Description"];
		$this->Application_Description->AdvancedSearch->SearchOperator2 = @$filter["w_Application_Description"];
		$this->Application_Description->AdvancedSearch->Save();

		// Field Affected Users
		$this->Affected_Users->AdvancedSearch->SearchValue = @$filter["x_Affected_Users"];
		$this->Affected_Users->AdvancedSearch->SearchOperator = @$filter["z_Affected_Users"];
		$this->Affected_Users->AdvancedSearch->SearchCondition = @$filter["v_Affected_Users"];
		$this->Affected_Users->AdvancedSearch->SearchValue2 = @$filter["y_Affected_Users"];
		$this->Affected_Users->AdvancedSearch->SearchOperator2 = @$filter["w_Affected_Users"];
		$this->Affected_Users->AdvancedSearch->Save();

		// Field Status
		$this->Status->AdvancedSearch->SearchValue = @$filter["x_Status"];
		$this->Status->AdvancedSearch->SearchOperator = @$filter["z_Status"];
		$this->Status->AdvancedSearch->SearchCondition = @$filter["v_Status"];
		$this->Status->AdvancedSearch->SearchValue2 = @$filter["y_Status"];
		$this->Status->AdvancedSearch->SearchOperator2 = @$filter["w_Status"];
		$this->Status->AdvancedSearch->Save();

		// Field Location
		$this->Location->AdvancedSearch->SearchValue = @$filter["x_Location"];
		$this->Location->AdvancedSearch->SearchOperator = @$filter["z_Location"];
		$this->Location->AdvancedSearch->SearchCondition = @$filter["v_Location"];
		$this->Location->AdvancedSearch->SearchValue2 = @$filter["y_Location"];
		$this->Location->AdvancedSearch->SearchOperator2 = @$filter["w_Location"];
		$this->Location->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->Applications, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Associated_Apps2F_Service, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->IP_Address, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->System_Name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Support_Team, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Vendor, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Application_Description, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Affected_Users, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Status, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Location, $arKeywords, $type);
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
			$this->UpdateSort($this->Applications); // Applications
			$this->UpdateSort($this->Associated_Apps2F_Service); // Associated Apps/ Service
			$this->UpdateSort($this->IP_Address); // IP Address
			$this->UpdateSort($this->System_Name); // System Name
			$this->UpdateSort($this->Support_Team); // Support Team
			$this->UpdateSort($this->Vendor); // Vendor
			$this->UpdateSort($this->Affected_Users); // Affected Users
			$this->UpdateSort($this->Status); // Status
			$this->UpdateSort($this->Location); // Location
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
				$this->Applications->setSort("ASC");
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
				$this->setSessionOrderByList($sOrderBy);
				$this->Applications->setSort("");
				$this->Associated_Apps2F_Service->setSort("");
				$this->IP_Address->setSort("");
				$this->System_Name->setSort("");
				$this->Support_Team->setSort("");
				$this->Vendor->setSort("");
				$this->Affected_Users->setSort("");
				$this->Status->setSort("");
				$this->Location->setSort("");
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
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanView();
		$item->OnLeft = TRUE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = TRUE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = TRUE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssClass = "text-nowrap";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = TRUE;
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->ID->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
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
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = TRUE;
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fAppInventorylistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fAppInventorylistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fAppInventorylist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fAppInventorylistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Search highlight button
		$item = &$this->SearchOptions->Add("searchhighlight");
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewHighlight active\" title=\"" . $Language->Phrase("Highlight") . "\" data-caption=\"" . $Language->Phrase("Highlight") . "\" data-toggle=\"button\" data-form=\"fAppInventorylistsrch\" data-name=\"" . $this->HighlightName() . "\">" . $Language->Phrase("HighlightBtn") . "</button>";
		$item->Visible = ($this->SearchWhere <> "" && $this->TotalRecs > 0);

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
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderByList())));
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
		$this->ID->setDbValue($row['ID']);
		$this->Applications->setDbValue($row['Applications']);
		$this->Associated_Apps2F_Service->setDbValue($row['Associated Apps/ Service']);
		$this->IP_Address->setDbValue($row['IP Address']);
		$this->System_Name->setDbValue($row['System Name']);
		$this->Support_Team->setDbValue($row['Support Team']);
		if (array_key_exists('EV__Support_Team', $rs->fields)) {
			$this->Support_Team->VirtualValue = $rs->fields('EV__Support_Team'); // Set up virtual field value
		} else {
			$this->Support_Team->VirtualValue = ""; // Clear value
		}
		$this->Vendor->setDbValue($row['Vendor']);
		$this->Url->setDbValue($row['Url']);
		$this->Application_Description->setDbValue($row['Application Description']);
		$this->Affected_Users->setDbValue($row['Affected Users']);
		$this->Status->setDbValue($row['Status']);
		$this->Location->setDbValue($row['Location']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['ID'] = NULL;
		$row['Applications'] = NULL;
		$row['Associated Apps/ Service'] = NULL;
		$row['IP Address'] = NULL;
		$row['System Name'] = NULL;
		$row['Support Team'] = NULL;
		$row['Vendor'] = NULL;
		$row['Url'] = NULL;
		$row['Application Description'] = NULL;
		$row['Affected Users'] = NULL;
		$row['Status'] = NULL;
		$row['Location'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ID->DbValue = $row['ID'];
		$this->Applications->DbValue = $row['Applications'];
		$this->Associated_Apps2F_Service->DbValue = $row['Associated Apps/ Service'];
		$this->IP_Address->DbValue = $row['IP Address'];
		$this->System_Name->DbValue = $row['System Name'];
		$this->Support_Team->DbValue = $row['Support Team'];
		$this->Vendor->DbValue = $row['Vendor'];
		$this->Url->DbValue = $row['Url'];
		$this->Application_Description->DbValue = $row['Application Description'];
		$this->Affected_Users->DbValue = $row['Affected Users'];
		$this->Status->DbValue = $row['Status'];
		$this->Location->DbValue = $row['Location'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("ID")) <> "")
			$this->ID->CurrentValue = $this->getKey("ID"); // ID
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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

			// Applications
			$this->Applications->LinkCustomAttributes = "";
			$this->Applications->HrefValue = "";
			$this->Applications->TooltipValue = "";
			if ($this->Export == "")
				$this->Applications->ViewValue = $this->HighlightValue($this->Applications);

			// Associated Apps/ Service
			$this->Associated_Apps2F_Service->LinkCustomAttributes = "";
			$this->Associated_Apps2F_Service->HrefValue = "";
			$this->Associated_Apps2F_Service->TooltipValue = "";
			if ($this->Export == "")
				$this->Associated_Apps2F_Service->ViewValue = $this->HighlightValue($this->Associated_Apps2F_Service);

			// IP Address
			$this->IP_Address->LinkCustomAttributes = "";
			$this->IP_Address->HrefValue = "";
			$this->IP_Address->TooltipValue = "";
			if ($this->Export == "")
				$this->IP_Address->ViewValue = $this->HighlightValue($this->IP_Address);

			// System Name
			$this->System_Name->LinkCustomAttributes = "";
			$this->System_Name->HrefValue = "";
			$this->System_Name->TooltipValue = "";
			if ($this->Export == "")
				$this->System_Name->ViewValue = $this->HighlightValue($this->System_Name);

			// Support Team
			$this->Support_Team->LinkCustomAttributes = "";
			$this->Support_Team->HrefValue = "";
			$this->Support_Team->TooltipValue = "";
			if ($this->Export == "")
				$this->Support_Team->ViewValue = $this->HighlightValue($this->Support_Team);

			// Vendor
			$this->Vendor->LinkCustomAttributes = "";
			$this->Vendor->HrefValue = "";
			$this->Vendor->TooltipValue = "";

			// Affected Users
			$this->Affected_Users->LinkCustomAttributes = "";
			$this->Affected_Users->HrefValue = "";
			$this->Affected_Users->TooltipValue = "";
			if ($this->Export == "")
				$this->Affected_Users->ViewValue = $this->HighlightValue($this->Affected_Users);

			// Status
			$this->Status->LinkCustomAttributes = "";
			$this->Status->HrefValue = "";
			$this->Status->TooltipValue = "";

			// Location
			$this->Location->LinkCustomAttributes = "";
			$this->Location->HrefValue = "";
			$this->Location->TooltipValue = "";
			if ($this->Export == "")
				$this->Location->ViewValue = $this->HighlightValue($this->Location);
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
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
		$item->Body = "<button id=\"emf_AppInventory\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_AppInventory',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fAppInventorylist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = TRUE;
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
if (!isset($AppInventory_list)) $AppInventory_list = new cAppInventory_list();

// Page init
$AppInventory_list->Page_Init();

// Page main
$AppInventory_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$AppInventory_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($AppInventory->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fAppInventorylist = new ew_Form("fAppInventorylist", "list");
fAppInventorylist.FormKeyCountName = '<?php echo $AppInventory_list->FormKeyCountName ?>';

// Form_CustomValidate event
fAppInventorylist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fAppInventorylist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fAppInventorylist.Lists["x_Support_Team"] = {"LinkField":"x_Support_Team","Ajax":true,"AutoFill":false,"DisplayFields":["x_Support_Team","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"Support_Teams"};
fAppInventorylist.Lists["x_Support_Team"].Data = "<?php echo $AppInventory_list->Support_Team->LookupFilterQuery(FALSE, "list") ?>";
fAppInventorylist.Lists["x_Vendor"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fAppInventorylist.Lists["x_Vendor"].Options = <?php echo json_encode($AppInventory_list->Vendor->Options()) ?>;
fAppInventorylist.Lists["x_Status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fAppInventorylist.Lists["x_Status"].Options = <?php echo json_encode($AppInventory_list->Status->Options()) ?>;

// Form object for search
var CurrentSearchForm = fAppInventorylistsrch = new ew_Form("fAppInventorylistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($AppInventory->Export == "") { ?>
<div class="ewToolbar">
<?php if ($AppInventory_list->TotalRecs > 0 && $AppInventory_list->ExportOptions->Visible()) { ?>
<?php $AppInventory_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($AppInventory_list->SearchOptions->Visible()) { ?>
<?php $AppInventory_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($AppInventory_list->FilterOptions->Visible()) { ?>
<?php $AppInventory_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $AppInventory_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($AppInventory_list->TotalRecs <= 0)
			$AppInventory_list->TotalRecs = $AppInventory->ListRecordCount();
	} else {
		if (!$AppInventory_list->Recordset && ($AppInventory_list->Recordset = $AppInventory_list->LoadRecordset()))
			$AppInventory_list->TotalRecs = $AppInventory_list->Recordset->RecordCount();
	}
	$AppInventory_list->StartRec = 1;
	if ($AppInventory_list->DisplayRecs <= 0 || ($AppInventory->Export <> "" && $AppInventory->ExportAll)) // Display all records
		$AppInventory_list->DisplayRecs = $AppInventory_list->TotalRecs;
	if (!($AppInventory->Export <> "" && $AppInventory->ExportAll))
		$AppInventory_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$AppInventory_list->Recordset = $AppInventory_list->LoadRecordset($AppInventory_list->StartRec-1, $AppInventory_list->DisplayRecs);

	// Set no record found message
	if ($AppInventory->CurrentAction == "" && $AppInventory_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$AppInventory_list->setWarningMessage(ew_DeniedMsg());
		if ($AppInventory_list->SearchWhere == "0=101")
			$AppInventory_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$AppInventory_list->setWarningMessage($Language->Phrase("NoRecord"));
	}

	// Audit trail on search
	if ($AppInventory_list->AuditTrailOnSearch && $AppInventory_list->Command == "search" && !$AppInventory_list->RestoreSearch) {
		$searchparm = ew_ServerVar("QUERY_STRING");
		$searchsql = $AppInventory_list->getSessionWhere();
		$AppInventory_list->WriteAuditTrailOnSearch($searchparm, $searchsql);
	}
$AppInventory_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($AppInventory->Export == "" && $AppInventory->CurrentAction == "") { ?>
<form name="fAppInventorylistsrch" id="fAppInventorylistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($AppInventory_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fAppInventorylistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="AppInventory">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($AppInventory_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($AppInventory_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $AppInventory_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($AppInventory_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($AppInventory_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($AppInventory_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($AppInventory_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $AppInventory_list->ShowPageHeader(); ?>
<?php
$AppInventory_list->ShowMessage();
?>
<?php if ($AppInventory_list->TotalRecs > 0 || $AppInventory->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($AppInventory_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> AppInventory">
<?php if ($AppInventory->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($AppInventory->CurrentAction <> "gridadd" && $AppInventory->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($AppInventory_list->Pager)) $AppInventory_list->Pager = new cPrevNextPager($AppInventory_list->StartRec, $AppInventory_list->DisplayRecs, $AppInventory_list->TotalRecs, $AppInventory_list->AutoHidePager) ?>
<?php if ($AppInventory_list->Pager->RecordCount > 0 && $AppInventory_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($AppInventory_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $AppInventory_list->PageUrl() ?>start=<?php echo $AppInventory_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($AppInventory_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $AppInventory_list->PageUrl() ?>start=<?php echo $AppInventory_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $AppInventory_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($AppInventory_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $AppInventory_list->PageUrl() ?>start=<?php echo $AppInventory_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($AppInventory_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $AppInventory_list->PageUrl() ?>start=<?php echo $AppInventory_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $AppInventory_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($AppInventory_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $AppInventory_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $AppInventory_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $AppInventory_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($AppInventory_list->TotalRecs > 0 && (!$AppInventory_list->AutoHidePageSizeSelector || $AppInventory_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="AppInventory">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($AppInventory_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($AppInventory_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($AppInventory_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="100"<?php if ($AppInventory_list->DisplayRecs == 100) { ?> selected<?php } ?>>100</option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($AppInventory_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fAppInventorylist" id="fAppInventorylist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($AppInventory_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $AppInventory_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="AppInventory">
<div id="gmp_AppInventory" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($AppInventory_list->TotalRecs > 0 || $AppInventory->CurrentAction == "gridedit") { ?>
<table id="tbl_AppInventorylist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$AppInventory_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$AppInventory_list->RenderListOptions();

// Render list options (header, left)
$AppInventory_list->ListOptions->Render("header", "left");
?>
<?php if ($AppInventory->Applications->Visible) { // Applications ?>
	<?php if ($AppInventory->SortUrl($AppInventory->Applications) == "") { ?>
		<th data-name="Applications" class="<?php echo $AppInventory->Applications->HeaderCellClass() ?>"><div id="elh_AppInventory_Applications" class="AppInventory_Applications"><div class="ewTableHeaderCaption"><?php echo $AppInventory->Applications->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Applications" class="<?php echo $AppInventory->Applications->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $AppInventory->SortUrl($AppInventory->Applications) ?>',1);"><div id="elh_AppInventory_Applications" class="AppInventory_Applications">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $AppInventory->Applications->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($AppInventory->Applications->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($AppInventory->Applications->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($AppInventory->Associated_Apps2F_Service->Visible) { // Associated Apps/ Service ?>
	<?php if ($AppInventory->SortUrl($AppInventory->Associated_Apps2F_Service) == "") { ?>
		<th data-name="Associated_Apps2F_Service" class="<?php echo $AppInventory->Associated_Apps2F_Service->HeaderCellClass() ?>"><div id="elh_AppInventory_Associated_Apps2F_Service" class="AppInventory_Associated_Apps2F_Service"><div class="ewTableHeaderCaption"><?php echo $AppInventory->Associated_Apps2F_Service->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Associated_Apps2F_Service" class="<?php echo $AppInventory->Associated_Apps2F_Service->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $AppInventory->SortUrl($AppInventory->Associated_Apps2F_Service) ?>',1);"><div id="elh_AppInventory_Associated_Apps2F_Service" class="AppInventory_Associated_Apps2F_Service">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $AppInventory->Associated_Apps2F_Service->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($AppInventory->Associated_Apps2F_Service->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($AppInventory->Associated_Apps2F_Service->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($AppInventory->IP_Address->Visible) { // IP Address ?>
	<?php if ($AppInventory->SortUrl($AppInventory->IP_Address) == "") { ?>
		<th data-name="IP_Address" class="<?php echo $AppInventory->IP_Address->HeaderCellClass() ?>"><div id="elh_AppInventory_IP_Address" class="AppInventory_IP_Address"><div class="ewTableHeaderCaption"><?php echo $AppInventory->IP_Address->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="IP_Address" class="<?php echo $AppInventory->IP_Address->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $AppInventory->SortUrl($AppInventory->IP_Address) ?>',1);"><div id="elh_AppInventory_IP_Address" class="AppInventory_IP_Address">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $AppInventory->IP_Address->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($AppInventory->IP_Address->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($AppInventory->IP_Address->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($AppInventory->System_Name->Visible) { // System Name ?>
	<?php if ($AppInventory->SortUrl($AppInventory->System_Name) == "") { ?>
		<th data-name="System_Name" class="<?php echo $AppInventory->System_Name->HeaderCellClass() ?>"><div id="elh_AppInventory_System_Name" class="AppInventory_System_Name"><div class="ewTableHeaderCaption"><?php echo $AppInventory->System_Name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="System_Name" class="<?php echo $AppInventory->System_Name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $AppInventory->SortUrl($AppInventory->System_Name) ?>',1);"><div id="elh_AppInventory_System_Name" class="AppInventory_System_Name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $AppInventory->System_Name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($AppInventory->System_Name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($AppInventory->System_Name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($AppInventory->Support_Team->Visible) { // Support Team ?>
	<?php if ($AppInventory->SortUrl($AppInventory->Support_Team) == "") { ?>
		<th data-name="Support_Team" class="<?php echo $AppInventory->Support_Team->HeaderCellClass() ?>"><div id="elh_AppInventory_Support_Team" class="AppInventory_Support_Team"><div class="ewTableHeaderCaption"><?php echo $AppInventory->Support_Team->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Support_Team" class="<?php echo $AppInventory->Support_Team->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $AppInventory->SortUrl($AppInventory->Support_Team) ?>',1);"><div id="elh_AppInventory_Support_Team" class="AppInventory_Support_Team">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $AppInventory->Support_Team->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($AppInventory->Support_Team->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($AppInventory->Support_Team->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($AppInventory->Vendor->Visible) { // Vendor ?>
	<?php if ($AppInventory->SortUrl($AppInventory->Vendor) == "") { ?>
		<th data-name="Vendor" class="<?php echo $AppInventory->Vendor->HeaderCellClass() ?>"><div id="elh_AppInventory_Vendor" class="AppInventory_Vendor"><div class="ewTableHeaderCaption"><?php echo $AppInventory->Vendor->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Vendor" class="<?php echo $AppInventory->Vendor->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $AppInventory->SortUrl($AppInventory->Vendor) ?>',1);"><div id="elh_AppInventory_Vendor" class="AppInventory_Vendor">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $AppInventory->Vendor->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($AppInventory->Vendor->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($AppInventory->Vendor->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($AppInventory->Affected_Users->Visible) { // Affected Users ?>
	<?php if ($AppInventory->SortUrl($AppInventory->Affected_Users) == "") { ?>
		<th data-name="Affected_Users" class="<?php echo $AppInventory->Affected_Users->HeaderCellClass() ?>"><div id="elh_AppInventory_Affected_Users" class="AppInventory_Affected_Users"><div class="ewTableHeaderCaption"><?php echo $AppInventory->Affected_Users->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Affected_Users" class="<?php echo $AppInventory->Affected_Users->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $AppInventory->SortUrl($AppInventory->Affected_Users) ?>',1);"><div id="elh_AppInventory_Affected_Users" class="AppInventory_Affected_Users">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $AppInventory->Affected_Users->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($AppInventory->Affected_Users->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($AppInventory->Affected_Users->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($AppInventory->Status->Visible) { // Status ?>
	<?php if ($AppInventory->SortUrl($AppInventory->Status) == "") { ?>
		<th data-name="Status" class="<?php echo $AppInventory->Status->HeaderCellClass() ?>"><div id="elh_AppInventory_Status" class="AppInventory_Status"><div class="ewTableHeaderCaption"><?php echo $AppInventory->Status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Status" class="<?php echo $AppInventory->Status->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $AppInventory->SortUrl($AppInventory->Status) ?>',1);"><div id="elh_AppInventory_Status" class="AppInventory_Status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $AppInventory->Status->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($AppInventory->Status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($AppInventory->Status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($AppInventory->Location->Visible) { // Location ?>
	<?php if ($AppInventory->SortUrl($AppInventory->Location) == "") { ?>
		<th data-name="Location" class="<?php echo $AppInventory->Location->HeaderCellClass() ?>"><div id="elh_AppInventory_Location" class="AppInventory_Location"><div class="ewTableHeaderCaption"><?php echo $AppInventory->Location->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Location" class="<?php echo $AppInventory->Location->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $AppInventory->SortUrl($AppInventory->Location) ?>',1);"><div id="elh_AppInventory_Location" class="AppInventory_Location">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $AppInventory->Location->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($AppInventory->Location->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($AppInventory->Location->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$AppInventory_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($AppInventory->ExportAll && $AppInventory->Export <> "") {
	$AppInventory_list->StopRec = $AppInventory_list->TotalRecs;
} else {

	// Set the last record to display
	if ($AppInventory_list->TotalRecs > $AppInventory_list->StartRec + $AppInventory_list->DisplayRecs - 1)
		$AppInventory_list->StopRec = $AppInventory_list->StartRec + $AppInventory_list->DisplayRecs - 1;
	else
		$AppInventory_list->StopRec = $AppInventory_list->TotalRecs;
}
$AppInventory_list->RecCnt = $AppInventory_list->StartRec - 1;
if ($AppInventory_list->Recordset && !$AppInventory_list->Recordset->EOF) {
	$AppInventory_list->Recordset->MoveFirst();
	$bSelectLimit = $AppInventory_list->UseSelectLimit;
	if (!$bSelectLimit && $AppInventory_list->StartRec > 1)
		$AppInventory_list->Recordset->Move($AppInventory_list->StartRec - 1);
} elseif (!$AppInventory->AllowAddDeleteRow && $AppInventory_list->StopRec == 0) {
	$AppInventory_list->StopRec = $AppInventory->GridAddRowCount;
}

// Initialize aggregate
$AppInventory->RowType = EW_ROWTYPE_AGGREGATEINIT;
$AppInventory->ResetAttrs();
$AppInventory_list->RenderRow();
while ($AppInventory_list->RecCnt < $AppInventory_list->StopRec) {
	$AppInventory_list->RecCnt++;
	if (intval($AppInventory_list->RecCnt) >= intval($AppInventory_list->StartRec)) {
		$AppInventory_list->RowCnt++;

		// Set up key count
		$AppInventory_list->KeyCount = $AppInventory_list->RowIndex;

		// Init row class and style
		$AppInventory->ResetAttrs();
		$AppInventory->CssClass = "";
		if ($AppInventory->CurrentAction == "gridadd") {
		} else {
			$AppInventory_list->LoadRowValues($AppInventory_list->Recordset); // Load row values
		}
		$AppInventory->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$AppInventory->RowAttrs = array_merge($AppInventory->RowAttrs, array('data-rowindex'=>$AppInventory_list->RowCnt, 'id'=>'r' . $AppInventory_list->RowCnt . '_AppInventory', 'data-rowtype'=>$AppInventory->RowType));

		// Render row
		$AppInventory_list->RenderRow();

		// Render list options
		$AppInventory_list->RenderListOptions();
?>
	<tr<?php echo $AppInventory->RowAttributes() ?>>
<?php

// Render list options (body, left)
$AppInventory_list->ListOptions->Render("body", "left", $AppInventory_list->RowCnt);
?>
	<?php if ($AppInventory->Applications->Visible) { // Applications ?>
		<td data-name="Applications"<?php echo $AppInventory->Applications->CellAttributes() ?>>
<span id="el<?php echo $AppInventory_list->RowCnt ?>_AppInventory_Applications" class="AppInventory_Applications">
<span<?php echo $AppInventory->Applications->ViewAttributes() ?>>
<?php echo $AppInventory->Applications->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($AppInventory->Associated_Apps2F_Service->Visible) { // Associated Apps/ Service ?>
		<td data-name="Associated_Apps2F_Service"<?php echo $AppInventory->Associated_Apps2F_Service->CellAttributes() ?>>
<span id="el<?php echo $AppInventory_list->RowCnt ?>_AppInventory_Associated_Apps2F_Service" class="AppInventory_Associated_Apps2F_Service">
<span<?php echo $AppInventory->Associated_Apps2F_Service->ViewAttributes() ?>>
<?php echo $AppInventory->Associated_Apps2F_Service->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($AppInventory->IP_Address->Visible) { // IP Address ?>
		<td data-name="IP_Address"<?php echo $AppInventory->IP_Address->CellAttributes() ?>>
<span id="el<?php echo $AppInventory_list->RowCnt ?>_AppInventory_IP_Address" class="AppInventory_IP_Address">
<span<?php echo $AppInventory->IP_Address->ViewAttributes() ?>>
<?php echo $AppInventory->IP_Address->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($AppInventory->System_Name->Visible) { // System Name ?>
		<td data-name="System_Name"<?php echo $AppInventory->System_Name->CellAttributes() ?>>
<span id="el<?php echo $AppInventory_list->RowCnt ?>_AppInventory_System_Name" class="AppInventory_System_Name">
<span<?php echo $AppInventory->System_Name->ViewAttributes() ?>>
<?php echo $AppInventory->System_Name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($AppInventory->Support_Team->Visible) { // Support Team ?>
		<td data-name="Support_Team"<?php echo $AppInventory->Support_Team->CellAttributes() ?>>
<span id="el<?php echo $AppInventory_list->RowCnt ?>_AppInventory_Support_Team" class="AppInventory_Support_Team">
<span<?php echo $AppInventory->Support_Team->ViewAttributes() ?>>
<?php echo $AppInventory->Support_Team->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($AppInventory->Vendor->Visible) { // Vendor ?>
		<td data-name="Vendor"<?php echo $AppInventory->Vendor->CellAttributes() ?>>
<span id="el<?php echo $AppInventory_list->RowCnt ?>_AppInventory_Vendor" class="AppInventory_Vendor">
<span<?php echo $AppInventory->Vendor->ViewAttributes() ?>>
<?php echo $AppInventory->Vendor->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($AppInventory->Affected_Users->Visible) { // Affected Users ?>
		<td data-name="Affected_Users"<?php echo $AppInventory->Affected_Users->CellAttributes() ?>>
<span id="el<?php echo $AppInventory_list->RowCnt ?>_AppInventory_Affected_Users" class="AppInventory_Affected_Users">
<span<?php echo $AppInventory->Affected_Users->ViewAttributes() ?>>
<?php echo $AppInventory->Affected_Users->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($AppInventory->Status->Visible) { // Status ?>
		<td data-name="Status"<?php echo $AppInventory->Status->CellAttributes() ?>>
<span id="el<?php echo $AppInventory_list->RowCnt ?>_AppInventory_Status" class="AppInventory_Status">
<span<?php echo $AppInventory->Status->ViewAttributes() ?>>
<?php echo $AppInventory->Status->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($AppInventory->Location->Visible) { // Location ?>
		<td data-name="Location"<?php echo $AppInventory->Location->CellAttributes() ?>>
<span id="el<?php echo $AppInventory_list->RowCnt ?>_AppInventory_Location" class="AppInventory_Location">
<span<?php echo $AppInventory->Location->ViewAttributes() ?>>
<?php echo $AppInventory->Location->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$AppInventory_list->ListOptions->Render("body", "right", $AppInventory_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($AppInventory->CurrentAction <> "gridadd")
		$AppInventory_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($AppInventory->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($AppInventory_list->Recordset)
	$AppInventory_list->Recordset->Close();
?>
<?php if ($AppInventory->Export == "") { ?>
<div class="box-footer ewGridLowerPanel">
<?php if ($AppInventory->CurrentAction <> "gridadd" && $AppInventory->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($AppInventory_list->Pager)) $AppInventory_list->Pager = new cPrevNextPager($AppInventory_list->StartRec, $AppInventory_list->DisplayRecs, $AppInventory_list->TotalRecs, $AppInventory_list->AutoHidePager) ?>
<?php if ($AppInventory_list->Pager->RecordCount > 0 && $AppInventory_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($AppInventory_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $AppInventory_list->PageUrl() ?>start=<?php echo $AppInventory_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($AppInventory_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $AppInventory_list->PageUrl() ?>start=<?php echo $AppInventory_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $AppInventory_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($AppInventory_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $AppInventory_list->PageUrl() ?>start=<?php echo $AppInventory_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($AppInventory_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $AppInventory_list->PageUrl() ?>start=<?php echo $AppInventory_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $AppInventory_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($AppInventory_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $AppInventory_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $AppInventory_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $AppInventory_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($AppInventory_list->TotalRecs > 0 && (!$AppInventory_list->AutoHidePageSizeSelector || $AppInventory_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="AppInventory">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($AppInventory_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($AppInventory_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($AppInventory_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="100"<?php if ($AppInventory_list->DisplayRecs == 100) { ?> selected<?php } ?>>100</option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($AppInventory_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($AppInventory_list->TotalRecs == 0 && $AppInventory->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($AppInventory_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($AppInventory->Export == "") { ?>
<script type="text/javascript">
fAppInventorylistsrch.FilterList = <?php echo $AppInventory_list->GetFilterList() ?>;
fAppInventorylistsrch.Init();
fAppInventorylist.Init();
</script>
<?php } ?>
<?php
$AppInventory_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($AppInventory->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$AppInventory_list->Page_Terminate();
?>
