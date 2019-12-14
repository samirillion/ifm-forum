# Ideal Forum Roadmap
## Bug Fixes
[x] Pagination

[x] Karma Calculation

[ ] Disable, remove spam

[ ] Double check how urls are parsed for links out

## Refactor
[ ] Get working without any Javascript

[ ] Get to work with keyboard/A11

[ ] Add more links on relevant pages to and from comments, etc.

[ ] Get working for any WP instance on first install

[ ] Confirm search functionality

[ ] Add some sort of wrapper function to make api calls like 
```
<form action="<?php api('/comment-on-post') ?>" id="reply-to-post">;
```

## New Features
[ ] Implement ORM

- https://github.com/jdpedrie/wp-orm


[ ] Complete more advanced editor for page posts

[ ] Add Direct Messaging or group messaging(?) capability

[ ] Make algorithms more modular

[ ] Add backend options pages

[ ] Implement unit testing

[x] Implement PHP router, c.f., 

- https://carlalexander.ca/designing-system-wordpress-routing/
- https://developer.wordpress.org/reference/functions/rest_do_request/
- https://developer.wordpress.org/rest-api/using-the-rest-api/frequently-asked-questions/
- https://developer.wordpress.org/rest-api/extending-the-rest-api/


[ ] Implement ActivityPub

[ ] Find way to integrate with Peertube instance(s)

## DevOps(?)