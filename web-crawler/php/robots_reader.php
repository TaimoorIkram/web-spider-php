<?php
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