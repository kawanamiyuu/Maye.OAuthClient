<?php

namespace Kawanamiyuu\OAuthClient;

// ---------------------------- //
// Override build-in functions  //
// ---------------------------- //

function header($header, $replace = null, $http_response_code = null)
{
    echo $header;
}
