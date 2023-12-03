<?php

function search_unique_item_within_file($file_lines, $file_tag) {
    foreach ($file_lines as $line_number => $line) {
        if (preg_match("/\\b(?:$file_tag)\\b/i", $line)) {
            return explode(" ", $line, 2)[1];
        }
    }
}

function search_within_file($regex, $file_lines) {
    $results = null;
    if ($file_lines == false) return array();
    foreach ($file_lines as $line_number => $line) {
        if (preg_match($regex, $line)) {
            $results[$line_number] = $line;
        }
    }
    return $results;
}
?>

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
    </div>
    <form>
    <div class='search-container' style='background-color: var(--card-blue);'>
        <input type=text class='search bar' name='search' placeholder='Type something to search!'/>
        <input type='submit' class='button' value='Search'/>
    </div>
    </form>
    
    <section>

    <div class='table'>
    <?php
        
        $search_item = "NFL";
        $regex = "/\\b(?:$search_item)\\b/i";
        
        // System loop that extracts the results, obtains the associated title and url and displays it to the user with all the lines containing the correct results.
        for ($filename=0; $filename < 1000; $filename++) { 
            try {
                $path = "../data/$filename.html";
                $file_lines = file($path, FILE_IGNORE_NEW_LINES);
                $a_href = search_unique_item_within_file($file_lines, "url");
                $search_results = search_within_file($regex, $file_lines);
                echo "<div class='row' style='background-color: var(--card-blue);'>";
                echo "<div class='item head'><h2><a href='$a_href'>".$a_href."</a></h2>";
                echo "<p style='font-weight: 300px;'>From the URL ".$a_href."</p><div>";
                foreach ($search_results as $line_number => $line) {
                    echo "<div class='item'>$line</div>";
                }
                echo "</div>";
            } catch (Throwable $th) {
                echo "Unable to find file.";
            }
        }
    ?>
    </div>
    </section>
</body>
</html>