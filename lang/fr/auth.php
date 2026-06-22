<?php

declare(strict_types=1);

return [
    // Titres & descriptions des pages
    'login_title' => 'Connexion à votre compte',
    'login_description' => 'Saisissez votre e-mail et votre mot de passe pour vous connecter',
    'register_title' => 'Créer un compte',
    'register_description' => 'Renseignez vos informations pour créer votre compte',
    'forgot_password_title' => 'Mot de passe oublié',
    'forgot_password_description' => 'Saisissez votre e-mail pour recevoir un lien de réinitialisation',
    'reset_password_title' => 'Réinitialiser le mot de passe',
    'reset_password_description' => 'Veuillez saisir votre nouveau mot de passe ci-dessous',
    'confirm_password_title' => 'Confirmer le mot de passe',
    'confirm_password_description' => 'Ceci est une zone sécurisée. Veuillez confirmer votre mot de passe avant de continuer.',
    'verify_email_title' => 'Vérification de l\'e-mail',
    'verify_email_description' => 'Veuillez vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer.',
    'two_factor_title' => 'Authentification à deux facteurs',
    'two_factor_auth_code_title' => 'Code d\'authentification',
    'two_factor_auth_code_description' => 'Saisissez le code fourni par votre application d\'authentification.',
    'two_factor_recovery_title' => 'Code de récupération',
    'two_factor_recovery_description' => 'Veuillez confirmer l\'accès à votre compte en saisissant l\'un de vos codes de récupération d\'urgence.',

    // Champs
    'email' => 'Adresse e-mail',
    'email_field' => 'E-mail',
    'password' => 'Mot de passe',
    'confirm_password' => 'Confirmer le mot de passe',
    'name' => 'Nom',
    'full_name' => 'Nom complet',
    'remember_me' => 'Se souvenir de moi',

    // Boutons & actions
    'login_button' => 'Se connecter',
    'login_action' => 'Se connecter',
    'register_button' => 'Créer le compte',
    'register_action' => 'S\'inscrire',
    'send_reset_link' => 'Envoyer le lien de réinitialisation',
    'reset_password_button' => 'Réinitialiser le mot de passe',
    'confirm_password_button' => 'Confirmer le mot de passe',
    'confirm_passkey' => 'Confirmer avec la clé d\'accès',
    'confirming' => 'Confirmation...',
    'resend_verification' => 'Renvoyer l\'e-mail de vérification',
    'continue' => 'Continuer',
    'logout' => 'Se déconnecter',

    // Liens & phrases
    'forgot_password' => 'Mot de passe oublié ?',
    'no_account' => 'Pas encore de compte ?',
    'sign_up' => 'S\'inscrire',
    'have_account' => 'Vous avez déjà un compte ?',
    'or_return_to' => 'Ou, retourner à la',
    'log_in' => 'connexion',
    'or_confirm_with_password' => 'Ou confirmer avec le mot de passe',
    'use_recovery_code' => 'utiliser un code de récupération',
    'use_auth_code' => 'utiliser un code d\'authentification',
    'or_you_can' => 'ou vous pouvez',
    'enter_recovery_code' => 'Saisir le code de récupération',
    'verification_link_sent' => 'Un nouveau lien de vérification a été envoyé à l\'adresse e-mail fournie lors de l\'inscription.',

    // Horodatages passkey
    'passkey_added' => 'Ajoutée le',
    'passkey_last_used' => 'Dernière utilisation',

    // Valeurs par défaut passkey-verify
    'sign_in_with_passkey' => 'Se connecter avec une clé d\'accès',
    'authenticating' => 'Authentification...',
    'or_continue_with_email' => 'Ou continuer avec l\'e-mail',

    // manage-passkeys
    'passkeys_title' => 'Clés d\'accès',
    'passkeys_description' => 'Gérez vos clés d\'accès pour une connexion sans mot de passe',
    'no_passkeys' => 'Aucune clé d\'accès',
    'no_passkeys_description' => 'Ajoutez une clé d\'accès pour vous connecter sans mot de passe',

    // passkey-item
    'remove_passkey' => 'Supprimer la clé d\'accès',
    'remove_passkey_description' => 'Êtes-vous sûr de vouloir supprimer la clé d\'accès ":name" ? Vous ne pourrez plus l\'utiliser pour vous connecter.',
    'removing' => 'Suppression...',

    // passkey-register
    'passkeys_not_supported' => 'Les clés d\'accès ne sont pas prises en charge par ce navigateur.',
    'add_passkey' => 'Ajouter une clé d\'accès',
    'passkey_name' => 'Nom de la clé d\'accès',
    'passkey_name_placeholder' => 'ex. MacBook Pro, iPhone',
    'passkey_name_hint' => 'Un nom vous aide à identifier cette clé d\'accès plus tard.',
    'registering' => 'Enregistrement...',
    'register_passkey' => 'Enregistrer la clé d\'accès',

    // manage-two-factor
    'two_factor_manage_description' => 'Gérez vos paramètres d\'authentification à deux facteurs',
    'two_factor_enabled_pin_info' => 'Un code PIN sécurisé et aléatoire vous sera demandé lors de la connexion, que vous pouvez récupérer depuis votre application TOTP.',
    'two_factor_disabled_info' => 'En activant l\'authentification à deux facteurs, un code PIN sécurisé vous sera demandé lors de la connexion. Ce code peut être récupéré depuis une application TOTP sur votre téléphone.',
    'two_factor_disable' => 'Désactiver la 2FA',
    'two_factor_continue_setup' => 'Continuer la configuration',
    'two_factor_enable' => 'Activer la 2FA',

    // two-factor-recovery-codes
    'recovery_codes_title' => 'Codes de récupération 2FA',
    'recovery_codes_description' => 'Les codes de récupération vous permettent de regagner l\'accès si vous perdez votre appareil 2FA. Conservez-les dans un gestionnaire de mots de passe sécurisé.',
    'recovery_codes_hide' => 'Masquer les codes de récupération',
    'recovery_codes_view' => 'Afficher les codes de récupération',
    'recovery_codes_regenerate' => 'Régénérer les codes',
    'recovery_codes_warning_prefix' => 'Chaque code de récupération ne peut être utilisé qu\'une seule fois et sera supprimé après usage. Si vous en avez besoin de nouveaux, cliquez sur',
    'recovery_codes_warning_suffix' => 'ci-dessus.',

    // Modal de configuration 2FA
    'two_factor_setup_title' => 'Activer l\'authentification à deux facteurs',
    'two_factor_setup_description' => 'Pour terminer l\'activation, scannez le QR code ou saisissez la clé de configuration dans votre application d\'authentification',
    'two_factor_enabled_title' => 'Authentification à deux facteurs activée',
    'two_factor_enabled_description' => 'L\'authentification à deux facteurs est maintenant activée. Scannez le QR code ou saisissez la clé dans votre application d\'authentification.',
    'two_factor_verify_title' => 'Vérifier le code d\'authentification',
    'two_factor_verify_description' => 'Saisissez le code à 6 chiffres de votre application d\'authentification',
    'two_factor_enter_manually' => 'ou, saisir le code manuellement',
    'two_factor_close' => 'Fermer',
];
