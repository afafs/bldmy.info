<?php

namespace App\Http\Controllers;

use App\Models\BasicExtended;
use Illuminate\Support\Facades\Schema;
use App\Models\Language;
use App\Models\User;
use App\Models\User\Language as UserLanguage;
use App\Models\User\UserEmailTemplate;
use App\Models\User\UserPermission;
use App\Models\User\UserVcard;
use Illuminate\Http\Request;
use Artisan;
use DB;

class UpdateController extends Controller
{
    public function version()
    {
        return view('updater.version');
    }

    public function filesFolders($src, $des) {
        $dir = $src;//"path/to/targetFiles";
        $dirNew = $des;//path/to/destination/files
        // Open a known directory, and proceed to read its contents
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                echo '<br>Archivo: '.$file;
                //exclude unwanted 
                if ($file=="move.php")continue;
                if ($file==".") continue;
                if ($file=="..")continue;
                if ($file=="viejo2014")continue;
                if ($file=="viejo2013")continue;
                if ($file=="cgi-bin")continue;
                //if ($file=="index.php") continue; for example if you have index.php in the folder
    
                if (rename($dir.'/'.$file,$dirNew.'/'.$file))
                    {
                    echo " Files Copyed Successfully";
                    echo ": $dirNew/$file"; 
                    //if files you are moving are images you can print it from 
                    //new folder to be sure they are there 
                    }
                    else {echo "File Not Copy";}
                }
                closedir($dh);
            }
        }
    }

    public function recurse_copy($src, $dst)
    {

        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->recurse_copy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    @copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    public function upversion(Request $request)
    {
        $assets = array(
            ['path' => 'assets/admin/js', 'type' => 'folder', 'action' => 'replace'],
            ['path' => 'assets/admin/css', 'type' => 'folder', 'action' => 'replace'],
            ['path' => 'assets/admin/fonts', 'type' => 'folder', 'action' => 'replace'],

            ['path' => 'assets/front/css', 'type' => 'folder', 'action' => 'replace'],
            ['path' => 'assets/front/js', 'type' => 'folder', 'action' => 'replace'],
            ['path' => 'assets/front/fonts', 'type' => 'folder', 'action' => 'replace'],
            ['path' => 'assets/front/img', 'type' => 'folder', 'action' => 'add'],

            ['path' => 'assets/user/js', 'type' => 'folder', 'action' => 'add'],

            ['path' => 'config', 'type' => 'folder', 'action' => 'replace'],
            ['path' => 'database/migrations', 'type' => 'folder', 'action' => 'add'],
            ['path' => 'resources/views', 'type' => 'folder', 'action' => 'replace'],
            ['path' => 'resources/profile', 'type' => 'folder', 'action' => 'replace'],
            ['path' => 'routes/web.php', 'type' => 'file', 'action' => 'replace'],
            ['path' => 'app', 'type' => 'folder', 'action' => 'replace'],

            ['path' => 'composer.json', 'type' => 'file', 'action' => 'replace'],
            ['path' => 'composer.lock', 'type' => 'file', 'action' => 'replace'],
            ['path' => 'version.json', 'type' => 'file', 'action' => 'replace']
        );

        foreach ($assets as $key => $asset) {
            $des = '';
            
            if(strpos($asset["path"], 'assets/') !== false){
                $des = 'public/' . $asset["path"];
            } else{
                $des = $asset["path"];
            }

            // if 'resources/views/user/profile' folder exists, only then replace it.
            // otherwise, skip it
            if ($asset['path'] == 'resources/profile') {
                $profile = file_exists('resources/views/user/profile');
                if ($profile) {
                    @unlink('resources/views/user/profile');
                    $this->recurse_copy('public/updater/' . 'resources/profile', 'resources/views/user/profile');
                }
                continue;
            }
            // if updater need to replace files / folder (with/without content)
            if ($asset['action'] == 'replace') {
                if ($asset['type'] == 'file') {
                    @copy('public/updater/' . $asset["path"], $des);
                }
                if ($asset['type'] == 'folder') {
                    @unlink($des);
                    $this->recurse_copy('public/updater/' . $asset["path"], $des);
                }
            }
            // if updater need to add files / folder (with/without content)
            elseif ($asset['action'] == 'add') {
                if ($asset['type'] == 'folder') {
                    @mkdir($des . '/', 0775, true);
                    $this->recurse_copy('public/updater/' . $asset["path"], $des);
                }
            }
        }

        $arr = ['WEBSITE_HOST' => $request->website_host];
        setEnvironmentValue($arr);


        $this->updateLanguage();

        Artisan::call('config:clear');
        // run migration files
        Artisan::call('migrate');

        // $vcards = UserVcard::all();
        // $newKeys = ["Share_On"=>"Share On","Facebook"=>"Facebook","Linkedin"=>"Linkedin","Twitter"=>"Twitter","SMS"=>"SMS"];
        // foreach ($vcards as $key => $vcard) {

        //     $keyArr = json_decode($vcard->keywords, true);
        //     foreach ($newKeys as $key => $newKey) {
        //         $keyArr["$key"] = $newKey;
        //     }
        //     $vcard->keywords = json_encode($keyArr);

        //     $vcard->save();
        // }


        // // read vcard json file
        // $data = file_get_contents('core/resources/lang/vcard.json');
        // // decode default json
        // $json_arr = json_decode($data, true);
        // // new keys
        // foreach ($newKeys as $key => $newKey) {
        //     // # code...
        //     if (!array_key_exists($key, $json_arr)) {
        //         $json_arr[$key] = $newKey;
        //     }
        // }
        // // push the new key-value pairs in vcard json files
        // file_put_contents('core/resources/lang/vcard.json', json_encode($json_arr));


        $permissions = UserPermission::all();
        $newPermissions = ["Appointment","Google Analytics","Disqus","WhatsApp","Facebook Pixel","Tawk.to"];
        foreach ($permissions as $key => $permission) {

            $permissionArr = json_decode($permission->permissions, true);
            foreach ($newPermissions as $key => $newPer) {
                $permissionArr[] = $newPer;
            }
            $permission->permissions = json_encode($permissionArr);

            $permission->save();
        }

        $users = User::all();
        $this->updateUserMailTemplates($users);
        $this->updateUserDays($users);


        $langs = UserLanguage::all();
        $newKeys = ["lifetime" => "lifetime", "yearly" => "yearly", "monthly" => "monthly", "Current" => "Current", "Plutinum" => "Plutinum", "Free" => "Free", "Golden" => "Golden", "Entrepreneur" => "Entrepreneur", "Freelancer" => "Freelancer", "Begninner" => "Begninner", "Online_CV_&_Export" => "Online CV & Export", "Follow/Unfollow" => "Follow/Unfollow", "Skill" => "Skill", "Achievements" => "Achievements", "Portfolio" => "Portfolio", "Blog" => "Blog", "Online_CV_and_Export" => "Online CV & Export", "vCard" => "vCard", "Service" => "Service", "QR_Builder" => "QR Builder", "qr_code_warning_msg" => "If the QR Code cannnot be scanned, then choose a darker color", "If_the_QR_Code_cannnot_be_scanned_then_choose_a_darker_color" => "If the QR Code cannnot be scanned then choose a darker color", "Image_Vertical_Poistion" => "Image Vertical Poistion", "Image_Horizontal_Poistion" => "Image Horizontal Poistion", "Save_QR_Code" => "Save QR Code", "qr_Name_msg" => "This name will be used to identify this specific QR Code from the QR Codes List", "Close" => "Close", "Text_Size" => "Text Size", "Text_Color" => "Text Color", "Text_Vertical_Position" => "Text Vertical Position", "Text_Horizontal_Poistion" => "Text Horizontal Poistion", "qr_reduce_size_msg" => "If the QR Code cannnot be scanned, then reduce this size", "Image_Size" => "Image Size", "Download_Image" => "Download Image", "Preview" => "Preview", "Clear" => "Clear", "Save" => "Save", "Text" => "Text", "Type" => "Type", "circle" => "circle", "round" => "round", "square" => "square", "Style" => "Style", "Eye_Style" => "Eye Style", "White_Space" => "White Space", "Size" => "Size", "If_the_QR_Code_cannnot_be_scanned,_then_choose_a_darker_color" => "If the QR Code cannnot be scanned, then choose a darker color", "QR_Code_will_be_generated_for_this_URL" => "QR Code will be generated for this URL", "QR_Code_Builder" => "QR Code Builder", "CV_URLs" => "CV URLs", "Enter_occupation" => "Enter occupation", "Website_URL_Color" => "Website URL Color", "Address_Icon_Color" => "Address Icon Color", "Email_Icon_Color" => "Email Icon Color", "Phone_Icon_Color" => "Phone Icon Color", "Share_vCard_Button_Color" => "Share vCard Button Color", "Add_to_Contact_Button_Color" => "Add to Contact Button Color", "Mail_Button_Color" => "Mail Button Color", "Whatsapp_Button_Color" => "Whatsapp Button Color", "Call_Button_Color" => "Call Button Color", "vCard_URLs" => "vCard URLs", "Subdomain_Based_URL" => "Subdomain Based URL", "reset_password" => "reset password", "Cancel" => "Cancel", "Yes_delete_it!" => "Yes delete it!", "You_won_not_be_able_to_revert_this!" => "You won not be able to revert this!", "Are_you_sure?" => "Are you sure?", "Send_Request" => "Send Request", "The_valid_format_will_be_exactly_like_this_one" => "The valid format will be exactly like this one", "or" => "or", "Do_not_use" => "Do not use", "enter_url" => "enter url", "My_Appointments" => "My Appointments", "Back_to_Home" => "Back to Home", "Payment_Success" => "Payment Success", "Send_Mail" => "Send Mail", "forget_password" => "forget password", "Signup" => "Signup", "Confirm_Password" => "Confirm Password", "Copyright" => "Copyright", "All_Right_Reserved" => "All Right Reserved", "save_change" => "Save Change", "This_time_slot_is_booked_Please_try_another_slot" => "This time slot is booked! Please try another slot", "Due_amount" => "Due amount", "Paid_Fee" => "Paid Fee", "Booking_Day" => "Booking Day", "appointment_details" => "Appointment Details", "Confirm_New_Password" => "Confirm New Password", "SL_No" => "SL. No", "Update_profile" => "Update profile", "account_information" => "Account Information", "Signout" => "Sign Out", "Profile" => "Profile", "My_Profile" => "My Profile", "Confirm" => "Confirm", "Choose_an_option" => "Choose an option", "Advance" => "Advance", "Payable_amount" => "Payable amount", "Catgory" => "Catgory", "Booking_Time" => "Booking Time", "Booking_Date" => "Booking Date", "Appointment_summary" => "Appointment Summary", "Payment" => "Payment", "Checkout" => "Checkout", "choose_a_date_to_see_slots" => "Please choose a date to see the available slots", "Select_a_slot" => "Select a slot", "Select_a_date" => "Select a date", "Book_as_guest" => "Book as guest", "OR" => "OR", "New_user" => "New user", "Donot_have_an_account" => "Don\'t have an account", "Enter_email_address" => "Enter email address", "Enter_password" => "Enter password", "Lost_your_password" => "Lost your password", "Login" => "Login", "old_pass_doesnot_match" => "Old password does not match with the existing password", "Your_Current_Password" => "Your Current Password", "Download" => "Download", "Update_CV_Upload" => "Update CV Upload", "CV_Upload" => "CV Upload", "Instructions" => "Instructions", "Receipt_Image" => "Receipt Image", "offline_gateway_serial_no_text" => "The higher the serial number is, the later the gateway will be shown everywhere", "Edit_Gateway" => "Edit Gateway", "Add_Gateway" => "Add Gateway", "NO_OFFLINE_PAYMENT_GATEWAY_FOUND" => "NO OFFLINE PAYMENT GATEWAY FOUND", "Mercadopago_Token" => "Mercadopago Token", "Mercado_Pago_Test_Mode" => "Mercado Pago Test Mode", "Mercado_Pago" => "Mercado Pago", "Mercadopago" => "Mercadopago", "Public_Client_Key" => "Public Client Key", "Transaction_Key" => "Transaction Key", "API_Login_ID" => "API Login ID", "Authorize_Net_Test_Mode" => "Authorize.Net Test Mode", "Authorize_Net" => "Authorize.Net", "Razorpay_Secret" => "Razorpay Secret", "Razorpay_Key" => "Razorpay Key", "Razorpay" => "Razorpay", "Mollie_Payment_Key" => "Mollie Payment Key", "Mollie_Payment" => "Mollie Payment", "Flutterwave_Secret_Key" => "Flutterwave Secret Key", "Flutterwave_Public_Key" => "Flutterwave Public Key", "Flutterwave" => "Flutterwave", "Paystack_Secret_Key" => "Paystack Secret Key", "Paystack" => "Paystack", "Instamojo_Auth_Token" => "Instamojo Auth Token", "Instamojo_API_Key" => "Instamojo API Key", "Test_Mode" => "Test Mode", "Instamojo" => "Instamojo", "Industry_type_id" => "Industry type id", "Paytm_Merchant_website" => "Paytm Merchant website", "Paytm_Merchant_mid" => "Paytm Merchant mid", "Paytm_Merchant_Key" => "Paytm Merchant Key", "Stripe_Secret" => "Stripe Secret", "Stripe_Key" => "Stripe Key", "Stripe" => "Stripe", "Paypal_Client_Secret" => "Paypal Client Secret", "Paypal_Client_ID" => "Paypal Client ID", "Paypal_Test_Mode" => "Paypal Test Mode", "Paypal" => "Paypal", "Paytm" => "Paytm", "Paytm_Environment" => "Paytm Environment", "Production" => "Production", "Local" => "Local", "Next" => "Next", "Current_Package" => "Current Package", "another_package_activate_msg" => "You have another package to activate after the current package expires. You cannot purchase / extend any package, until the next package is activated", "membership_expired_text" => "Your membership is expired. Please purchase a new package / extend the current package", "Decision_Pending" => "Decision Pending", "Pending_Package" => "Pending Package", "buy_plan_approve_reject_text" => "You have requested a package which needs an action (Approval / Rejection) by Admin. You will be notified via mail once an action is taken", "Buy_Now" => "Buy Now", "Extend" => "Extend", "Edit_Language_Keyword" => "Edit Language Keyword", "Edit_Language" => "Edit Language", "Enter_code" => "Enter code", "Make_Default" => "Make Default", "Default" => "Default", "Add_New_Keyword" => "Add New Keyword", "Add_Language" => "Add Language", "Edit_Keyword" => "Edit Keyword", "Appearance_in_Website" => "Appearance in Website", "Code" => "Code", "Languages" => "Languages", "Regular" => "Regular", "Trial" => "Trial", "Never_Activated" => "Never Activated", "Cost" => "Cost", "Receipt" => "Receipt", "Payment_Method" => "Payment Method", "Package" => "Package", "Search_by_Transaction_ID" => "Search by Transaction ID", "Unfollow" => "Unfollow", "NO_FOLLOWING_USER_FOUND" => "NO FOLLOWING USER FOUND", "Following" => "Following", "Following_Page" => "Following Page", "Following_List" => "Following List", "NO_FOLLOWER_FOUND" => "NO FOLLOWER FOUND", "Follower_Page" => "Follower Page", "Follower_List" => "Follower List", "Qr_Code" => "Qr Code", "NO_QR_CODE_FOUND" => "NO QR CODE FOUND", "Add_Content" => "Add Content", "Remove_Content" => "Remove Content", "Add_Sapcing" => "Add Sapcing", "Description" => "Description", "Cv_section_Duration_text" => "Here, You can enter durations like this - (1st October, 2021 >> Present)", "Subtitle" => "Subtitle", "Duration" => "Duration", "Enable" => "Enable", "Disable" => "Disable", "Left_Border" => "Left Border", "CV_Section_Content" => "CV Section Content", "NO_SECTION_FOUND" => "NO SECTION FOUND", "Right_Column_in_CV" => "Right Column in CV", "Left_Column_in_CV" => "Left Column in CV", "Select_a_Column" => "Select a Column", "Which_Column" => "Which Column", "Section_Content" => "Section Content", "Column_Side" => "Column Side", "Section_Name" => "Section Name", "Drag_and_Drop_the_sections_to_change_the_order" => "Drag & Drop the sections to change the order", "Add_Section" => "Add Section", "Rename_Contact_Section_Title" => "Rename Contact Section Title", "CV_Content" => "CV Content", "Your_Occupation" => "Your Occupation", "Your_Name" => "Your Name", "Enter_base_color" => "Enter base color", "Base_Color_Code" => "Base Color Code", "RTL" => "RTL", "LTR" => "LTR", "Select_a_Direction" => "Select a Direction", "CV_Name_msg" => "This will be used to identify this specific CV from CVs list", "Enter_CV_name" => "Enter CV name", "Your_Image" => "Your Image", "CV_Management" => "CV Management", "Edit_CV" => "Edit CV", "Sections" => "Sections", "CV_Name" => "CV Name", "NO_CV_FOUND" => "NO CV FOUND", "Add_CV" => "Add CV", "Enter_embed_URL_of_video" => "Enter embed URL of video", "Vcard_about_Video_Link_msg" => "Please enter embed URL of video, don\'t take URL from browser search bar", "Video_Link" => "Video Link", "About_and_Video" => "About & Video", "Edit_vCard_Testimonial" => "Edit vCard Testimonial", "Add_vCard_Testimonial" => "Add vCard Testimonial", "Enter_Rating" => "Enter Rating", "Enter_comment" => "Enter comment", "Rating_must_be_between_1_to_5" => "Rating must be between 1 to 5", "Comment" => "Comment", "Rating" => "Rating", "Client" => "Client", "vCard_Testimonials" => "vCard Testimonials", "Edit_vCard_Project" => "Edit vCard Project", "project_serial_numer_msg" => "The higher the serial number is, the later the project will be shown", "NO_PROJECT_FOUND" => "NO PROJECT FOUND", "Add_Project" => "Add Project", "vCard_Projects" => "vCard Projects", "Enter_short_details" => "Enter short details", "Short_Details" => "Short Details", "Edit_vCard_Service" => "Edit vCard Service", "service_serial_numer_msg" => "The higher the serial number is, the later the service will be shown", "External_Link_Text" => "If you dont want any details content, then leave this field blank", "External_Link" => "External Link", "External_Link_Status" => "External Link Status", "Add_vCard_Service" => "Add vCard Service", "NO_SERVICE_FOUND" => "NO SERVICE FOUND", "Add_Service" => "Add Service", "vCard_Services" => "vCard Services", "Enquiry_Form_Section" => "Enquiry Form Section", "Testimonials_Section" => "Testimonials Section", "Projects_Section" => "Projects Section", "Services_Section" => "Services Section", "About_Us_Section" => "About Us Section", "Video_Section" => "Video Section", "Information_Section" => "Information Section", "Share_vCard_Button" => "Share vCard Button", "Add_to_Contact_Button" => "Add to Contact Button", "Mail_Button" => "Mail Button", "Whatsapp_Button" => "Whatsapp Button", "Call_Button" => "Call Button", "vCard_Preferences" => "vCard Preferences", "Button_and_Icon_Colors" => "Button and Icon Colors", "vCard_Information" => "vCard Information", "Value" => "Value", "Label" => "Label", "Icon_Color" => "Icon Color", "Link" => "Link", "Add_Information" => "Add Information", "Other_Infromation" => "Other Infromation", "Enter_Introduction" => "Enter Introduction", "Introduction" => "Introduction", "Enter_website_url" => "Enter website url", "Website_URL" => "Website URL", "Enter_Address" => "Enter Address", "Country_Code" => "Country Code", "Enter_Phone_Number_with" => "Enter Phone Number with", "Enter_Email" => "Enter Email", "Enter_Company" => "Enter Company", "Enter_company" => "Enter company", "Enter_vcard_name" => "Enter vcard name", "vCard_Name_text" => "Use this name to identify sepcific vcard from your vcards list", "Cover_Image" => "Cover Image", "Choose_a_Template" => "Choose a Template", "Testimonials" => "Testimonials", "Projects" => "Projects", "Translate_Keywords" => "Translate Keywords", "About_and_video" => "About and video", "Preferences" => "Preferences", "Colors" => "Colors", "Infromation" => "Infromation", "Right_to_Left" => "Right to Left", "Left_to_Right" => "Left to Right", "URLs" => "URLs", "Direction" => "Direction", "vCard_Name" => "vCard Name", "NO_VCARD_FOUND" => "NO VCARD FOUND", "Your_total_vCard" => "Your total vCard", "Your_current_package_vCard_limit" => "Your current package vCard limit", "You_added_maximum_number_of_vCard_in_your_list" => "You added maximum number of vCard in your list", "Advanced" => "Advanced", "Paid" => "Paid", "Add_Holiday" => "Add Holiday", "serial" => "serial", "reset" => "reset", "Search_Name" => "Search Name", "Details" => "Details", "Rejected" => "Rejected", "Completed" => "Completed", "Approved" => "Approved", "Pending" => "Pending", "Payment_status" => "Payment status", "No_Appointment_Found" => "No Appointment Found", "Reset" => "Reset", "Search_transaction_id" => "Search transaction id", "Search_Date" => "Search Date", "search_sl_no" => "Search SL.No.", "All_Appointments" => "All Appointments", "UPDATE_FIELD" => "UPDATE FIELD", "Edit_Input" => "Edit Input", "Edit_Time_Frame" => "Edit Time Frame", "Maximum_Booking" => "Maximum Booking", "Enter_0_for_unlimited_booking" => "Enter 0 for unlimited booking", "Add_Time_Frame" => "Add Time Frame", "Manage" => "Manage", "Input_Fields" => "Input Fields", "input_field_name_email_text" => "input field, it will be in the Appointment form By default", "Category_name" => "Category name", "Add_Category" => "Add Category", "Edit_Category" => "Edit Category", "enter_fee" => "enter fee", "enter_category_name" => "enter category name", "Date" => "Date", "NO_HOLIDAYS_FOUND" => "NO HOLIDAYS FOUND", "Holidays" => "Holidays", "Form" => "Form", "Fee" => "Fee", "Category_Name" => "Category Name", "Category_Icon" => "Category Icon", "NO_CATEGORY_FOUND" => "NO CATEGORY FOUND", "Weekend" => "Weekend", "Time_slots" => "Time slots", "Day" => "Day", "Add" => "Add", "Max_Booking" => "Max Booking", "End_Time" => "End Time", "Start_Time" => "Start Time", "NO_TIMESLOTS_AVAILABLE" => "NO TIMESLOTS AVAILABLE", "Days" => "Days", "Time_Slot_Management" => "Time Slot Management", "ADD_FIELD" => "ADD FIELD", "Add_Option" => "Add Option", "Option_label" => "Option label", "Options" => "Options", "file_extensions" => "file extensions", "Enter_Placeholder" => "Enter Placeholder", "Placeholder" => "Placeholder", "Enter_Label_Name" => "Enter Label Name", "Label_Name" => "Label Name", "Required" => "Required", "File" => "File", "Timepicker" => "Timepicker", "Datepicker" => "Datepicker", "Textarea" => "Textarea", "Checkbox" => "Checkbox", "Select" => "Select", "Text_Field" => "Text Field", "Field_Type" => "Field Type", "Create_Input" => "Create Input", "Allowed_extensions" => "Allowed extensions", "Optional" => "Optional", "input_field_it_will_be_in_the_Appointment_form_By_default" => "input field, it will be in the Appointment form By default", "Do_not_create" => "Do not create", "Form_Builder" => "Form Builder", "Guest_Checkout_Enabled" => "Guest Checkout Enabled", "Advance_Percentage" => "Advance Percentage", "Full_Payment_Enabled" => "Full Payment Enabled", "appointment_booking_fee" => "appointment booking fee", "Total_Fee" => "Total Fee", "No" => "No", "Yes" => "Yes", "Appointment_Category_is_Enabled" => "Appointment Category is Enabled", "Appointment_Settings" => "Appointment Settings", "bolg_Serial_Number_msg" => "The higher the serial number is, the later the blog will be shown", "Edit_Blog" => "Edit Blog", "Add_Blog" => "Add Blog", "NO_BLOG_FOUND" => "NO BLOG FOUND", "Blog_Page" => "Blog Page", "Edit_Blog_Category" => "Edit Blog Category", "blog_category_Serial_Number_msg" => "The higher the serial number is, the later the blog category will be shown", "NO_BLOG_CATEGORY_FOUND" => "NO BLOG CATEGORY FOUND", "Add_Blog_Category" => "Add Blog Category", "Blog_Categories" => "Blog Categories", "Add_Testimonial" => "Add Testimonial", "Occupation" => "Occupation", "Enter_Occupation" => "Enter Occupation", "Enter_Feedback" => "Enter Feedback", "Feedback" => "Feedback", "Testimonial_Serial_Number_msg" => "The higher the serial number is, the later the testimonial will be shown", "Edit_Testimonial" => "Edit Testimonial", "Publish_Date" => "Publish Date", "NO_TESTIMONIAL_FOUND" => "NO TESTIMONIAL FOUND", "Testimonial_Page" => "Testimonial Page", "Edit_Portfolio" => "Edit Portfolio", "Meta_Description" => "Meta Description", "Meta_Keywords" => "Meta Keywords", "Enter_content" => "Enter content", "Content" => "Content", "portfolio_Serial_Number_msg" => "The higher the serial number is, the later the portfolio will be shown", "Select_a_category" => "Select a category", "Enter_title" => "Enter title", "Thumbnail" => "Thumbnail", "Slider_Images" => "Slider Images", "NO_PORTFOLIO_FOUND" => "NO PORTFOLIO FOUND", "Add_Portfolios" => "Add Portfolios", "Portfolio_Page" => "Portfolio Page", "Edit_Portfolio_Category" => "Edit Portfolio Category", "Select_a_status" => "Select a status", "portfolio_category_Serial_Number_msg" => "The higher the serial number is, the later the portfolio category will be shown", "Status" => "Status", "NO_PORTFOLIO_CATEGORY_FOUND" => "NO PORTFOLIO CATEGORY FOUND", "Add_Portfolio_Category" => "Add Portfolio Category", "Portfolio_Categories" => "Portfolio Categories", "Edit_Achievement" => "Edit Achievement", "Achievement_Serial_Number_msg" => "The higher the serial number is, the later the Skill will be shown", "Enter_achievement_count" => "Enter achievement count", "Count" => "Count", "NO_ACHIEVEMENT_FOUND" => "NO ACHIEVEMENT FOUND", "Add_Achievement" => "Add Achievement", "Achievement_Page" => "Achievement Page", "Edit_Education" => "Edit Education", "experience_Serial_Number_msg" => "The higher the serial number is, the later the experience will be shown", "Enter_Short_Description" => "Enter short description", "Short_Description" => "Short Description", "Enter_Degree_Name" => "Enter degree name", "Degree_Name" => "Degree Name", "NO_EDUCATION_FOUND" => "NO EDUCATION FOUND", "NO_LANGUAGE_FOUND" => "NO LANGUAGE FOUND", "Add_Educations" => "Add Educations", "Educations_Page" => "Educations Page", "End_Date" => "End Date", "job_Serial_Number_msg" => "The higher the serial number is, the later the job will be shown", "Content_responsibilitis" => "content/ job responsibilitis", "Enter_Company_Name" => "Enter company name", "Company_Name" => "Company Name", "NO_JOB_EXPERIENCE_FOUND" => "NO JOB EXPERIENCE FOUND", "Add_Job_Experiences" => "Add Job Experiences", "Job_Experiences_Page" => "Job Experience Page", "Edit_Service" => "Edit Service", "Featured" => "Featured", "Service_Page" => "Service Page", "Enter_Color" => "Enter Color", "Enter_skill_percentage" => "Enter skill percentage", "Select_a_language" => "Select a language", "skill_serial_number_msg" => "The higher the serial number is, the later the Skill will be shown", "skills_percentage_msg" => "The percentage should between 1 to 100", "Color" => "Color", "Edit_Skill" => "Edit Skill", "Percentage" => "Percentage", "Language" => "Language", "NO_SKILL_FOUND" => "NO SKILL FOUND", "Add_Skill" => "Add Skill", "Skill_Page" => "Skill Page", "Footer_Mail" => "Footer Mail", "Profile_Listing" => "Profile Listing", "Appointment" => "Appointment", "Follow_Unfollow" => "Follow/Unfollow", "User" => "User", "Contact_Section_Subtitle" => "Contact Section Subtitle", "Contact_Section_Title" => "Contact Section Title", "Contact_Section" => "Contact Section", "Blog_Section_Subtitle" => "Blog Section Subtitle", "Blog_Section_Title" => "Blog Section Title", "Blog_Section" => "Blog Section", "Testimonial_Section_Subtitle" => "Testimonial Section Subtitle", "Testimonial_Section_Title" => "Testimonial Section Title", "Testimonial_Section" => "Testimonial Section", "Portfolio_Section_Subtitle" => "Portfolio Section Subtitle", "Portfolio_Section_Title" => "Portfolio Section Title", "Portfolio_Section" => "Portfolio Section", "Achievement_Section_Subtitle" => "Achievement Section Subtitle", "Achievement_Section_Title" => "Achievement Section Title", "Achievements_Image" => "Achievements Image", "Achievements_Section" => "Achievements Section", "Experience_Section_Subtitle" => "Experience Section Subtitle", "Experience_Section_Title" => "Experience Section Title", "Experience_Section" => "Experience Section", "Service_Section_Subtitle" => "Service Section Subtitle", "Service_Section_Title" => "Service Section Title", "Service_Section" => "Service Section", "Skills_Section_Content" => "Skills Section Content", "Skills_Section_Subtitle" => "Skills Section Subtitle", "Skills_Section_Title" => "Skills Section Title", "Skills_Image" => "Skills Image", "Skills_Section" => "Skills Section", "About_Section_Content" => "About Section Content", "About_Section_Subtitle" => "About Section Subtitle", "About_Section_Title" => "About Section Title", "About_Section_Image" => "About Section Image", "About_Section" => "About Section", "multiple_designations_text" => "use comma (,) to add multiple designations", "Enter_designations" => "Enter designations", "Designation" => "Designation", "Hero_Section_Image" => "Hero Section Image", "Hero_Section" => "Hero Section", "save" => "save", "Enter_From_Name" => "Enter From name", "From_Name" => "From Name", "Reply_To" => "Reply To", "Mail_Information_For_Subscribers" => "Mail Information For Subscribers", "Subscribers" => "Subscribers", "Mail_Subscribers" => "Mail Subscribers", "Password_Reset_Link" => "Password Reset Link", "Appointment_Category" => "Appointment Category", "Due_Amount" => "Due Amount", "Paid_Amount" => "Paid Amount", "Appointment_Total_Fee" => "Appointment Total Fee", "Appointment_Booking_Time" => "Appointment Booking Time", "Appointment_Booking_Date" => "Appointment Booking Date", "Appointment_Serial_Number" => "Appointment Serial Number", "Email_Verification_Link" => "Email Verification Link", "Name_of_The_Customer" => "Name of The Customer", "Meaning" => "Meaning", "Short_Code" => "Short Code", "Enter_Email_Body_Format" => "Enter Email Body Format", "Mail_Body" => "Mail Body", "Enter_Mail_Subject" => "Enter Mail Subject", "Edit_Mail_Template" => "Edit Mail Template", "NO_MAIL_TEMPLATE_FOUND" => "NO MAIL TEMPLATE FOUND", "Mail_Subject" => "Mail Subject", "Email_Type" => "Email Type", "Meta_Description_For_Portfolios_Page" => "Meta Description For Portfolios Page", "Meta_Keywords_For_Portfolios_Page" => "Meta Keywords For Portfolios Page", "Meta_Description_For_Services_Page" => "Meta Description For Services Page", "Meta_Keywords_For_Services_Page" => "Meta Keywords For Services Page", "Enter_Meta_Keywords" => "Enter Meta Keywords", "Meta_Description_For_Blogs_Page" => "Meta Description For Blogs Page", "Meta_Keywords_For_Blogs_Page" => "Meta Keywords For Blogs Page", "Enter_Meta_Description" => "Enter Meta Description", "Meta_Description_For_Testimonial_Page" => "Meta Description For Testimonial Page", "Meta_Keywords_For_Testimonial_Page" => "Meta Keywords For Testimonial Page", "Meta_Description_For_Experience_Page" => "Meta Description For Experience Page", "Meta_Keywords_For_Experience_Page" => "Meta Keywords For Experience Page", "Meta_Description_For_About_Page" => "Meta Description For About Page", "Meta_Keywords_For_About_Page" => "Meta Keywords For About Page", "Meta_Description_For_Home_Page" => "Meta Description For Home Page", "Meta_Keywords_For_Home_Page" => "Meta Keywords For Home Page", "Update_SEO_Information" => "Update SEO Information", "Tawk_to_Chat_Link" => "Tawk.to Direct Chat Link", "Tawk_to_Status" => "Tawk.to Status", "Tawk_to" => "Tawk.to", "Facebook_Pixel_ID" => "Facebook Pixel ID", "fb_pixel_hint_text" => "Click Here to see where to get the Facebook Pixel ID", "Hint" => "Hint", "Facebook_Pixel_Status" => "Facebook Pixel Status", "Facebook_Pixel" => "Facebook Pixel", "WhatsApp_Popup_Message" => "WhatsApp Popup Message", "WhatsApp_Popup_Status" => "WhatsApp Popup Status", "WhatsApp_Header_Title" => "WhatsApp Header Title", "WhatsApp_Number_warning_msg" => "Phone Code must be included in Phone Number", "WhatsApp_Number" => "WhatsApp Number", "WhatsApp_Status" => "WhatsApp Status", "WhatsApp" => "WhatsApp", "Disqus_Short_Name" => "Disqus Short Name", "Disqus_Status" => "Disqus Status", "Disqus" => "Disqus", "Measurement_ID" => "Measurement ID", "Google_Analytics_Status" => "Google Analytics Status", "Google_Analytics" => "Google Analytics", "Edit_Social_Link" => "Edit Social Link", "Edit" => "Edit", "Icon" => "Icon", "NO_LINK_ADDED" => "NO LINK ADDED", "Submit" => "Submit", "social_icon_serial_msg" => "The higher the serial number is, the later the social link will be shown", "Enter_Serial_Number" => "Enter Serial Number", "Serial_Number" => "Serial Number", "Enter_URL_of_social_media_account" => "Enter URL of social media account", "URL" => "URL", "Social_Icon_nb_text" => "NB: click on the dropdown icon to select a social link icon", "Social_Icon" => "Social Icon", "Add_Social_Links" => "Add Social Links", "Base_Currency_Rate" => "Base Currency Rate", "Base_Currency_Text_Position" => "Base Currency Text Position", "Base_Currency_Text" => "Base Currency Text", "Right" => "Right", "Left" => "Left", "Base_Currency_Symbol_Position" => "Base Currency Symbol Position", "Base_Currency_Symbol" => "Base Currency Symbol", "Currency_Settings" => "Currency Settings", "Enter_Website_Title" => "Enter Website Title", "Website_Title" => "Website Title", "Update_Information" => "Update Information", "Information" => "Information", "Primary" => "Primary", "Secondary" => "Secondary", "Base_Color" => "Base Color", "Update_Preloader" => "Update Preloader", "img_validation_msg" => "Only JPG, JPEG, PNG images are allowed", "Update_Logo" => "Update Logo", "Update_Favicon" => "Update Favicon", "Basic_Settings" => "Basic Settings", "Theme_8" => "Theme 8", "Theme_7" => "Theme 7", "Theme_6" => "Theme 6", "Theme_5" => "Theme 5", "Theme_4" => "Theme 4", "Theme_3" => "Theme 3", "Theme_2" => "Theme 2", "Theme_1" => "Theme 1", "Dark_Theme" => "Dark Theme", "Light_Theme" => "Light Theme", "Home_Settings" => "Home Settings", "Home_Page_Version" => "Home Page Version", "New_Password_Again" => "New Password Again", "New_Password" => "New Password", "Old_password_doesnot_match_msg" => "Old password does not match with the existing password", "Current_Password" => "Current Password", "Update_Password" => "Update Password", "Profile_Settings" => "Profile Settings", "Password" => "Password", "Back" => "Back", "Account_Status" => "Account Status", "Customers" => "Customers", "Customer_Details" => "Customer Details", "Add_User" => "Add User", "Deactive" => "Deactive", "Active" => "Active", "Unverified" => "Unverified", "Verified" => "Verified", "Delete" => "Delete", "Search_by_Username_Email" => "Search by Username or Email", "NO_USER_FOUND" => "NO USER FOUND", "Account" => "Account", "Email_Status" => "Email Status", "Subdomain" => "Subdomain", "Requested_Domain" => "Requested Domain", "Current_Domain" => "Current Domain", "Requested_custom_domain_not_available" => "REQUESTED OR CONNECTED CUSTOM DOMAIN NOT AVAILABLE", "will_be_removed" => "will be removed", "domain_connection_warning_message" => "if you request another domain now & if it gets connected with our server, then your current domain", "connected_with_your_portfolio_website" => "connected with your portfolio website", "You_already_have_a_custom_domain" => "You already have a custom domain", "Request_Custom_Domain" => "Request Custom Domain", "Update" => "Update", "Country" => "Country", "State" => "State", "City" => "City", "Address" => "Address", "Enter_phone" => "Enter phone", "Enter_username" => "Enter username", "Last_Name" => "Last Name", "Enter_Last_Name" => "Enter Last Name", "Enter_first_name" => "Enter first name", "First_Name" => "First Name", "Profile_Image" => "Profile Image", "Update_Profile" => "Update Profile", "Payment_Logs" => "Payment Logs", "Upload_CV" => "Upload CV", "Offline_Gateways" => "Offline Gateways", "Online_Gateways" => "Online Gateways", "Payment_Gateways" => "Payment Gateways", "Buy_Plan" => "Buy Plan", "Language_Management" => "Language Management", "Follower_Following" => "Follower/Following", "Saved_QR_Codes" => "Saved QR Codes", "Generate_QR_Code" => "Generate QR Code", "QR_Codes" => "QR Codes", "Add_vCard" => "Add vCard", "vCards" => "vCards", "vCards_Management" => "vCards Management", "ALL" => "ALL", "Appointments" => "Appointments", "Time_Slots" => "Time Slots", "Form_builder" => "Form builder", "Category" => "Category", "Educations" => "Educations", "Experiences" => "Experiences", "Preference" => "Preference", "Home_Sections" => "Home Sections", "Mail_Information" => "Mail Information", "Mail_Templates" => "Mail Templates", "Email_Settings" => "Email Settings", "SEO_Information" => "SEO Information", "Plugins" => "Plugins", "Social_Links" => "Socia Links", "General_Settings" => "General Settings", "Color_Settings" => "Color Settings", "Preloader" => "Preloader", "Logo" => "Logo", "Favicon" => "Favicon", "Themes" => "Themes", "Settings" => "Settings", "Registered_User" => "Registered User", "Path_Based_URL" => "Path Based URL", "Subdomain_and_Path_URL" => "Subdomain & Path URL", "Custom_Domain" => "Custom Domain", "Domains_and_URLs" => "Domains & URLs", "Dashboard" => "Dashboard", "Search_Menu_Here" => "Search Menu Here", "Logout" => "Logout", "Change_Password" => "Change Password", "Edit_Profile" => "Edit Profile", "View" => "View", "User_name" => "User name", "Image" => "Image", "10_latest_followings" => "10 latest followings", "Latest_Followings" => "Latest Followings", "Purchase_Type" => "Purchase Type", "modified_by_Admin" => "modified by Admin", "Start_Date" => "Start Date", "Term" => "Term", "Title" => "Title", "Package_Details" => "Package Details", "Method" => "Method", "Currency" => "Currency", "Payment_details" => "Payment details", "Phone" => "Phone", "Email" => "Email", "Member_details" => "Member details", "Owner_Details" => "Owner Details", "Success" => "Success", "Actions" => "Actions", "Payment_Status" => "Payment Status", "Amount" => "Amount", "Transaction_Id" => "Transaction Id", "NO_PAYMENT_LOG_FOUND" => "NO PAYMENT LOG FOUND", "10_latest_payment_logs" => "10 latest payment logs", "Recent_Payment_Logs" => "Recent Payment Logs", "Followers" => "Followers", "Job_Experiences" => "Job Experiences", "Skills" => "Skills", "Expire_Date" => "Expire Date", "package_activation_warning" => "You have another package to activate after the current package expires. You cannot purchase or extend any package, until the next package is activated", "expired_package" => "Your membership is expired. Please purchase a new package or extend the current package", "pending_package" => "Pending Package", "pending_package_text" => "You have requested a package which needs an action (Approval or Rejection) by Admin. You will be notified via mail once an action is taken", "Welcome_back" => "Welcome back", "Email Verified Successfully" => "Email Verified Successfully", "Your Email is not Verified!" => "Your Email is not Verified!", "Your account is disabled" => "Your account is disabled", "First, delete all the experiences under the selected categories!" => "First, delete all the experiences under the selected categories!", "First, delete all the experiences under this category!" => "First, delete all the experiences under this category!", "First, delete all the skills under the selected categories!" => "First, delete all the skills under the selected categories!", "First, delete all the skills under this category!" => "First, delete all the skills under this category!", "Cleared all filters" => "Cleared all filters", "First, delete all the portfolios under the selected categories!" => "First, delete all the portfolios under the selected categories!", "First, delete all the portfolios under this category!" => "First, delete all the portfolios under this category!", "Default language cannot be deleted!" => "Default language cannot be deleted!", "Status Changed successfully!" => "Status Changed successfully!", "No Account Found With This Email." => "No Account Found With This Email.", "Your Password Reseted Successfully Please Check your email for new Password." => "Your Password Reseted Successfully Please Check your email for new Password.", "You cannot unfollow the user!" => "You cannot unfollow the user!", "You have unfollowed successfully!" => "You have unfollowed successfully!", "You already have a Pending Membership Request." => "You already have a Pending Membership Request.", "First delete all the blogs under this category!" => "First delete all the blogs under this category!", "First delete all the blogs under the selected categories!" => "First delete all the blogs under the selected categories!", "This date already taken!" => "This date already taken!", "Serial reset successfully!" => "Serial reset successfully!", "Image Removed" => "Image Removed", "cancel payment" => "cancel payment", "successful payment" => "successful payment", "maximum limit exceeded" => "maximum limit exceeded", "Successfully change your password" => "Successfully change your password", "Mail sent successfully!" => "Mail sent successfully!", "You subscribed successfully!" => "You subscribed successfully!", "Authorization Failed" => "Authorization Failed", "success" => "success", "warning" => "warning", "Bulk Deleted successfully" => "Bulk Deleted successfully", "Deleted successfully!" => "Deleted successfully!", "Updated successfully!" => "Updated successfully!", "Store successfully!" => "Store successfully!", "Receipt image must be" => "Receipt image must be"];
        foreach ($langs as $key => $lang) {

            $keyArr = json_decode($lang->keywords, true);
            foreach ($newKeys as $key => $newKey) {
                $keyArr["$key"] = $newKey;
            }
            $lang->keywords = json_encode($keyArr);

            $lang->save();
        }

        // DB::table('email_templates')->insert([
        //     ['email_type' => 'admin_changed_current_package', 'email_subject' => 'Admin has changed your current package'],
        //     ['email_type' => 'admin_added_current_package', 'email_subject' => 'Admin has added current package for you'],
        //     ['email_type' => 'admin_changed_next_package', 'email_subject' => 'Admin has changed your next package'],
        //     ['email_type' => 'admin_added_next_package', 'email_subject' => 'Admin has added next package for you'],
        //     ['email_type' => 'admin_removed_current_package', 'email_subject' => 'Admin has removed current package for you'],
        //     ['email_type' => 'admin_removed_next_package', 'email_subject' => 'Admin has removed your next package'],
        // ]);

        // @unlink('index.php');
        // @copy('updater/core/index.php', 'index.php');


        \Session::flash('success', 'Updated successfully');
        return redirect('public/updater/success.php');
    }

    function delete_directory($dirname)
    {
        if (is_dir($dirname))
            $dir_handle = opendir($dirname);
        if (!$dir_handle)
            return false;
        while ($file = readdir($dir_handle)) {
            if ($file != "." && $file != "..") {
                if (!is_dir($dirname . "/" . $file))
                    unlink($dirname . "/" . $file);
                else
                    $this->delete_directory($dirname . '/' . $file);
            }
        }
        closedir($dir_handle);
        rmdir($dirname);
        return true;
    }

    public function updateLanguage()
    {
        $langCodes = [];
        $languages = Language::all();
        foreach ($languages as $key => $language) {
            $langCodes[] = $language->code;
        }
        $langCodes[] = 'default';

        foreach ($langCodes as $key => $langCode) {
            // read language json file
            $data = file_get_contents(base_path('resources/lang/' . $langCode . '.json'));

            // decode default json
            $json_arr = json_decode($data, true);


            // new keys
            $newKeywordsJson = file_get_contents(base_path('public/updater/language.json'));
            $newKeywords = json_decode($newKeywordsJson, true);
            foreach ($newKeywords as $key => $newKeyword) {
                // # code...
                if (!array_key_exists($key, $json_arr)) {
                    $json_arr[$key] = $key;
                }
            }

            // push the new key-value pairs in language json files
            file_put_contents(base_path('resources/lang/' . $langCode . '.json'), json_encode($json_arr));
        }
    }

    public function redirectToWebsite(Request $request) {
        $arr = ['WEBSITE_HOST' => $request->website_host];
        setEnvironmentValue($arr);
        \Artisan::call('config:clear');

        return redirect()->route('front.index');
    }

    public function updateUserMailTemplates($users) {
        foreach ($users as $key => $user) {
            $uTemplate = UserEmailTemplate::where('user_id', $user->id)->where('email_type', 'email_verification')->count();
            if ($uTemplate == 0) {
                DB::table('user_email_templates')->insert([
                    ['user_id' => $user->id, 'email_type' => 'email_verification', 'email_subject' => 'Customer Email Verification', 'email_body' => '<p></p>'],
                    ['user_id' => $user->id, 'email_type' => 'product_order', 'email_subject' => 'Thank you for your order.', 'email_body' => '<p></p>'],
                    ['user_id' => $user->id, 'email_type' => 'reset_password', 'email_subject' => 'Recover Password of Your Account', 'email_body' => '<p></p>'],
                    ['user_id' => $user->id, 'email_type' => 'product_order', 'email_subject' => 'Thank you for your order', 'email_body' => '<p></p>']
                ]);
            }
        }
    }

    public function updateUserDays($users) {
        foreach ($users as $key => $user) {
            DB::table('user_days')->insert([
                ['user_id' => $user->id, 'day' => 'Sunday', 'weekend' => 1, 'index' => 0],
                ['user_id' => $user->id, 'day' => 'Monday', 'weekend' => 0, 'index' => 1],
                ['user_id' => $user->id, 'day' => 'Tuesday', 'weekend' => 0, 'index' => 2],
                ['user_id' => $user->id, 'day' => 'Wednesday', 'weekend' => 0, 'index' => 3],
                ['user_id' => $user->id, 'day' => 'Thursday', 'weekend' => 0, 'index' => 4],
                ['user_id' => $user->id, 'day' => 'Friday', 'weekend' => 0, 'index' => 5],
                ['user_id' => $user->id, 'day' => 'Saturday', 'weekend' => 0, 'index' => 6]
            ]);
        }
    }
}
