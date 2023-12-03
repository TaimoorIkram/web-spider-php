<?php
    set_time_limit(120);
    libxml_use_internal_errors(true);

    /**
     * Get the list of urls that meet a certain criteria, including being '#' or '/' character only.
     * 
     * requires the list of string urls to filter out from and
     * returns a list of new urls that obey the specified filters.
     */
    function filter_urls($urls) {
        $new_urls = array();
        foreach($urls as $url) {
            if ($url === "#") continue;
            elseif ($url === "/") continue;
            array_push($new_urls, $url);
        }
        return $new_urls;
    }

    /**
     * Get the domain name (www.google.com) less the http://.
     * 
     * requires the complete string url with the protocol, domain name and path parameters if any and
     * returns a new url that is the base url for other pages.
     */
    function extract_domain_name($url) {
        $domain_url = explode("/", $url)[2];
        return $domain_url;
    }

    /**
     * Determine if the link in question is an absolute URL or a relative URL.
     * 
     * requires a string url
     * returns 1 if the url is relative (/wiki/Article), and 0 if it is absolute (www.google.com/search/...).
     */
    function should_crawl($url, $starting_urls) {
        foreach ($starting_urls as $starting_url) {
            if ($url[0] == '/') return 1;
            elseif (extract_domain_name($url) === $starting_url) return 0;
        }
        return -1;
    }

    /**
     * Get the url prefix (with the http part). Basically the extract_domain_name function with an added http:// part.
     * 
     * requires the absolute string url to extract the name of and
     * returns a url containing a joined http:// before the domain name.
     */
    function extract_url_prefix($url) {
        $url_parts = explode("/", $url);
        return $url_parts[0].'//'.$url_parts[2];
    }

    /**
     * Get the text part only, from the DOMXPath elements that have been extracted from the DOMDocument.
     * 
     * requires the DOMXPath object to extract text content from and
     * returns the text content inside that object.
     */
    function extract_dom_xelements($dom_x_elements){
        $output = '';
        foreach ($dom_x_elements as $dom_x_element) {
            $output.=strip_tags($dom_x_element->nodeValue)."\n";
            break;
        }
        return $output;
    }

    // Global list of urls that have been explored already. Contains disallowed urls from the robots.txt file as well.
    $EXPLORED_URLS = array('/');

    /**
     * Mark a certain url as visited by pushing it into the global array of explored urls.
     * 
     * requries a string url that has been explored and
     * returns nothing.
     */
    function mark_explored($url) {
        global $EXPLORED_URLS;
        array_push($EXPLORED_URLS);
    }

    /**
     * Checks to see if the url under process is one from the global list of explored urls.
     * 
     * requries a string url to check the exploration status of and
     * returns true if the link has been explored already, false otherwise.
     */
    function is_explored($url) {
        global $EXPLORED_URLS;
        if (array_search($url, $EXPLORED_URLS)) return true;
        else return false;
    }

    // Include the robots.txt file to for the crawler to adhere to.
    include('robots_reader.php');

    /**
     * Main system loop that looks for the content on pages, cleans them out and writes them to corresponding indexed file.
     * 
     * requires a seed url, a string url to start the seeding process,
     * a depth, indicating how deep must the code go, in the url tree to read the content,
     * an array of urls that checks for the allowed base urls to allow the search of data from and
     * returns nothing 
     */
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
            $heads = $xpath->query('//h');
            
            // Persistence; saving the read data in plain text form in the storage
            if (isset($html)) file_put_contents("../data/$index.html","--url $url\n"."--title ".extract_dom_xelements($title)."\n\n".extract_dom_xelements($heads).extract_dom_xelements($divs)."\n\n");
        }
        echo " ... complete! ".'<br>';
    }

    read_robot($seed_url);
?>