<?php
namespace MpesaSdk;

class MPesaResponseCode
{
    const SUCCESS                     = "INS-0";
    const CREATED                     = "INS-0";
    const INTERNAL_ERROR              = "INS-1";
    const INVALID_API_KEY             = "INS-2";
    const USER_NOT_ACTIVE             = "INS-4";
    const TRANSACTION_CANCELLED       = "INS-5";
    const TRANSACTION_FAILED          = "INS-6";
    const REQUEST_TIMEOUT             = "INS-9";
    const DUPLICATE_TRANSACTION       = "INS-10";
    const INVALID_SHORTCODE           = "INS-13";
    const INVALID_REFERENCE           = "INS-14";
    const INVALID_AMOUNT              = "INS-15";
    const TEMPORARY_OVERLOAD          = "INS-16";
    const INVALID_TRANSACTION_REF     = "INS-17";
    const INVALID_TRANSACTION_ID      = "INS-18";
    const INVALID_THIRD_PARTY_REF     = "INS-19";
    const MISSING_PARAMETERS          = "INS-20";
    const PARAMETER_VALIDATION_FAILED = "INS-21";
    const INVALID_OPERATION_TYPE      = "INS-22";
    const UNKNOWN_STATUS              = "INS-23";
    const INVALID_INITIATOR_ID        = "INS-24";
    const INVALID_CREDENTIAL          = "INS-25";
    const NOT_AUTHORIZED              = "INS-26";
    const DIRECT_DEBIT_MISSING        = "INS-993";
    const DIRECT_DEBIT_EXISTS         = "INS-994";
    const CUSTOMER_PROFILE_ISSUE      = "INS-995";
    const ACCOUNT_NOT_ACTIVE          = "INS-996";
    const LINKING_TRANSACTION_MISSING = "INS-997";
    const INVALID_MARKET              = "INS-998";
    const INITIATOR_AUTH_ERROR        = "INS-2001";
    const INVALID_RECEIVER            = "INS-2002";
    const INSUFFICIENT_BALANCE        = "INS-2006";
    const INVALID_MSISDN              = "INS-2051";
    const INVALID_LANGUAGE_CODE       = "INS-2057";
}