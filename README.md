# Simple Web Spider as Assignment 2 of Web Engineering
Made by Taimoor Ikram, BESE 12-A, SEECS, NUST
## About this Code
The code features a simple web crawler software that can surf the page for 2 minutes to recursively fetch the HTML content, filter out the headings and div contents, strip it off its tags and place that content into appropriately indexed HTML file. The code is divided into three main modules.
1. **The Crawler Engine** that fetches and cleans the HTML content from the webpages. It takes as input, in the search field a base url to start the seeding process from and from there on, recursively fetches other urls to look for. The greater the time, the more the data is going to be fetches. *Please note that you must NOT provide trailing slash to the URL being seeded (for example, don't use ```http://www.example.com/``` instead use ```http://www.example.com```) as this has the chance of corrupting the data so you may as well end up receiving distorted data!*
2. **The Search Engine** that searches through the appropriately indexed files and returns all the lines where that particular data exists. There are two ways you can search for items in this module.
   a. Using normal text based search.
   b. Using regular expressions.
3. The **Robots File Reader** is a submodule of the Crawler Engine that is only responsible for reading the ```BASE_URL/robots.txt``` file from the seeding webpage.

## Getting Started
Follow the steps hereunder to set up your php project to work with the XAMPP utility.
1. Make sure to download this repository and copy-paste only the ```web-crawler``` folder from this project, inside the ```htdocs``` folder of the ```xampp``` directory.
2. Once pasted, set up you XAMPP Apache server and head over to the browser.
3. In the URL section, type ```http://localhost/web-crawler/php/crawler.php```.
4. Here type the seed URL as it asks you to.
5. Make sure that you carefully place the ```http://``` or the ```https://``` prefixes in the search bar as they are the part of the URL you're providing.
6. Press the **Start Seed** button and wait for a few minutes while the code is running (default of 2m runtime).
7. Once the seed is complete, click on the **Search** button or visit the URL ```http://localhost/web-crawler/php/search.php``` to get a search page.
8. Once on this page, type the search term and press enter or press the **Search** button to the right end of the search bar.
9. With time, the search results will be returned containing the title of the page, lines that contain the word you described.

## Key features.
1. URL Queueing helps manage URLs for the crawler to explore one after the other. They are progressively marked as explored so other identical URLs are ignored.
2. Crawling feature is the key feature of this software as the code moves from one explored HTML page to the next, based on what URL is next in its recursive queue.
3. HTML Parsing enables the module to filter out, for now, only the heading and div contents for them to be safely stored on a local file, free of any scripts and stylesheets.
4. URL Extraction obtains the URL of the webpage and stores it using a *crawler-specific template language* that helps locate the urls easily. These tags are present at the top of the file and are unique so they can be found quickly by the system during recursive search processes.
5. Depth Limit is a must when it comes to recursive searching, and in this case, the depth of the URLs has been set to 2 by default. However, though it isnt advisable, the limit can be changed in the sourcecode but it is generally not required.
6. Output for the scrapped URLs is displayed on the screen letting the user know what URLs have successfully been scraped. *Please note that the last URL may or may not be explored, which depends on the timing of the 2 minute mark that stops the scripts from running further*. 
7. Robots.txt Compliance (robots.txt) ensures that whenever a seed is sown, the robot files for that seed that govern the URL protection of some of the routes are not explored.
8. Content Search Module enables searching from the scraped data that is locally always stored inside the ```web-crawler/data``` folder.
9. Error Handling (Timeouts, File Not Found, Robot File Not Present) has been implemented to ensure proper graceful exits in case of unintended exceptions.
10. Persistent Storage (Flat Files) to keep a record of scraped data. These files are inside the ```web-crawler/data``` folder and are *overridden* on every new search to protect it from duplicate data.
11. Advanced Regex Searching helps find data quicker using more specific text formatting, allowing more exact search to take place.
