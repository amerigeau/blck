<?php
if (PHP_SAPI !== 'cli') {
    exit;
}
require __DIR__.'/sanitize.php';

$fail = 0;
function expect($label, $got, $want)
{
    global $fail;
    if ($got !== $want) {
        $fail++;
        fwrite(STDERR, "FAIL $label: got ".var_export($got, true)." want ".var_export($want, true)."\n");
    }
}

expect('hex ok', blckpaid_hex('#AB12EF', '#000000'), '#ab12ef');
expect('hex bad', blckpaid_hex('red', '#000000'), '#000000');
expect('hex short', blckpaid_hex('#fff', '#111111'), '#111111');
expect('url js', blckpaid_url('javascript:alert(1)'), '');
expect('url path', blckpaid_url('/promo'), '/promo');
expect('text tags', blckpaid_text(' <b>Soldes</b> '), 'Soldes');

if ($fail) {
    exit(1);
}
echo "ok\n";
