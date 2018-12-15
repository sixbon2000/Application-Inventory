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

$AppInventory_add = NULL; // Initialize page object first

class cAppInventory_add extends cAppInventory {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{F928ACD0-3C23-4A28-A63B-8FA9605A2019}';

	// Table name
	var $TableName = 'AppInventory';

	// Page object name
	var $PageObjName = 'AppInventory_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		$this->FormClassName = "ewForm ewAddForm form-horizontal";

		// Set up current action
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["ID"] != "") {
				$this->ID->setQueryStringValue($_GET["ID"]);
				$this->setKey("ID", $this->ID->CurrentValue); // Set up key
			} else {
				$this->setKey("ID", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Load old record / default values
		$loaded = $this->LoadOldRecord();

		// Load form values
		if (@$_POST["a_add"] <> "") {
			$this->LoadFormValues(); // Load form values
		}

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform current action
		switch ($this->CurrentAction) {
			case "I": // Blank record
				break;
			case "C": // Copy an existing record
				if (!$loaded) { // Record not loaded
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("AppInventorylist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "AppInventorylist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "AppInventoryview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to View page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->ID->CurrentValue = NULL;
		$this->ID->OldValue = $this->ID->CurrentValue;
		$this->Applications->CurrentValue = NULL;
		$this->Applications->OldValue = $this->Applications->CurrentValue;
		$this->Associated_Apps2F_Service->CurrentValue = NULL;
		$this->Associated_Apps2F_Service->OldValue = $this->Associated_Apps2F_Service->CurrentValue;
		$this->IP_Address->CurrentValue = NULL;
		$this->IP_Address->OldValue = $this->IP_Address->CurrentValue;
		$this->System_Name->CurrentValue = NULL;
		$this->System_Name->OldValue = $this->System_Name->CurrentValue;
		$this->Support_Team->CurrentValue = NULL;
		$this->Support_Team->OldValue = $this->Support_Team->CurrentValue;
		$this->Vendor->CurrentValue = NULL;
		$this->Vendor->OldValue = $this->Vendor->CurrentValue;
		$this->Url->CurrentValue = NULL;
		$this->Url->OldValue = $this->Url->CurrentValue;
		$this->Application_Description->CurrentValue = NULL;
		$this->Application_Description->OldValue = $this->Application_Description->CurrentValue;
		$this->Affected_Users->CurrentValue = NULL;
		$this->Affected_Users->OldValue = $this->Affected_Users->CurrentValue;
		$this->Status->CurrentValue = NULL;
		$this->Status->OldValue = $this->Status->CurrentValue;
		$this->Location->CurrentValue = NULL;
		$this->Location->OldValue = $this->Location->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
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
		$this->LoadDefaultValues();
		$row = array();
		$row['ID'] = $this->ID->CurrentValue;
		$row['Applications'] = $this->Applications->CurrentValue;
		$row['Associated Apps/ Service'] = $this->Associated_Apps2F_Service->CurrentValue;
		$row['IP Address'] = $this->IP_Address->CurrentValue;
		$row['System Name'] = $this->System_Name->CurrentValue;
		$row['Support Team'] = $this->Support_Team->CurrentValue;
		$row['Vendor'] = $this->Vendor->CurrentValue;
		$row['Url'] = $this->Url->CurrentValue;
		$row['Application Description'] = $this->Application_Description->CurrentValue;
		$row['Affected Users'] = $this->Affected_Users->CurrentValue;
		$row['Status'] = $this->Status->CurrentValue;
		$row['Location'] = $this->Location->CurrentValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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

			// Add refer script
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// Applications
		$this->Applications->SetDbValueDef($rsnew, $this->Applications->CurrentValue, NULL, FALSE);

		// Associated Apps/ Service
		$this->Associated_Apps2F_Service->SetDbValueDef($rsnew, $this->Associated_Apps2F_Service->CurrentValue, NULL, FALSE);

		// IP Address
		$this->IP_Address->SetDbValueDef($rsnew, $this->IP_Address->CurrentValue, NULL, FALSE);

		// System Name
		$this->System_Name->SetDbValueDef($rsnew, $this->System_Name->CurrentValue, NULL, FALSE);

		// Support Team
		$this->Support_Team->SetDbValueDef($rsnew, $this->Support_Team->CurrentValue, NULL, FALSE);

		// Vendor
		$this->Vendor->SetDbValueDef($rsnew, $this->Vendor->CurrentValue, NULL, FALSE);

		// Url
		$this->Url->SetDbValueDef($rsnew, $this->Url->CurrentValue, NULL, FALSE);

		// Application Description
		$this->Application_Description->SetDbValueDef($rsnew, $this->Application_Description->CurrentValue, NULL, FALSE);

		// Affected Users
		$this->Affected_Users->SetDbValueDef($rsnew, $this->Affected_Users->CurrentValue, NULL, FALSE);

		// Status
		$this->Status->SetDbValueDef($rsnew, $this->Status->CurrentValue, NULL, FALSE);

		// Location
		$this->Location->SetDbValueDef($rsnew, $this->Location->CurrentValue, NULL, FALSE);

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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("AppInventorylist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($AppInventory_add)) $AppInventory_add = new cAppInventory_add();

// Page init
$AppInventory_add->Page_Init();

// Page main
$AppInventory_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$AppInventory_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fAppInventoryadd = new ew_Form("fAppInventoryadd", "add");

// Validate form
fAppInventoryadd.Validate = function() {
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
fAppInventoryadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fAppInventoryadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fAppInventoryadd.Lists["x_Support_Team"] = {"LinkField":"x_Support_Team","Ajax":true,"AutoFill":false,"DisplayFields":["x_Support_Team","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"Support_Teams"};
fAppInventoryadd.Lists["x_Support_Team"].Data = "<?php echo $AppInventory_add->Support_Team->LookupFilterQuery(FALSE, "add") ?>";
fAppInventoryadd.Lists["x_Vendor"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fAppInventoryadd.Lists["x_Vendor"].Options = <?php echo json_encode($AppInventory_add->Vendor->Options()) ?>;
fAppInventoryadd.Lists["x_Status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fAppInventoryadd.Lists["x_Status"].Options = <?php echo json_encode($AppInventory_add->Status->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $AppInventory_add->ShowPageHeader(); ?>
<?php
$AppInventory_add->ShowMessage();
?>
<form name="fAppInventoryadd" id="fAppInventoryadd" class="<?php echo $AppInventory_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($AppInventory_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $AppInventory_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="AppInventory">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($AppInventory_add->IsModal) ?>">
<?php if (!$AppInventory_add->IsMobileOrModal) { ?>
<div class="ewDesktop"><!-- desktop -->
<?php } ?>
<?php if ($AppInventory_add->IsMobileOrModal) { ?>
<div class="ewAddDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_AppInventoryadd" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($AppInventory->Applications->Visible) { // Applications ?>
<?php if ($AppInventory_add->IsMobileOrModal) { ?>
	<div id="r_Applications" class="form-group">
		<label id="elh_AppInventory_Applications" for="x_Applications" class="<?php echo $AppInventory_add->LeftColumnClass ?>"><?php echo $AppInventory->Applications->FldCaption() ?></label>
		<div class="<?php echo $AppInventory_add->RightColumnClass ?>"><div<?php echo $AppInventory->Applications->CellAttributes() ?>>
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
<?php if ($AppInventory_add->IsMobileOrModal) { ?>
	<div id="r_Associated_Apps2F_Service" class="form-group">
		<label id="elh_AppInventory_Associated_Apps2F_Service" for="x_Associated_Apps2F_Service" class="<?php echo $AppInventory_add->LeftColumnClass ?>"><?php echo $AppInventory->Associated_Apps2F_Service->FldCaption() ?></label>
		<div class="<?php echo $AppInventory_add->RightColumnClass ?>"><div<?php echo $AppInventory->Associated_Apps2F_Service->CellAttributes() ?>>
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
<?php if ($AppInventory_add->IsMobileOrModal) { ?>
	<div id="r_IP_Address" class="form-group">
		<label id="elh_AppInventory_IP_Address" for="x_IP_Address" class="<?php echo $AppInventory_add->LeftColumnClass ?>"><?php echo $AppInventory->IP_Address->FldCaption() ?></label>
		<div class="<?php echo $AppInventory_add->RightColumnClass ?>"><div<?php echo $AppInventory->IP_Address->CellAttributes() ?>>
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
<?php if ($AppInventory_add->IsMobileOrModal) { ?>
	<div id="r_System_Name" class="form-group">
		<label id="elh_AppInventory_System_Name" for="x_System_Name" class="<?php echo $AppInventory_add->LeftColumnClass ?>"><?php echo $AppInventory->System_Name->FldCaption() ?></label>
		<div class="<?php echo $AppInventory_add->RightColumnClass ?>"><div<?php echo $AppInventory->System_Name->CellAttributes() ?>>
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
<?php if ($AppInventory_add->IsMobileOrModal) { ?>
	<div id="r_Support_Team" class="form-group">
		<label id="elh_AppInventory_Support_Team" for="x_Support_Team" class="<?php echo $AppInventory_add->LeftColumnClass ?>"><?php echo $AppInventory->Support_Team->FldCaption() ?></label>
		<div class="<?php echo $AppInventory_add->RightColumnClass ?>"><div<?php echo $AppInventory->Support_Team->CellAttributes() ?>>
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
<?php if ($AppInventory_add->IsMobileOrModal) { ?>
	<div id="r_Vendor" class="form-group">
		<label id="elh_AppInventory_Vendor" class="<?php echo $AppInventory_add->LeftColumnClass ?>"><?php echo $AppInventory->Vendor->FldCaption() ?></label>
		<div class="<?php echo $AppInventory_add->RightColumnClass ?>"><div<?php echo $AppInventory->Vendor->CellAttributes() ?>>
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
<?php if ($AppInventory_add->IsMobileOrModal) { ?>
	<div id="r_Url" class="form-group">
		<label id="elh_AppInventory_Url" for="x_Url" class="<?php echo $AppInventory_add->LeftColumnClass ?>"><?php echo $AppInventory->Url->FldCaption() ?></label>
		<div class="<?php echo $AppInventory_add->RightColumnClass ?>"><div<?php echo $AppInventory->Url->CellAttributes() ?>>
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
<?php if ($AppInventory_add->IsMobileOrModal) { ?>
	<div id="r_Application_Description" class="form-group">
		<label id="elh_AppInventory_Application_Description" for="x_Application_Description" class="<?php echo $AppInventory_add->LeftColumnClass ?>"><?php echo $AppInventory->Application_Description->FldCaption() ?></label>
		<div class="<?php echo $AppInventory_add->RightColumnClass ?>"><div<?php echo $AppInventory->Application_Description->CellAttributes() ?>>
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
<?php if ($AppInventory_add->IsMobileOrModal) { ?>
	<div id="r_Affected_Users" class="form-group">
		<label id="elh_AppInventory_Affected_Users" for="x_Affected_Users" class="<?php echo $AppInventory_add->LeftColumnClass ?>"><?php echo $AppInventory->Affected_Users->FldCaption() ?></label>
		<div class="<?php echo $AppInventory_add->RightColumnClass ?>"><div<?php echo $AppInventory->Affected_Users->CellAttributes() ?>>
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
<?php if ($AppInventory_add->IsMobileOrModal) { ?>
	<div id="r_Status" class="form-group">
		<label id="elh_AppInventory_Status" class="<?php echo $AppInventory_add->LeftColumnClass ?>"><?php echo $AppInventory->Status->FldCaption() ?></label>
		<div class="<?php echo $AppInventory_add->RightColumnClass ?>"><div<?php echo $AppInventory->Status->CellAttributes() ?>>
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
<?php if ($AppInventory_add->IsMobileOrModal) { ?>
	<div id="r_Location" class="form-group">
		<label id="elh_AppInventory_Location" for="x_Location" class="<?php echo $AppInventory_add->LeftColumnClass ?>"><?php echo $AppInventory->Location->FldCaption() ?></label>
		<div class="<?php echo $AppInventory_add->RightColumnClass ?>"><div<?php echo $AppInventory->Location->CellAttributes() ?>>
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
<?php if ($AppInventory_add->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
<?php if (!$AppInventory_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $AppInventory_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $AppInventory_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
<?php if (!$AppInventory_add->IsMobileOrModal) { ?>
</div><!-- /desktop -->
<?php } ?>
</form>
<script type="text/javascript">
fAppInventoryadd.Init();
</script>
<?php
$AppInventory_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$AppInventory_add->Page_Terminate();
?>
