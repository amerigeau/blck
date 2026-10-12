<?php
if (!function_exists('blckpaid_hex')) {
    function blckpaid_hex($value, $fallback)
    {
        $value = strtolower(trim((string) $value));
        if (preg_match('/^#[0-9a-f]{6}$/', $value)) {
            return $value;
        }

        return $fallback;
    }

    function blckpaid_url($value)
    {
        $value = trim((string) $value);
        if ($value === '' || preg_match('#^(javascript|data):#i', $value)) {
            return '';
        }

        return substr($value, 0, 255);
    }

    function blckpaid_text($value)
    {
        $value = trim(strip_tags((string) $value));
        if (function_exists('mb_substr')) {
            return mb_substr($value, 0, 180);
        }

        return substr($value, 0, 180);
    }
}
