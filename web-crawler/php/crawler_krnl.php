<?php
    set_time_limit(10);
    libxml_use_internal_errors(true);

    function filter_urls($urls) {
        $new_urls = array();
        foreach($urls as $url) {
            if ($url === "#") continue;
            elseif ($url === "/") continue;
            array_push($new_urls, $url);
        }
        return $new_urls;
    }

    function extract_domain_name($url) {
        $domain_url = explode("/", $url)[2];
        return $domain_url;
    }

    function should_crawl($url, $starting_urls) {
        foreach ($starting_urls as $starting_url) {
            if ($url[0] == '/') return 1;
            elseif (extract_domain_name($url) === $starting_url) return 0;
        }
        return -1;
    }

    function extract_url_prefix($url) {
        $url_parts = explode("/", $url);
        return $url_parts[0].'//'.$url_parts[2];
    }
    
    function extract_dom_xelements($dom_x_elements){
        $output = '';
        foreach ($dom_x_elements as $dom_x_element) {
            $output.=strip_tags($dom_x_element->nodeValue)."\n";
            break;
        }
        return $output;
    }

    $EXPLORED_URLS = array('/');

    function mark_explored($url) {
        global $EXPLORED_URLS;
        array_push($EXPLORED_URLS);
    }

    function is_explored($url) {
        global $EXPLORED_URLS;
        if (array_search($url, $EXPLORED_URLS)) return true;
        else return false;
    }

    include('robots_reader.php');

    function crawl_page($url,$depth=2, $starting_urls=array())
    {
        echo "Reading: ".$url;
        if($depth>0)
        {
            // Fetch the remote html file contents for reading, reads them and then separates out the urls inside the a tags for recrsive read.
            $html = file_get_contents($url);
            preg_match_all('~<a.*?href="(.*?)".*?>~',$html,$matches);
            $matches[1] = filter_urls($matches[1]);

            // Recursive read operation that reads one by one through each url with one less depth.
            mark_explored($url);
            foreach($matches[1] as $index => $newurl)
            {
                if (!is_explored($newurl)) {
                    // Crawl based upon type of url.
                    if (should_crawl($newurl, $starting_urls) == 0){
                        crawl_page($newurl,$depth-1);
                    }
                    elseif (should_crawl($newurl, $starting_urls) == 1){
                        crawl_page('http://'.extract_domain_name($url).$newurl,$depth-1);
                    }
                }
            }
            
            // Setting up the DOM for reading specific tags
            $dom = new DOMDocument;
            $dom->loadHTML($html);
            $xpath = new DOMXPath($dom);
            
            // Remove all scripts from the html
            $scripts = $dom->getElementsByTagName('script');
            foreach ($scripts as $script) {
                $script->parentNode->removeChild($script);
            }
            
            // Remove all stylesheet tags
            $styles = $dom->getElementsByTagName('style');
            foreach ($styles as $style) {
                $style->parentNode->removeChild($style);
            }
            
            // Extract only requried content to be written to a file
            $title = $xpath->query('//title');
            $divs = $xpath->query('//div');
            // $paragraphs = $xpath->query('//p');
            // extract_dom_xelements($paragraphs).
            // $lists = $xpath->query('//li');
            // extract_dom_xelements($lists).
            $heads = $xpath->query('//h');
            
            // Persistence; saving the read data in plain text form in the storage
            if (isset($html)) file_put_contents("../data/$index.html","--url $url\n"."--title ".extract_dom_xelements($title)."\n\n".extract_dom_xelements($heads).extract_dom_xelements($divs)."\n\n");
        }
        echo " ... complete! ".'<br>';
    }

    read_robot($seed_url);
?>