<?php

namespace PayCheckout;

class Currency
{
    // For backwards compatibility
    const EUR = 0;
    const USD = 1;
    const GBP = 2;
    const SEK = 3;
    const NOK = 4;
    const DKK = 5;

    // Due to keyword TRY inclusion C_ was prepended
    const C_AED =   6;
    const C_AFN =   7;
    const C_ALL =   8;
    const C_AMD =   9;
    const C_ANG =  10;
    const C_ARS =  11;
    const C_AUD =  12;
    const C_AWG =  13;
    const C_AZN =  14;
    const C_BAM =  15;
    const C_BBD =  16;
    const C_BDT =  17;
    const C_BGN =  18;
    const C_BHD =  19;
    const C_BMD =  20;
    const C_BND =  21;
    const C_BOB =  22;
    const C_BRL =  23;
    const C_BSD =  24;
    const C_BWP =  25;
    const C_BYR =  26;
    const C_BZD =  27;
    const C_CAD =  28;
    const C_CHF =  29;
    const C_CLP =  30;
    const C_CNY =  31;
    const C_COP =  32;
    const C_CRC =  33;
    const C_CVE =  34;
    const C_CZK =  35;
    const C_DJF =  36;
    const C_DKK =   5;
    const C_DOP =  37;
    const C_DZD =  38;
    const C_EGP =  39;
    const C_ETB =  40;
    const C_EUR =   0;
    const C_FJD =  41;
    const C_FKP =  42;
    const C_GBP =   2;
    const C_GEL =  43;
    const C_GGP =  44;
    const C_GHS =  45;
    const C_GIP =  46;
    const C_GMD =  47;
    const C_GNF =  48;
    const C_GTQ =  49;
    const C_GYD =  50;
    const C_HKD =  51;
    const C_HNL =  52;
    const C_HRK =  53;
    const C_HTG =  54;
    const C_HUF =  55;
    const C_IDR =  56;
    const C_ILS =  57;
    const C_IMP =  58;
    const C_INR =  59;
    const C_ISK =  60;
    const C_JEP =  61;
    const C_JMD =  62;
    const C_JOD =  63;
    const C_JPY =  64;
    const C_KES =  65;
    const C_KGS =  66;
    const C_KHR =  67;
    const C_KMF =  68;
    const C_KRW =  69;
    const C_KWD =  70;
    const C_KYD =  71;
    const C_KZT =  72;
    const C_LAK =  73;
    const C_LBP =  74;
    const C_LKR =  75;
    const C_LYD =  75;
    const C_MAD =  76;
    const C_MDL =  77;
    const C_MKD =  78;
    const C_MMK =  79;
    const C_MNT =  80;
    const C_MOP =  81;
    const C_MRO =  82;
    const C_MUR =  83;
    const C_MVR =  84;
    const C_MWK =  85;
    const C_MXN =  86;
    const C_MYR =  87;
    const C_MZN =  88;
    const C_NAD =  89;
    const C_NGN =  90;
    const C_NIO =  91;
    const C_NOK =   4;
    const C_NPR =  92;
    const C_NZD =  93;
    const C_OMR =  94;
    const C_PAB =  95;
    const C_PEN =  96;
    const C_PGK =  97;
    const C_PHP =  98;
    const C_PKR =  99;
    const C_PLN = 100;
    const C_PYG = 101;
    const C_QAR = 102;
    const C_RON = 103;
    const C_RSD = 104;
    const C_RUB = 105;
    const C_RWF = 106;
    const C_SAR = 107;
    const C_SBD = 108;
    const C_SCR = 109;
    const C_SEK =   3;
    const C_SGD = 110;
    const C_SHP = 111;
    const C_SLL = 112;
    const C_SOS = 113;
    const C_SRD = 114;
    const C_STD = 115;
    const C_SVC = 116;
    const C_SZL = 117;
    const C_THB = 118;
    const C_TND = 119;
    const C_TOP = 120;
    const C_TRY = 121;
    const C_TTD = 122;
    const C_TVD = 123;
    const C_TWD = 124;
    const C_TZS = 125;
    const C_UAH = 126;
    const C_UGX = 127;
    const C_USD =   1;
    const C_UYU = 128;
    const C_UZS = 129;
    const C_VEF = 130;
    const C_VND = 131;
    const C_VUV = 132;
    const C_WST = 133;
    const C_XAF = 134;
    const C_XCD = 135;
    const C_XOF = 136;
    const C_XPF = 137;
    const C_YER = 138;
    const C_ZAR = 139;
    const C_ZMW = 140;  

    public static $allCurrencies = array (
        Currency::EUR,
        Currency::USD,
        Currency::GBP,
        Currency::SEK,
        Currency::NOK,
        Currency::DKK,
        Currency::C_AED,
        Currency::C_AFN,
        Currency::C_ALL,
        Currency::C_AMD,
        Currency::C_ANG,
        Currency::C_ARS,
        Currency::C_AUD,
        Currency::C_AWG,
        Currency::C_AZN,
        Currency::C_BAM,
        Currency::C_BBD,
        Currency::C_BDT,
        Currency::C_BGN,
        Currency::C_BHD,
        Currency::C_BMD,
        Currency::C_BND,
        Currency::C_BOB,
        Currency::C_BRL,
        Currency::C_BSD,
        Currency::C_BWP,
        Currency::C_BYR,
        Currency::C_BZD,
        Currency::C_CAD,
        Currency::C_CHF,
        Currency::C_CLP,
        Currency::C_CNY,
        Currency::C_COP,
        Currency::C_CRC,
        Currency::C_CVE,
        Currency::C_CZK,
        Currency::C_DJF,
        Currency::C_DKK,
        Currency::C_DOP,
        Currency::C_DZD,
        Currency::C_EGP,
        Currency::C_ETB,
        Currency::C_EUR,
        Currency::C_FJD,
        Currency::C_FKP,
        Currency::C_GBP,
        Currency::C_GEL,
        Currency::C_GGP,
        Currency::C_GHS,
        Currency::C_GIP,
        Currency::C_GMD,
        Currency::C_GNF,
        Currency::C_GTQ,
        Currency::C_GYD,
        Currency::C_HKD,
        Currency::C_HNL,
        Currency::C_HRK,
        Currency::C_HTG,
        Currency::C_HUF,
        Currency::C_IDR,
        Currency::C_ILS,
        Currency::C_IMP,
        Currency::C_INR,
        Currency::C_ISK,
        Currency::C_JEP,
        Currency::C_JMD,
        Currency::C_JOD,
        Currency::C_JPY,
        Currency::C_KES,
        Currency::C_KGS,
        Currency::C_KHR,
        Currency::C_KMF,
        Currency::C_KRW,
        Currency::C_KWD,
        Currency::C_KYD,
        Currency::C_KZT,
        Currency::C_LAK,
        Currency::C_LBP,
        Currency::C_LKR,
        Currency::C_LYD,
        Currency::C_MAD,
        Currency::C_MDL,
        Currency::C_MKD,
        Currency::C_MMK,
        Currency::C_MNT,
        Currency::C_MOP,
        Currency::C_MRO,
        Currency::C_MUR,
        Currency::C_MVR,
        Currency::C_MWK,
        Currency::C_MXN,
        Currency::C_MYR,
        Currency::C_MZN,
        Currency::C_NAD,
        Currency::C_NGN,
        Currency::C_NIO,
        Currency::C_NOK,
        Currency::C_NPR,
        Currency::C_NZD,
        Currency::C_OMR,
        Currency::C_PAB,
        Currency::C_PEN,
        Currency::C_PGK,
        Currency::C_PHP,
        Currency::C_PKR,
        Currency::C_PLN,
        Currency::C_PYG,
        Currency::C_QAR,
        Currency::C_RON,
        Currency::C_RSD,
        Currency::C_RUB,
        Currency::C_RWF,
        Currency::C_SAR,
        Currency::C_SBD,
        Currency::C_SCR,
        Currency::C_SEK,
        Currency::C_SGD,
        Currency::C_SHP,
        Currency::C_SLL,
        Currency::C_SOS,
        Currency::C_SRD,
        Currency::C_STD,
        Currency::C_SVC,
        Currency::C_SZL,
        Currency::C_THB,
        Currency::C_TND,
        Currency::C_TOP,
        Currency::C_TRY,
        Currency::C_TTD,
        Currency::C_TVD,
        Currency::C_TWD,
        Currency::C_TZS,
        Currency::C_UAH,
        Currency::C_UGX,
        Currency::C_USD,
        Currency::C_UYU,
        Currency::C_UZS,
        Currency::C_VEF,
        Currency::C_VND,
        Currency::C_VUV,
        Currency::C_WST,
        Currency::C_XAF,
        Currency::C_XCD,
        Currency::C_XOF,
        Currency::C_XPF,
        Currency::C_YER,
        Currency::C_ZAR,
        Currency::C_ZMW );
    
}