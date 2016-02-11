var _lang = {
    "english" : { 
        /** 
        * Error Message for all forms
        * 1. Form Field Validation error messages
        * 2. Server error Message
        */
        "errorMessage" : {
            "signupForm" : {
                "privilege":"Please select user in the drop down list to create a user account",/* Drop Down : Choosen only by admin: To set the privilege*/
                "firstName"                     : "First Name should contain minimum of 4 characters",
                "lastName"                         : "Last Name should contain minimum of 4 characters",
                "password"                         : "Password should contain minimum of 6 characters and Maximum of 15 characters",
                "confirmPassword":"Confirm Password should be same as the password entered above",
                "passwordHint"                     : "",
                "belongsTo":"Please select an option from the 'belongs to' dropdown",
                "contractorZipCode":"", /* Text Box: To search for a contractor based on your project's zip code */
                "contractorList":"", /* Radio button: To select at least one Contractor from the search result */
                "partnerCompanyName":"", /* Text Box: To search for a partner company based on zip code */
                "adjusterList":"", /* Radio button: To select an Adjuster from the search result */
                "userStatus":"To select an option from the user status dropdown",
                "activeStartDate":"Choose active start date from the calendar popup",
                "activeEndDate":"Choose active end date from calendar popup. The end date needs to be later than the start date",
                "emailId":"Please provide a valid email address",
                "contactPhoneNumber":"Phone number needs to be in 'xxx-xxx-xxxx' format",
                "mobileNumber":"Mobile number need to be in 'xxx-xxx-xxxx' format",
                "primaryMobileNumber":"Mobile number need to be in 'xxx-xxx-xxxx'format",
                "altNumber":"Alternate number need to be in 'xxx-xxx-xxxx'format",
                "addressLine1":"Please provide a valid mailing address",
                "addressLine2"                    : "",
                "city":"Please provide a city name",
                "country":"Please select an option from the country dropdown",
                "state":"Please select an option from the state dropdown",
                "zipCode":"Zip Code needs to be a 5 digit number",
                "prefContactEmailId":"",/* check box:Please select the preferred contact Email ID */
                "prefContactContactPhoneNumber":"",/* check box: Please select the preferred contact phone number */ 
                "prefContactMobileNumber":"",/* check box: Please select the preferred contact mobile number */ 
                "prefContactAltNumber":"",/* check box: Please select the preferred contact alternate number */ 
                "referredBy":"",/* Drop Down: Please select who you were referred by Customer / Contractor / Adjuster */ 
                "referredBycontractorZipCode":"",/* Text Box: To search a contractor based on zip code for Referred By */ 
                "referredBycontractorList"         : "", /* Radio button : To select at least on Contractor from the search result */
                "referredBypartnerCompanyName"     : "", /* Text Box : Search partner company based on Company name for Referred By */
                "referredByadjusterList"         : "" /* Radio button : To select at least on Adjuster from the search result */
            },
            "roleForm" : {
                "roleId":"Role ID should be a number with 5 digits",
                "roleName":"Role Name should be a string",
                "roleDescr":"Role Description should be text with a maximum of 150 characters"
            },
            "operationForm" : {
                "operationId":"Operation ID should be a number with 5 digits",
                "operationName":"Operation Name should be a string",
                "operationDescr":"Operation Description should be text with a maximum of 150 characters"
            },
            "functionForm" : {
                "functionId":"Function ID should be a number with 5 digits",
                "functionName":"Function Name should be a string",
                "functionDescr":"Function Description should be text with a maximum of 150 characters"
            },
            "dataFilterForm" : {
                "dataFilterId":"date Filter ID should be number with 5 digits",
                "dataFilterName":"date Filter Name should be a string",
                "dataFilterDescr":"date Filter Description should be text with a maximum of 150 characters"
            },
            "projectForm" : {
                "projectTitle":"Please provide a project title",
                "description":"Please provide a project description",
                "project_type":"Please select a project type from dropdown",
                "project_status":"Please select a project status from dropdown",
                "start_date":"Start date should be start before the end date",
                "end_date":"End date should be later than the start date",
                "addressLine1":"Please provide a mailing address",
                "addressLine2"                : "",
                "city":"Please provide a city name",
                "country":"Please select an option from country dropdown",
                "state":"Please select an option from state dropdown",
                "zipCode":"Zip Code needs to be a 5 digit number",
                "contractorZipCode"         : "", /* Text Box : Search contractor by zip code to add into the project */
                "project_budget":"Project budget needs to be in dollar and cents i.e. (12.50) without the $ symbol.",
                "lend_amount":"Project lend amount needs to be in dollar and cents i.e. (12.50) without the $ symbol.",
                "project_lender"             : "",
                "deductible":"Project deductible needs to be in dollar and cents i.e. (12.50) without the $ symbol.",
                "property_owner_id"         : "",
                "searchCustomerName"         : "", /* Text Box : Search customer name to add into project */
                "customerNameList"             : "", /* Drop Down : Customer Name, Select a customer to assign this project to customer */
                "searchAdjusterName"         : "", /* text box : Provide adjuster name to search from existing Adjuster list */
                "associated_claim_num"         : ""
            },
            "taskForm" : {
                "task_name":"Please provide the task title",
                "task_desc":"Please provide the task description",
                "task_start_date":"Please provide the task start date, task start should be less than or equal to end date.",
                "task_end_date":"Please provide the task end date, task end should be less than or equal to start date.",
                "task_status":"Please select the task status from dropdown",
                "task_percent_complete":"Please provide the task % complete",
                "task_dependency"         : "",
                "task_trade_type"         : ""
            },
            "contractorForm" : {
                "name":"Please provide the contractor name",
                "company":"Please provide the contractor company name",
                "type":"Please provide the contractor type",
                "license":"Please provide the contractor license",
                "bbb":"Please provide the contractor BBB",
                "status":"Please select the contractor status from dropdown",
                "addressLine1":"Provide a mailing address",
                "addressLine2"                : "",
                "city"                        : "Provide city",
                "country"                    : "Select an option from country dropdown",
                "state"                        : "Select an option from state dropdown",
                "zipCode":"Zip Code needs to be a 5 digit number",
                "emailId":"Please provide a valid email for contractor",
                "contactPhoneNumber":"Contractor number needs to be in 'xxx-xxx-xxxx' format",
                "mobileNumber":"Contractor mobile number needs to be in 'xxx-xxx-xxxx' format",
                "prefContactEmailId"         : "",
                "prefContactofficeNumber"     : "",
                "prefContactMobileNumber"     : "",
                "websiteURL":"Please provide a valid URL",
                "serviceZip":"Please provide the contractor service area zip code"
            },
            "partnerForm" : {
                "name":"Please provide the partner point of contact name",
                "company":"Please provide the partner company name",
                "type":"Please select the partner type from dropdown",
                "license":"Please provide the partner license",
                "status":"Please select partner status from the dropdown",
                "addressLine1":"Provide a mailing address",
                "addressLine2"                : "",
                "city":"Provide the city name",
                "country"                    : "Select an option from country dropdown",
                "state"                        : "Select an option from state dropdown",
                "zipCode":"Zip Code needs to be a 5 digit number",
                "wNumber":"Please provide the partner work number",
                "wEmailId":"Please provide the partner work email address",
                "pNumber":"Please provide the partner personal number",
                "pEmailId":"Please provide the partner personal  email address",
                "prefwNumber"                 : "",
                "prefwEmailId"                 : "",
                "prefmNumber"                 : "",
                "prefwEmailId"                 : "",
                "websiteURL":"Please provide a partner url"
            },
            "projectDocsForm" : {
                "docName":"Please provide a name for the document",
                "docAttachment":"Please select a file for the document attachment"
            },
            "issueForm" : {
                "issueName":"Please provide a name for the issue",
                "issueDescr":"Please provide a description for the issue",
                "issueFromdate":"Please provide date for the issue",
                "issueStatus":"Please select a status for the issue from the dropdown",
                "issueNotes":"Please provide a note for issue"
            },
            "projectNotes" : {
                "noteContent":"Please provide a comment for the note"
            },
            "budgetForm" : {
                "amount":"Please provide value for amount, the amount needs to be a number",
                "descr":" Please provide a description for the budget item",
                "date":" Please provide a date for the budget item"
            },
            "claimForm" : {
                "customer_name"         : "Please search and select customer for this claim",
                "addressLine1"          : "Please provide a valid mailing address",
                "addressLine2"          : "",
                "city"                  : "Please provide a city name",
                "country"               : "Please select an option from the country dropdown",
                "state"                 : "Please select an option from the state dropdown",
                "zipCode"               : "Zip Code needs to be a 5 digit number",
                "contactPhoneNumber"    : "Phone number needs to be in 'xxx-xxx-xxxx' format",
                "emailId"               : "Please provide a valid email address",
                "claim_number"          : "Please provide claim number",
                "description"           : "Please provide description"
            },
            "claimSubrogationForm" : {
                "customer_name"         : "Please search and select customer for this subrogation",
                "addressLine1"          : "Please provide a valid mailing address",
                "addressLine2"          : "",
                "city"                  : "Please provide a city name",
                "country"               : "Please select an option from the country dropdown",
                "state"                 : "Please select an option from the state dropdown",
                "zipCode"               : "Zip Code needs to be a 5 digit number",
                "contactPhoneNumber"    : "Phone number needs to be in 'xxx-xxx-xxxx' format",
                "status"                : "Please select status",
                "climant_name"          : "Please provide claimant name",
                "description"           : "Please provide description"
            },
            "claimNotesForm" : {
                "notesDescription"         : "Please provide the notes description",
            },
            "claimDailyUpdateForm" : {
                "dairy_updatesDescription"         : "Please provide the dairy update description",
            }
        }
    }
}