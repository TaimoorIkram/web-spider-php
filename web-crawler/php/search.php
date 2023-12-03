<?php
/**
 * Search and otain unique HTML tags like title and html tags. Please 
 * note that this function is designed to read the file from the top to 
 * bottom, and extract only exclusive data marked by the -- markers at
 * the top of every file.
 * 
 * requires an object containing an array of all file lines,
 * a tag that needs to be extracted from the file
 * returns the value associated with the -- special file tags.
 */
function search_unique_item_within_file($file_lines, $file_tag) {
    foreach ($file_lines as $line_number => $line) {
        if (preg_match("/\\b(?:$file_tag)\\b/i", $line)) {
            return explode(" ", $line, 2)[1];
        }
    }
}

/**
 * Search for lines matching query within the list of given file lines.
 * 
 * requires an object containing an array of all file lines,
 * a regex that needs to be extracted from the file and
 * returns the array of lines that actually contain the 
 * terms satisfying the regex.
 */
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
        if ($_SERVER['REQUEST_METHOD'] == 'GET'){
            if(isset($_GET['search'])){
                $search_item = $_GET['search'];
                $regex = "/\\b(?:$search_item)\\b/i";
                
                // System loop that extracts the results, obtains the associated title and url and displays it to the user with all the lines containing the correct results.
                for ($filename=0; $filename < 1000; $filename++) { 
                    try {
                        $path = "../data/$filename.html";
                        if (!file_exists($path)) continue; 
                        $file_lines = file($path, FILE_IGNORE_NEW_LINES);
                        $a_href = search_unique_item_within_file($file_lines, "url");
                        $a_title = search_unique_item_within_file($file_lines, "title");
                        $search_results = search_within_file($regex, $file_lines);
                        if (!isset($search_results)) continue;
                        echo "<div class='row' style='background-color: var(--card-blue);'>";
                        echo "<div class='item head'><h2><a href='$a_href'>".$a_title."</a></h2>";
                        echo "<p style='font-weight: 300px;'>From the URL ".$a_href."</p></div>";
                        foreach ($search_results as $line_number => $line) {
                            echo "<div class='item'>$line</div>";
                        }
                        echo "</div>";
                    } catch (Throwable $th) {
                        echo "Unable to find file.";
                    }
                }
            }
        }
    ?>
    </div>
    </section>
</body>
</html>