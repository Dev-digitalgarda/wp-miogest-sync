<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

/**
 * Sign Up notifications
 */
if (!function_exists('resideo_signup_notifications')): 
    function resideo_signup_notifications($user, $user_pass = '') {
        $new_user = new WP_User($user);

        $user_login      = stripslashes($new_user->user_login);
        $user_email      = stripslashes($new_user->user_email);
        $user_first_name = stripslashes($new_user->first_name);

        $message = sprintf( __('New user Sign Up on %s:', 'estaresideoteprime'), get_option('blogname') ) . '<br /><br />';
        $message .= sprintf( __('Username: %s', 'resideo'), esc_html($user_login) ) . '<br />';
        $message .= sprintf( __('E-mail: %s', 'resideo'), esc_html($user_email) );

        wp_mail(
            get_option('admin_email'),
            sprintf(__('[%s] New User Sign Up', 'resideo'), get_option('blogname') ),
            $message
        );

        if (empty($user_pass)) return;

        $message  = sprintf( __('Welcome, %s!', 'resideo'), esc_html($user_first_name) ) . '<br /><br />';
        $message .= __('Thank you for signing up with us. Your new account has been setup and you can now sign in using the details below.', 'resideo') . '<br /><br />';
        $message .= sprintf( __('Username: %s', 'resideo'), esc_html($user_login) ) . '<br />';
        $message .= sprintf( __('Password: %s', 'resideo'), esc_html($user_pass) ) . '<br /><br />';
        $message .= __('Thank you,', 'resideo') . '<br />';
        $message .= sprintf( __('%s Team', 'resideo'), get_option('blogname') );

        wp_mail(
            esc_html($user_email),
            sprintf( __('[%s] Your username and password', 'resideo'), get_option('blogname') ),
            $message
        );
    }
endif;

/**
 * User Sign Up
 */
if (!function_exists('resideo_user_signup')): 
    function resideo_user_signup() {
        check_ajax_referer('signin_ajax_nonce', 'security');

        $signup_firstname = isset($_POST['signup_firstname']) ? sanitize_text_field($_POST['signup_firstname']) : '';
        $signup_lastname  = isset($_POST['signup_lastname']) ? sanitize_text_field($_POST['signup_lastname']) : '';
        $signup_email     = isset($_POST['signup_email']) ? sanitize_email($_POST['signup_email']) : '';
        $signup_pass      = isset($_POST['signup_pass']) ? $_POST['signup_pass'] : '';
        $user_type        = isset($_POST['user_type']) ? sanitize_text_field($_POST['user_type']) : '';
        $terms            = isset($_POST['terms']) ? sanitize_text_field($_POST['terms']) : '';

        $auth_settings = get_option('resideo_authentication_settings');
        $terms_setting = isset($auth_settings['resideo_terms_field']) ? $auth_settings['resideo_terms_field'] : '';

        if (empty($signup_firstname) || empty($signup_lastname) || empty($signup_pass)) {
            echo json_encode(array('signedup'=>false, 'message'=>__('Required form fields are empty!', 'resideo')));
            exit();
        }
        if (!is_email($signup_email)) {
            echo json_encode(array('signedup'=>false, 'message'=>__('Invalid Email!', 'resideo')));
            exit();
        }
        if (email_exists($signup_email)) {
            echo json_encode(array('signedup'=>false, 'message'=>__('Email already exists!', 'resideo')));
            exit();
        }
        if (6 > strlen($signup_pass)) {
            echo json_encode(array('signedup'=>false, 'message'=>__('Password too short. Please enter at least 6 characters!', 'resideo')));
            exit();
        }

        if ($terms_setting && $terms_setting != '') {
            if($terms == '' || $terms != 'true') {
                echo json_encode(array('reset'=>false, 'message'=>__('You need to agree with Terms and Conditions', 'resideo')));
                exit();
            }
        }

        $user_data = array(
            'user_login' => $signup_email,
            'user_email' => $signup_email,
            'user_pass'  => $signup_pass,
            'first_name' => $signup_firstname,
            'last_name'  => $signup_lastname
        );

        $new_user = wp_insert_user($user_data);

        if (is_wp_error($new_user)) {
            echo json_encode(array('signedup'=>false, 'message'=>__('Something went wrong!', 'resideo')));
            exit();
        } else {
            echo json_encode(array('signedup'=>true, 'message'=>__('Congratulations! You have successfully signed up.', 'resideo')));

            resideo_signup_notifications($new_user, $signup_pass);

            if ($user_type != '') {
                resideo_register_agent($new_user, $user_type);
            }
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_user_signup', 'resideo_user_signup');
add_action('wp_ajax_resideo_user_signup', 'resideo_user_signup');

/**
 * Register user as agent/owner
 */
if (!function_exists('resideo_register_agent')): 
    function resideo_register_agent($user_id, $user_type) {
        $user = get_user_by('id', $user_id);
        $user_fullname = $user->first_name . ' ' . $user->last_name;
        $agent = array(
            'post_title' => $user_fullname,
            'post_type' => 'agent',
            'post_author' => $user->ID,
            'post_status' => 'publish'
        );

        $agent_id = wp_insert_post($agent);
        update_post_meta($agent_id, 'agent_email', $user->user_email);
        update_post_meta($agent_id, 'agent_user', $user->ID);
        update_post_meta($agent_id, 'agent_type', $user_type);

        // Set comments open if enabled review/ratings from theme settings
        $general_settings = get_option('resideo_general_settings');
        $show_rating      = isset($general_settings['resideo_agents_rating_field']) ? $general_settings['resideo_agents_rating_field'] : '';

        global $wpdb;

        if ($show_rating != '') {
            $wpdb->query("UPDATE $wpdb->posts SET comment_status = 'open' WHERE post_type = 'agent' AND ID = $agent_id");
        } else {
            $wpdb->query("UPDATE $wpdb->posts SET comment_status = 'close' WHERE post_type = 'agent' AND ID = $agent_id");
        }

        // Set default payment settings
        $membership_settings = get_option('resideo_membership_settings');
        $payment_type        = isset($membership_settings['resideo_paid_field']) ? $membership_settings['resideo_paid_field'] : '';
        $free_standard       = isset($membership_settings['resideo_free_submissions_no_field']) ? $membership_settings['resideo_free_submissions_no_field'] : '';
        $free_featured       = isset($membership_settings['resideo_free_featured_submissions_no_field']) ? $membership_settings['resideo_free_featured_submissions_no_field'] : '';

        if ($payment_type == 'listing') {
            update_post_meta($agent_id, 'agent_free_listings', $free_standard);
            update_post_meta($agent_id, 'agent_free_featured_listings', $free_featured);
        }
    }
endif;

/**
 * Become an agent after registration as normal user
 */
if (!function_exists('resideo_become_agent')): 
    function resideo_become_agent() {
        check_ajax_referer('account_settings_ajax_nonce', 'security');

        $user_id       = isset($_POST['user_id']) ? $_POST['user_id'] : '';
        $type          = isset($_POST['type']) ? $_POST['type'] : 'agent';
        $user          = get_user_by('id', $user_id);
        $avatar        = get_user_meta($user_id, 'avatar', true);
        $user_fullname = $user->first_name . ' ' . $user->last_name;

        $agent = array(
            'post_title'  => $user_fullname,
            'post_type'   => 'agent',
            'post_author' => $user->ID,
            'post_status' => 'publish'
        );

        $agent_id = wp_insert_post($agent);

        update_post_meta($agent_id, 'agent_email', $user->user_email);
        update_post_meta($agent_id, 'agent_user', $user->ID);
        update_post_meta($agent_id, 'agent_avatar', $avatar);
        update_post_meta($agent_id, 'agent_type', $type);

        // Set comments open if enabled review/ratings from theme settings
        $general_settings = get_option('resideo_general_settings');
        $show_rating      = isset($general_settings['resideo_agents_rating_field']) ? $general_settings['resideo_agents_rating_field'] : '';

        global $wpdb;

        if ($show_rating != '') {
            $wpdb->query("UPDATE $wpdb->posts SET comment_status = 'open' WHERE post_type = 'agent' AND ID = $agent_id");
        } else {
            $wpdb->query("UPDATE $wpdb->posts SET comment_status = 'close' WHERE post_type = 'agent' AND ID = $agent_id");
        }

        // Set default payment settings
        $membership_settings = get_option('resideo_membership_settings');
        $payment_type        = isset($membership_settings['resideo_paid_field']) ? $membership_settings['resideo_paid_field'] : '';
        $free_standard       = isset($membership_settings['resideo_free_submissions_no_field']) ? $membership_settings['resideo_free_submissions_no_field'] : '';
        $free_featured       = isset($membership_settings['resideo_free_featured_submissions_no_field']) ? $membership_settings['resideo_free_featured_submissions_no_field'] : '';

        if ($payment_type == 'listing') {
            update_post_meta($agent_id, 'agent_free_listings', $free_standard);
            update_post_meta($agent_id, 'agent_free_featured_listings', $free_featured);
        }

        echo json_encode(array('save'=>true, 'message'=>__('Your profile was successfully updated. Redirecting...', 'resideo')));
        exit();

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_become_agent', 'resideo_become_agent');
add_action('wp_ajax_resideo_become_agent', 'resideo_become_agent');

/**
 * Check if user is an agent function
 */
if (!function_exists('resideo_check_user_agent')): 
    function resideo_check_user_agent($user_id) {
        $args = array(
            'post_type' => 'agent',
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key'   => 'agent_user',
                    'value' => $user_id,
                )
            )
        );

        $query = new WP_Query($args);

        wp_reset_postdata();

        if ($query->have_posts()) {
            wp_reset_query();

            return true;
        } else {
            return false;
        }
    }
endif;

/**
 * Get agent by user id
 */
if (!function_exists('resideo_get_agent_by_userid')): 
    function resideo_get_agent_by_userid($user_id) {
        $args = array(
            'post_type' => 'agent',
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key'   => 'agent_user',
                    'value' => $user_id,
                )
            )
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $agent_id = get_the_ID();
            }

            wp_reset_postdata();
            wp_reset_query();

            return $agent_id;
        } else {
            return false;
        }
    }
endif;

/**
 * User Sign In
 */
if (!function_exists('resideo_user_signin')): 
    function resideo_user_signin() {
        if (is_user_logged_in()) { 
            echo json_encode(array('signedin'=>true, 'message'=>__('You are already signed in, redirecting...', 'resideo')));
            exit();
        }

        check_ajax_referer('signin_ajax_nonce', 'security');

        $signin_user = isset($_POST['signin_user']) ? sanitize_text_field($_POST['signin_user']) : '';
        $signin_pass = isset($_POST['signin_pass']) ? $_POST['signin_pass'] : '';

        if ($signin_user == '' || $signin_pass == '') {
            echo json_encode(array('signedin'=>false, 'message'=>__('Invalid username or password!', 'resideo')));
            exit();
        }

        $data = array();
        $data['user_login']    = $signin_user;
        $data['user_password'] = $signin_pass;

        $user_signon = wp_signon($data);

        if (is_wp_error($user_signon)) {
            echo json_encode(array('signedin'=>false, 'message'=>__('Invalid username or password!', 'resideo')));
            exit();
        } else {
            echo json_encode(array('signedin'=>true,'newuser'=>$user_signon->ID, 'message'=>__('Sign in successful, redirecting...', 'resideo')));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_user_signin', 'resideo_user_signin');
add_action('wp_ajax_resideo_user_signin', 'resideo_user_signin');

/**
 * Forgot Password
 */
if (!function_exists('resideo_forgot_pass')): 
    function resideo_forgot_pass() {
        global $wpdb, $wp_hasher;

        check_ajax_referer('signin_ajax_nonce', 'security');

        $forgot_email = isset($_POST['forgot_email']) ? sanitize_email($_POST['forgot_email']) : '';

        if ($forgot_email == '') {
            echo json_encode(array('sent'=>false, 'message'=>__('Invalid email address!', 'resideo')));
            exit();
        }

        $user_input = trim($forgot_email);

        if (strpos($user_input, '@')) {
            $user_data = get_user_by('email', $user_input);

            if (empty($user_data)) {
                echo json_encode(array('sent'=>false, 'message'=>__('Invalid email address!', 'resideo')));
                exit();
            }
        } else {
            $user_data = get_user_by('login', $user_input);

            if (empty($user_data)) {
                echo json_encode(array('sent'=>false, 'message'=>__('Invalid username!', 'resideo')));
                exit();
            }
        }

        $user_login = $user_data->user_login;
        $user_email = $user_data->user_email;

        $key = wp_generate_password(20, false);
        do_action('retrieve_password_key', $user_login, $key);

        if (empty($wp_hasher)) {
            require_once ABSPATH . WPINC . '/class-phpass.php';

            $wp_hasher = new PasswordHash( 8, true );
        }

        $hashed = time() . ':' . $wp_hasher->HashPassword($key);
        $wpdb->update($wpdb->users, array('user_activation_key' => $hashed), array('user_login' => $user_login));

        $message = __('Someone has asked to reset the password for the following site and username.', 'resideo') . '<br /><br />';
        $message .= get_option('siteurl') . '<br /><br />';
        $message .= sprintf(__('Username: %s', 'resideo'), $user_login) . '<br /><br />';
        $message .= __('To reset your password visit the following address, otherwise just ignore this email and nothing will happen.', 'resideo') . '<br /><br />';
        $message .= network_site_url("?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . "\r\n";

        if ($message && !wp_mail($user_email, __('Password Reset Request', 'resideo'), $message)) {
            echo json_encode(array('sent'=>false, 'message'=>__('Email failed to be sent for some unknown reason.', 'resideo')));
            exit();
        } else {
            echo json_encode(array('sent'=>true, 'message'=>__('An email with password reset instructions was sent to you.', 'resideo')));
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_forgot_pass', 'resideo_forgot_pass');
add_action('wp_ajax_resideo_forgot_pass', 'resideo_forgot_pass');

/**
 * Reset Password
 */
if (!function_exists('resideo_reset_pass')): 
    function resideo_reset_pass() {
        check_ajax_referer('signin_ajax_nonce', 'security');

        $allowed_html = array();
        $pass         = isset($_POST['pass']) ? wp_kses($_POST['pass'], $allowed_html) : '';
        $key          = isset($_POST['key']) ? wp_kses($_POST['key'], $allowed_html) : '';
        $login        = isset($_POST['login']) ? wp_kses($_POST['login'], $allowed_html) : '';

        if ($pass == '') {
            echo json_encode(array('reset'=>false, 'message'=>__('Password field empty!', 'resideo')));
            exit();
        }

        $user = check_password_reset_key($key, $login);

        if (is_wp_error($user)) {
            if ($user->get_error_code() === 'expired_key') {
                echo json_encode(array('reset'=>false, 'message'=>__('Sorry, the link does not appear to be valid or is expired!', 'resideo')));
                exit();
            } else {
                echo json_encode(array('reset'=>false, 'message'=>__('Sorry, the link does not appear to be valid or is expired!', 'resideo')));
                exit();
            }
        }

        reset_password($user, $pass);
        echo json_encode(array('reset'=>true, 'message'=>__('Your password has been reset.', 'resideo')));

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_reset_pass', 'resideo_reset_pass');
add_action('wp_ajax_resideo_reset_pass', 'resideo_reset_pass');

/**
 * Import external image to Media Library and returns image ID
 */
if (!function_exists('resideo_import_image')):
    function resideo_import_image($src, $title) {
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $media = media_sideload_image($src, 0, $title, 'id');

        return $media;
    }
endif;

/**
 * Facebook Login
 */
if (!function_exists('resideo_facebook_login')): 
    function resideo_facebook_login() {
        if (is_user_logged_in()) { 
            echo json_encode(array('signedin'=>true, 'message'=>__('You are already signed in, redirecting...', 'resideo')));
            exit();
        }

        check_ajax_referer('signin_ajax_nonce', 'security');

        $auth_settings = get_option('resideo_authentication_settings');
        $fb_app_id     = isset($auth_settings['resideo_facebook_id_field']) ? $auth_settings['resideo_facebook_id_field'] : '';
        $fb_app_secret = isset($auth_settings['resideo_facebook_secret_field']) ? $auth_settings['resideo_facebook_secret_field'] : '';

        $user_id     = isset($_POST['userid']) ? sanitize_text_field($_POST['userid']) : '';
        $signin_user = isset($_POST['signin_user']) ? sanitize_text_field($_POST['signin_user']) : '';
        $first_name  = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : '';
        $last_name   = isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : '';
        $email       = isset($_POST['email']) ? sanitize_text_field($_POST['email']) : '';
        $avatar      = isset($_POST['avatar']) ? sanitize_text_field($_POST['avatar']) : '';
        $signin_pass = $fb_app_secret . $user_id;

        $photo = resideo_import_image($avatar, 'Avatar');

        resideo_social_signup($email, $signin_user, $first_name, $last_name, $signin_pass);

        $vsessionid = session_id();
        if (empty($vsessionid)) {
            session_name('PHPSESSID');
            session_start();
        }

        wp_clear_auth_cookie();
        $data = array();
        $data['user_login'] = $signin_user;
        $data['user_password'] = $signin_pass;
        $data['remember'] = true;

        $user_signon = wp_signon($data, false);
        update_user_meta($user_signon->ID, 'avatar', $photo);

        if (is_wp_error($user_signon)) {
            echo json_encode(array('signedin'=>false, 'message'=>__('Something went wrong!', 'resideo')));
            exit();
        } else {
            wp_set_current_user($user_signon->ID);
            do_action('set_current_user');
            global $current_user;
            $current_user = wp_get_current_user();
            echo json_encode(array('signedin'=>true, 'message'=>__('Sign in successful, redirecting...', 'resideo')));
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_facebook_login', 'resideo_facebook_login');
add_action('wp_ajax_resideo_facebook_login', 'resideo_facebook_login');

/**
 * Google Signin
 */
if (!function_exists('resideo_google_signin')): 
    function resideo_google_signin() {
        if (is_user_logged_in()) { 
            echo json_encode(array('signedin'=>true, 'message'=>__('You are already signed in, redirecting...', 'resideo')));
            exit();
        }
        check_ajax_referer('signin_ajax_nonce', 'security');

        $auth_settings        = get_option('resideo_authentication_settings');
        $google_client_id     = isset($auth_settings['resideo_google_id_field']) ? $auth_settings['resideo_google_id_field'] : '';
        $google_client_secret = isset($auth_settings['resideo_google_secret_field']) ? $auth_settings['resideo_google_secret_field'] : '';

        $user_id     = isset($_POST['userid']) ? sanitize_text_field($_POST['userid']) : '';
        $signin_user = isset($_POST['signin_user']) ? sanitize_text_field($_POST['signin_user']) : '';
        $first_name  = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : '';
        $last_name   = isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : '';
        $email       = isset($_POST['email']) ? sanitize_text_field($_POST['email']) : '';
        $avatar      = isset($_POST['avatar']) ? sanitize_text_field($_POST['avatar']) : '';
        $signin_pass = $google_client_secret . $user_id;

        $photo = resideo_import_image($avatar, 'Avatar');

        resideo_social_signup($email, $signin_user, $first_name, $last_name, $signin_pass);

        $vsessionid = session_id();
        if (empty($vsessionid)) {
            session_name('PHPSESSID');
            session_start();
        }

        wp_clear_auth_cookie();
        $data = array();
        $data['user_login'] = $signin_user;
        $data['user_password'] = $signin_pass;
        $data['remember'] = true;

        $user_signon = wp_signon($data, false);
        update_user_meta($user_signon->ID, 'avatar', $photo);

        if (is_wp_error($user_signon)) {
            echo json_encode(array('signedin'=>false, 'message'=>__('Something went wrong!', 'resideo')));
            exit();
        } else {
            wp_set_current_user($user_signon->ID);
            do_action('set_current_user');
            global $current_user;
            $current_user = wp_get_current_user();
            echo json_encode(array('signedin'=>true, 'message'=>__('Sign in successful, redirecting...', 'resideo')));
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_google_signin', 'resideo_google_signin');
add_action('wp_ajax_resideo_google_signin', 'resideo_google_signin');

/**
 * Social Sign Up
 */
if (!function_exists('resideo_social_signup')): 
    function resideo_social_signup($email, $signin_user, $first_name, $last_name, $pass) {
        $user_data = array(
            'user_login' => $signin_user,
            'user_email' => $email,
            'user_pass'  => $pass,
            'first_name' => $first_name,
            'last_name'  => $last_name
        );

        if (email_exists($email)) {
            if (username_exists($signin_user)) {
                return;
            } else {
                $user_data['user_email'] = ' ';
                $new_user  = wp_insert_user($user_data);
                // register as admin by default with social accounts
                // reales_register_agent($new_user);
                if (is_wp_error($new_user)) {
                    // social user signup failed
                }
            }
        } else {
            if (username_exists($signin_user)) {
                return;
            } else {
                $new_user = wp_insert_user($user_data);
                // register as admin by default with social accounts
                // reales_register_agent($new_user);
                if (is_wp_error($new_user)) {
                    // social user signup failed
                }
            }
        }
    }
endif;

/**
 * Update user profile
 */
if (!function_exists('resideo_update_account_settings')): 
    function resideo_update_account_settings() {
        check_ajax_referer('account_settings_ajax_nonce', 'security');

        $user_id    = isset($_POST['user_id']) ? sanitize_text_field($_POST['user_id']) : '';
        $first_name = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : '';
        $last_name  = isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : '';
        $nickname   = isset($_POST['nickname']) ? sanitize_text_field($_POST['nickname']) : '';
        $email      = isset($_POST['email']) ? sanitize_text_field($_POST['email']) : '';
        $password   = isset($_POST['password']) ? sanitize_text_field($_POST['password']) : '';
        $avatar     = isset($_POST['avatar']) ? sanitize_text_field($_POST['avatar']) : '';

        $agent_id        = isset($_POST['agent_id']) ? sanitize_text_field($_POST['agent_id']) : '';
        $agent_about     = isset($_POST['agent_about']) ? $_POST['agent_about'] : '';
        $agent_title     = isset($_POST['agent_title']) ? sanitize_text_field($_POST['agent_title']) : '';
        $agent_specs     = isset($_POST['agent_specs']) ? sanitize_text_field($_POST['agent_specs']) : '';
        $agent_phone     = isset($_POST['agent_phone']) ? sanitize_text_field($_POST['agent_phone']) : '';
        $agent_skype     = isset($_POST['agent_skype']) ? sanitize_text_field($_POST['agent_skype']) : '';
        $agent_facebook  = isset($_POST['agent_facebook']) ? sanitize_text_field($_POST['agent_facebook']) : '';
        $agent_twitter   = isset($_POST['agent_twitter']) ? sanitize_text_field($_POST['agent_twitter']) : '';
        $agent_pinterest = isset($_POST['agent_pinterest']) ? sanitize_text_field($_POST['agent_pinterest']) : '';
        $agent_linkedin  = isset($_POST['agent_linkedin']) ? sanitize_text_field($_POST['agent_linkedin']) : '';
        $agent_instagram = isset($_POST['agent_instagram']) ? sanitize_text_field($_POST['agent_instagram']) : '';

        if ($first_name == '') {
            echo json_encode(array('save' => false, 'message' => __('First Name field is mandatory.', 'resideo')));
            exit();
        }
        if ($last_name == '') {
            echo json_encode(array('save' => false, 'message' => __('Last Name field is mandatory.', 'resideo')));
            exit();
        }
        if ($email == '') {
            echo json_encode(array('save' => false, 'message' => __('E-mail field is mandatory.', 'resideo')));
            exit();
        }
        if ($nickname == '') {
            echo json_encode(array('save' => false, 'message' => __('Nickname field is mandatory.', 'resideo')));
            exit();
        }

        update_user_meta($user_id, 'first_name', $first_name);
        update_user_meta($user_id, 'last_name', $last_name);
        update_user_meta($user_id, 'nickname', $nickname);
        update_user_meta($user_id, 'avatar', $avatar);

        if ($password != '') {
            wp_update_user(array('ID' => $user_id, 'user_email' => $email, 'user_pass' => $password));
        } else {
            wp_update_user(array('ID' => $user_id, 'user_email' => $email));
        }

        if($agent_id != '') {
            $agent_name = $first_name . ' ' . $last_name;

            $agent = array(
                'ID'           => $agent_id,
                'post_title'   => $agent_name,
                'post_content' => $agent_about,
                'post_type'    => 'agent',
                'post_status'  => 'publish'
            );

            $agent_id = wp_insert_post($agent);

            update_post_meta($agent_id, 'agent_title', $agent_title);
            update_post_meta($agent_id, 'agent_specs', $agent_specs);
            update_post_meta($agent_id, 'agent_phone', $agent_phone);
            update_post_meta($agent_id, 'agent_skype', $agent_skype);
            update_post_meta($agent_id, 'agent_facebook', $agent_facebook);
            update_post_meta($agent_id, 'agent_twitter', $agent_twitter);
            update_post_meta($agent_id, 'agent_pinterest', $agent_pinterest);
            update_post_meta($agent_id, 'agent_linkedin', $agent_linkedin);
            update_post_meta($agent_id, 'agent_instagram', $agent_instagram);
            update_post_meta($agent_id, 'agent_email', $email);
            update_post_meta($agent_id, 'agent_avatar', $avatar);
        }

        echo json_encode(array('save' => true, 'message' => __('Your account settings were successfully updated. Redirecting...', 'resideo')));
        exit();

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_update_account_settings', 'resideo_update_account_settings');
add_action('wp_ajax_resideo_update_account_settings', 'resideo_update_account_settings');

/**
 * Save search
 */
if (!function_exists('resideo_save_search')): 
    function resideo_save_search() {
        check_ajax_referer('savesearch_ajax_nonce', 'security');

        $search_name = isset($_POST['search_name']) ? sanitize_text_field($_POST['search_name']) : '';
        $search_url  = isset($_POST['search_url']) ? sanitize_text_field($_POST['search_url']) : '';

        if (empty($search_name)) {
            echo json_encode(array('saved'=>false, 'message'=>__('Please enter a name for your search and try again.', 'resideo')));
            exit();
        }

        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;

        if ($user_id != '') {
            $search_user_data = get_user_meta($user_id, 'user_search', true);

            $queryString = parse_url($search_url);
            $queryString = $queryString['query']; 

            $args = array();
            parse_str($queryString, $args);

            $search_data = array(
                'name'   => $search_name,
                'url'    => $search_url,
                'date'   => date('Y-m-d'),
                'fields' => $args,
            );

            if (!is_array($search_user_data)) $search_user_data = array();
            array_push($search_user_data, $search_data);
            update_user_meta($user_id, 'user_search', $search_user_data);

            $search_new_data = get_user_meta($user_id, 'user_search', true);

            if ($search_new_data != $search_user_data) {
                echo json_encode(array('saved'=>false, 'message'=>__('Something went wrong. Please try again.', 'resideo')));
                exit();
            } else {
                echo json_encode(array('saved'=>true, 'message'=>__('Your search was successfully saved.', 'resideo'), 'data'=>$search_new_data));
                exit();
            }
        } else {
            echo json_encode(array('saved'=>false, 'message'=>__('Something went wrong. Please try again.', 'resideo')));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_save_search', 'resideo_save_search');
add_action('wp_ajax_resideo_save_search', 'resideo_save_search');

/**
 * Delete search
 */
if (!function_exists('resideo_delete_search')): 
    function resideo_delete_search() {
        check_ajax_referer('deletesearch_ajax_nonce', 'security');

        $search_name = isset($_POST['search_name']) ? sanitize_text_field($_POST['search_name']) : '';
        $user_id     = isset($_POST['user_id']) ? sanitize_text_field($_POST['user_id']) : '';

        $searches = get_user_meta($user_id, 'user_search', true);

        foreach ($searches as $key => $value) {
            if ($value['name'] == $search_name) {
                unset($searches[$key]);
            }
        }

        update_user_meta($user_id, 'user_search', $searches);

        echo json_encode(array('sent'=>true));
        exit();

        die();
    }
endif;
add_action('wp_ajax_nopriv_resideo_delete_search', 'resideo_delete_search');
add_action('wp_ajax_resideo_delete_search', 'resideo_delete_search');

/**
 * Get saved searches page URL
 */
if (!function_exists('resideo_get_searches_url')):
    function resideo_get_searches_url() {
        $pages = get_pages(array(
            'meta_key'   => '_wp_page_template',
            'meta_value' => 'saved-searches.php'
        ));

        if ($pages) {
            $searches_url = get_permalink($pages[0]->ID);
        } else {
            $searches_url = home_url();
        }

        return $searches_url;
    }
endif;

/**
 * Get submit new property page URL
 */
if (!function_exists('resideo_get_submit_url')):
    function resideo_get_submit_url() {
        $pages = get_pages(array(
            'meta_key'   => '_wp_page_template',
            'meta_value' => 'submit-property.php'
        ));

        if ($pages) {
            $submit_url = get_permalink($pages[0]->ID);
        } else {
            $submit_url = home_url();
        }

        return $submit_url;
    }
endif;

/**
 * Get my properties page URL
 */
if (!function_exists('resideo_get_myproperties_url')):
    function resideo_get_myproperties_url() {
        $pages = get_pages(array(
            'meta_key'   => '_wp_page_template',
            'meta_value' => 'my-properties.php'
        ));

        if ($pages) {
            $myproperties_url = get_permalink($pages[0]->ID);
        } else {
            $myproperties_url = home_url();
        }

        return $myproperties_url;
    }
endif;

/**
 * Get my leads page URL
 */
if (!function_exists('resideo_get_myleads_url')):
    function resideo_get_myleads_url() {
        $pages = get_pages(array(
            'meta_key'   => '_wp_page_template',
            'meta_value' => 'my-leads.php'
        ));

        if ($pages) {
            $myleads_url = get_permalink($pages[0]->ID);
        } else {
            $myleads_url = home_url();
        }

        return $myleads_url;
    }
endif;

/**
 * Get account settings page URL
 */
if (!function_exists('resideo_get_account_url')):
    function resideo_get_account_url() {
        $pages = get_pages(array(
            'meta_key'   => '_wp_page_template',
            'meta_value' => 'account-settings.php'
        ));

        if ($pages) {
            $account_url = get_permalink($pages[0]->ID);
        } else {
            $account_url = home_url();
        }

        return $account_url;
    }
endif;

/**
 * Add custom profile fields
 */
if (!function_exists('resideo_add_custom_profile_fields')) :
    function resideo_add_custom_profile_fields($user) {
        $avatar_src = wp_get_attachment_image_src(get_the_author_meta('avatar', $user->ID), 'pxp-agent');

        print   '<h3>' . __('Profile Picture', 'resideo'). '</h3>
                <div class="adminField">
                    <input type="hidden" id="avatar" name="avatar" value="' . esc_attr(get_the_author_meta('avatar', $user->ID)) . '">
                    <div class="user-avatar-placeholder-container';

        if ($avatar_src !== false) { 
            echo ' has-image'; 
        }

        print '"><div id="user-avatar-image-placeholder" style="background-image: url(';

        if ($avatar_src !== false) { 
            echo esc_url($avatar_src[0]); 
        } else { 
            echo esc_url(RESIDEO_PLUGIN_PATH . 'post-types/images/avatar-placeholder.png');
        }

        print ');"></div>
                <div id="delete-user-avatar-image"><span class="fa fa-trash-o"></span></div>
            </div></div>
        ';
}
endif;
add_action('show_user_profile', 'resideo_add_custom_profile_fields');
add_action('edit_user_profile', 'resideo_add_custom_profile_fields');

if (!function_exists('resideo_save_custom_profile_fields')) :
    function resideo_save_custom_profile_fields($user_id) {
        if (!current_user_can('edit_user', $user_id)) { 
            return false;
        }

        update_user_meta($user_id, 'avatar', $_POST['avatar']);
    }
endif;
add_action('personal_options_update', 'resideo_save_custom_profile_fields');
add_action('edit_user_profile_update', 'resideo_save_custom_profile_fields');

if (!function_exists('resideo_custom_avatar')) :
    function resideo_custom_avatar($avatar, $id_or_email, $size, $default, $alt) {
        $user = false;

        if (is_numeric($id_or_email)) {
            $id = (int) $id_or_email;
            $user = get_user_by('id' , $id);
        } elseif(is_object($id_or_email)) {
            if (!empty( $id_or_email->user_id)) {
                $id = (int) $id_or_email->user_id;
                $user = get_user_by('id', $id);
            }
        } else {
            $user = get_user_by('email', $id_or_email);
        }

        if ($user && is_object($user)) {
            $user_avatar = wp_get_attachment_image_src(get_the_author_meta('avatar', $user->data->ID), 'pxp-agent');

            if ($user_avatar !== false) {
                $avatar = $user_avatar[0];
            } else {
                $avatar = RESIDEO_PLUGIN_PATH . 'images/avatar-default.png';
            }

            $avatar_img = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
        } else {
            $avatar = RESIDEO_PLUGIN_PATH . 'images/avatar-default.png';
            $avatar_img = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
        }

        return $avatar_img;
    }
endif;
add_filter('get_avatar', 'resideo_custom_avatar', 10, 5);
?>