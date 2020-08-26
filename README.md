shorten_url_lite
Drupal 9.x Module -- It should run on Drupal 8.8+ but I have not tested on anything under 9.0.3

Creates short urls using sites domain


1. Main page (/) -To set to front page change Default front page(/admin/config/system/site-information) to /new/short 
2. Info page (/view/{code}) -  This page displays Entity after creation
3. Redirect link -- I have not found a way in Drupal 8/9 to have a parameter as the first section of a route. -- According to: https://www.drupal.org/docs/drupal-apis/routing-system/structure-of-routes The first item of the path cannot be an argument, and must be a string. We need to find a way to bypass this to meet the requirement of the redirect link, for now links are /sh/{code}
It looks like this could be achieved with an event:subscriber - https://www.drupal.org/node/2013014


Additional Feature:
1. User with permission 'add vanity urls to url entities' will be able to override the randomly generated short code
2. View page displays the number of times a short link has been followed.
--- Note browser cache prevents counting the same user multiple times, until cache is clear
3. If Views is enabled the module will load a simple Views config for a page listing short codes, links, counts (/short-url-counts)


Example running at chrisjlock.com


**Similar Modules
**https://www.drupal.org/project/shorten --> This module provides an API to shorten URLs via many services like bit.ly and TinyURL


