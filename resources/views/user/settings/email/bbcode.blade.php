<div class="col-lg-5">
    <table class="table table-striped" style="border: 1px solid #0000005a;">
        <thead>
            <tr>
                <th scope="col">{{ $keywords['Short_Code'] ?? __('Short Code') }}</th>
                <th scope="col">{{ $keywords['Meaning'] ?? __('Meaning') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{customer_name}</td>
                <td scope="row">{{ $keywords['Name_of_The_Customer'] ?? __('Name of The Customer') }}</td>
            </tr>

            @if ($templateInfo->email_type == 'email_verification')
                <tr>
                    <td>{verification_link}</td>
                    <td scope="row">{{ $keywords['Email_Verification_Link'] ?? __('Email Verification Link') }}</td>
                </tr>
            @endif

            @if ($templateInfo->email_type == 'appointment_booking_notification')
                <tr>
                    <td>{sl_no}</td>
                    <td scope="row">{{ $keywords['Appointment_Serial_Number'] ?? __('Appointment Serial Number') }}
                    </td>
                </tr>
                <tr>
                    <td>{booking_date}</td>
                    <td scope="row">{{ $keywords['Appointment_Booking_Date'] ?? __('Appointment Booking Date') }}</td>
                </tr>
                <tr>
                    <td>{booking_time}</td>
                    <td scope="row">{{ $keywords['Appointment_Booking_Time'] ?? __('Appointment Booking Time') }}</td>
                </tr>
                <tr>
                    <td>{total_fee}</td>
                    <td scope="row">{{ $keywords['Appointment_Total_Fee'] ?? __('Appointment Total Fee') }}</td>
                </tr>
                <tr>
                    <td>{paid}</td>
                    <td scope="row">{{ $keywords['Paid_Amount'] ?? __('Paid Amount') }}</td>
                </tr>
                <tr>
                    <td>{due}</td>
                    <td scope="row">{{ $keywords['Due_Amount'] ?? __('Due Amount') }}</td>
                </tr>
                <tr>
                    <td>{category}</td>
                    <td scope="row">{{ $keywords['Appointment_Category'] ?? __('Appointment Category') }}</td>
                </tr>
            @endif


            @if ($templateInfo->email_type == 'reset_password')
                <tr>
                    <td>{password_reset_link}</td>
                    <td scope="row">{{ $keywords['Password_Reset_Link'] ?? __('Password Reset Link') }}</td>
                </tr>
            @endif
            <tr>
                <td>{website_title}</td>
                <td scope="row">{{ $keywords['Website_Title'] ?? __('Website Title') }}</td>
            </tr>
        </tbody>
    </table>
</div>
