<?php
// deze functie werkniet goed met het php bestand, wel met het javascript (de bovenste)
function SelectBank()
        {
        $urltp="https://www.targetpay.com/ideal/getissuers.php";
        $strResponse = httpGetRequest($urltp);
       
        echo "<form name=\"bankselect\">Kies uw bank: ";
        echo "<select name=bank onChange=\"document.bankselect.submit();\">";
        echo "<script src=\"https://www.targetpay.com/ideal/issuers-nl.js\"></script>";
        echo "</select></form>";
        }
/*
function SelectBank()
        {
        $urltp="https://www.targetpay.com/ideal/getissuers.php";
        $strResponse = httpGetRequest($urltp);
        echo "<html>";
        echo "Betaal veilig en gemakkelijk met iDeal.<br />";
        echo "<form method=\"get\" name=\"idealform\">";
        echo "<select name=\"bank\">".$strResponse."</select> ";
        echo "<INPUT TYPE=\"submit\" VALUE=\"Verder gaan...\"></form>";
        echo "NB: Uw iDeal betaling wordt verwerkt door <a href=\"https://www.targetpay.com/info/secure\" target=\"_blank\">Targetpay</a>. Dit wordt ook op uw bankafschrift vermeld.";
        echo "</html>";
        }
*/
function StartTransaction( $rtlo, $bank, $description, $amount, $returnurl, $reporturl)
        {
        $urltp= "https://www.targetpay.com/ideal/start?"."rtlo=".$rtlo."&bank=".$bank."&description=".urlencode($description)."&amount=".$amount."&returnurl=".urlencode($returnurl)."&reporturl=".urlencode($reporturl);
        echo $urltp."<br />";
        $strResponse = httpGetRequest($urltp);
        $aResponse = explode('|', $strResponse );

        //echo "<pre>";
        //print_r($strResponse);
        //echo "</pre>";
        // error logger
/*		$date = date("Y-m-d.H:i:s");
		$uitvoer = "Response: ".$strResponse;
			$logfile = PUBLIC_PATH.'error.log';
			$filehandle = fopen($logfile, 'a') or die("can't open error file");
			$logdata = $date.' - '.$uitvoer ."\n";
                        fwrite($filehandle, $logdata);
			fclose($filehandle);
        echo $uitvoer;
*/
                # Bad response
                if ( !isset ( $aResponse[1] ) ) die('Error' . $aResponse[0] );
                $responsetype = explode ( ' ', $aResponse[0] );
                $trxid = $responsetype[1];

        // Hier kunt u het transactie id aan uw order toevoegen.
        if( $responsetype[0] == "000000" ) return $aResponse[1];
        else die($aResponse[0]);
        }

function CheckReturnurl($rtlo, $trxid)
        {
        $once=1;
        $test=1; // Set to 1 for testing as described in paragraph 1.3; 0 for live site
        $urltp= "https://www.targetpay.com/ideal/check?"."rtlo=".$rtlo."&trxid=".$trxid."&once=".$once."&test=".$test;
        return httpGetRequest($urltp);
        }

function HandleReporturl($rtlo, $trxid, $status )
        {
                if( substr($_SERVER['REMOTE_ADDR'],0,10) == "89.184.168" )
                {
                // Update uw orderstatus hier
                // ........
                // De reporturl hoort OK terug te geven aan Targetpay.
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
        //echo "=> ".$ch." |  ".$strResponse."| ";
                if ( $strResponse === false )
                {die("Could not fetch response " . $urltp );}
//                $strResponse = '<option selected value="">Kies uw bank...</option> <option value="0031">ABN Amro</option> <option value="0091">Friesland Bank</option> <option value="0721">ING</option> <option value="0021">Rabobank</option> <option value="0751">SNS Bank</option> <option value="0761">ASN Bank</option> <option value="0801">Knab</option> <option value="0771">RegioBank</option> <option value="0511">Triodos Bank</option> <option value="0161">Van Lanschot Bankiers</option>';

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