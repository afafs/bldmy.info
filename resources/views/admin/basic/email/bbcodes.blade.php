<table class="table table-striped mb-5 bbcodes-table" style="border: 1px solid #0000005a;">
    <thead>
        <tr>
            <th scope="col">{{ __('Short Code') }}</th>
            <th scope="col">{{ __('Meaning') }}</th>
        </tr>
    </thead>
    <tbody>

        @if ($templateInfo->email_type == 'email_verification')
            <tr>
                <td>
                    {customer_name}
                </td>
                <th scope="row">
                    {{ __('Customer Name') }}
                </th>
            </tr>
            <tr>
                <td>
                    {verification_link}
                </td>
                <th scope="row">
                    {{ __('Email verification link') }}
                </th>
            </tr>
        @endif

        @if ($templateInfo->email_type != 'email_verification')
            <tr>
                <td>
                    {username}
                </td>
                <th scope="row">
                    {{ __('Username') }}
                </th>
            </tr>
        @endif

        @if ($templateInfo->email_type == 'custom_domain_connected' || $templateInfo->email_type == 'custom_domain_rejected')
            <tr>
                <td>
                    {requested_domain}
                </td>
                <th scope="row">
                    {{ __('Requested Custom Domain of User') }}
                </th>
            </tr>
        @endif
        @if ($templateInfo->email_type == 'custom_domain_connected')
            <tr>
                <td>
                    {previous_domain}
                </td>
                <th scope="row">
                    {{ __('Previous Custom Domain of User') }}
                </th>
            </tr>
        @endif
        @if ($templateInfo->email_type == 'custom_domain_rejected')
            <tr>
                <td>
                    {current_domain}
                </td>
                <th scope="row">
                    {{ __('Current Custom Domain of User') }}
                </th>
            </tr>
        @endif
        @if (
            $templateInfo->email_type == 'registration_with_premium_package' ||
                $templateInfo->email_type == 'registration_with_trial_package' ||
                $templateInfo->email_type == 'registration_with_free_package' ||
                $templateInfo->email_type == 'membership_extend' ||
                $templateInfo->email_type == 'payment_accepted_for_membership_extension_offline_gateway' ||
                $templateInfo->email_type == 'payment_accepted_for_registration_offline_gateway')
            <tr>
                <td>
                    {package_title}
                </td>
                <th scope="row">
                    {{ __('Package Name') }}
                </th>
            </tr>
            <tr>
                <td>
                    {package_price}
                </td>
                <th scope="row">
                    {{ __('Price of Purchased Package') }}
                </th>
            </tr>
            <tr>
                <td>
                    {activation_date}
                </td>
                <th scope="row">
                    {{ __('Start Date of Membership') }}
                </th>
            </tr>
            <tr>
                <td>
                    {expire_date}
                </td>
                <th scope="row">
                    {{ __('Expire Date of Membership') }}
                </th>
            </tr>
        @endif
        @if ($templateInfo->email_type == 'membership_expiry_reminder')
            <tr>
                <td>
                    {last_day_of_membership}
                </td>
                <th scope="row">
                    {{ __('Last day of membership') }}
                </th>
            </tr>
        @endif
        @if ($templateInfo->email_type == 'membership_expiry_reminder' || $templateInfo->email_type == 'membership_expired')
            <tr>
                <td>
                    {login_link}
                </td>
                <th scope="row">
                    {{ __('Login Page Link for User') }}
                </th>
            </tr>
        @endif
        @if (
            $templateInfo->email_type == 'admin_changed_current_package' ||
                $templateInfo->email_type == 'admin_changed_next_package')
            <tr>
                <td>
                    {replaced_package}
                </td>
                <th scope="row">
                    {{ __('Replaced Package Name') }}
                </th>
            </tr>
        @endif
        @if (
            $templateInfo->email_type == 'admin_changed_current_package' ||
                $templateInfo->email_type == 'admin_changed_next_package' ||
                $templateInfo->email_type == 'admin_added_current_package' ||
                $templateInfo->email_type == 'admin_added_next_package')
            <tr>
                <td>
                    {package_title}
                </td>
                <th scope="row">
                    {{ __('New Package Name') }}
                </th>
            </tr>
            <tr>
                <td>
                    {package_price}
                </td>
                <th scope="row">
                    {{ __('New Package Price') }}
                </th>
            </tr>
            <tr>
                <td>
                    {activation_date}
                </td>
                <th scope="row">
                    {{ __('New Package Activation Date') }}
                </th>
            </tr>
            <tr>
                <td>
                    {expire_date}
                </td>
                <th scope="row">
                    {{ __('New Package Expire Date') }}
                </th>
            </tr>
        @endif
        @if (
            $templateInfo->email_type == 'admin_removed_current_package' ||
                $templateInfo->email_type == 'admin_removed_next_package')
            <tr>
                <td>
                    {removed_package_title}
                </td>
                <th scope="row">
                    {{ __('Removed Package Name') }}
                </th>
            </tr>
        @endif


        <tr>
            <td>
                {website_title}
            </td>
            <th scope="row">
                {{ __('Website Title') }}
            </th>
        </tr>

    </tbody>
</table>
