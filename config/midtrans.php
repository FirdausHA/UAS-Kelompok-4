<?php
// Midtrans Configuration - SANDBOX (TESTING)
define('MIDTRANS_SERVER_KEY', 'SB-Mid-server-NIduoNcz-aZq3WjE8BXL3eBY');
define('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-XsUvkW0DyXiyhOcl');
define('MIDTRANS_IS_PRODUCTION', false);

define('MIDTRANS_BASE_URL', MIDTRANS_IS_PRODUCTION 
    ? 'https://app.midtrans.com' 
    : 'https://app.sandbox.midtrans.com');
define('MIDTRANS_API_URL', MIDTRANS_IS_PRODUCTION 
    ? 'https://api.midtrans.com' 
    : 'https://api.sandbox.midtrans.com');
