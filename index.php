<?php
    require_once("classes/simple_html_dom.php");
    $url = "http://form.timeform.betfair.com/daypage?date=".(date("Ymd")-1);
    $html = @file_get_html($url);
    $races = array();
    $countries = "";
    if($html !== false){
        foreach ($html->find("div.module-expandable-wrapper") as $section) {
            $temp = array();
            foreach ($section->find("div.secondary-module-header h2 a.i13n-sec-country") as $country) {
                $temp["country"] = trim($country->plaintext);
                $countries .=str_replace(array(" "), "", $country->plaintext).":";
            }
            // $countries .= $temp["country"].":";
            $v = array();
            foreach($section->find("div.secondary-module-content div div p.course-data a") as $venue){
                $v[] = $venue->href;
            }
            $temp["venues"] = $v;
            $races[] = $temp;
        }
    }
?>
    <!doctype html>
    <html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <!-- Place favicon.ico in the root directory -->

        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
        <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
            <![endif]-->

            <!-- Add your site or application content here -->
            
            <div style="position:relative;">
                <div>
                    <div class="text-center instruction">
                        <h3>Instructions:</h3>
                        <ul>
                            <li>Scrape data one by one for each country.</li>
                            <li>Click on Download button after each country's data is scraped.</li>
                            <li>The downloaded file will be a combination of data of above files.</li>
                        </ul>
                    </div>
                    <table>
                        <tbody>
                        <tr>
                            <th>Country</th>
                            <th>Click to scrape</th>
                            <th>Status</th>
                        </tr>
                        <?php
                        $count = 0;
                        foreach ($races as $race) {
                            if(!empty($race) && isset($race["country"])){
                                echo "<tr>
                                <td> Country : ".$race['country']." </td>
                                <td> <button id = '".$count."' class='btn' value='".json_encode($race)."'>Scrape Data</button> </td>
                                <td><span id = 'status_".$count."'></span></td>
                                </tr>";
                            }
                            $count++;
                        }
                        ?>
                        </tbody>
                        <tfoot>
                            
                        </tfoot>
                        <tr>
                            <td colspan="3">
                                <form action="1.php" method="post">
                                    <input type ="hidden" name="download" value = <?php echo $countries; ?>>
                                    <input type="submit" value ="Download above scraped data in CSV format.">
                                </form>
                            </td>
                        </tr>

                    </table>
                </div>
            </div>

    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>

    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <!--
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X','auto');ga('send','pageview');
        </script>
    -->
</body>
</html>
