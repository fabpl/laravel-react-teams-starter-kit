<?php

declare(strict_types=1);

return [
    // Page titles & descriptions
    'login_title' => 'Log in to your account',
    'login_description' => 'Enter your email and password below to log in',
    'register_title' => 'Create an account',
    'register_description' => 'Enter your details below to create your account',
    'forgot_password_title' => 'Forgot password',
    'forgot_password_description' => 'Enter your email to receive a password reset link',
    'reset_password_title' => 'Reset password',
    'reset_password_description' => 'Please enter your new password below',
    'confirm_password_title' => 'Confirm password',
    'confirm_password_description' => 'This is a secure area of the application. Please confirm your password before continuing.',
    'verify_email_title' => 'Email verification',
    'verify_email_description' => 'Please verify your email address by clicking on the link we just emailed to you.',
    'two_factor_title' => 'Two-factor authentication',
    'two_factor_auth_code_title' => 'Authentication code',
    'two_factor_auth_code_description' => 'Enter the authentication code provided by your authenticator application.',
    'two_factor_recovery_title' => 'Recovery code',
    'two_factor_recovery_description' => 'Please confirm access to your account by entering one of your emergency recovery codes.',

    // Fields
    'email' => 'Email address',
    'email_field' => 'Email',
    'password' => 'Password',
    'confirm_password' => 'Confirm password',
    'name' => 'Name',
    'full_name' => 'Full name',
    'remember_me' => 'Remember me',

    // Buttons & actions
    'login_button' => 'Log in',
    'login_action' => 'Log in',
    'register_button' => 'Create account',
    'register_action' => 'Register',
    'send_reset_link' => 'Email password reset link',
    'reset_password_button' => 'Reset password',
    'confirm_password_button' => 'Confirm password',
    'confirm_passkey' => 'Confirm with passkey',
    'confirming' => 'Confirming...',
    'resend_verification' => 'Resend verification email',
    'continue' => 'Continue',
    'logout' => 'Log out',

    // Links & sentences
    'forgot_password' => 'Forgot password?',
    'no_account' => "Don't have an account?",
    'sign_up' => 'Sign up',
    'have_account' => 'Already have an account?',
    'or_return_to' => 'Or, return to',
    'log_in' => 'log in',
    'or_confirm_with_password' => 'Or confirm with password',
    'use_recovery_code' => 'login using a recovery code',
    'use_auth_code' => 'login using an authentication code',
    'or_you_can' => 'or you can',
    'enter_recovery_code' => 'Enter recovery code',
    'verification_link_sent' => 'A new verification link has been sent to the email address you provided during registration.',

    // passkey timestamps
    'passkey_added' => 'Added',
    'passkey_last_used' => 'Last used',

    // passkey-verify defaults
    'sign_in_with_passkey' => 'Sign in with a passkey',
    'authenticating' => 'Authenticating...',
    'or_continue_with_email' => 'Or continue with email',

    // manage-passkeys
    'passkeys_title' => 'Passkeys',
    'passkeys_description' => 'Manage your passkeys for passwordless sign-in',
    'no_passkeys' => 'No passkeys yet',
    'no_passkeys_description' => 'Add a passkey to sign in without a password',

    // passkey-item
    'remove_passkey' => 'Remove passkey',
    'remove_passkey_description' => 'Are you sure you want to remove the ":name" passkey? You will no longer be able to use it to sign in.',
    'removing' => 'Removing...',

    // passkey-register
    'passkeys_not_supported' => 'Passkeys are not supported in this browser.',
    'add_passkey' => 'Add passkey',
    'passkey_name' => 'Passkey name',
    'passkey_name_placeholder' => 'e.g., MacBook Pro, iPhone',
    'passkey_name_hint' => 'A name helps you identify this passkey later.',
    'registering' => 'Registering...',
    'register_passkey' => 'Register passkey',

    // manage-two-factor
    'two_factor_manage_description' => 'Manage your two-factor authentication settings',
    'two_factor_enabled_pin_info' => 'You will be prompted for a secure, random pin during login, which you can retrieve from the TOTP-supported application on your phone.',
    'two_factor_disabled_info' => 'When you enable two-factor authentication, you will be prompted for a secure pin during login. This pin can be retrieved from a TOTP-supported application on your phone.',
    'two_factor_disable' => 'Disable 2FA',
    'two_factor_continue_setup' => 'Continue setup',
    'two_factor_enable' => 'Enable 2FA',

    // two-factor-recovery-codes
    'recovery_codes_title' => '2FA recovery codes',
    'recovery_codes_description' => 'Recovery codes let you regain access if you lose your 2FA device. Store them in a secure password manager.',
    'recovery_codes_hide' => 'Hide recovery codes',
    'recovery_codes_view' => 'View recovery codes',
    'recovery_codes_regenerate' => 'Regenerate codes',
    'recovery_codes_warning_prefix' => 'Each recovery code can be used once to access your account and will be removed after use. If you need more, click',
    'recovery_codes_warning_suffix' => 'above.',

    // 2FA setup modal
    'two_factor_setup_title' => 'Enable two-factor authentication',
    'two_factor_setup_description' => 'To finish enabling two-factor authentication, scan the QR code or enter the setup key in your authenticator app',
    'two_factor_enabled_title' => 'Two-factor authentication enabled',
    'two_factor_enabled_description' => 'Two-factor authentication is now enabled. Scan the QR code or enter the setup key in your authenticator app.',
    'two_factor_verify_title' => 'Verify authentication code',
    'two_factor_verify_description' => 'Enter the 6-digit code from your authenticator app',
    'two_factor_enter_manually' => 'or, enter the code manually',
    'two_factor_close' => 'Close',
];
