# Ideal Forum Roadmap
## Bug Fixes
[ ] Disable, remove spam

[ ] Double check how urls are parsed for links out

[ ] Add page titles

## Refactor

### Accessibility 

[ ] Get working without any Javascript

[ ] Get to work with keyboard/A11

[ ] Internationalize

### Architecture

[ ] Turn views from classes to PHP partials

[ ] Get working with or without Relevanssi search functionality

[ ] Add wrapper function (in `includes/helpers.php`) to make api calls, like 
```
<form action="<?php api('/comment-on-post') ?>" id="reply-to-post">;
```

[ ] Add "with shortcode" option to router::render() function

[ ] Find way to extend plugin functionality (may already be there)

## New Features

[ ] Add Direct Messaging or group messaging capability

[ ] Add backend options pages

[ ] Implement unit testing

[ ] Implement ActivityPub
