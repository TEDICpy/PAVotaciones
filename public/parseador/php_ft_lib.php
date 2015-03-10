<?php
/*
 *adaptacion a api v1 @bauerpy Juan Bauer
 *   */


function GoogleClientLogin($username, $password, $service) { 
        // Check that we have all the parameters 
        if(!$username || !$password || !$service) { 
                throw new Exception("You must provide a username, password, and service when creating a new GoogleClientLogin."); 
        } 
        // Set up the post body 
        $body = "accountType=GOOGLE&Email=$username&Passwd=$password&service=$service"; 
        // Set up the cURL 
        $c = curl_init ("https://www.google.com/accounts/ClientLogin"); 
        curl_setopt($c, CURLOPT_POST, true); 
        curl_setopt($c, CURLOPT_POSTFIELDS, $body); 
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true); 
        $response = curl_exec($c); 
        // Parse the response to obtain just the Auth token 
        // Basically, we remove everything before the "Auth=" 
        return preg_replace("/[\s\S]*Auth=/", "", $response); 
} 

class FusionTable { 
        var $token; 
        function FusionTable($token, $key) { 
                if (!$token) { 
                        throw new Exception("You must provide a token when creating a new FusionTable."); 
                } 
                $this->token = $token;
                $this->key = $key;
        } 
        function query($query) { 
                if(!$query) { 
                        throw new Exception("query method requires a query."); 
                } 
                // Check to see if we have a query that will retrieve data 
            if(preg_match("/^select|^show tables|^describe/i", $query)) { 
                        $request_url = "https://www.googleapis.com/fusiontables/v1/query?sql=" . urlencode($query)."&key=".$this->key; 
                        $c = curl_init ($request_url); 
                        curl_setopt($c, CURLOPT_HTTPHEADER, array("Authorization: GoogleLogin auth=" . $this->token)); 
                        curl_setopt($c, CURLOPT_RETURNTRANSFER, true); 
                        // Place the lines of the output into an array 
                        $results = preg_split("/\n/", curl_exec ($c)); 
                        // If we got an error, raise it 
                        if(curl_getinfo($c, CURLINFO_HTTP_CODE) != 200) { 
                                return $this->output_error($results); 
                        } 
                        // Drop the last (empty) array value 
                        array_pop($results); 
                        // Parse the output 
                        return $results; 
                }
                
                // Otherwise we are going to be updating the table, so we need to the POST method 
                else if(preg_match("/^update|^delete|^insert|^create/i", $query)) { 
                        // Set up the cURL 
                        //echo $query;
                        $body = "sql=" . urlencode($query)."&key=".$this->key; 
                      /*  $body = array (
                            "sql"=>urlencode($query),
                            "key"=>$this->key
                        );*/
                        $c = curl_init ("https://www.googleapis.com/fusiontables/v1/query"); 
                        curl_setopt($c, CURLOPT_POST, true); 
                        curl_setopt($c, CURLOPT_RETURNTRANSFER, true); 
                        curl_setopt($c, CURLOPT_HTTPHEADER, array( 
                                "Content-type: application/x-www-form-urlencoded", 
                                "Authorization: GoogleLogin auth=" . $this->token . " "
                        )); 
                        curl_setopt($c, CURLOPT_POSTFIELDS, $body); 
                        // Place the lines of the output into an array 
                        $results = preg_split("/\n/", curl_exec ($c)); 
                        // If we got an error, raise it 
                        if(curl_getinfo($c, CURLINFO_HTTP_CODE) != 200) { 
                                return $this->output_error($results); 
                        } 
                        // Drop the last (empty) array value 
                        array_pop($results); 
                        return $results; 
                } 
                else { 
                        throw new Exception("Unknown SQL query submitted."); 
                } 
        } 
        
        private function output_error($err) { 
                $err = implode("", $err); 
                // Remove everything outside of the H1 tag 
                $err = preg_replace("/[\s\S]*<H1>|<\/H1>[\s\S]*/i", "", $err); 
                // Return the error 
                return $err; 
        } 
} 

?>
