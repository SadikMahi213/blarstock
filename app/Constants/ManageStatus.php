<?php

namespace App\Constants;

class ManageStatus {
    const ACTIVE   = 1;
    const INACTIVE = 0;

    const YES = 1;
    const NO  = 0;

    const UNVERIFIED = 0;
    const VERIFIED   = 1;
    const PENDING    = 2;

    const PAYMENT_INITIATE = 0;
    const PAYMENT_SUCCESS  = 1;
    const PAYMENT_PENDING  = 2;
    const PAYMENT_CANCEL   = 3;

    const IMAGE_PENDING  = 0;
    const IMAGE_APPROVED = 1;
    const IMAGE_REJECTED = 2;

    const AUTHOR_PENDING  = 1;
    const AUTHOR_APPROVED = 2;
    const AUTHOR_REJECTED = 3;
    const AUTHOR_BANNED   = 4;
    const IS_NOT_AUTHOR   = 0;

    const PREMIUM = 0;
    const FREE    = 1;

    const IMAGE = 1;
    const VIDEO = 2;

    const EMPTY = 0;

    const PUBLIC_COLLECTION  = 1;
    const PRIVATE_COLLECTION = 0;

    const DONATION_INITIATE = 0;
    const DONATION_APPROVED = 1;
    const DONATION_PENDING  = 2;
    const DONATION_REJECTED = 3;

    const DAILY_PLAN       = 1;
    const WEEKLY_PLAN      = 2;
    const MONTHLY_PLAN     = 3;
    const QUARTERLY_PLAN   = 4;
    const SEMI_ANNUAL_PLAN = 5;
    const ANNUAL_PLAN      = 6;

    const UNLIMITED_DOWNLOAD = -1;

    const LOCAL_STORAGE         = 1;
    const FTP_STORAGE           = 2;
    const WASABI_STORAGE        = 3;
    const DIGITAL_OCEAN_STORAGE = 4;
    const VULTR_STORAGE         = 5;
}
