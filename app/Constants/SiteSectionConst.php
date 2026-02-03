<?php

namespace App\Constants;

class SiteSectionConst{
    const AUTH_SECTION        = "Auth Section";
    const BANNER_SECTION      = "Banner Section";
    const BRAND_SECTION       = "Brand Section";
    const ABOUT_US_SECTION    = "About Us Section"; 
    const HOW_IT_WORK_SECTION = "How It Work";
    const FOOTER_SECTION      = "Footer Section";
    const ABOUT_PAGE_SECTION  = "About Page Section";
    const SERVICE_PAGE_SECTION  = "Service Page Section";
    const CONTACT_US_SECTION  = "Contact Us Section";
    const FAQ_SECTION         = "FAQ Section";
    const FEATURE_SECTION     = "Feature Section";
    const SECURITY_SYSTEM_SECTION     = "Security System Section";
    const CLIENT_FEEDBACK_SECTION     = "Client Feedback Section";
    const WHY_CHOOSE_US     = "Why Choose Us Section";
    const ANNOUNCEMENT_SECTION     = "Announcement Section";
    const APP_SECTION         = "App Section";

    const NOT_DISPLAY_COOKIE_SECTION     = "site_cookie";
    const NOT_DISPLAY_AUTH_SECTION       = "auth-section";
    const NOT_DISPLAY_FOOTER_SECTION     = "footer-section";
    
    public static function notDisplaySections(): array{
            return [
                self::NOT_DISPLAY_COOKIE_SECTION,
                self::NOT_DISPLAY_AUTH_SECTION,
                self::NOT_DISPLAY_FOOTER_SECTION
            ];
    }


}