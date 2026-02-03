<?php

namespace App\Constants;

class GlobalConst {
    const USER_PASS_RESEND_TIME_MINUTE = "1";

    const ACTIVE = true;
    const BANNED = false;
    const SUCCESS = true;
    const DEFAULT_TOKEN_EXP_SEC = 3600;

    const VERIFIED = 1;
    const APPROVED = 1;
    const PENDING = 2;
    const REJECTED = 3;
    const CANCELED = 4;
    const DEFAULT = 0;
    const UNVERIFIED = 0;

     const STATUS_PENDING                = 2;
    const STATUS_CONFIRM_PAYMENT        = 6;
    const STATUS_CANCEL                 = 7;
    const STATUS_REJECT                 = 8;

    const USER      = "USER";
    const MERCHANT  = "MERCHANT";
    const ADMIN = "ADMIN";

    const PERSONAL = "PERSONAL";
    const BUSINESS = "BUSINESS";

    const TRANSFER  = "transfer";
    const REQUEST  = "request";
    const EXCHANGE  = "exchange";
    const ADD       = "add";
    const OUT       = "out";
    const PAYMENT   = "payment";
    const VOUCHER   = "voucher";

    const INVEST_PROFIT_DAILY_BASIS = "DAILY-BASIS";
    const INVEST_PROFIT_ONE_TIME = "ONE-TIME";

    const RUNNING   = 2;
    const COMPLETE  = 1;
    const CANCEL = 3;

    const INVESTMENT = "INVESTMENT";
    const PROFIT     = "PROFIT";

    const UNKNOWN = "UNKNOWN";
    const USEFUL_LINK_PRIVACY_POLICY = "PRIVACY_POLICY";

    const MONEY_EXCHANGE = "MONEY-EXCHANGE";

    const SYSTEM_MAINTENANCE       = "system-maintenance";
    const CURRENCY_LAYER       = "CURRENCY-LAYER";

    const ENV_SANDBOX   = "sandbox";
    const ENV_PRODUCTION = "production";

    const LIVE = 'LIVE';
    const SANDBOX = 'SANDBOX';

    //cardyfie

    const CARD_CUSTOMER_APPROVED_STATUS = "APPROVED";
    const CARD_CUSTOMER_PENDING_STATUS  = "PENDING";
    const CARD_CUSTOMER_REJECTED_STATUS = "REJECTED";


    const CARD_PROCESSING_STATUS = "PROCESSING";
    const CARD_ENABLED_STATUS = "ENABLED";
    const CARD_FREEZE_STATUS  = "FREEZE";
    const CARD_CLOSED_STATUS = "CLOSED";

    const UNIVERSAL_TIER = "universal";
    const PLATINUM_TIER = "platinum";
}