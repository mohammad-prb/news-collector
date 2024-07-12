<?php
function getPage($url, $method = "GET", $queryPost = "") {
    $user_agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36';
    $options = array(
        CURLOPT_CUSTOMREQUEST => $method,           // set request type post or get
        CURLOPT_POST => !($method == "GET"),        // set to GET or POST
        CURLOPT_POSTFIELDS => $queryPost,           // set to POST
        CURLOPT_USERAGENT => $user_agent,           // set user agent
        CURLOPT_COOKIEFILE => "cookie.txt",         // set cookie file
        CURLOPT_COOKIEJAR => "cookie.txt",          // set cookie jar
        CURLOPT_RETURNTRANSFER => true,             // return web page
        CURLOPT_HEADER => false,                    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,             // follow redirects
        CURLOPT_ENCODING => "",                     // handle all encodings
        CURLOPT_AUTOREFERER => true,                // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,              // timeout on connect
        CURLOPT_TIMEOUT => 120,                     // timeout on response
        CURLOPT_MAXREDIRS => 10,                    // stop after 10 redirects
        CURLOPT_SSL_VERIFYPEER => false,            // connection gheir amn
    );

    $ch = curl_init($url);
    curl_setopt_array($ch, $options);
    $content = curl_exec($ch);
    $err = curl_errno($ch);
    $errmsg = curl_error($ch);
    $header = curl_getinfo($ch);
    curl_close($ch);

    $header['errno'] = $err;
    $header['errmsg'] = $errmsg;
    $header['content'] = $content;
    return $content;
}

@ set_time_limit(180);
$arrNatijeh = array();
$tarikhSabt = jdate("Y/m/d", "", "", "Asia/Tehran", "en");
$zamanSabt = jdate("H:i:s", "", "", "Asia/Tehran", "en");

/* سایت اول */
try {
    $namSite = "khabaronline.ir";
    $mohtavayeSite = getPage("http://www.khabaronline.ir/search?q=$ebaratJostojoo");
    if ($mohtavayeSite == "") throw new Exception("er:page ($namSite)");

    $dom = new DOMDocument();
    @ $dom->loadHTML($mohtavayeSite);
    $xpath = new DOMXpath($dom);
    $kadrKolAkhbar = $xpath->query("//div[@class='items']")->item(0);
    if (is_null($kadrKolAkhbar)) throw new Exception("er:countainer ($namSite)");

    $arrKolAkbar = $kadrKolAkhbar->getElementsByTagName("li");
    $i = 0;
    foreach ($arrKolAkbar as $akhbar) {
        if ($i++ == 10) break;

        $srcAks = "";
        $altAks = "";
        $aksMatlab = $akhbar->getElementsByTagName("img")->item(0);
        if (!is_null($aksMatlab)) {
            $srcAks = $aksMatlab->getAttribute("src");
            $altAks = utf8_decode($aksMatlab->getAttribute("alt"));
        }

        $hrefTitr = "";
        $matnTitr = "";
        $kadrTitrMatlab = $akhbar->getElementsByTagName("h3")->item(0);
        if (!is_null($kadrTitrMatlab)) {
            $titrMatlab = $kadrTitrMatlab->getElementsByTagName("a")->item(0);
            if (!is_null($titrMatlab)) {
                $hrefTitr = utf8_decode("https://www.khabaronline.ir" . $titrMatlab->getAttribute("href"));
                $matnTitr = utf8_decode($titrMatlab->textContent);
            }
        }

        $kholaehMatlab = "";
        $kadrKholaehMatlab = $akhbar->getElementsByTagName("p")->item(0);
        if (!is_null($kadrKholaehMatlab))
            $kholaehMatlab = utf8_decode($kadrKholaehMatlab->textContent);

        $stmt = $con->prepare("select id from news WHERE title = ? and website = ?");
        $stmt->bind_param("ss", $matnTitr, $namSite);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows == 0) {
            $sql = "insert into news (title, description, website, href, image, alt, search, date, time) VALUES (?,?,?,?,?,?,?,?,?)";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("sssssssss", $matnTitr, $kholaehMatlab, $namSite, $hrefTitr, $srcAks, $altAks, $ebaratJostojoo, $tarikhSabt, $zamanSabt);
            if (!($stmt->execute())) throw new Exception("er:sql ($namSite)");
            $stmt->free_result();
        }
    }
}
catch (Exception $e) {
    $arrNatijeh[0] = $e->getMessage();
}

/* سایت دوم */
try {
    $namSite = "mehrnews.ir";
    $mohtavayeSite = getPage("http://www.mehrnews.com/search?q=$ebaratJostojoo");
    if ($mohtavayeSite == "") throw new Exception("er:page ($namSite)");

    $dom = new DOMDocument();
    @ $dom->loadHTML($mohtavayeSite);
    $xpath = new DOMXpath($dom);
    $kadrKolAkhbar = $xpath->query("//div[@class='items']")->item(0);
    if (is_null($kadrKolAkhbar)) throw new Exception("er:countainer ($namSite)");

    $arrKolAkbar = $kadrKolAkhbar->getElementsByTagName("li");
    $i = 0;
    foreach ($arrKolAkbar as $akhbar) {
        if ($i++ == 10) break;

        $srcAks = "";
        $altAks = "";
        $aksMatlab = $akhbar->getElementsByTagName("img")->item(0);
        if (!is_null($aksMatlab)) {
            $srcAks = $aksMatlab->getAttribute("src");
            $altAks = utf8_decode($aksMatlab->getAttribute("alt"));
        }

        $hrefTitr = "";
        $matnTitr = "";
        $kadrTitrMatlab = $akhbar->getElementsByTagName("h3")->item(0);
        if (!is_null($kadrTitrMatlab)) {
            $titrMatlab = $kadrTitrMatlab->getElementsByTagName("a")->item(0);
            if (!is_null($titrMatlab)) {
                $hrefTitr = utf8_decode("https://www.mehrnews.com" . $titrMatlab->getAttribute("href"));
                $matnTitr = utf8_decode($titrMatlab->textContent);
            }
        }

        $kholaehMatlab = "";
        $kadrKholaehMatlab = $akhbar->getElementsByTagName("p")->item(0);
        if (!is_null($kadrKholaehMatlab))
            $kholaehMatlab = utf8_decode($kadrKholaehMatlab->textContent);

        $stmt = $con->prepare("select id from news WHERE title = ? and website = ?");
        $stmt->bind_param("ss", $matnTitr, $namSite);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows == 0) {
            $sql = "insert into news (title, description, website, href, image, alt, search, date, time) VALUES (?,?,?,?,?,?,?,?,?)";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("sssssssss", $matnTitr, $kholaehMatlab, $namSite, $hrefTitr, $srcAks, $altAks, $ebaratJostojoo, $tarikhSabt, $zamanSabt);
            if (!($stmt->execute())) throw new Exception("er:sql ($namSite)");
            $stmt->free_result();
        }
    }
}
catch (Exception $e) {
    $arrNatijeh[1] = $e->getMessage();
}

/* سایت سوم */
try {
    $namSite = "khabarfoori.ir";
    $mohtavayeSite = getPage("https://www.khabarfoori.com/fa/newsstudios/archive/?query=$ebaratJostojoo");
    if ($mohtavayeSite == "") throw new Exception("er:page ($namSite)");

    $dom = new DOMDocument();
    @ $dom->loadHTML($mohtavayeSite);
    $xpath = new DOMXpath($dom);
    $kadrKolAkhbar = $xpath->query("//ul[@class='container']")->item(0);
    if (is_null($kadrKolAkhbar)) throw new Exception("er:countainer ($namSite)");

    $arrKolAkbar = $kadrKolAkhbar->getElementsByTagName("li");
    $i = 0;
    foreach ($arrKolAkbar as $akhbar) {
        if ($i++ == 10) break;

        $srcAks = "";
        $altAks = "";
        $aksMatlab = $akhbar->getElementsByTagName("img")->item(0);
        if (!is_null($aksMatlab)) {
            $srcAks = $aksMatlab->getAttribute("src");
            $altAks = $aksMatlab->getAttribute("alt");
        }

        $hrefTitr = "";
        $matnTitr = "";
        $kadrTitrMatlab = $akhbar->getElementsByTagName("h2")->item(0);
        if (!is_null($kadrTitrMatlab)) {
            $titrMatlab = $kadrTitrMatlab->getElementsByTagName("a")->item(0);
            if (!is_null($titrMatlab)) {
                $hrefTitr = "https://www.khabarfoori.com" . $titrMatlab->getAttribute("href");
                $matnTitr = $titrMatlab->textContent;
            }
        }

        $kholaehMatlab = "";
        $kadrKholaehMatlab = $akhbar->getElementsByTagName("p")->item(0);
        if (!is_null($kadrKholaehMatlab))
            $kholaehMatlab = $kadrKholaehMatlab->textContent;

        $stmt = $con->prepare("select id from news WHERE title = ? and website = ?");
        $stmt->bind_param("ss", $matnTitr, $namSite);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows == 0) {
            $sql = "insert into news (title, description, website, href, image, alt, search, date, time) VALUES (?,?,?,?,?,?,?,?,?)";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("sssssssss", $matnTitr, $kholaehMatlab, $namSite, $hrefTitr, $srcAks, $altAks, $ebaratJostojoo, $tarikhSabt, $zamanSabt);
            if (!($stmt->execute())) throw new Exception("er:sql ($namSite)");
            $stmt->free_result();
        }
    }
}
catch (Exception $e) {
    $arrNatijeh[2] = $e->getMessage();
}

/* سایت چهارم */
try {
    $namSite = "irna.ir";
    $mohtavayeSite = getPage("http://www.irna.ir/search?q=$ebaratJostojoo");
    if ($mohtavayeSite == "") throw new Exception("er:page ($namSite)");

    $dom = new DOMDocument();
    @ $dom->loadHTML($mohtavayeSite);
    $xpath = new DOMXpath($dom);
    $kadrKolAkhbar = $xpath->query("//div[@class='items']")->item(0);
    if (is_null($kadrKolAkhbar)) throw new Exception("er:countainer ($namSite)");

    $arrKolAkbar = $kadrKolAkhbar->getElementsByTagName("li");
    $i = 0;
    foreach ($arrKolAkbar as $akhbar) {
        if ($i++ == 10) break;

        $srcAks = "";
        $altAks = "";
        $aksMatlab = $akhbar->getElementsByTagName("img")->item(0);
        if (!is_null($aksMatlab)) {
            $srcAks = $aksMatlab->getAttribute("src");
            $altAks = utf8_decode($aksMatlab->getAttribute("alt"));
        }

        $hrefTitr = "";
        $matnTitr = "";
        $kadrTitrMatlab = $akhbar->getElementsByTagName("h3")->item(0);
        if (!is_null($kadrTitrMatlab)) {
            $titrMatlab = $kadrTitrMatlab->getElementsByTagName("a")->item(0);
            if (!is_null($titrMatlab)) {
                $hrefTitr = utf8_decode("https://www.irna.ir" . $titrMatlab->getAttribute("href"));
                $matnTitr = utf8_decode($titrMatlab->textContent);
            }
        }

        $kholaehMatlab = "";
        $kadrKholaehMatlab = $akhbar->getElementsByTagName("p")->item(0);
        if (!is_null($kadrKholaehMatlab))
            $kholaehMatlab = utf8_decode($kadrKholaehMatlab->textContent);

        $stmt = $con->prepare("select id from news WHERE title = ? and website = ?");
        $stmt->bind_param("ss", $matnTitr, $namSite);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows == 0) {
            $sql = "insert into news (title, description, website, href, image, alt, search, date, time) VALUES (?,?,?,?,?,?,?,?,?)";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("sssssssss", $matnTitr, $kholaehMatlab, $namSite, $hrefTitr, $srcAks, $altAks, $ebaratJostojoo, $tarikhSabt, $zamanSabt);
            if (!($stmt->execute())) throw new Exception("er:sql ($namSite)");
            $stmt->free_result();
        }
    }
}
catch (Exception $e) {
    $arrNatijeh[3] = $e->getMessage();
}

/* سایت پنجم */
try {
    $namSite = "hamshahrionline.ir";
    $mohtavayeSite = getPage("http://www.hamshahrionline.ir/search?q=$ebaratJostojoo");
    if ($mohtavayeSite == "") throw new Exception("er:page ($namSite)");

    $dom = new DOMDocument();
    @ $dom->loadHTML($mohtavayeSite);
    $xpath = new DOMXpath($dom);
    $kadrKolAkhbar = $xpath->query("//div[@class='items']")->item(0);
    if (is_null($kadrKolAkhbar)) throw new Exception("er:countainer ($namSite)");

    $arrKolAkbar = $kadrKolAkhbar->getElementsByTagName("li");
    $i = 0;
    foreach ($arrKolAkbar as $akhbar) {
        if ($i++ == 10) break;

        $srcAks = "";
        $altAks = "";
        $aksMatlab = $akhbar->getElementsByTagName("img")->item(0);
        if (!is_null($aksMatlab)) {
            $srcAks = $aksMatlab->getAttribute("src");
            $altAks = utf8_decode($aksMatlab->getAttribute("alt"));
        }

        $hrefTitr = "";
        $matnTitr = "";
        $kadrTitrMatlab = $akhbar->getElementsByTagName("h3")->item(0);
        if (!is_null($kadrTitrMatlab)) {
            $titrMatlab = $kadrTitrMatlab->getElementsByTagName("a")->item(0);
            if (!is_null($titrMatlab)) {
                $hrefTitr = utf8_decode("https://hamshahrionline.ir" . $titrMatlab->getAttribute("href"));
                $matnTitr = utf8_decode($titrMatlab->textContent);
            }
        }

        $kholaehMatlab = "";
        $kadrKholaehMatlab = $akhbar->getElementsByTagName("p")->item(0);
        if (!is_null($kadrKholaehMatlab))
            $kholaehMatlab = utf8_decode($kadrKholaehMatlab->textContent);

        $stmt = $con->prepare("select id from news WHERE title = ? and website = ?");
        $stmt->bind_param("ss", $matnTitr, $namSite);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows == 0) {
            $sql = "insert into news (title, description, website, href, image, alt, search, date, time) VALUES (?,?,?,?,?,?,?,?,?)";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("sssssssss", $matnTitr, $kholaehMatlab, $namSite, $hrefTitr, $srcAks, $altAks, $ebaratJostojoo, $tarikhSabt, $zamanSabt);
            if (!($stmt->execute())) throw new Exception("er:sql ($namSite)");
            $stmt->free_result();
        }
    }
}
catch (Exception $e) {
    $arrNatijeh[4] = $e->getMessage();
}

if (count($arrNatijeh) > 0) {
    $reshtehErrorha = jdate("[Y/m/d-H:i:s] ", "", "", "Asia/Tehran", "en");
    $reshtehErrorha .= implode(" , ", $arrNatijeh) . "\r\n";
    $file = fopen("error-log.txt", "a");
    fwrite($file, $reshtehErrorha);
    fclose($file);
}
