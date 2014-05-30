<?php

function StartTransaction( $rtlo, $bank, $description, $amount, $returnurl, $reporturl)
        {
        $urltp= "https://www.targetpay.com/ideal/start?"."rtlo=".$rtlo."&bank=".$bank."&description=".urlencode($description)."&amount=".$amount."&returnurl=".urlencode($returnurl)."&reporturl=".urlencode($reporturl);
        echo $urltp."<br />";
        $strResponse = httpGetRequest($urltp);
        $aResponse = explode('|', $strResponse );


                # Bad response
                if ( !isset ( $aResponse[1] ) ) die('Error' . $aResponse[0] );
                $responsetype = explode ( ' ', $aResponse[0] );
                $trxid = $responsetype[1];

        // Add transaction-id to order.
        if( $responsetype[0] == "000000" ) return $aResponse[1];
        else die($aResponse[0]);
        }

function CheckReturnurl($rtlo, $trxid)
        {
        $once=1;
        $test=1; // Set to 1 for testing; 0 for live site
        $urltp= "https://www.targetpay.com/ideal/check?"."rtlo=".$rtlo."&trxid=".$trxid."&once=".$once."&test=".$test;
        return httpGetRequest($urltp);
        }

function HandleReporturl($rtlo, $trxid, $status )
        {
                if( substr($_SERVER['REMOTE_ADDR'],0,10) == "89.184.168" )
                {
                // Update order status 
                // ........
                // reporturl should return OK to Targetpay.
                die( "OK" );
                }
                else
                {
                die("IP address not correct... This call is not from Targetpay");
                }
        }

function httpGetRequest($urltp)
{
        //$ch = curl_init($urltp);
        //curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
        //$strResponse = curl_exec($ch);
        //curl_close($ch);
        $strResponse = file_get_contents($urltp);
           if ( $strResponse === false )
                {die("Could not fetch response " . $urltp );}

        return $strResponse;
        }

function curPageURL()
{
$isHTTPS = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on");
$port = (isset($_SERVER["SERVER_PORT"]) && ((!$isHTTPS && $_SERVER["SERVER_PORT"] != "80") || ($isHTTPS && $_SERVER["SERVER_PORT"] != "443")));
$port = ($port) ? ':'.$_SERVER["SERVER_PORT"] : '';
$pageURL = ($isHTTPS ? 'https://' : 'http://').$_SERVER["SERVER_NAME"].$port.$_SERVER["REQUEST_URI"];
return $pageURL;
}


?>