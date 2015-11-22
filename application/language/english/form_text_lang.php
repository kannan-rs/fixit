<?php
$lang["buttons"] = array(
	"reset" 	=> "Reset",
	"ok" 		=> "OK",
	"cancel" 	=> "Cancel",
	"clear" 	=> "Clear"
);

$lang["headers"] = array(
	"existing_user" => "Existing User? ##replace1## Sign in ##replace2##",
	"new_user" 		=> "New to the Fixit Network?  ##replace1## Signup ##replace2##"
);
/*
	Form Labels
*/
$lang['user'] = array(
	/*
		Title, Header and sub header text
	*/
	"headers" => array(
		"admin_edit" 		=> "Edit Users",
		"self_edit" 		=> "Edit Personal Details",
		"admin_create" 		=> "Create User",
		"enroll" 			=> "Account Creation",
		"view_all" 			=> "View All Users",
		"admin_view_one" 	=> "View User Details",
		"view_one" 			=> "Personal Details"
	),
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
		"privilege" 					=> "Privilege",
		"firstName" 					=> "First Name",
		"lastName" 						=> "Last Name",
		"password" 						=> "Password",
		"confirmPassword" 				=> "Confirm Password",
		"passwordHint" 					=> "Password Hint",
		"belongsTo" 					=> "User Belongs To",
		"contractorZipCode" 			=> "Search Contractor By Zip Code and Select",
		"partnerCompanyName" 			=> "Search Adjuster By Company Name and Select",
		"userStatus" 					=> "User Status",
		"activeStartDate" 				=> "Active Start Date",
		"activeEndDate" 				=> "Active End Date",
		"email" 						=> "Email ID",
		"contactPhoneNumber" 			=> "Contact Phone Number",
		"mobileNumber" 					=> "Mobile Number",
		"primaryContact" 				=> "Set as Primary Contact Number",
		"altNumber" 					=> "Alternate Number",
		"prefMode" 						=> "Prefered Mode for Contact:",
		"prefContactEmailId" 			=> "Email",
		"prefContactContactPhoneNumber" => "Home Phone",
		"prefContactMobileNumber" 		=> "Mobile Number",
		"prefContactAltNumber" 			=> "Alternate Number",
		"referredBy" 					=> "User Referred By:",
		"referredBycontractorZipCode" 	=> "Search Contractor By Zip Code and Select",
		"referredBypartnerCompanyName" 	=> "Search Adjuster By Company Name and Select",
		"tc" 							=> "I agree to the Fixit Network ##replace1## Terms and Condition ##replace2## and ##replace3## Privacy Policy ##replace4##",
		"tc_error" 						=> "You must agree to the Fixit Network's Terms of Conditions and Privacy Policy, in order to use our services.",

		/*
			Place holder for text input element in Create/Edit form
		*/
		"firstName_ph" 						=> "Enter your First Name",
		"lastName_ph" 						=> "Last Name",
		"password_ph" 						=> "Password",
		"confirmPassword_ph" 				=> "Confirm Password",
		"passwordHint_ph" 					=> "Password Hint",
		"contractorZipCode_ph" 				=> "Zip Code for search",
		"partnerCompanyName_ph" 			=> "adjuster Company Name",
		"activeStartDate_ph" 				=> "Active Start Date",
		"activeEndDate_ph" 					=> "Active End Date",
		"email_ph" 							=> "Email ID",
		"contactPhoneNumber_ph" 			=> "Contact Phone Number",
		"mobileNumber_ph" 					=> "Mobile Number",
		"altNumber_ph" 						=> "Alternate Number",
		"referredBycontractorZipCode_ph" 	=> "Zip Code for search",
		"referredBypartnerCompanyName_ph" 	=> "Adjuster Company Name",

		/*
			Dropdown's Default 1st text,
			this text will be shown if no option is selected in Dropdown under Create/Edit form
		*/
		"privilege_option_0" 	=> "--Select Privilege--",
		"belongsTo_option_0" 	=> "--Select Belongs To--",
		"userStatus_option_0" 	=> "--Select Status--",
		"referredBy_option_0" 	=> "--Select Referred By--"
	),
	
	/*
		Summary view / View All Table's Column header
	*/
	"summary_table" => array(
		"user_name" 	=> "User Name",
		"belongs_to" 	=> "Belongs To",
		"privilege" 	=> "Privilege",
		"actions" 		=> "Actions"
	),

	/*
		Detailed view / individual view Row label
	*/
	"details_view" => array(
		"privilege" 			=> "Privilege",
		"firstName" 			=> "First Name",
		"lastName" 				=> "Last Name",
		"belongsTo" 			=> "User Belongs To",
		"contractor" 			=> "Contractor Company",
		"adjuster" 				=> "Adjuster Company",
		"userStatus" 			=> "User Status",
		"activeStartDate" 		=> "Active Start Date",
		"activeEndDate" 		=> "Active End Date",
		"email" 				=> "Email ID",
		"contactPhoneNumber" 	=> "Contact Phone Number",
		"mobileNumber" 			=> "Mobile Number",
		"altNumber" 			=> "Alternate Number",
		"primaryContact" 		=> "Primary Contact Number",
		"prefMode" 				=> "Prefered Mode for Contact",
		"pref_email" 			=> "Email",
		"pref_home_phone" 		=> "Home Phone",
		"pref_mobile_number" 	=> "Mobile Number",
		"pref_alt_number" 		=> "Alternate Number"
	),
	/*
		Button text and link text
	*/
	"buttons_links" => array(
		"create" 		=> "Create User",
		"edit" 			=> "Edit",
		"delete" 		=> "Delete",
		"edit_details" 	=> "Edit Details"
	)
);

$lang["role"] = array(
	/*
		Title, Header and sub header text
	*/
	"headers" => array(
		"create" 	=> "Create Role",
		"update" 	=> "Edit Role",
		"view_all" 	=> "View All Roles",
		"view_one" 	=> "View Role Details"
	),
	"input_form" => array (
		/*
			Following are the (key => "text value") map for the "Roles" and edit form
			Sections
				1. After Login 	> Security > Roles > Create Role
				2. After Login 	> Security > Roles > Edit Roles
		*/
		"roleId" 		=> "Role Id",
		"roleName" 		=> "Role Name",
		"roleDescr" 	=> "Role Description",

		/*
			Place holder for text input element in Create/Edit form
		*/
		"roleId_ph" 	=> "Role Id",
		"roleName_ph" 	=> "Role Name",
		"roleDescr_ph" 	=> "Role Description",
	),
	
	/*
		Summary view / View All Table's Column header
	*/
	"summary_table" => array(
		"sno" 			=> "Sno",
		"roleId" 		=> "Role ID",
		"roleName" 		=> "Role Name",
		"roleDescr" 	=> "Role Descr",
		"action" 		=> "Action"
	),

	/*
		Detailed view / individual view Row label
	*/
	"details_view" => array(
		"roleId" 		=> "Role ID",
		"roleName" 		=> "Role Name",
		"roleDescr" 	=> "Role Description",
	),
	/*
		Button text and link text
	*/
	"buttons_links" => array(
		"create" => "Create Role",
		"update" => "Update Role",
		"edit" => "Edit",
		"delete" => "Delete"
	)
);

$lang["operation"] = array(
	/*
		Title, Header and sub header text
	*/
	"headers" => array(
		"create" => "Create Operation",
		"update" => "Update Operation",
		"view_all" => "View All Operations",
		"view_one" => "View Operation Details"
	),
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
	),
	
	/*
		Summary view / View All Table's Column header
	*/
	"summary_table" => array(
		"sno" => "Sno",
		"operationId" => "Operation ID",
		"operationName" => "Operation Name",
		"operationDescr" => "Operation Descr",
		"action" => "Action"
	),

	/*
		Detailed view / individual view Row label
	*/
	"details_view" => array(
		"operationId" => "Operation ID",
		"operationName" => "Operation Name",
		"operationDescr" => "Operation Description"
	),
	/*
		Button text and link text
	*/
	"buttons_links" => array(
		"create" => "Create Operation",
		"update" => "Update Operation",
		"edit" => "Edit",
		"delete" => "Delete"
	)
);

$lang["function"] = array(
	/*
		Title, Header and sub header text
	*/
	"headers" => array(
		"create" => "Create Function",
		"update" => "Edit Function",
		"view_all" => "View All Functions",
		"view_one" => "View Function Details"
	),
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
	),
	
	/*
		Summary view / View All Table's Column header
	*/
	"summary_table" => array(
		"sno" => "Sno",
		"functionId" => "Function ID",
		"functionName" => "Function Name",
		"functionDescr" => "Function Descr",
		"action"=> "Action"
	),

	/*
		Detailed view / individual view Row label
	*/
	"details_view" => array(
		"functionId" => "Function ID",
		"functionName" => "Function Name",
		"functionDescr" => "Function Description"
	),
	/*
		Button text and link text
	*/
	"buttons_links" => array(
		"create" => "Create Function",
		"update" => "Update Function",
		"edit" => "Edit",
		"delete" => "Delete"
	)
);

$lang["data_filter"] = array(
	/*
		Title, Header and sub header text
	*/
	"headers" => array(
		"create" => "Create Data Filter",
		"update" => "Edit Data Filter",
		"view_all" => "View All Data Filters",
		"view_one" => "View Data Filter Details"
	),
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
	),
	
	/*
		Summary view / View All Table's Column header
	*/
	"summary_table" => array(
		"sno" => "Sno",
		"dataFilterId" => "Data Filter ID",
		"dataFilterName" => "Data Filter Name",
		"dataFilterDescr" => "Data Filter Descr",
		"action" => "Action"
	),

	/*
		Detailed view / individual view Row label
	*/
	"details_view" => array(
		"dataFilterId" => "Data Filter ID",
		"dataFilterName" => "Data Filter Name",
		"dataFilterDescr" => "Data Filter Description",
	),
	/*
		Button text and link text
	*/
	"buttons_links" => array(
		"create" => "Create Data Filter",
		"update" => "Update Data Filter",
		"edit" => "Edit",
		"delete" => "Delete"
	)
);

$lang["permission"] = array(
	/*
		Title, Header and sub header text
	*/
	"headers" => array (
		"title" => "Permissions",
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
	),
	/*
		Button text and link text
	*/
	"buttons_links" => array(
		"set" => "Set Permissions"
	)
);

$lang["projects"] = array (
	/*
		Title, Header and sub header text
	*/
	"headers" => array(
		"customer_details" => "Customer Details",
		"create" => "Create Project",
		"update" => "Update Project",
		"view_all" => "List of Projects",
		"view_one" => "Project Details",
		"project_description" => "Project Description",
		"project_date" => "Project Date",
		"project_address" => "Project Address",
		"budget" => "Budget",
		"contractor_details" => "Contractor Details",
		"contractor_name" => "Contractor Name",
		"partners_details" => "Partner Details",
		"partner_name" => "Partner Name",
		"tasks_list" => "Tasks List",
		"notes" => "Notes",
		"documents" => "Documents",
		"insurance_details" => "Insurance Details"
	),
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
	),
	
	/*
		Summary view / View All Table's Column header
	*/
	"summary_table" => array(
		"project_name" => "Project Name",
		"complete" => "% Complete",
		"start_date" => "Start Date",
		"end_date" => "End Date"
	),

	/*
		Detailed view / individual view Row label
	*/
	"details_view" => array(
		"project_title" => "Project Title",
		"project_start_date" => "Project Start Date",
		"project_end_date" => "Projected End Date"
	),
	/*
		Customer Details Section
	*/
	"details_view_customer" => array(
		"first_name" => "First Name",
		"last_name" => "Last Name",
		"address" => "Address",
		"phone" => "Phone",
		"mobile" => "Mobile",
		"alternate_no" => "Alternate No",
		"email" => "Email",
		"contact_pref" => "Contact Pref"
	),
	/*
		Contractor Details Section
	*/
	"details_view_contractor" => array(
		"name" => "Name",
		"company" => "Company",
		"prefered_contact_mode" => "Prefered Contact Mode",
		"contact_office_email" => "Contact Office Email",
		"contact_office_number" => "Contact Office Number",
		"contact_mobile_number" => "Contact Mobile Number",
		"address" => "Address",
		"yet_to_assign" => "Yet to assign contractor",
	),
	/*
		Partners Details section
	*/
	"details_view_partner" => array(
		"name" => "Name",
		"company" => "Company",
		"prefered_contact_mode" => "Prefered Contact Mode",
		"contact_office_email" => "Contact Office Email",
		"contact_office_number" => "Contact Office Number",
		"contact_personal_email" => "Contact Personal Email",
		"contact_mobile_number" => "Contact Mobile Number",
		"address" => "Address",
		"yet_to_assign" => "Yet to assign contractor",
	),
	/*
		Insurence Details section
	*/
	"details_view_insurance" => array(
		"provider" => "Insurance Provider",
		"dummy_text" => "-- Need to Take from Insirence Provider --",
		"provider_address" => "Provider Address",
		"provider_phone" => "Provider Phone"
	),
	/*
		Remaining Budget Details view
	*/
	"details_view_budget" => array(
		"project_budget" => "Project Budget",
		"paid_from_budget" => "Paid from Budget",
		"remaining_bbudget" => "Remaining Bbudget",
		"deductible" => "Deductible",
		"referral_fee" => "Referral Fee"
	),
	/*
		Button text and link text
	*/
	"buttons_links" => array(
		"create" => "Create Project",
		"update" => "Update Project",
		"tasks" => "Tasks",
		"project_notes" => "Project Notes",
		"task_notes" => "Task Notes",
		"documents" => "Documents",
		"update_project" => "Update Project",
		"delete_project" => "Delete Project",
		"create_task" => "Create Task",
		"new_customer" => "Do you want to add new customer? ##replace1## Click Here ##replace2##.",
		"new_adjuster" => "Do you want to add new adjuster? ##replace1## Click Here ##replace2##.",
		"new_contractor" => "Do you want to add new contractor? ##replace1## Click Here ##replace2##.",
		"open" => "Open",
		"completed" => "Completed",
		"deleted" => "Deleted",
		"issues" => "Issues",
		"all" => "All",
		"open_title" => "Click on this button to view open/active projects",
		"completed_title" => "Click on this button to view completed projects",
		"deleted_title" => "Click on this button to view deleted projects",
		"issues_title" => "Click on this button to view projects with issues",
		"all_title" => "Click on this button to view all projects",
		"project_issue_title" => "Project Issues",
		"edit_project" => "Edit Project",
		"export_csv" => "Export this Project to a CSV file (you can save as an excel file)",
		"delete_project" => "Delete Project",
		"update_budget_title" => "Update remaining budget",
		"add_project_title" => "Add a Partner",
		"add_task_title" => "Add a Task",
		"add_note_title" => "Add a Note",
		"add_docs_title" => "Add a Document",
		"add_contractor_title" => "Add a Contractor"
	)
);

$lang["contractor"] = array(
	/*
		Title, Header and sub header text
	*/
	"headers" => array(
		"create" => "Create Contractor",
		"update" => "Update Contractor",
		"view_all" => "Contractors List",
		"view_one" => "Contractor Details"
	),
	/*
		Create and edit form text
	*/
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
	),
	
	/*
		Summary view / View All Table's Column header
	*/
	"summary_table" => array(
		"contractor_name" => "contractor Name",
		"company" => "Company",
		"type" => "Type",
		"status" => "Status"
	),

	/*
		Detailed view / individual view Row label
	*/
	"details_view" => array(
		"name" => "Name",
		"company" => "Company",
		"type" => "Type",
		"license" => "License",
		"bbb" => "BBB",
		"dtatus" => "Status",
		"office_email_id" => "Office Email ID",
		"office_number" => "Office Number",
		"mobile_number" => "Mobile Number",
		"prefered_mode" => "Prefered Mode for Contact",
		"email" => "Email",
		"office_phone" => "Office Phone",
		"mobile_number" => "Mobile Number",
		"webSite_url" => "WebSite URL",
		"serive_provided" => "Service Provided in Zip code",
		"created_by" => "Created By",
		"created_on" => "Created On",
		"updated_by" => "Updated By",
		"updated_on" => "Updated On"
	),
	/*
		Button text and link text
	*/
	"buttons_links" => array(
		"create" => "Create Contractor",
		"update" => "Update Contractor",
		"active" => "Active",
		"in_active" => "InActive",
		"deleted" => "Deleted",
		"all" => "All",
		"active_hover_text" => "Click on this button to view active contractors",
		"in_active_hover_text" => "Click on this button to view in-active contractors",
		"all_hover_text" => "Click on this button to view all contractors",
		"edit_hover_text" => "Edit Contractor",
		"delete_hover_text" => "Delete Contractor"
	)
);

$lang["partner"] = array(
	/*
		Title, Header and sub header text
	*/
	"headers" => array(
		"create" => "Create  Partner",
		"update" => "Update  Partner",
		"view_all" => "Partners List",
		"view_one" => "Partner Details"
	),
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
	),
	
	/*
		Summary view / View All Table's Column header
	*/
	"summary_table" => array(
		"partner_name" => "Partner Name",
		"company" => "Company",
		"type" => "Type",
		"status" => "Status"
	),

	/*
		Detailed view / individual view Row label
	*/
	"details_view" => array(
		"name" => "Name",
		"company" => "Company",
		"type" => "Type",
		"status" => "Status",
		"license" => "License",
		"office_email_iD" => "Office Email ID",
		"office_number" => "Office Number",
		"personal_email_id" => "Personal Email ID",
		"personal_mobile_number" => "Personal Mobile Number",
		"prefered_mode_for_contact" => "Prefered Mode for Contact",
		"pref_office_phone_number" => "Office Phone Number",
		"pref_office_email_id" => "Office Email ID",
		"pref_personal_mobile_number" => "Personal Mobile Number",
		"pref_personal_email_id" => "Personal Email ID",
		"website_url" => "WebSite URL",
		"created_by" => "Created By",
		"created_on" => "Created On",
		"updated_by" => "Updated By",
		"updated_on" => "Updated On"
	),
	/*
		Button text and link text
	*/
	"buttons_links" => array(
		"create" =>"Create Partner",
		"update" => "Update partner",
		"active" => "Active",
		"in_active" => "InActive",
		"active_title" => "Click on this button to view active partners",
		"in_active_title" => "Click on this button to view in-active partners",
		"all" => "All",
		"all_title" => "Click on this button to view all partners",
		"delete" => "Deleted",
		"edit_title" => "Edit Partner",
		"delete_title" => "Delete Partner"
	)
);

$lang["tasks"] = array(
	/*
		Title, Header and sub header text
	*/
	"headers" => array(
		"create" => "Create Task",
		"update" => "Edit Task",
		"view_all" => "Tasks List",
		"view_one" => ""
	),
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
	),
	
	/*
		Summary view / View All Table's Column header
	*/
	"summary_table" => array(
		"task_name" => "Task Name",
		"description" => "Description",
		"owner" => "Owner",
		"complete" => "% Complete",
		"start_date" => "Start Date",
		"end_date" => "End Date"
	),

	/*
		Detailed view / individual view Row label
	*/
	"details_view" => array(
		"task_title" => "Task Title",
		"description" => "Description",
		"start_date" => "Start Date",
		"end_date" => "End Date",
		"task_status" => "Task Status",
		"owner_name" => "Owner Name",
		"dependency" => "Dependency",
		"trend_type" => "Trend Type",
		"complete" => "% Complete",
		"created_by" => "Created By",
		"created_on" => "Created On",
		"updated_by" => "Updated By",
		"updated_on" => "Last Updated on"
	),
	/*
		Button text and link text
	*/
	"buttons_links" => array(
		"create" => "Create Task",
		"update" => "update Task",
		"open" => "Open",
		"completed" => "Completed",
		"all" => "All",
		"open_title" => "Click on this button to view open/active tasks",
		"completed_title" => "Click on this button to completed tasks",
		"all_title" => "Click on this button to view all tasks",
		"notes_task_title" => "Notes For Task",
		"project_issue_title" => "Project Issues",
		"edit_title" => "Edit Task",
		"delete_title" => "Delete Task"
	)
);

$lang["issues"] = array(
	/*
		Title, Header and sub header text
	*/
	"headers" => array(
		"create" => "Create Issues",
		"edit" => "Edit Issues",
		"view_all" => "Issues List",
		"view_one" => "Issue Details"
	),
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
	),
	
	/*
		Summary view / View All Table's Column header
	*/
	"summary_table" => array(
		"issue_name" => "Issue Name",
		"issue_status" => "Issue Status",
		"issue_from_date" => "Issue From Date"
	),

	/*
		Detailed view / individual view Row label
	*/
	"details_view" => array(
		"issue_name" => "Issue Name",
		"issue_description" => "Issue Description",
		"assigned_to" => "Assigned to",
		"issue_from_date" => "Issue From Date",
		"assigned_date" => "Assigned Date",
		"issue_status" => "Issue Status",
		"issue_notes" => "Issue Notes",
		"created_by" => "Created By",
		"created_on" => "Created On",
		"updated_by" => "Updated By",
		"updated_on" => "Updated On"
	),
	/*
		Button text and link text
	*/
	"buttons_links" => array(
		"update" => "Update Issue",
		"create" => "Create Issue",
		"open" => "Open",
		"closed" => "Closed",
		"cancelled" => "Cancelled",
		"all" => "All",
		"open_title" => "Click on this button to view open issues",
		"closed_title" => "Click on this button to view closed issues",
		"cancelled_title" => "Click on this button to view cancelled issues",
		"all_title" => "Click on this button to view all issues",
		"add_issues_title" => "Add issues to this project",
		"edit_issue_title" => "Edit Issues",
		"edit_single_issue_title" => "Edit Issue",
		"delete_title" => "Delete Issue"
	)
);

$lang["docs"] = array(
	/*
		Title, Header and sub header text
	*/
	"headers" => array(
	),
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
	),
	
	/*
		Summary view / View All Table's Column header
	*/
	"summary_table" => array(
	),

	/*
		Detailed view / individual view Row label
	*/
	"details_view" => array(
	),
	/*
		Button text and link text
	*/
	"buttons_links" => array(
		"upload" => "Upload Document",
		"delete_title" => "Delete Document"
	)
);

$lang["notes"] = array(
	/*
		Title, Header and sub header text
	*/
	"headers" => array(
		"create" => "Create Note",
		"view_all" => "Notes"
	),
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
	),
	
	/*
		Summary view / View All Table's Column header
	*/
	"summary_table" => array(
		"created_by" => "Created By"
	),

	/*
		Detailed view / individual view Row label
	*/
	"details_view" => array(
	),
	/*
		Button text and link text
	*/
	"buttons_links" => array(
		"add" => "Add Notes"
	)
);

$lang["budget"] = array(
	/*
		Title, Header and sub header text
	*/
	"headers" => array(
		"add" => "Add Budget",
		"update" => "Update Budget"
	),
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
	),
	
	/*
		Summary view / View All Table's Column header
	*/
	"summary_table" => array(
		"paid_date" => "Paid Date",
		"amount" => "Amount",
		"description" => "Description",
		"action" => "Action"
	),

	/*
		Detailed view / individual view Row label
	*/
	"details_view" => array(
	),
	/*
		Button text and link text
	*/
	"buttons_links" => array(
		"add" => "Add Budget",
		"update" => "Update Budget",
		"add_new" => "Add New Budget",
		"edit_budget_title" => "Edit Paid Budget",
		"delete_budget_title" => "Delete Paid Budget"
	)
);

$lang["login"] = array(
	/*
		Title, Header and sub header text
	*/
	"headers" => array(
		"title" => "Login Form"
	),
	"input_form" => array(
		/*
			Following are the (key => "text value") map for the "Login" create and edit form
			Sections
				1. Before Login > Login form
		*/
		"login_email" => "User Name",
		"login_password" => "Password",

		/*
			Place holder for text input element in Create/Edit form
		*/
		"login_email_ph" => "Email id",
		"login_password_ph" => "Password"
	),
	
	/*
		Summary view / View All Table's Column header
	*/
	"summary_table" => array(
	),

	/*
		Detailed view / individual view Row label
	*/
	"details_view" => array(
	),
	/*
		Button text and link text
	*/
	"buttons_links" => array(
		"login" => "Login",
		"new_customer" => "New to the Fixit Network ##replace1## Click here ##replace2## to register",
		"forgot_pass" => "Forgot your password ##replace1## Click here ##replace2## to retrive"
	)
);

$lang["change_password"] = array(
	/*
		Title, Header and sub header text
	*/
	"headers" => array(
		"title" => "Change your password"
	),
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
	),
	/*
		Button text and link text
	*/
	"buttons_links" => array(
		"update" => "Update Password"
	)
);

$lang["forgot_password"] = array(
	/*
		Title, Header and sub header text
	*/
	"headers" => array(
		"title" => "Password Retrive Form"
	),
	"input_form" => array(
		/*
			Following are the (key => "text value") map for the "Change Password" create and edit form
			Sections
				1. After Login 	> Home > Change Password
		*/
		"user_name" => "user Name",

		/*
			Place holder for text input element in Create/Edit form
		*/
		"user_name_ph" => "Email Id"
	),
	/*
		Button text and link text
	*/
	"buttons_links" => array(
		"retrive" => "Retrive Password"
	)
);

$lang["address"] = array(
	/*
		Title, Header and sub header text
	*/
	"headers" => array(
	),
	"input_form" => array(
		/*
			Following are the (key => "text value") map for the "Address" create and edit form
			Sections
				1. Before Login > Signup form
				2. After Login 	> Where ever address applicable
		*/
		"addressLine1" => "Address Line 1",
		"addressLine2" => "Address Line 2",
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
		"state_option_0" => "--Select Ciry--",
		"state_option_0" => "--Select State--",
		"country_option_0" => "--Select Country--",
		"zipcode_option_0" => "--Select Zipcode--",
	),
	
	/*
		Summary view / View All Table's Column header
	*/
	"summary_table" => array(
	),

	/*
		Detailed view / individual view Row label
	*/
	"details_view" => array(
	),
	/*
		Button text and link text
	*/
	"buttons_links" => array(
	)
);

$lang["activation"] = array(
	/*
		Title, Header and sub header text
	*/
	"headers" => array(
		"successful" => "Account Activation Successful",
		"failed" => "Account Activation failed"
	),
	"input_form" => array(
	),
	/*
		Summary view / View All Table's Column header
	*/
	"summary_table" => array(
	),
	/*
		Detailed view / individual view Row label
	*/
	"details_view" => array(
	),
	/*
		Button text and link text
	*/
	"buttons_links" => array(
	)
);