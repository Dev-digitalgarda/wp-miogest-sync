<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!function_exists('resideo_admin_general_settings')): 
    function resideo_admin_general_settings() {
        add_settings_section('resideo_general_section', __('General Settings', 'resideo'), 'resideo_general_section_callback', 'resideo_general_settings');
        add_settings_field('resideo_auto_country_field', __('Limit Google Autocomplete to One Country', 'resideo'), 'resideo_auto_country_field_render', 'resideo_general_settings', 'resideo_general_section');
        add_settings_field('resideo_locale_field', __('Price Format', 'resideo'), 'resideo_locale_field_render', 'resideo_general_settings', 'resideo_general_section');
        add_settings_field('resideo_map_marker_price_format_field', __('Map Marker Price Format', 'resideo'), 'resideo_map_marker_price_format_field_render', 'resideo_general_settings', 'resideo_general_section');
        add_settings_field('resideo_decimals_field', __('Use Decimals for Price', 'resideo'), 'resideo_decimals_field_render', 'resideo_general_settings', 'resideo_general_section');
        add_settings_field('resideo_currency_symbol_field', __('Currency Symbol', 'resideo'), 'resideo_currency_symbol_field_render', 'resideo_general_settings', 'resideo_general_section');
        add_settings_field('resideo_currency_symbol_pos_field', __('Currency Symbol Position', 'resideo'), 'resideo_currency_symbol_pos_field_render', 'resideo_general_settings', 'resideo_general_section');
        add_settings_field('resideo_max_price_field', __('Price Range Max Value', 'resideo'), 'resideo_max_price_field_render', 'resideo_general_settings', 'resideo_general_section');
        add_settings_field('resideo_beds_label_field', __('Property Bedrooms Label', 'resideo'), 'resideo_beds_label_field_render', 'resideo_general_settings', 'resideo_general_section' );
        add_settings_field('resideo_baths_label_field', __('Property Bathrooms Label', 'resideo'), 'resideo_baths_label_field_render', 'resideo_general_settings', 'resideo_general_section' );
        add_settings_field('resideo_unit_field', __('Property Size Unit', 'resideo'), 'resideo_unit_field_render', 'resideo_general_settings', 'resideo_general_section' );
        add_settings_field('resideo_max_files_field', __('Max Number of Uploaded Photos per Property', 'resideo'), 'resideo_max_files_field_render', 'resideo_general_settings', 'resideo_general_section' );
        add_settings_field('resideo_agents_rate_field', __('Enable Agent/Owner Reviews and Rating', 'resideo'), 'resideo_agents_rating_field_render', 'resideo_general_settings', 'resideo_general_section' );
        add_settings_field('resideo_no_review_field', __('Allow Property Publish Without Admin Approval', 'resideo'), 'resideo_no_review_field_render', 'resideo_general_settings', 'resideo_general_section' );
        add_settings_field('resideo_featured_property_field', __('Allow Agents to Set Properties as Featured', 'resideo'), 'resideo_featured_property_field_render', 'resideo_general_settings', 'resideo_general_section' );
        add_settings_field('resideo_show_print_property_field', __('Show Print Property Option', 'resideo'), 'resideo_show_print_property_field_render', 'resideo_general_settings', 'resideo_general_section' );
        add_settings_field('resideo_show_report_property_field', __('Show Report Property Option', 'resideo'), 'resideo_show_report_property_field_render', 'resideo_general_settings', 'resideo_general_section' );
        add_settings_field('resideo_copyright_field', __('Footer Copyright Text', 'resideo'), 'resideo_copyright_field_render', 'resideo_general_settings', 'resideo_general_section' );
    }
endif;

if (!function_exists('resideo_general_section_callback')): 
    function resideo_general_section_callback() {
        echo '';
    }
endif;

if (!function_exists('resideo_auto_country_field_render')): 
    function resideo_auto_country_field_render() { 
        $options   = get_option('resideo_general_settings');
        $countries = array("af" => __("Afghanistan", "resideo"), "ax" => __("Åland Islands", "resideo"), "al" => __("Albania", "resideo"), "dz" => __("Algeria", "resideo"), "as" => __("American Samoa", "resideo"), "ad" => __("Andorra", "resideo"), "ao" => __("Angola", "resideo"), "ai" => __("Anguilla", "resideo"), "aq" => __("Antarctica", "resideo"), "ag" => __("Antigua and Barbuda", "resideo"), "ar" => __("Argentina", "resideo"), "am" => __("Armenia", "resideo"), "aw" => __("Aruba", "resideo"), "au" => __("Australia", "resideo"), "at" => __("Austria", "resideo"), "az" => __("Azerbaijan", "resideo"), "bs" => __("Bahamas", "resideo"), "bh" => __("Bahrain", "resideo"), "bd" => __("Bangladesh", "resideo"), "bb" => __("Barbados", "resideo"), "by" => __("Belarus", "resideo"), "be" => __("Belgium", "resideo"), "bz" => __("Belize", "resideo"), "bj" => __("Benin", "resideo"), "bm" => __("Bermuda", "resideo"), "bt" => __("Bhutan", "resideo"), "bo" => __("Bolivia (Plurinational State of)", "resideo"), "bq" => __("Bonaire, Sint Eustatius and Saba", "resideo"), "ba" => __("Bosnia and Herzegovina", "resideo"), "bw" => __("Botswana", "resideo"), "bv" => __("Bouvet Island", "resideo"), "br" => __("Brazil", "resideo"), "io" => __("British Indian Ocean Territory", "resideo"), "bn" => __("Brunei Darussalam", "resideo"), "bg" => __("Bulgaria", "resideo"), "bf" => __("Burkina Faso", "resideo"), "bi" => __("Burundi", "resideo"), "cv" => __("Cabo Verde", "resideo"), "kh" => __("Cambodia", "resideo"), "cm" => __("Cameroon", "resideo"), "ca" => __("Canada", "resideo"), "ky" => __("Cayman Islands", "resideo"), "cf" => __("Central African Republic", "resideo"), "td" => __("Chad", "resideo"), "cl" => __("Chile", "resideo"), "cn" => __("China", "resideo"), "cx" => __("Christmas Island", "resideo"), "cc" => __("Cocos (Keeling) Islands", "resideo"), "co" => __("Colombia", "resideo"), "km" => __("Comoros", "resideo"), "cg" => __("Congo", "resideo"), "cd" => __("Congo (Democratic Republic of the)", "resideo"), "ck" => __("Cook Islands", "resideo"), "cr" => __("Costa Rica", "resideo"), "ci" => __("Côte d'Ivoire", "resideo"), "hr" => __("Croatia", "resideo"), "cu" => __("Cuba", "resideo"), "cw" => __("Curaçao", "resideo"), "cy" => __("Cyprus", "resideo"), "cz" => __("Czech Republic", "resideo"), "dk" => __("Denmark", "resideo"), "dj" => __("Djibouti", "resideo"), "dm" => __("Dominica", "resideo"), "do" => __("Dominican Republic", "resideo"), "ec" => __("Ecuador", "resideo"), "eg" => __("Egypt", "resideo"), "sv" => __("El Salvador", "resideo"), "gq" => __("Equatorial Guinea", "resideo"), "er" => __("Eritrea", "resideo"), "ee" => __("Estonia", "resideo"), "et" => __("Ethiopia", "resideo"), "fk" => __("Falkland Islands (Malvinas)", "resideo"), "fo" => __("Faroe Islands", "resideo"), "fj" => __("Fiji", "resideo"), "fi" => __("Finland", "resideo"), "fr" => __("France", "resideo"), "gf" => __("French Guiana", "resideo"), "pf" => __("French Polynesia", "resideo"), "tf" => __("French Southern Territories", "resideo"), "ga" => __("Gabon", "resideo"), "gm" => __("Gambia", "resideo"), "ge" => __("Georgia", "resideo"), "de" => __("Germany", "resideo"), "gh" => __("Ghana", "resideo"), "gi" => __("Gibraltar", "resideo"), "gr" => __("Greece", "resideo"), "gl" => __("Greenland", "resideo"), "gd" => __("Grenada", "resideo"), "gp" => __("Guadeloupe", "resideo"), "gu" => __("Guam", "resideo"), "gt" => __("Guatemala", "resideo"), "gg" => __("Guernsey", "resideo"), "gn" => __("Guinea", "resideo"), "gw" => __("Guinea-Bissau", "resideo"), "gy" => __("Guyana", "resideo"), "ht" => __("Haiti", "resideo"), "hm" => __("Heard Island and McDonald Islands", "resideo"), "va" => __("Holy See", "resideo"), "hn" => __("Honduras", "resideo"), "hk" => __("Hong Kong", "resideo"), "hu" => __("Hungary", "resideo"), "is" => __("Iceland", "resideo"), "in" => __("India", "resideo"), "id" => __("Indonesia", "resideo"), "ir" => __("Iran (Islamic Republic of)", "resideo"), "iq" => __("Iraq", "resideo"), "ie" => __("Ireland", "resideo"), "im" => __("Isle of Man", "resideo"), "il" => __("Israel", "resideo"), "it" => __("Italy", "resideo"), "jm" => __("Jamaica", "resideo"), "jp" => __("Japan", "resideo"), "je" => __("Jersey", "resideo"), "jo" => __("Jordan", "resideo"), "kz" => __("Kazakhstan", "resideo"), "ke" => __("Kenya", "resideo"), "ki" => __("Kiribati", "resideo"), "kp" => __("Korea (Democratic People's Republic of)", "resideo"), "kr" => __("Korea (Republic of)", "resideo"), "kw" => __("Kuwait", "resideo"), "kg" => __("Kyrgyzstan", "resideo"), "la" => __("Lao People's Democratic Republic", "resideo"), "lv" => __("Latvia", "resideo"), "lb" => __("Lebanon", "resideo"), "ls" => __("Lesotho", "resideo"), "lr" => __("Liberia", "resideo"), "ly" => __("Libya", "resideo"), "li" => __("Liechtenstein", "resideo"), "lt" => __("Lithuania", "resideo"), "lu" => __("Luxembourg", "resideo"), "mo" => __("Macao", "resideo"), "mk" => __("Macedonia (the former Yugoslav Republic of)", "resideo"), "mg" => __("Madagascar", "resideo"), "mw" => __("Malawi", "resideo"), "my" => __("Malaysia", "resideo"), "mv" => __("Maldives", "resideo"), "ml" => __("Mali", "resideo"), "mt" => __("Malta", "resideo"), "mh" => __("Marshall Islands", "resideo"), "mq" => __("Martinique", "resideo"), "mr" => __("Mauritania", "resideo"), "mu" => __("Mauritius", "resideo"), "yt" => __("Mayotte", "resideo"), "mx" => __("Mexico", "resideo"), "fm" => __("Micronesia (Federated States of)", "resideo"), "md" => __("Moldova (Republic of)", "resideo"), "mc" => __("Monaco", "resideo"), "mn" => __("Mongolia", "resideo"), "me" => __("Montenegro", "resideo"), "ms" => __("Montserrat", "resideo"), "ma" => __("Morocco", "resideo"), "mz" => __("Mozambique", "resideo"), "mm" => __("Myanmar", "resideo"), "na" => __("Namibia", "resideo"), "nr" => __("Nauru", "resideo"), "np" => __("Nepal", "resideo"), "nl" => __("Netherlands", "resideo"), "nc" => __("New Caledonia", "resideo"), "nz" => __("New Zealand", "resideo"), "ni" => __("Nicaragua", "resideo"), "ne" => __("Niger", "resideo"), "ng" => __("Nigeria", "resideo"), "nu" => __("Niue", "resideo"), "nf" => __("Norfolk Island", "resideo"), "mp" => __("Northern Mariana Islands", "resideo"), "no" => __("Norway", "resideo"), "om" => __("Oman", "resideo"), "pk" => __("Pakistan", "resideo"), "pw" => __("Palau", "resideo"), "ps" => __("Palestine, State of", "resideo"), "pa" => __("Panama", "resideo"), "pg" => __("Papua New Guinea", "resideo"), "py" => __("Paraguay", "resideo"), "pe" => __("Peru", "resideo"), "ph" => __("Philippines", "resideo"), "pn" => __("Pitcairn", "resideo"), "pl" => __("Poland", "resideo"), "pt" => __("Portugal", "resideo"), "pr" => __("Puerto Rico", "resideo"), "qa" => __("Qatar", "resideo"), "re" => __("Réunion", "resideo"), "ro" => __("Romania", "resideo"), "ru" => __("Russian Federation", "resideo"), "rw" => __("Rwanda", "resideo"), "bl" => __("Saint Barthélemy", "resideo"), "sh" => __("Saint Helena, Ascension and Tristan da Cunha", "resideo"), "kn" => __("Saint Kitts and Nevis", "resideo"), "lc" => __("Saint Lucia", "resideo"), "mf" => __("Saint Martin (French part)", "resideo"), "pm" => __("Saint Pierre and Miquelon", "resideo"), "vc" => __("Saint Vincent and the Grenadines", "resideo"), "ws" => __("Samoa", "resideo"), "sm" => __("San Marino", "resideo"), "st" => __("Sao Tome and Principe", "resideo"), "sa" => __("Saudi Arabia", "resideo"), "sn" => __("Senegal", "resideo"), "rs" => __("Serbia", "resideo"), "sc" => __("Seychelles", "resideo"), "sl" => __("Sierra Leone", "resideo"), "sg" => __("Singapore", "resideo"), "sx" => __("Sint Maarten (Dutch part)", "resideo"), "sk" => __("Slovakia", "resideo"), "si" => __("Slovenia", "resideo"), "sb" => __("Solomon Islands", "resideo"), "so" => __("Somalia", "resideo"), "za" => __("South Africa", "resideo"), "gs" => __("South Georgia and the South Sandwich Islands", "resideo"), "ss" => __("South Sudan", "resideo"), "es" => __("Spain", "resideo"), "lk" => __("Sri Lanka", "resideo"), "sd" => __("Sudan", "resideo"), "sr" => __("Suriname", "resideo"), "sj" => __("Svalbard and Jan Mayen", "resideo"), "sz" => __("Swaziland", "resideo"), "se" => __("Sweden", "resideo"), "ch" => __("Switzerland", "resideo"), "sy" => __("Syrian Arab Republic", "resideo"), "tw" => __("Taiwan, Province of China", "resideo"), "tj" => __("Tajikistan", "resideo"), "tz" => __("Tanzania, United Republic of", "resideo"), "th" => __("Thailand", "resideo"), "tl" => __("Timor-Leste", "resideo"), "tg" => __("Togo", "resideo"), "tk" => __("Tokelau", "resideo"), "to" => __("Tonga", "resideo"), "tt" => __("Trinidad and Tobago", "resideo"), "tn" => __("Tunisia", "resideo"), "tr" => __("Turkey", "resideo"), "tm" => __("Turkmenistan", "resideo"), "tc" => __("Turks and Caicos Islands", "resideo"), "tv" => __("Tuvalu", "resideo"), "ug" => __("Uganda", "resideo"), "ua" => __("Ukraine", "resideo"), "ae" => __("United Arab Emirates", "resideo"), "gb" => __("United Kingdom of Great Britain and Northern Ireland", "resideo"), "us" => __("United States of America", "resideo"), "um" => __("United States Minor Outlying Islands", "resideo"), "uy" => __("Uruguay", "resideo"), "uz" => __("Uzbekistan", "resideo"), "vu" => __("Vanuatu", "resideo"), "ve" => __("Venezuela (Bolivarian Republic of)", "resideo"), "vn" => __("Viet Nam", "resideo"), "vg" => __("Virgin Islands (British)", "resideo"), "vi" => __("Virgin Islands (U.S.)", "resideo"), "wf" => __("Wallis and Futuna", "resideo"), "eh" => __("Western Sahara", "resideo"), "ye" => __("Yemen", "resideo"), "zm" => __("Zambia", "resideo"), "zw" => __("Zimbabwe", "resideo"));

        $field = '<select id="resideo_general_settings[resideo_auto_country_field]" name="resideo_general_settings[resideo_auto_country_field]">';
        $field .= '<option value="">' . __('All', 'resideo') . '</option>';

        foreach ($countries as $key => $value) {
            $field .= '<option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_auto_country_field']) && $options['resideo_auto_country_field'] == $key) {
                $field .= 'selected="selected"';
            }
            $field .= '>' . esc_html($value) . '</option>';
        }

        $field .= '</select>';

        print $field;
    }
endif;

if (!function_exists('resideo_locale_field_render')): 
    function resideo_locale_field_render() { 
        $options = get_option('resideo_general_settings');
        $locales = array("en_US" => __("English (United States)", "resideo"), "af_ZA" => __("Afrikaans (South Africa)", "resideo"), "sq_AL" => __("Albanian (Albania)", "resideo"), "ar_BH" => __("Arabic (Bahrain)", "resideo"), "ar_EG" => __("Arabic (Egypt)", "resideo"), "ar_IQ" => __("Arabic (Iraq)", "resideo"), "ar_JO" => __("Arabic (Jordan)", "resideo"), "ar_KW" => __("Arabic (Kuwait)", "resideo"), "ar_LB" => __("Arabic (Lebanon)", "resideo"), "ar_LY" => __("Arabic (Libya)", "resideo"), "ar_MA" => __("Arabic (Morocco)", "resideo"), "ar_OM" => __("Arabic (Oman)", "resideo"), "ar_QA" => __("Arabic (Qatar)", "resideo"), "ar_SA" => __("Arabic (Saudi Arabia)", "resideo"), "ar_SD" => __("Arabic (Sudan)", "resideo"), "ar_SY" => __("Arabic (Syria)", "resideo"), "ar_TN" => __("Arabic (Tunisia)", "resideo"), "ar_AE" => __("Arabic (United Arab Emirates)", "resideo"), "ar_YE" => __("Arabic (Yemen)", "resideo"), "hy_AM" => __("Armenian (Armenia)", "resideo"), "as_IN" => __("Assamese (India)", "resideo"), "bm_ML" => __("Bambara (Mali)", "resideo"), "eu_ES" => __("Basque (Spain)", "resideo"), "be_BY" => __("Belarusian (Belarus)", "resideo"), "bn_BD" => __("Bengali (Bangladesh)", "resideo"), "bn_IN" => __("Bengali (India)", "resideo"), "bs_BA" => __("Bosnian (Bosnia and Herzegovina)", "resideo"), "bg_BG" => __("Bulgarian (Bulgaria)", "resideo"), "my_MM" => __("Burmese (Myanmar [Burma])", "resideo"), "ca_ES" => __("Catalan (Spain)", "resideo"), "cgg_UG" => __("Chiga (Uganda)", "resideo"), "zh_Hans" => __("Chinese (Simplified Han)", "resideo"), "zh_Hant" => __("Chinese (Traditional Han)", "resideo"), "kw_GB" => __("Cornish (United Kingdom)", "resideo"), "hr_HR" => __("Croatian (Croatia)", "resideo"), "cs_CZ" => __("Czech (Czech Republic)", "resideo"), "da_DK" => __("Danish (Denmark)", "resideo"), "nl_BE" => __("Dutch (Belgium)", "resideo"), "nl_NL" => __("Dutch (Netherlands)", "resideo"), "en_AS" => __("English (American Samoa)", "resideo"), "en_AU" => __("English (Australia)", "resideo"), "en_BE" => __("English (Belgium)", "resideo"), "en_BZ" => __("English (Belize)", "resideo"), "en_BW" => __("English (Botswana)", "resideo"), "en_CA" => __("English (Canada)", "resideo"), "en_GU" => __("English (Guam)", "resideo"), "en_HK" => __("English (Hong Kong SAR China)", "resideo"), "en_IN" => __("English (India)", "resideo"), "en_IE" => __("English (Ireland)", "resideo"), "en_JM" => __("English (Jamaica)", "resideo"), "en_MT" => __("English (Malta)", "resideo"), "en_MH" => __("English (Marshall Islands)", "resideo"), "en_MU" => __("English (Mauritius)", "resideo"), "en_NA" => __("English (Namibia)", "resideo"), "en_NZ" => __("English (New Zealand)", "resideo"), "en_MP" => __("English (Northern Mariana Islands)", "resideo"), "en_PK" => __("English (Pakistan)", "resideo"), "en_PH" => __("English (Philippines)", "resideo"), "en_SG" => __("English (Singapore)", "resideo"), "en_ZA" => __("English (South Africa)", "resideo"), "en_TT" => __("English (Trinidad and Tobago)", "resideo"), "en_UM" => __("English (U.S. Minor Outlying Islands)", "resideo"), "en_VI" => __("English (U.S. Virgin Islands)", "resideo"), "en_GB" => __("English (United Kingdom)", "resideo"), "en_ZW" => __("English (Zimbabwe)", "resideo"), "et_EE" => __("Estonian (Estonia)", "resideo"), "fo_FO" => __("Faroese (Faroe Islands)", "resideo"), "fil_PH" => __("Filipino (Philippines)", "resideo"), "fi_FI" => __("Finnish (Finland)", "resideo"), "fr_BE" => __("French (Belgium)", "resideo"), "fr_BJ" => __("French (Benin)", "resideo"), "fr_BF" => __("French (Burkina Faso)", "resideo"), "fr_BI" => __("French (Burundi)", "resideo"), "fr_CM" => __("French (Cameroon)", "resideo"), "fr_CA" => __("French (Canada)", "resideo"), "fr_CF" => __("French (Central African Republic)", "resideo"), "fr_TD" => __("French (Chad)", "resideo"), "fr_KM" => __("French (Comoros)", "resideo"), "fr_CG" => __("French (Congo - Brazzaville)", "resideo"), "fr_CD" => __("French (Congo - Kinshasa)", "resideo"), "fr_CI" => __("French (Côte d’Ivoire)", "resideo"), "fr_DJ" => __("French (Djibouti)", "resideo"), "fr_GQ" => __("French (Equatorial Guinea)", "resideo"), "fr_FR" => __("French (France)", "resideo"), "fr_GA" => __("French (Gabon)", "resideo"), "fr_GP" => __("French (Guadeloupe)", "resideo"), "fr_GN" => __("French (Guinea)", "resideo"), "fr_LU" => __("French (Luxembourg)", "resideo"), "fr_MG" => __("French (Madagascar)", "resideo"), "fr_ML" => __("French (Mali)", "resideo"), "fr_MQ" => __("French (Martinique)", "resideo"), "fr_MC" => __("French (Monaco)", "resideo"), "fr_NE" => __("French (Niger)", "resideo"), "fr_RW" => __("French (Rwanda)", "resideo"), "fr_RE" => __("French (Réunion)", "resideo"), "fr_BL" => __("French (Saint Barthélemy)", "resideo"), "fr_MF" => __("French (Saint Martin)", "resideo"), "fr_SN" => __("French (Senegal)", "resideo"), "fr_CH" => __("French (Switzerland)", "resideo"), "fr_TG" => __("French (Togo)", "resideo"), "ff_SN" => __("Fulah (Senegal)", "resideo"), "gl_ES" => __("Galician (Spain)", "resideo"), "ka_GE" => __("Georgian (Georgia)", "resideo"), "de_AT" => __("German (Austria)", "resideo"), "de_BE" => __("German (Belgium)", "resideo"), "de_DE" => __("German (Germany)", "resideo"), "de_LI" => __("German (Liechtenstein)", "resideo"), "de_LU" => __("German (Luxembourg)", "resideo"), "de_CH" => __("German (Switzerland)", "resideo"), "el_CY" => __("Greek (Cyprus)", "resideo"), "el_GR" => __("Greek (Greece)", "resideo"), "gu_IN" => __("Gujarati (India)", "resideo"), "guz_KE" => __("Gusii (Kenya)", "resideo"), "he_IL" => __("Hebrew (Israel)", "resideo"), "hi_IN" => __("Hindi (India)", "resideo"), "hu_HU" => __("Hungarian (Hungary)", "resideo"), "is_IS" => __("Icelandic (Iceland)", "resideo"), "ig_NG" => __("Igbo (Nigeria)", "resideo"), "id_ID" => __("Indonesian (Indonesia)", "resideo"), "ga_IE" => __("Irish (Ireland)", "resideo"), "it_IT" => __("Italian (Italy)", "resideo"), "it_CH" => __("Italian (Switzerland)", "resideo"), "ja_JP" => __("Japanese (Japan)", "resideo"), "kn_IN" => __("Kannada (India)", "resideo"), "km_KH" => __("Khmer (Cambodia)", "resideo"), "ko_KR" => __("Korean (South Korea)", "resideo"), "khq_ML" => __("Koyra Chiini (Mali)", "resideo"), "ses_ML" => __("Koyraboro Senni (Mali)", "resideo"), "lv_LV" => __("Latvian (Latvia)", "resideo"), "lt_LT" => __("Lithuanian (Lithuania)", "resideo"), "mk_MK" => __("Macedonian (Macedonia)", "resideo"), "ms_BN" => __("Malay (Brunei)", "resideo"), "ms_MY" => __("Malay (Malaysia)", "resideo"), "ml_IN" => __("Malayalam (India)", "resideo"), "mt_MT" => __("Maltese (Malta)", "resideo"), "gv_GB" => __("Manx (United Kingdom)", "resideo"), "mr_IN" => __("Marathi (India)", "resideo"), "mfe_MU" => __("Morisyen (Mauritius)", "resideo"), "ne_IN" => __("Nepali (India)", "resideo"), "nb_NO" => __("Norwegian Bokmål (Norway)", "resideo"), "nn_NO" => __("Norwegian Nynorsk (Norway)", "resideo"), "or_IN" => __("Oriya (India)", "resideo"), "ps_AF" => __("Pashto (Afghanistan)", "resideo"), "fa_AF" => __("Persian (Afghanistan)", "resideo"), "fa_IR" => __("Persian (Iran)", "resideo"), "pl_PL" => __("Polish (Poland)", "resideo"), "pt_BR" => __("Portuguese (Brazil)", "resideo"), "pt_PT" => __("Portuguese (Portugal)", "resideo"), "pa_Arab" => __("Punjabi (Arabic)", "resideo"), "ro_MD" => __("Romanian (Moldova)", "resideo"), "ro_RO" => __("Romanian (Romania)", "resideo"), "rm_CH" => __("Romansh (Switzerland)", "resideo"), "ru_MD" => __("Russian (Moldova)", "resideo"), "ru_RU" => __("Russian (Russia)", "resideo"), "ru_UA" => __("Russian (Ukraine)", "resideo"), "sg_CF" => __("Sango (Central African Republic)", "resideo"), "sr_Latn" => __("Serbian (Latin)", "resideo"), "ii_CN" => __("Sichuan Yi (China)", "resideo"), "si_LK" => __("Sinhala (Sri Lanka)", "resideo"), "sk_SK" => __("Slovak (Slovakia)", "resideo"), "sl_SI" => __("Slovenian (Slovenia)", "resideo"), "so_ET" => __("Somali (Ethiopia)", "resideo"), "es_AR" => __("Spanish (Argentina)", "resideo"), "es_BO" => __("Spanish (Bolivia)", "resideo"), "es_CL" => __("Spanish (Chile)", "resideo"), "es_CO" => __("Spanish (Colombia)", "resideo"), "es_CR" => __("Spanish (Costa Rica)", "resideo"), "es_DO" => __("Spanish (Dominican Republic)", "resideo"), "es_EC" => __("Spanish (Ecuador)", "resideo"), "es_SV" => __("Spanish (El Salvador)", "resideo"), "es_GQ" => __("Spanish (Equatorial Guinea)", "resideo"), "es_GT" => __("Spanish (Guatemala)", "resideo"), "es_HN" => __("Spanish (Honduras)", "resideo"), "es_419" => __("Spanish (Latin America)", "resideo"), "es_MX" => __("Spanish (Mexico)", "resideo"), "es_NI" => __("Spanish (Nicaragua)", "resideo"), "es_PA" => __("Spanish (Panama)", "resideo"), "es_PY" => __("Spanish (Paraguay)", "resideo"), "es_PE" => __("Spanish (Peru)", "resideo"), "es_PR" => __("Spanish (Puerto Rico)", "resideo"), "es_ES" => __("Spanish (Spain)", "resideo"), "es_US" => __("Spanish (United States)", "resideo"), "es_UY" => __("Spanish (Uruguay)", "resideo"), "es_VE" => __("Spanish (Venezuela)", "resideo"), "sw_KE" => __("Swahili (Kenya)", "resideo"), "sw_TZ" => __("Swahili (Tanzania)", "resideo"), "sv_FI" => __("Swedish (Finland)", "resideo"), "sv_SE" => __("Swedish (Sweden)", "resideo"), "gsw_CH" => __("Swiss German (Switzerland)", "resideo"), "shi_Latn" => __("Tachelhit (Latin)", "resideo"), "ta_IN" => __("Tamil (India)", "resideo"), "ta_LK" => __("Tamil (Sri Lanka)", "resideo"), "te_IN" => __("Telugu (India)", "resideo"), "th_TH" => __("Thai (Thailand)", "resideo"), "bo_CN" => __("Tibetan (China)", "resideo"), "bo_IN" => __("Tibetan (India)", "resideo"), "to_TO" => __("Tonga (Tonga)", "resideo"), "tr_TR" => __("Turkish (Turkey)", "resideo"), "uk_UA" => __("Ukrainian (Ukraine)", "resideo"), "ur_IN" => __("Urdu (India)", "resideo"), "ur_PK" => __("Urdu (Pakistan)", "resideo"), "uz_Arab" => __("Uzbek (Arabic)", "resideo"), "uz_Latn" => __("Uzbek (Latin)", "resideo"), "vi_VN" => __("Vietnamese (Vietnam)", "resideo"), "cy_GB" => __("Welsh (United Kingdom)", "resideo"), "zu_ZA" => __("Zulu (South Africa)", "resideo"));

        $field = '<select id="resideo_general_settings[resideo_locale_field]" name="resideo_general_settings[resideo_locale_field]">';

        foreach ($locales as $key => $value) {
            $field .= '<option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_locale_field']) && $options['resideo_locale_field'] == $key) {
                $field .= 'selected="selected"';
            }
            $field .= '>' . esc_html($value) . '</option>';
        }

        $field .= '</select>';

        print $field;
    }
endif;

if (!function_exists('resideo_map_marker_price_format_field_render')): 
    function resideo_map_marker_price_format_field_render() {
        $options = get_option('resideo_general_settings');
        $formats = array(
            'short' => __('Short', 'resideo'),
            'long'  => __('Long', 'resideo'),
        );

        $field = '<select id="resideo_general_settings[resideo_map_marker_price_format]" name="resideo_general_settings[resideo_map_marker_price_format]">';

        foreach ($formats as $key => $value) {
            $field .= '<option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_map_marker_price_format']) && $options['resideo_map_marker_price_format'] == $key) {
                $field .= 'selected="selected"';
            }
            $field .= '>' . esc_html($value) . '</option>';
        }

        $field .= '</select>';

        print $field;
    }
endif;

if (!function_exists('resideo_decimals_field_render')): 
    function resideo_decimals_field_render() {
        $options = get_option('resideo_general_settings'); ?>

        <input type="checkbox" name="resideo_general_settings[resideo_decimals_field]" <?php if(isset($options['resideo_decimals_field'])) { checked($options['resideo_decimals_field'], 1); } ?> value="1">
    <?php }
endif;

if (!function_exists('resideo_currency_symbol_field_render')): 
    function resideo_currency_symbol_field_render() {
        $options = get_option('resideo_general_settings'); ?>

        <input id="resideo_general_settings[resideo_currency_symbol_field]" type="text" size="10" name="resideo_general_settings[resideo_currency_symbol_field]" value="<?php if (isset($options['resideo_currency_symbol_field'])) { echo esc_attr($options['resideo_currency_symbol_field']); } ?>" />
    <?php }
endif;

if (!function_exists('resideo_currency_symbol_pos_field_render')): 
    function resideo_currency_symbol_pos_field_render() {
        $options = get_option('resideo_general_settings');
        $positions = array(
            'before' => __('Before', 'resideo'),
            'after'  => __('After', 'resideo'),
        );

        $field = '<select id="resideo_general_settings[resideo_currency_symbol_pos_field]" name="resideo_general_settings[resideo_currency_symbol_pos_field]">';

        foreach ($positions as $key => $value) {
            $field .= '<option value="' . esc_attr($key) . '"';
            if (isset($options['resideo_currency_symbol_pos_field']) && $options['resideo_currency_symbol_pos_field'] == $key) {
                $field .= 'selected="selected"';
            }
            $field .= '>' . esc_html($value) . '</option>';
        }

        $field .= '</select>';

        print $field;
    }
endif;

if (!function_exists('resideo_max_price_field_render')): 
    function resideo_max_price_field_render() {
        $options = get_option('resideo_general_settings'); ?>

        <input id="resideo_general_settings[resideo_max_price_field]" type="text" size="16" name="resideo_general_settings[resideo_max_price_field]" value="<?php if (isset($options['resideo_max_price_field'])) { echo esc_attr($options['resideo_max_price_field']); } ?>" />
    <?php }
endif;

if (!function_exists('resideo_beds_label_field_render')): 
    function resideo_beds_label_field_render() {
        $options = get_option('resideo_general_settings'); ?>

        <input id="resideo_general_settings[resideo_beds_label_field]" type="text" size="16" name="resideo_general_settings[resideo_beds_label_field]" value="<?php if (isset($options['resideo_beds_label_field'])) { echo esc_attr($options['resideo_beds_label_field']); } ?>" /> <i>(E.g. BD, Beds)</i>
    <?php }
endif;

if (!function_exists('resideo_baths_label_field_render')): 
    function resideo_baths_label_field_render() {
        $options = get_option('resideo_general_settings'); ?>

        <input id="resideo_general_settings[resideo_baths_label_field]" type="text" size="16" name="resideo_general_settings[resideo_baths_label_field]" value="<?php if (isset($options['resideo_baths_label_field'])) { echo esc_attr($options['resideo_baths_label_field']); } ?>" /> <i>(E.g. BA, Baths)</i>
    <?php }
endif;

if (!function_exists('resideo_unit_field_render')): 
    function resideo_unit_field_render() {
        $options = get_option('resideo_general_settings'); ?>

        <input id="resideo_general_settings[resideo_unit_field]" type="text" size="16" name="resideo_general_settings[resideo_unit_field]" value="<?php if (isset($options['resideo_unit_field'])) { echo esc_attr($options['resideo_unit_field']); } ?>" /> <i>(E.g. Sqft, Sqm, m2, ft2)</i>
    <?php }
endif;

if (!function_exists('resideo_max_files_field_render')): 
    function resideo_max_files_field_render() {
        $options = get_option('resideo_general_settings'); ?>

        <input id="resideo_general_settings[resideo_max_files_field]" type="text" size="16" name="resideo_general_settings[resideo_max_files_field]" value="<?php if (isset($options['resideo_max_files_field'])) { echo esc_attr($options['resideo_max_files_field']); } ?>" />
    <?php }
endif;

if (!function_exists('resideo_agents_rating_field_render')): 
    function resideo_agents_rating_field_render() {
        $options = get_option('resideo_general_settings'); ?>

        <input type="checkbox" name="resideo_general_settings[resideo_agents_rating_field]" <?php if (isset($options['resideo_agents_rating_field'])) { checked( $options['resideo_agents_rating_field'], 1 ); } ?> value="1">
    <?php }
endif;

if (!function_exists('resideo_no_review_field_render')): 
    function resideo_no_review_field_render() {
        $options = get_option('resideo_general_settings'); ?>

        <input type="checkbox" name="resideo_general_settings[resideo_no_review_field]" <?php if (isset($options['resideo_no_review_field'])) { checked( $options['resideo_no_review_field'], 1 ); } ?> value="1">
    <?php }
endif;

if (!function_exists('resideo_featured_property_field_render')): 
    function resideo_featured_property_field_render() {
        $options = get_option('resideo_general_settings'); ?>

        <input type="checkbox" name="resideo_general_settings[resideo_featured_property_field]" <?php if (isset($options['resideo_featured_property_field'])) { checked( $options['resideo_featured_property_field'], 1 ); } ?> value="1">
    <?php }
endif;

if (!function_exists('resideo_show_print_property_field_render')): 
    function resideo_show_print_property_field_render() {
        $options = get_option('resideo_general_settings'); ?>

        <input type="checkbox" name="resideo_general_settings[resideo_show_print_property_field]" <?php if (isset($options['resideo_show_print_property_field'])) { checked( $options['resideo_show_print_property_field'], 1 ); } ?> value="1">
    <?php }
endif;

if (!function_exists('resideo_show_report_property_field_render')): 
    function resideo_show_report_property_field_render() {
        $options = get_option('resideo_general_settings'); ?>

        <input type="checkbox" name="resideo_general_settings[resideo_show_report_property_field]" <?php if (isset($options['resideo_show_report_property_field'])) { checked( $options['resideo_show_report_property_field'], 1 ); } ?> value="1">
    <?php }
endif;

if (!function_exists('resideo_copyright_field_render')): 
    function resideo_copyright_field_render() { 
        $options = get_option('resideo_general_settings'); ?>

        <textarea cols='40' rows='5' name='resideo_general_settings[resideo_copyright_field]'><?php if (isset($options['resideo_copyright_field'])) { echo esc_html($options['resideo_copyright_field']); } ?></textarea>
    <?php }
endif;
?>