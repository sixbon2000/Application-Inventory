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

$AppInventory_edit = NULL; // Initialize page object first

class cAppInventory_edit extends cAppInventory {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{F928ACD0-3C23-4A28-A63B-8FA9605A2019}';

	// Table name
	var $TableName = 'AppInventory';

	// Page object name
	var $PageObjName = 'AppInventory_edit';

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

		// Table object (Users)
		if (!isset($GLOBALS['Users'])) $GLOBALS['Users'] = new cUsers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (IsPasswordExpired())
			$this->Page_Terminate(ew_GetUrl("changepwd.php"));
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("AppInventorylist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
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

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->ID->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->ID->Visible = FALSE;
		$this->Applications->SetVisibility();
		$this->Associated_Apps2F_Service->SetVisibility();
		$this->IP_Address->SetVisibility();
		$this->System_Name->SetVisibility();
		$this->Support_Team->SetVisibility();
		$this->Vendor->SetVisibility();
		$this->Url->SetVisibility();
		$this->Application_Description->SetVisibility();
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

			// Handle modal response
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "AppInventoryview.php")
						$row["view"] = "1";
				} else { // List page should not be shown as modal => error
					$row["error"] = $this->getFailureMessage();
					$this->clearFailureMessage();
				}
				header("Content-Type: application/json; charset=utf-8");
				echo ew_ConvertToUtf8(ew_ArrayToJson(array($row)));
			} else {
				ew_SaveDebugMsg();
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $AutoHidePager = EW_AUTO_HIDE_PAGER;
	var $RecCnt;
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gbSkipHeaderFooter;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		$this->FormClassName = "ewForm ewEditForm form-horizontal";

		// Load record by position
		$loadByPosition = FALSE;
		$sReturnUrl = "";
		$loaded = FALSE;
		$postBack = FALSE;

		// Set up current action and primary key
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			if ($this->CurrentAction <> "I") // Not reload record, handle as postback
				$postBack = TRUE;

			// Load key from Form
			if ($objForm->HasValue("x_ID")) {
				$this->ID->setFormValue($objForm->GetValue("x_ID"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["ID"])) {
				$this->ID->setQueryStringValue($_GET["ID"]);
				$loadByQuery = TRUE;
			} else {
				$this->ID->CurrentValue = NULL;
			}
			if (!$loadByQuery)
				$loadByPosition = TRUE;
		}

		// Load recordset
		$this->StartRec = 1; // Initialize start position
		if ($this->Recordset = $this->LoadRecordset()) // Load records
			$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
		if ($this->TotalRecs <= 0) { // No record found
			if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$this->Page_Terminate("AppInventorylist.php"); // Return to list page
		} elseif ($loadByPosition) { // Load record by position
			$this->SetupStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$this->Recordset->Move($this->StartRec-1);
				$loaded = TRUE;
			}
		} else { // Match key values
			if (!is_null($this->ID->CurrentValue)) {
				while (!$this->Recordset->EOF) {
					if (strval($this->ID->CurrentValue) == strval($this->Recordset->fields('ID'))) {
						$this->setStartRecordNumber($this->StartRec); // Save record position
						$loaded = TRUE;
						break;
					} else {
						$this->StartRec++;
						$this->Recordset->MoveNext();
					}
				}
			}
		}

		// Load current row values
		if ($loaded)
			$this->LoadRowValues($this->Recordset);

		// Process form if post back
		if ($postBack) {
			$this->LoadFormValues(); // Get form values
		}

		// Validate form if post back
		if ($postBack) {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}

		// Perform current action
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$loaded) {
					if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
						$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
					$this->Page_Terminate("AppInventorylist.php"); // Return to list page
				} else {
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "AppInventorylist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->ID->FldIsDetailKey)
			$this->ID->setFormValue($objForm->GetValue("x_ID"));
		if (!$this->Applications->FldIsDetailKey) {
			$this->Applications->setFormValue($objForm->GetValue("x_Applications"));
		}
		if (!$this->Associated_Apps2F_Service->FldIsDetailKey) {
			$this->Associated_Apps2F_Service->setFormValue($objForm->GetValue("x_Associated_Apps2F_Service"));
		}
		if (!$this->IP_Address->FldIsDetailKey) {
			$this->IP_Address->setFormValue($objForm->GetValue("x_IP_Address"));
		}
		if (!$this->System_Name->FldIsDetailKey) {
			$this->System_Name->setFormValue($objForm->GetValue("x_System_Name"));
		}
		if (!$this->Support_Team->FldIsDetailKey) {
			$this->Support_Team->setFormValue($objForm->GetValue("x_Support_Team"));
		}
		if (!$this->Vendor->FldIsDetailKey) {
			$this->Vendor->setFormValue($objForm->GetValue("x_Vendor"));
		}
		if (!$this->Url->FldIsDetailKey) {
			$this->Url->setFormValue($objForm->GetValue("x_Url"));
		}
		if (!$this->Application_Description->FldIsDetailKey) {
			$this->Application_Description->setFormValue($objForm->GetValue("x_Application_Description"));
		}
		if (!$this->Affected_Users->FldIsDetailKey) {
			$this->Affected_Users->setFormValue($objForm->GetValue("x_Affected_Users"));
		}
		if (!$this->Status->FldIsDetailKey) {
			$this->Status->setFormValue($objForm->GetValue("x_Status"));
		}
		if (!$this->Location->FldIsDetailKey) {
			$this->Location->setFormValue($objForm->GetValue("x_Location"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->ID->CurrentValue = $this->ID->FormValue;
		$this->Applications->CurrentValue = $this->Applications->FormValue;
		$this->Associated_Apps2F_Service->CurrentValue = $this->Associated_Apps2F_Service->FormValue;
		$this->IP_Address->CurrentValue = $this->IP_Address->FormValue;
		$this->System_Name->CurrentValue = $this->System_Name->FormValue;
		$this->Support_Team->CurrentValue = $this->Support_Team->FormValue;
		$this->Vendor->CurrentValue = $this->Vendor->FormValue;
		$this->Url->CurrentValue = $this->Url->FormValue;
		$this->Application_Description->CurrentValue = $this->Application_Description->FormValue;
		$this->Affected_Users->CurrentValue = $this->Affected_Users->FormValue;
		$this->Status->CurrentValue = $this->Status->FormValue;
		$this->Location->CurrentValue = $this->Location->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// ID
			$this->ID->EditAttrs["class"] = "form-control";
			$this->ID->EditCustomAttributes = "";
			$this->ID->EditValue = $this->ID->CurrentValue;
			$this->ID->ViewCustomAttributes = "";

			// Applications
			$this->Applications->EditAttrs["class"] = "form-control";
			$this->Applications->EditCustomAttributes = "";
			$this->Applications->EditValue = ew_HtmlEncode($this->Applications->CurrentValue);
			$this->Applications->PlaceHolder = ew_RemoveHtml($this->Applications->FldCaption());

			// Associated Apps/ Service
			$this->Associated_Apps2F_Service->EditAttrs["class"] = "form-control";
			$this->Associated_Apps2F_Service->EditCustomAttributes = "";
			$this->Associated_Apps2F_Service->EditValue = ew_HtmlEncode($this->Associated_Apps2F_Service->CurrentValue);
			$this->Associated_Apps2F_Service->PlaceHolder = ew_RemoveHtml($this->Associated_Apps2F_Service->FldCaption());

			// IP Address
			$this->IP_Address->EditAttrs["class"] = "form-control";
			$this->IP_Address->EditCustomAttributes = "";
			$this->IP_Address->EditValue = ew_HtmlEncode($this->IP_Address->CurrentValue);
			$this->IP_Address->PlaceHolder = ew_RemoveHtml($this->IP_Address->FldCaption());

			// System Name
			$this->System_Name->EditAttrs["class"] = "form-control";
			$this->System_Name->EditCustomAttributes = "";
			$this->System_Name->EditValue = ew_HtmlEncode($this->System_Name->CurrentValue);
			$this->System_Name->PlaceHolder = ew_RemoveHtml($this->System_Name->FldCaption());

			// Support Team
			$this->Support_Team->EditCustomAttributes = "";
			if (trim(strval($this->Support_Team->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "[Support Team]" . ew_SearchString("=", $this->Support_Team->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT [Support Team], [Support Team] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [Support Teams]";
			$sWhereWrk = "";
			$this->Support_Team->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Support_Team, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [Support Team] ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->Support_Team->ViewValue = $this->Support_Team->DisplayValue($arwrk);
			} else {
				$this->Support_Team->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Support_Team->EditValue = $arwrk;

			// Vendor
			$this->Vendor->EditCustomAttributes = "";
			$this->Vendor->EditValue = $this->Vendor->Options(FALSE);

			// Url
			$this->Url->EditAttrs["class"] = "form-control";
			$this->Url->EditCustomAttributes = "";
			$this->Url->EditValue = ew_HtmlEncode($this->Url->CurrentValue);
			$this->Url->PlaceHolder = ew_RemoveHtml($this->Url->FldCaption());

			// Application Description
			$this->Application_Description->EditAttrs["class"] = "form-control";
			$this->Application_Description->EditCustomAttributes = "";
			$this->Application_Description->EditValue = ew_HtmlEncode($this->Application_Description->CurrentValue);
			$this->Application_Description->PlaceHolder = ew_RemoveHtml($this->Application_Description->FldCaption());

			// Affected Users
			$this->Affected_Users->EditAttrs["class"] = "form-control";
			$this->Affected_Users->EditCustomAttributes = "";
			$this->Affected_Users->EditValue = ew_HtmlEncode($this->Affected_Users->CurrentValue);
			$this->Affected_Users->PlaceHolder = ew_RemoveHtml($this->Affected_Users->FldCaption());

			// Status
			$this->Status->EditCustomAttributes = "";
			$this->Status->EditValue = $this->Status->Options(FALSE);

			// Location
			$this->Location->EditAttrs["class"] = "form-control";
			$this->Location->EditCustomAttributes = "";
			$this->Location->EditValue = ew_HtmlEncode($this->Location->CurrentValue);
			$this->Location->PlaceHolder = ew_RemoveHtml($this->Location->FldCaption());

			// Edit refer script
			// ID

			$this->ID->LinkCustomAttributes = "";
			$this->ID->HrefValue = "";

			// Applications
			$this->Applications->LinkCustomAttributes = "";
			$this->Applications->HrefValue = "";

			// Associated Apps/ Service
			$this->Associated_Apps2F_Service->LinkCustomAttributes = "";
			$this->Associated_Apps2F_Service->HrefValue = "";

			// IP Address
			$this->IP_Address->LinkCustomAttributes = "";
			$this->IP_Address->HrefValue = "";

			// System Name
			$this->System_Name->LinkCustomAttributes = "";
			$this->System_Name->HrefValue = "";

			// Support Team
			$this->Support_Team->LinkCustomAttributes = "";
			$this->Support_Team->HrefValue = "";

			// Vendor
			$this->Vendor->LinkCustomAttributes = "";
			$this->Vendor->HrefValue = "";

			// Url
			$this->Url->LinkCustomAttributes = "";
			if (!ew_Empty($this->Url->CurrentValue)) {
				$this->Url->HrefValue = ((!empty($this->Url->EditValue) && !is_array($this->Url->EditValue)) ? ew_RemoveHtml($this->Url->EditValue) : $this->Url->CurrentValue); // Add prefix/suffix
				$this->Url->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->Url->HrefValue = ew_FullUrl($this->Url->HrefValue, "href");
			} else {
				$this->Url->HrefValue = "";
			}

			// Application Description
			$this->Application_Description->LinkCustomAttributes = "";
			$this->Application_Description->HrefValue = "";

			// Affected Users
			$this->Affected_Users->LinkCustomAttributes = "";
			$this->Affected_Users->HrefValue = "";

			// Status
			$this->Status->LinkCustomAttributes = "";
			$this->Status->HrefValue = "";

			// Location
			$this->Location->LinkCustomAttributes = "";
			$this->Location->HrefValue = "";
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

			// ID
			// Applications

			$this->Applications->SetDbValueDef($rsnew, $this->Applications->CurrentValue, NULL, $this->Applications->ReadOnly);

			// Associated Apps/ Service
			$this->Associated_Apps2F_Service->SetDbValueDef($rsnew, $this->Associated_Apps2F_Service->CurrentValue, NULL, $this->Associated_Apps2F_Service->ReadOnly);

			// IP Address
			$this->IP_Address->SetDbValueDef($rsnew, $this->IP_Address->CurrentValue, NULL, $this->IP_Address->ReadOnly);

			// System Name
			$this->System_Name->SetDbValueDef($rsnew, $this->System_Name->CurrentValue, NULL, $this->System_Name->ReadOnly);

			// Support Team
			$this->Support_Team->SetDbValueDef($rsnew, $this->Support_Team->CurrentValue, NULL, $this->Support_Team->ReadOnly);

			// Vendor
			$this->Vendor->SetDbValueDef($rsnew, $this->Vendor->CurrentValue, NULL, $this->Vendor->ReadOnly);

			// Url
			$this->Url->SetDbValueDef($rsnew, $this->Url->CurrentValue, NULL, $this->Url->ReadOnly);

			// Application Description
			$this->Application_Description->SetDbValueDef($rsnew, $this->Application_Description->CurrentValue, NULL, $this->Application_Description->ReadOnly);

			// Affected Users
			$this->Affected_Users->SetDbValueDef($rsnew, $this->Affected_Users->CurrentValue, NULL, $this->Affected_Users->ReadOnly);

			// Status
			$this->Status->SetDbValueDef($rsnew, $this->Status->CurrentValue, NULL, $this->Status->ReadOnly);

			// Location
			$this->Location->SetDbValueDef($rsnew, $this->Location->CurrentValue, NULL, $this->Location->ReadOnly);

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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("AppInventorylist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_Support_Team":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT [Support Team] AS [LinkFld], [Support Team] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [Support Teams]";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '[Support Team] IN ({filter_value})', "t0" => "202", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Support_Team, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [Support Team] ASC";
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
if (!isset($AppInventory_edit)) $AppInventory_edit = new cAppInventory_edit();

// Page init
$AppInventory_edit->Page_Init();

// Page main
$AppInventory_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$AppInventory_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fAppInventoryedit = new ew_Form("fAppInventoryedit", "edit");

// Validate form
fAppInventoryedit.Validate = function() {
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

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fAppInventoryedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fAppInventoryedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fAppInventoryedit.Lists["x_Support_Team"] = {"LinkField":"x_Support_Team","Ajax":true,"AutoFill":false,"DisplayFields":["x_Support_Team","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"Support_Teams"};
fAppInventoryedit.Lists["x_Support_Team"].Data = "<?php echo $AppInventory_edit->Support_Team->LookupFilterQuery(FALSE, "edit") ?>";
fAppInventoryedit.Lists["x_Vendor"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fAppInventoryedit.Lists["x_Vendor"].Options = <?php echo json_encode($AppInventory_edit->Vendor->Options()) ?>;
fAppInventoryedit.Lists["x_Status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fAppInventoryedit.Lists["x_Status"].Options = <?php echo json_encode($AppInventory_edit->Status->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $AppInventory_edit->ShowPageHeader(); ?>
<?php
$AppInventory_edit->ShowMessage();
?>
<?php if (!$AppInventory_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($AppInventory_edit->Pager)) $AppInventory_edit->Pager = new cPrevNextPager($AppInventory_edit->StartRec, $AppInventory_edit->DisplayRecs, $AppInventory_edit->TotalRecs, $AppInventory_edit->AutoHidePager) ?>
<?php if ($AppInventory_edit->Pager->RecordCount > 0 && $AppInventory_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($AppInventory_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $AppInventory_edit->PageUrl() ?>start=<?php echo $AppInventory_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($AppInventory_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $AppInventory_edit->PageUrl() ?>start=<?php echo $AppInventory_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $AppInventory_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($AppInventory_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $AppInventory_edit->PageUrl() ?>start=<?php echo $AppInventory_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($AppInventory_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $AppInventory_edit->PageUrl() ?>start=<?php echo $AppInventory_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $AppInventory_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="fAppInventoryedit" id="fAppInventoryedit" class="<?php echo $AppInventory_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($AppInventory_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $AppInventory_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="AppInventory">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($AppInventory_edit->IsModal) ?>">
<?php if (!$AppInventory_edit->IsMobileOrModal) { ?>
<div class="ewDesktop"><!-- desktop -->
<?php } ?>
<?php if ($AppInventory_edit->IsMobileOrModal) { ?>
<div class="ewEditDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_AppInventoryedit" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($AppInventory->ID->Visible) { // ID ?>
<?php if ($AppInventory_edit->IsMobileOrModal) { ?>
	<div id="r_ID" class="form-group">
		<label id="elh_AppInventory_ID" class="<?php echo $AppInventory_edit->LeftColumnClass ?>"><?php echo $AppInventory->ID->FldCaption() ?></label>
		<div class="<?php echo $AppInventory_edit->RightColumnClass ?>"><div<?php echo $AppInventory->ID->CellAttributes() ?>>
<span id="el_AppInventory_ID">
<span<?php echo $AppInventory->ID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $AppInventory->ID->EditValue ?></p></span>
</span>
<input type="hidden" data-table="AppInventory" data-field="x_ID" name="x_ID" id="x_ID" value="<?php echo ew_HtmlEncode($AppInventory->ID->CurrentValue) ?>">
<?php echo $AppInventory->ID->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_ID">
		<td class="col-sm-2"><span id="elh_AppInventory_ID"><?php echo $AppInventory->ID->FldCaption() ?></span></td>
		<td<?php echo $AppInventory->ID->CellAttributes() ?>>
<span id="el_AppInventory_ID">
<span<?php echo $AppInventory->ID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $AppInventory->ID->EditValue ?></p></span>
</span>
<input type="hidden" data-table="AppInventory" data-field="x_ID" name="x_ID" id="x_ID" value="<?php echo ew_HtmlEncode($AppInventory->ID->CurrentValue) ?>">
<?php echo $AppInventory->ID->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($AppInventory->Applications->Visible) { // Applications ?>
<?php if ($AppInventory_edit->IsMobileOrModal) { ?>
	<div id="r_Applications" class="form-group">
		<label id="elh_AppInventory_Applications" for="x_Applications" class="<?php echo $AppInventory_edit->LeftColumnClass ?>"><?php echo $AppInventory->Applications->FldCaption() ?></label>
		<div class="<?php echo $AppInventory_edit->RightColumnClass ?>"><div<?php echo $AppInventory->Applications->CellAttributes() ?>>
<span id="el_AppInventory_Applications">
<input type="text" data-table="AppInventory" data-field="x_Applications" name="x_Applications" id="x_Applications" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($AppInventory->Applications->getPlaceHolder()) ?>" value="<?php echo $AppInventory->Applications->EditValue ?>"<?php echo $AppInventory->Applications->EditAttributes() ?>>
</span>
<?php echo $AppInventory->Applications->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_Applications">
		<td class="col-sm-2"><span id="elh_AppInventory_Applications"><?php echo $AppInventory->Applications->FldCaption() ?></span></td>
		<td<?php echo $AppInventory->Applications->CellAttributes() ?>>
<span id="el_AppInventory_Applications">
<input type="text" data-table="AppInventory" data-field="x_Applications" name="x_Applications" id="x_Applications" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($AppInventory->Applications->getPlaceHolder()) ?>" value="<?php echo $AppInventory->Applications->EditValue ?>"<?php echo $AppInventory->Applications->EditAttributes() ?>>
</span>
<?php echo $AppInventory->Applications->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($AppInventory->Associated_Apps2F_Service->Visible) { // Associated Apps/ Service ?>
<?php if ($AppInventory_edit->IsMobileOrModal) { ?>
	<div id="r_Associated_Apps2F_Service" class="form-group">
		<label id="elh_AppInventory_Associated_Apps2F_Service" for="x_Associated_Apps2F_Service" class="<?php echo $AppInventory_edit->LeftColumnClass ?>"><?php echo $AppInventory->Associated_Apps2F_Service->FldCaption() ?></label>
		<div class="<?php echo $AppInventory_edit->RightColumnClass ?>"><div<?php echo $AppInventory->Associated_Apps2F_Service->CellAttributes() ?>>
<span id="el_AppInventory_Associated_Apps2F_Service">
<input type="text" data-table="AppInventory" data-field="x_Associated_Apps2F_Service" name="x_Associated_Apps2F_Service" id="x_Associated_Apps2F_Service" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($AppInventory->Associated_Apps2F_Service->getPlaceHolder()) ?>" value="<?php echo $AppInventory->Associated_Apps2F_Service->EditValue ?>"<?php echo $AppInventory->Associated_Apps2F_Service->EditAttributes() ?>>
</span>
<?php echo $AppInventory->Associated_Apps2F_Service->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_Associated_Apps2F_Service">
		<td class="col-sm-2"><span id="elh_AppInventory_Associated_Apps2F_Service"><?php echo $AppInventory->Associated_Apps2F_Service->FldCaption() ?></span></td>
		<td<?php echo $AppInventory->Associated_Apps2F_Service->CellAttributes() ?>>
<span id="el_AppInventory_Associated_Apps2F_Service">
<input type="text" data-table="AppInventory" data-field="x_Associated_Apps2F_Service" name="x_Associated_Apps2F_Service" id="x_Associated_Apps2F_Service" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($AppInventory->Associated_Apps2F_Service->getPlaceHolder()) ?>" value="<?php echo $AppInventory->Associated_Apps2F_Service->EditValue ?>"<?php echo $AppInventory->Associated_Apps2F_Service->EditAttributes() ?>>
</span>
<?php echo $AppInventory->Associated_Apps2F_Service->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($AppInventory->IP_Address->Visible) { // IP Address ?>
<?php if ($AppInventory_edit->IsMobileOrModal) { ?>
	<div id="r_IP_Address" class="form-group">
		<label id="elh_AppInventory_IP_Address" for="x_IP_Address" class="<?php echo $AppInventory_edit->LeftColumnClass ?>"><?php echo $AppInventory->IP_Address->FldCaption() ?></label>
		<div class="<?php echo $AppInventory_edit->RightColumnClass ?>"><div<?php echo $AppInventory->IP_Address->CellAttributes() ?>>
<span id="el_AppInventory_IP_Address">
<input type="text" data-table="AppInventory" data-field="x_IP_Address" name="x_IP_Address" id="x_IP_Address" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($AppInventory->IP_Address->getPlaceHolder()) ?>" value="<?php echo $AppInventory->IP_Address->EditValue ?>"<?php echo $AppInventory->IP_Address->EditAttributes() ?>>
</span>
<?php echo $AppInventory->IP_Address->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_IP_Address">
		<td class="col-sm-2"><span id="elh_AppInventory_IP_Address"><?php echo $AppInventory->IP_Address->FldCaption() ?></span></td>
		<td<?php echo $AppInventory->IP_Address->CellAttributes() ?>>
<span id="el_AppInventory_IP_Address">
<input type="text" data-table="AppInventory" data-field="x_IP_Address" name="x_IP_Address" id="x_IP_Address" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($AppInventory->IP_Address->getPlaceHolder()) ?>" value="<?php echo $AppInventory->IP_Address->EditValue ?>"<?php echo $AppInventory->IP_Address->EditAttributes() ?>>
</span>
<?php echo $AppInventory->IP_Address->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($AppInventory->System_Name->Visible) { // System Name ?>
<?php if ($AppInventory_edit->IsMobileOrModal) { ?>
	<div id="r_System_Name" class="form-group">
		<label id="elh_AppInventory_System_Name" for="x_System_Name" class="<?php echo $AppInventory_edit->LeftColumnClass ?>"><?php echo $AppInventory->System_Name->FldCaption() ?></label>
		<div class="<?php echo $AppInventory_edit->RightColumnClass ?>"><div<?php echo $AppInventory->System_Name->CellAttributes() ?>>
<span id="el_AppInventory_System_Name">
<input type="text" data-table="AppInventory" data-field="x_System_Name" name="x_System_Name" id="x_System_Name" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($AppInventory->System_Name->getPlaceHolder()) ?>" value="<?php echo $AppInventory->System_Name->EditValue ?>"<?php echo $AppInventory->System_Name->EditAttributes() ?>>
</span>
<?php echo $AppInventory->System_Name->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_System_Name">
		<td class="col-sm-2"><span id="elh_AppInventory_System_Name"><?php echo $AppInventory->System_Name->FldCaption() ?></span></td>
		<td<?php echo $AppInventory->System_Name->CellAttributes() ?>>
<span id="el_AppInventory_System_Name">
<input type="text" data-table="AppInventory" data-field="x_System_Name" name="x_System_Name" id="x_System_Name" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($AppInventory->System_Name->getPlaceHolder()) ?>" value="<?php echo $AppInventory->System_Name->EditValue ?>"<?php echo $AppInventory->System_Name->EditAttributes() ?>>
</span>
<?php echo $AppInventory->System_Name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($AppInventory->Support_Team->Visible) { // Support Team ?>
<?php if ($AppInventory_edit->IsMobileOrModal) { ?>
	<div id="r_Support_Team" class="form-group">
		<label id="elh_AppInventory_Support_Team" for="x_Support_Team" class="<?php echo $AppInventory_edit->LeftColumnClass ?>"><?php echo $AppInventory->Support_Team->FldCaption() ?></label>
		<div class="<?php echo $AppInventory_edit->RightColumnClass ?>"><div<?php echo $AppInventory->Support_Team->CellAttributes() ?>>
<span id="el_AppInventory_Support_Team">
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" aria-expanded="false"<?php if ($AppInventory->Support_Team->ReadOnly) { ?> readonly<?php } else { ?>data-toggle="dropdown"<?php } ?>>
		<?php echo $AppInventory->Support_Team->ViewValue ?>
	</span>
	<?php if (!$AppInventory->Support_Team->ReadOnly) { ?>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<?php } ?>
	<div id="dsl_x_Support_Team" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php echo $AppInventory->Support_Team->RadioButtonListHtml(TRUE, "x_Support_Team") ?>
		</div>
	</div>
	<div id="tp_x_Support_Team" class="ewTemplate"><input type="radio" data-table="AppInventory" data-field="x_Support_Team" data-value-separator="<?php echo $AppInventory->Support_Team->DisplayValueSeparatorAttribute() ?>" name="x_Support_Team" id="x_Support_Team" value="{value}"<?php echo $AppInventory->Support_Team->EditAttributes() ?>></div>
</div>
<?php if (AllowAdd(CurrentProjectID() . "Support Teams") && !$AppInventory->Support_Team->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $AppInventory->Support_Team->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_Support_Team',url:'Support_Teamsaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_Support_Team"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $AppInventory->Support_Team->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php echo $AppInventory->Support_Team->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_Support_Team">
		<td class="col-sm-2"><span id="elh_AppInventory_Support_Team"><?php echo $AppInventory->Support_Team->FldCaption() ?></span></td>
		<td<?php echo $AppInventory->Support_Team->CellAttributes() ?>>
<span id="el_AppInventory_Support_Team">
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" aria-expanded="false"<?php if ($AppInventory->Support_Team->ReadOnly) { ?> readonly<?php } else { ?>data-toggle="dropdown"<?php } ?>>
		<?php echo $AppInventory->Support_Team->ViewValue ?>
	</span>
	<?php if (!$AppInventory->Support_Team->ReadOnly) { ?>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<?php } ?>
	<div id="dsl_x_Support_Team" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php echo $AppInventory->Support_Team->RadioButtonListHtml(TRUE, "x_Support_Team") ?>
		</div>
	</div>
	<div id="tp_x_Support_Team" class="ewTemplate"><input type="radio" data-table="AppInventory" data-field="x_Support_Team" data-value-separator="<?php echo $AppInventory->Support_Team->DisplayValueSeparatorAttribute() ?>" name="x_Support_Team" id="x_Support_Team" value="{value}"<?php echo $AppInventory->Support_Team->EditAttributes() ?>></div>
</div>
<?php if (AllowAdd(CurrentProjectID() . "Support Teams") && !$AppInventory->Support_Team->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $AppInventory->Support_Team->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_Support_Team',url:'Support_Teamsaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_Support_Team"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $AppInventory->Support_Team->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php echo $AppInventory->Support_Team->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($AppInventory->Vendor->Visible) { // Vendor ?>
<?php if ($AppInventory_edit->IsMobileOrModal) { ?>
	<div id="r_Vendor" class="form-group">
		<label id="elh_AppInventory_Vendor" class="<?php echo $AppInventory_edit->LeftColumnClass ?>"><?php echo $AppInventory->Vendor->FldCaption() ?></label>
		<div class="<?php echo $AppInventory_edit->RightColumnClass ?>"><div<?php echo $AppInventory->Vendor->CellAttributes() ?>>
<span id="el_AppInventory_Vendor">
<div id="tp_x_Vendor" class="ewTemplate"><input type="radio" data-table="AppInventory" data-field="x_Vendor" data-value-separator="<?php echo $AppInventory->Vendor->DisplayValueSeparatorAttribute() ?>" name="x_Vendor" id="x_Vendor" value="{value}"<?php echo $AppInventory->Vendor->EditAttributes() ?>></div>
<div id="dsl_x_Vendor" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $AppInventory->Vendor->RadioButtonListHtml(FALSE, "x_Vendor") ?>
</div></div>
</span>
<?php echo $AppInventory->Vendor->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_Vendor">
		<td class="col-sm-2"><span id="elh_AppInventory_Vendor"><?php echo $AppInventory->Vendor->FldCaption() ?></span></td>
		<td<?php echo $AppInventory->Vendor->CellAttributes() ?>>
<span id="el_AppInventory_Vendor">
<div id="tp_x_Vendor" class="ewTemplate"><input type="radio" data-table="AppInventory" data-field="x_Vendor" data-value-separator="<?php echo $AppInventory->Vendor->DisplayValueSeparatorAttribute() ?>" name="x_Vendor" id="x_Vendor" value="{value}"<?php echo $AppInventory->Vendor->EditAttributes() ?>></div>
<div id="dsl_x_Vendor" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $AppInventory->Vendor->RadioButtonListHtml(FALSE, "x_Vendor") ?>
</div></div>
</span>
<?php echo $AppInventory->Vendor->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($AppInventory->Url->Visible) { // Url ?>
<?php if ($AppInventory_edit->IsMobileOrModal) { ?>
	<div id="r_Url" class="form-group">
		<label id="elh_AppInventory_Url" for="x_Url" class="<?php echo $AppInventory_edit->LeftColumnClass ?>"><?php echo $AppInventory->Url->FldCaption() ?></label>
		<div class="<?php echo $AppInventory_edit->RightColumnClass ?>"><div<?php echo $AppInventory->Url->CellAttributes() ?>>
<span id="el_AppInventory_Url">
<textarea data-table="AppInventory" data-field="x_Url" name="x_Url" id="x_Url" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($AppInventory->Url->getPlaceHolder()) ?>"<?php echo $AppInventory->Url->EditAttributes() ?>><?php echo $AppInventory->Url->EditValue ?></textarea>
</span>
<?php echo $AppInventory->Url->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_Url">
		<td class="col-sm-2"><span id="elh_AppInventory_Url"><?php echo $AppInventory->Url->FldCaption() ?></span></td>
		<td<?php echo $AppInventory->Url->CellAttributes() ?>>
<span id="el_AppInventory_Url">
<textarea data-table="AppInventory" data-field="x_Url" name="x_Url" id="x_Url" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($AppInventory->Url->getPlaceHolder()) ?>"<?php echo $AppInventory->Url->EditAttributes() ?>><?php echo $AppInventory->Url->EditValue ?></textarea>
</span>
<?php echo $AppInventory->Url->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($AppInventory->Application_Description->Visible) { // Application Description ?>
<?php if ($AppInventory_edit->IsMobileOrModal) { ?>
	<div id="r_Application_Description" class="form-group">
		<label id="elh_AppInventory_Application_Description" for="x_Application_Description" class="<?php echo $AppInventory_edit->LeftColumnClass ?>"><?php echo $AppInventory->Application_Description->FldCaption() ?></label>
		<div class="<?php echo $AppInventory_edit->RightColumnClass ?>"><div<?php echo $AppInventory->Application_Description->CellAttributes() ?>>
<span id="el_AppInventory_Application_Description">
<input type="text" data-table="AppInventory" data-field="x_Application_Description" name="x_Application_Description" id="x_Application_Description" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($AppInventory->Application_Description->getPlaceHolder()) ?>" value="<?php echo $AppInventory->Application_Description->EditValue ?>"<?php echo $AppInventory->Application_Description->EditAttributes() ?>>
</span>
<?php echo $AppInventory->Application_Description->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_Application_Description">
		<td class="col-sm-2"><span id="elh_AppInventory_Application_Description"><?php echo $AppInventory->Application_Description->FldCaption() ?></span></td>
		<td<?php echo $AppInventory->Application_Description->CellAttributes() ?>>
<span id="el_AppInventory_Application_Description">
<input type="text" data-table="AppInventory" data-field="x_Application_Description" name="x_Application_Description" id="x_Application_Description" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($AppInventory->Application_Description->getPlaceHolder()) ?>" value="<?php echo $AppInventory->Application_Description->EditValue ?>"<?php echo $AppInventory->Application_Description->EditAttributes() ?>>
</span>
<?php echo $AppInventory->Application_Description->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($AppInventory->Affected_Users->Visible) { // Affected Users ?>
<?php if ($AppInventory_edit->IsMobileOrModal) { ?>
	<div id="r_Affected_Users" class="form-group">
		<label id="elh_AppInventory_Affected_Users" for="x_Affected_Users" class="<?php echo $AppInventory_edit->LeftColumnClass ?>"><?php echo $AppInventory->Affected_Users->FldCaption() ?></label>
		<div class="<?php echo $AppInventory_edit->RightColumnClass ?>"><div<?php echo $AppInventory->Affected_Users->CellAttributes() ?>>
<span id="el_AppInventory_Affected_Users">
<input type="text" data-table="AppInventory" data-field="x_Affected_Users" name="x_Affected_Users" id="x_Affected_Users" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($AppInventory->Affected_Users->getPlaceHolder()) ?>" value="<?php echo $AppInventory->Affected_Users->EditValue ?>"<?php echo $AppInventory->Affected_Users->EditAttributes() ?>>
</span>
<?php echo $AppInventory->Affected_Users->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_Affected_Users">
		<td class="col-sm-2"><span id="elh_AppInventory_Affected_Users"><?php echo $AppInventory->Affected_Users->FldCaption() ?></span></td>
		<td<?php echo $AppInventory->Affected_Users->CellAttributes() ?>>
<span id="el_AppInventory_Affected_Users">
<input type="text" data-table="AppInventory" data-field="x_Affected_Users" name="x_Affected_Users" id="x_Affected_Users" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($AppInventory->Affected_Users->getPlaceHolder()) ?>" value="<?php echo $AppInventory->Affected_Users->EditValue ?>"<?php echo $AppInventory->Affected_Users->EditAttributes() ?>>
</span>
<?php echo $AppInventory->Affected_Users->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($AppInventory->Status->Visible) { // Status ?>
<?php if ($AppInventory_edit->IsMobileOrModal) { ?>
	<div id="r_Status" class="form-group">
		<label id="elh_AppInventory_Status" class="<?php echo $AppInventory_edit->LeftColumnClass ?>"><?php echo $AppInventory->Status->FldCaption() ?></label>
		<div class="<?php echo $AppInventory_edit->RightColumnClass ?>"><div<?php echo $AppInventory->Status->CellAttributes() ?>>
<span id="el_AppInventory_Status">
<div id="tp_x_Status" class="ewTemplate"><input type="radio" data-table="AppInventory" data-field="x_Status" data-value-separator="<?php echo $AppInventory->Status->DisplayValueSeparatorAttribute() ?>" name="x_Status" id="x_Status" value="{value}"<?php echo $AppInventory->Status->EditAttributes() ?>></div>
<div id="dsl_x_Status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $AppInventory->Status->RadioButtonListHtml(FALSE, "x_Status") ?>
</div></div>
</span>
<?php echo $AppInventory->Status->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_Status">
		<td class="col-sm-2"><span id="elh_AppInventory_Status"><?php echo $AppInventory->Status->FldCaption() ?></span></td>
		<td<?php echo $AppInventory->Status->CellAttributes() ?>>
<span id="el_AppInventory_Status">
<div id="tp_x_Status" class="ewTemplate"><input type="radio" data-table="AppInventory" data-field="x_Status" data-value-separator="<?php echo $AppInventory->Status->DisplayValueSeparatorAttribute() ?>" name="x_Status" id="x_Status" value="{value}"<?php echo $AppInventory->Status->EditAttributes() ?>></div>
<div id="dsl_x_Status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $AppInventory->Status->RadioButtonListHtml(FALSE, "x_Status") ?>
</div></div>
</span>
<?php echo $AppInventory->Status->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($AppInventory->Location->Visible) { // Location ?>
<?php if ($AppInventory_edit->IsMobileOrModal) { ?>
	<div id="r_Location" class="form-group">
		<label id="elh_AppInventory_Location" for="x_Location" class="<?php echo $AppInventory_edit->LeftColumnClass ?>"><?php echo $AppInventory->Location->FldCaption() ?></label>
		<div class="<?php echo $AppInventory_edit->RightColumnClass ?>"><div<?php echo $AppInventory->Location->CellAttributes() ?>>
<span id="el_AppInventory_Location">
<input type="text" data-table="AppInventory" data-field="x_Location" name="x_Location" id="x_Location" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($AppInventory->Location->getPlaceHolder()) ?>" value="<?php echo $AppInventory->Location->EditValue ?>"<?php echo $AppInventory->Location->EditAttributes() ?>>
</span>
<?php echo $AppInventory->Location->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_Location">
		<td class="col-sm-2"><span id="elh_AppInventory_Location"><?php echo $AppInventory->Location->FldCaption() ?></span></td>
		<td<?php echo $AppInventory->Location->CellAttributes() ?>>
<span id="el_AppInventory_Location">
<input type="text" data-table="AppInventory" data-field="x_Location" name="x_Location" id="x_Location" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($AppInventory->Location->getPlaceHolder()) ?>" value="<?php echo $AppInventory->Location->EditValue ?>"<?php echo $AppInventory->Location->EditAttributes() ?>>
</span>
<?php echo $AppInventory->Location->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($AppInventory_edit->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
<?php if (!$AppInventory_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $AppInventory_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $AppInventory_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
<?php if (!$AppInventory_edit->IsMobileOrModal) { ?>
</div><!-- /desktop -->
<?php } ?>
<?php if (!$AppInventory_edit->IsModal) { ?>
<?php if (!isset($AppInventory_edit->Pager)) $AppInventory_edit->Pager = new cPrevNextPager($AppInventory_edit->StartRec, $AppInventory_edit->DisplayRecs, $AppInventory_edit->TotalRecs, $AppInventory_edit->AutoHidePager) ?>
<?php if ($AppInventory_edit->Pager->RecordCount > 0 && $AppInventory_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($AppInventory_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $AppInventory_edit->PageUrl() ?>start=<?php echo $AppInventory_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($AppInventory_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $AppInventory_edit->PageUrl() ?>start=<?php echo $AppInventory_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $AppInventory_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($AppInventory_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $AppInventory_edit->PageUrl() ?>start=<?php echo $AppInventory_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($AppInventory_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $AppInventory_edit->PageUrl() ?>start=<?php echo $AppInventory_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $AppInventory_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
fAppInventoryedit.Init();
</script>
<?php
$AppInventory_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$AppInventory_edit->Page_Terminate();
?>
