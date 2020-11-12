<?php

/**
 * Class for holding notification data
 */

namespace IFM;

use DeliciousBrains\WPMDB\Pro\Queue\Job;

// Cannot Extend WP_Comment at this time, since WP_Comment is final class
class Model_Notification
{
    public static function add($code)
    {
        global $IFM_NOTIFY;

        $IFM_NOTIFY[] = $code;
    }

    public static function get()
    {
        global $IFM_NOTIFY;

        if (get_query_var('ifm_notifications')) {
            $ifm_notifications = get_query_var('ifm_notifications');
            $IFM_NOTIFY = array_merge($ifm_notifications, $IFM_NOTIFY);
        }

        return $IFM_NOTIFY;
    }
    /**
     * Finds and returns a matching error message for the given error code.
     *
     * @param string $code    The error code to look up.
     *
     * @return string               An error message.
     */
    public static function hydrate($code)
    {
        switch ($code) {

                // Lost password
            case 'email_verify':
                return self::email_verify();

            case 'verify_email_sent':
                return __('Check your mail for a verification link!', IFM_NAMESPACE);

            case 'empty_username':
                return __('You need to enter your username  to continue.', IFM_NAMESPACE);

            case 'invalidcombo':
                return __('There are no users registered with this email address.', IFM_NAMESPACE);

            case 'invalid_username':
                return __('There are no users registered with this username.', IFM_NAMESPACE);

            case 'empty_username':
                return __('You do have an email address, right?', IFM_NAMESPACE);

            case 'username_exists':
                return __('Your chosen username is not available', IFM_NAMESPACE);

            case 'empty_password':
                return __('You need to enter a password to login.', IFM_NAMESPACE);

            case 'invalid_email':
                return __(
                    "We don't have any users with that email address. Maybe you used a different one when signing up?",
                    IFM_NAMESPACE
                );
            case 'email':
                return __('The email address you entered is not valid.', IFM_NAMESPACE);

            case 'retrieve_password_email_failure':
                return __('There does not seem to be an email address associated with this account. Contact the admins for help.', IFM_NAMESPACE);

            case 'email_exists':
                return __('An account already exists with this email address.', IFM_NAMESPACE);

            case 'closed':
                return __('Registering new users is currently not allowed.', IFM_NAMESPACE);

            case 'incorrect_password':
                $err = __(
                    "The password you entered wasn't quite right. <a href='%s'>Did you forget your password</a>?",
                    IFM_NAMESPACE
                );
                return sprintf($err, wp_lostpassword_url());

            default:
                break;
        }

        return __($code, IFM_NAMESPACE);
    }

    public static function email_verify()
    {
        return __('You need to verify your email. <a href="' . IFM_ROUTE_ACCOUNT . '/send-verify-email">Get a new verification link</a>', IFM_NAMESPACE);
    }
}
