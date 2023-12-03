<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Search</title>
    <link rel='stylesheet' href='../css/styles.css'/>
</head>
<body>
<div class='header-container'>
    <h1><a href='crawler.php'>Google Lite</a></h1>
    <a href='search.php'><input type='button' class='button' value='Search'/></a>
</div>
<form id='seed_form'>
    <div class='search-container' style='background-color: var(--card-blue);'>
        <input type=text class='search bar' id='seed_url' name='seedurl' placeholder='http://www.foxsports.com/'/>
        <input type='submit' id='seed_button' class='button' value='Start Seed'/>
        <p>This will take several moments...</p>
    </div>
</form>

<section>
<?php
    function url_exists($url) {
        $headers = @get_headers($url);
    
        // Check if $headers is not false and the HTTP status code is 200 OK
        return $headers && strpos($headers[0], '200') !== false;
    }

    // Initailizaitons
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['seedurl'])) {
            $seed_url = $_GET['seedurl'];
            if (!$seed_url == ''){
                if (url_exists($seed_url)) {
                    include('crawler_krnl.php');
                    $seed_prefix = extract_url_prefix($seed_url);
                    $domain_allowed = array(extract_domain_name($seed_url));
                    crawl_page($seed_url,2, $domain_allowed);
                }
                else {
                    echo "<h1>Oh no, that's a bad URL!</h1>";
                }
            }
            else {
                echo "<h1>You need to type something to seed it!</h1>";
            }

        }
    }
?>
<link rel='stylesheet' href='../css/styles.css'/>
<script>
    seed_button.addEventListener('click', (e) => {
        seed_form.classList.add('disabled');
        seed_url.setAttribute('readonly', true);
    })
</script>
</section>
</body>
</html>