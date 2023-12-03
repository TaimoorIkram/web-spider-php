<?php
    /**
     * Read the robots.txt file for a seed url if it exists, or displays an 
     * error message if no such file exists.
     * 
     * requires the string seed url from where the crawling initiates,
     * modifies the original explored urls list so no further exploration 
     * is carried out on those urls if they're ever scraped and
     * returns nothing.
     */
    function read_robot($seed_url){
        try {
            $robot_content = file_get_contents($seed_url."/robots.txt");
            $robot_lines = explode("\n", $robot_content);

            foreach ($robot_lines as $line) {
                if (preg_match("/Disallow: (.+)/i", $line)) mark_explored($seed_url.$line);
            }
        } catch (Throwable $th) {
            echo "<h2>No Robot File Found</h2>";
        }
    }
?>