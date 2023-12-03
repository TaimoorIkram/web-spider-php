# Simple Web Spider as Assignment 2 of Web Engineering
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
8. Once on this page, type the search term and press enter or press the ***Search** button to the right end of the search bar.
9. With time, the search results will be returned containing the title of the page, lines that contain the word you described.

## Key features.
1. URL Queueing
2. Crawling
3. HTML Parsing
4. URL Extraction
5. Depth Limit
6. Output
7. Robots.txt Compliance (robots.txt)
8. Content Search Module
9. Error Handling (Timeouts, File Not Found, Robot File Not Present)
10. Persistent Storage (Flat Files)
11. Advanced Regex Searching

## Credits
Taimoor Ikram
