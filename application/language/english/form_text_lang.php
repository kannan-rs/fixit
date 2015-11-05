<?php
/*
	Form Labels
*/
$lang['user'] = array(
	'input_form' => array(
		/*
			Following are the (key => "text value") map for the "Users" create and edit form
			Sections
				1. Before Login > Signup form
				2. After Login 	> Security > Users > Create User
				3. After Login 	> Security > Users > Edit User
				4. After Login 	> Home > Personal info > Edit
				5. After Login 	> Projects > Create Project > Add new Customer
				6. After Login > projects > Edit project > Add new customer
		*/
		"privilege" => "Privilege",
		"firstName" => "First Name",
		"lastName" => "Last Name",
		"password" => "Password",
		"confirmPassword" => "Confirm Password",
		"passwordHint" => "Password Hint",
		"belongsTo" => "User Belongs To",
		"contractorZipCode" => "Search Contractor By Zip Code and Select",
		"partnerCompanyName" => "Search Adjuster By Company Name and Select",
		"userStatus" => "User Status",
		"activeStartDate" => "Active Start Date",
		"activeEndDate" => "Active End Date",
		"email" => "Email ID",
		"contactPhoneNumber" => "Contact Phone Number",
		"mobileNumber" => "Mobile Number",
		"primaryContact" => "Set as Primary Contact Number",
		"altNumber" => "Alternate Number",
		"prefMode" => "Prefered Mode for Contact:",
		"prefContactEmailId" => "Email",
		"prefContactContactPhoneNumber" => "Home Phone",
		"prefContactMobileNumber" => "Mobile Number",
		"prefContactAltNumber" => "Alternate Number",
		"referredBy" => "User Referred By:",
		"referredBycontractorZipCode" => "Search Contractor By Zip Code and Select",
		"referredBypartnerCompanyName" => "Search Adjuster By Company Name and Select",

		/*
			Place holder for text input element in Create/Edit form
		*/
		"firstName_ph" => "Enter your First Name",
		"lastName_ph" => "Last Name",
		"password_ph" => "Password",
		"confirmPassword_ph" => "Confirm Password",
		"passwordHint_ph" => "Password Hint",
		"contractorZipCode_ph" => "Zip Code for search",
		"partnerCompanyName_ph" => "adjuster Company Name",
		"activeStartDate_ph" => "Active Start Date",
		"activeEndDate_ph" => "Active End Date",
		"email_ph" => "Email ID",
		"contactPhoneNumber_ph" => "Contact Phone Number",
		"mobileNumber_ph" => "Mobile Number",
		"altNumber_ph" => "Alternate Number",
		"referredBycontractorZipCode_ph" => "Zip Code for search",
		"referredBypartnerCompanyName_ph" => "Adjuster Company Name",

		/*
			Dropdown's Default 1st text,
			this text will be shown if no option is selected in Dropdown under Create/Edit form
		*/
		"privilege_option_0" => "--Select Privilege--",
		"belongsTo_option_0" => "--Select Belongs To--",
		"userStatus_option_0" => "--Select Status--",
		"referredBy_option_0" => "--Select Referred By--"
	),
	
	/*
		Summary view / View All Table's Column header
	*/
	"summary_table" => array(
		"user_name" => "User Name",
		"belongs_to" => "Belongs To",
		"privilege" => "Privilege",
		"actions" => "Actions"
	),

	/*
		Detailed view / individual view Row label
	*/
	"details_view" => array(
		"privilege" => "Privilege",
		"firstName" => "First Name",
		"lastName" => "Last Name",
		"belongsTo" => "User Belongs To",
		"contractor" => "Contractor Company",
		"adjuster" => "Adjuster Company",
		"userStatus" => "User Status",
		"activeStartDate" => "Active Start Date",
		"activeEndDate" => "Active End Date",
		"email" => "Email ID",
		"contactPhoneNumber" => "Contact Phone Number",
		"mobileNumber" => "Mobile Number",
		"altNumber" => "Alternate Number"
	)
);

$lang["role"] = array(
	"input_form" => array (
		/*
			Following are the (key => "text value") map for the "Roles" and edit form
			Sections
				1. After Login 	> Security > Roles > Create Role
				2. After Login 	> Security > Roles > Edit Roles
		*/
		"roleId" => "Role Id",
		"roleName" => "Role Name",
		"roleDescr" => "Role Description",

		/*
			Place holder for text input element in Create/Edit form
		*/
		"roleId_ph" => "Role Id",
		"roleName_ph" => "Role Name",
		"roleDescr_ph" => "Role Description",
	)
);

$lang["operation"] = array(
	"input_form" => array (
		/*
			Following are the (key => "text value") map for the "Operations" create and edit form
			Sections
				1. After Login 	> Security > Operations > Create Operation
				2. After Login 	> Security > Operations > Edit Operation
		*/
		"operationId" => "Operation Id",
		"operationName" => "Operation Name",
		"operationDescr" => "Operation Description",

		/*
			Place holder for text input element in Create/Edit form
		*/
		"operationId_ph" => "Operation Id",
		"operationName_ph" => "Operation Name",
		"operationDescr_ph" => "Operation Description"
	)
);

$lang["function"] = array(
	"input_form" => array (
		/*
			Following are the (key => "text value") map for the "Functions" create and edit form
			Sections
				1. After Login 	> Security > Functions > Create Function
				2. After Login 	> Security > Functions > Edit Function
		*/
		"functionId" => "Function Id",
		"functionName" => "Function Name",
		"functionDescr" => "Function Description",

		/*
			Place holder for text input element in Create/Edit form
		*/
		"functionId_ph" => "Function Id",
		"functionName_ph" => "Function Name",
		"functionDescr_ph" => "Function Description"
	)
);

$lang["data_filter"] = array(
	"input_form" => array (
		/*
			Following are the (key => "text value") map for the "Data Filters" create and edit form
			Sections
				1. After Login 	> Security > Data Filters > Create Data Filter
				2. After Login 	> Security > Data Filters > Edit Data Filter
		*/
		"dataFilterId" => "Data Filter Id",
		"dataFilterName" => "Data Filter Name",
		"dataFilterDescr" => "Data Filter Description",

		/*
			Place holder for text input element in Create/Edit form
		*/
		"dataFilterId_ph" => "Data Filter Id",
		"dataFilterName_ph" => "Data Filter Name",
		"dataFilterDescr_ph" => "Data Filter Description"
	)
);

$lang["permission"] = array(
	"header" => array (
		"user" => "Users",
		"role" => "Role",
		"operation" => "Operations",
		"function" => "Functions",
		"data_filter" => "Data Filters"
	),
	"input_form" => array(
		/*
			Following are the (key => "text value") map for the "Permissions" create and edit form
			Sections
				1. After Login 	> Security > Permissions > Create Permission
				2. After Login 	> Security > Permissions > Edit Permission
		*/
		"user" => "Select Users",
		"role" => "Select Roles",
		"operation" => "Select Operations",
		"function" => "Select Functions",
		"data_filter" => "Select Data Filters",

		/*
			Dropdown's Default 1st text,
			this text will be shown if no option is selected in Dropdown under Create/Edit form
		*/
		"user_option_0" => "--Select Users--"
	)
);

$lang["projects"] = array (
	"input_form" => array(
		/*
			Following are the (key => "text value") map for the "Projects" create and edit form
			Sections
				1. After Login 	> Projects > Create Project
				2. After Login 	> Projects > Projects > Individual project view > Edit Project
		*/
		"projectTitle" => "Project Title",
		"description" => "Description",
		"project_type" => "Project Type",
		"project_status" => "Project Status",
		"start_date" => "Project Start Date",
		"end_date" => "Project End Date",
		"contractorZipCode" => "Search Contractor By Zip Code",
		"project_budget" => "Project Budget",
		"lend_amount" => "Loan Amount",
		"project_lender" => "Project Lender",
		"deductible" => "Deductible",
		"property_owner_id" => "Property Owner ID",
		"searchCustomerName" => "Customer Name",
		"searchAdjusterName" => "Search Adjuster By Name or Company Name",
		"associated_claim_num" => "Associated Claim Number",

		/*
			Place holder for text input element in Create/Edit form
		*/
		"projectTitle_ph" => "Project Title",
		"description_ph" => "Description",
		"start_date_ph" => "Project Start Date",
		"end_date_ph" => "Project End Date",
		"contractorZipCode_ph" => "Zip Code for search",
		"project_budget_ph" => "Project Budget",
		"lend_amount_ph" => "Loan Amount",
		"project_lender_ph" => "Project Lender",
		"deductible_ph" => "Deductible",
		"property_owner_id_ph" => "Property Owner ID",
		"searchCustomerName_ph" => "Customer Name",
		"searchAdjusterName_ph" => "Search Adjuster By Name or Company Name",
		"associated_claim_num_ph" => "Associated Claim Number",

		/*
			Dropdown's Default 1st text,
			this text will be shown if no option is selected in Dropdown under Create/Edit form
		*/
		"project_type_option_0" => "--Select Project Type--",
		"project_status_option_0" => "--Select Project Status--"
	)
);

$lang["contractor"] = array(
	"input_form" => array(
		/*
			Following are the (key => "text value") map for the "Contractors" create and edit form
			Sections
				1. After Login 	> Projects > Create Contractor
				2. After Login 	> Projects > Contractors > Individual Contractor View > Edit Contractor
				3. After Login 	> Projects > Create Project > Add New Contractor
				4. After Login 	> Projects > Edit Project > Add New Contractor
		*/
		"name" => "Name",
		"company" => "Company",
		"type" => "Type",
		"license" => "License",
		"bbb" => "BBB",
		"status" => "Status",
		"emailId" => "Office Email ID",
		"contactPhoneNumber" => "Office Number",
		"mobileNumber" => "Mobile Number",
		"prefMode" => "Prefered Mode for Contact",
		"prefContactEmailId" => "Email",
		"prefContactofficeNumber" => "Office Phone",
		"prefContactMobileNumber" => "Mobile Number",
		"websiteURL" => "Website URL",
		"serviceZip" => "Zip codes of Available Service Area",
		"searchForDefaultContractor" => "Assign Default Contractor",

		/*
			Place holder for text input element in Create/Edit form
		*/
		"name_ph" => "Contractor Name",
		"company_ph" => "Company Name",
		"type_ph" => "Contractor Type",
		"license_ph" => "Contractor License",
		"bbb_ph" => "BBB",
		"emailId_ph" => "Email ID",
		"contactPhoneNumber_ph" => "Contact Phone Number",
		"mobileNumber_ph" => "Contact Mobile Number",
		"websiteURL_ph" => "Website URL",
		"serviceZip_ph" => "Zip codes of Available Service Area",
		"searchForDefaultContractor_ph" => "Email ID of default contractor",

		/*
			Dropdown's Default 1st text,
			this text will be shown if no option is selected in Dropdown under Create/Edit form
		*/
		"status_option_0" => "--Select Status--",
	)
);

$lang["partner"] = array(
	"input_form" => array(
		/*
			Following are the (key => "text value") map for the "Partners / Adjusters" create and edit form
			Sections
				1. After Login 	> Projects > Create Partner
				2. After Login 	> Projects > Partners > Individual Partner / Adjuster View > Edit Partner / Adjuster
				3. After Login 	> Projects > Create Project > Add New Partner / Adjuster
				4. After Login 	> Projects > Edit Project > Add New Partner / Adjuster
		*/
		"name" => "Partner Name:",
		"company" => "Company Name",
		"type" => "Type",
		"license" => "License",
		"status" => "Status",
		"wNumber" => "Office Number",
		"wEmailId" => "Office Email ID",
		"pNumber" => "Mobile Number",
		"pEmailId" => "Personal Email ID",
		"prefMode" => "Prefered Mode for Contact",
		"prefwNumber" => "Office Phone Number",
		"prefwEmailId" => "Office Email ID",
		"prefmNumber" => "Personal Mobile Number",
		"prefwEmailId" => "Personal Email ID",
		"websiteURL" => "Website URL",

		/*
			Place holder for text input element in Create/Edit form
		*/
		"name_ph" => "Partner Name:",
		"company_ph" => "Company Name",
		"type_ph" => "Partner Type",
		"license_ph" => "Partner License",
		"wNumber_ph" => "Office Phone Number",
		"wEmailId_ph" => "Office Email ID",
		"pNumber_ph" => "Contact Mobile Number",
		"pEmailId_ph" => "Personal Email ID",
		"websiteURL_ph" => "Website URL",

		/*
			Dropdown's Default 1st text,
			this text will be shown if no option is selected in Dropdown under Create/Edit form
		*/
		"status_option_0" => "--Select Status--"
	)
);

$lang["tasks"] = array(
	"input_form" => array(
		/*
			Following are the (key => "text value") map for the "Tasks" create and edit form
			Sections
				1. After Login 	> Projects > Individual Project View > Task List > Create Task
				2. After Login 	> Projects > Individual Project View > Task List > Edit Task
		*/
		"task_name" => "Task Title",
		"task_desc" => "Description",
		"task_start_date" => "Start Date",
		"task_end_date" => "End Date",
		"task_status" => "Status",
		"task_percent_complete" => "% Complete",
		"taskOwnerId" => "Choose Owner",
		"task_dependency" => "Dependency",
		"task_trade_type" => "Trade Type",

		/*
			Place holder for text input element in Create/Edit form
		*/
		"task_name_ph" => "Task Title",
		"task_desc_ph" => "Description",
		"task_start_date_ph" => "Start Date",
		"task_end_date_ph" => "End Date",
		"task_percent_complete_ph" => "% Complete",
		"task_dependency_ph" => "Dependency",
		"task_trade_type_ph" => "Trade Type",

		/*
			Dropdown's Default 1st text,
			this text will be shown if no option is selected in Dropdown under Create/Edit form
		*/
		"task_status_option_0" => "--Select Task Status--",
	)
);

$lang["issues"] = array(
	"input_form" => array(
		/*
			Following are the (key => "text value") map for the "Issues" create and edit form
			Sections
				1. After Login 	> Projects > Project List > Issues List > Create Issue (If issue exist for the project then this option is available)
				2. After Login 	> Projects > Project List > Issues List > Edit Issue (If issue exist for the project then this option is available)
				1. After Login 	> Projects > Individual Project View > Issues List > Create Issue
				2. After Login 	> Projects > Individual Project View > Issues List > Edit Issue
		*/
		"issueName" => "Issue Name",
		"issueDescr" => "Issue Description",
		"assignedToUserType" => "Assigned to",
		"issueAssignedToCustomer" => "Assigned to Customer",
		"issueContractorResult" => "Select Assigned to Contractor",
		"issueAdjusterResult" => "Select Assigned to Adjuster",
		"issueFromdate" => "Issue From Date",
		"issueStatus" => "Issue Status",
		"issueNotes" => "Issue Notes",

		/*
			Place holder for text input element in Create/Edit form
		*/
		"issueName_ph" => "Issue Name",
		"issueDescr_ph" => "Issue Description",
		"issueAssignedToCustomer_ph" => "Assigned To Customer",
		"issueFromdate_ph" => "Issue From Date",
		"issueNotes_ph" => "Issue Notes",

		/*
			Dropdown's Default 1st text,
			this text will be shown if no option is selected in Dropdown under Create/Edit form
		*/
		"assignedToUserType_option_0" => "--Select User Type--",
		"issueStatus_option_0" => "--Select Status--"
	)
);

$lang["docs"] = array(
	"input_form" => array(
		/*
			Following are the (key => "text value") map for the "Documents" create and edit form
			Sections
				1. After Login 	> Projects > Individual Project View > Documents List > Add Document
		*/
		"docName" => "Document Name",
		"docAttachment" => "Choose Document",

		/*
			Place holder for text input element in Create/Edit form
		*/
		"docName_ph" => "Document Name",
		"docAttachment_ph" => "Choose Document",
	)
);

$lang["notes"] = array(
	"input_form" => array(
		/*
			Following are the (key => "text value") map for the "Notes" create and edit form
			Sections
				1. After Login 	> Projects > Individual Project View > Notes List > Add Note
				2. After Login 	> Projects > Individual Project View > Issue List > Notes List > Add Note
		*/
		"noteContent" => "Notes Content",

		/*
			Place holder for text input element in Create/Edit form
		*/
		"noteContent_ph" => "Notes Content"
	)
);

$lang["budget"] = array(
	"input_form" => array(
		/*
			Following are the (key => "text value") map for the "Budget" create and edit form
			Sections
				1. After Login 	> Projects > Individual Project View > Budget List > Update Budget
				2. After Login 	> Projects > Individual Project View > Budget List > Update Budget> Edit Budget
		*/
		"descr" => "Description",
		"amount" => "Amount",
		"date" => "Date",

		/*
			Place holder for text input element in Create/Edit form
		*/
		"descr_ph" => "Description",
		"amount_ph" => "Paid Amount",
		"date_ph" => "Payment Date",
	)
);

$lang["login"] = array(
	"input_form" => array(
		/*
			Following are the (key => "text value") map for the "Login" create and edit form
			Sections
				1. Before Login > Login form
		*/
		"login_email" => "user Name",
		"login_password" => "Password",

		/*
			Place holder for text input element in Create/Edit form
		*/
		"login_email_ph" => "Email id",
		"login_password_ph" => "Password"
	)
);

$lang["change_password"] = array(
	"input_form" => array(
		/*
			Following are the (key => "text value") map for the "Change Password" create and edit form
			Sections
				1. After Login 	> Home > Change Password
		*/
		"password" => "Password",
		"confirmPassword" => "Confirm Password",
		"passwordHint" => "Password Hint",

		/*
			Place holder for text input element in Create/Edit form
		*/
		"password_ph" => "Password",
		"confirmPassword_ph" => "Confirm Password",
		"passwordHint_ph" => "Password Hint"
	)
);

$lang["address"] = array(
	"input_form" => array(
		/*
			Following are the (key => "text value") map for the "Address" create and edit form
			Sections
				1. Before Login > Signup form
				2. After Login 	> Where ever address applicable
		*/
		"addressLine1" => "Address Line1",
		"addressLine2" => "Address Line2",
		"city" => "City",
		"state" => "State",
		"zipCode" => "Zip Code",
		"country" => "Country",

		/*
			Place holder for text input element in Create/Edit form
		*/
		"addressLine1_ph" => "Address Line #1",
		"addressLine2_ph" => "Address Line #2",
		"zipCode_ph" => "Zip Code",

		/*
			Dropdown's Default 1st text,
			this text will be shown if no option is selected in Dropdown under Create/Edit form
		*/
		"state_option_0" => "--Select State--",
		"country_option_0" => "--Select Country--"
	)
);