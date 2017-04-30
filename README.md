# crowdsorter
Wordpress Plugin for sorting all the things.

Current plugin functionality:
 - adds a few core pages (Login, Register, Aggregator) on plugin activation
 - adds a custom post type for adding links
 - allows upvoting posts for logged-in members
 - AJAX-ified paging function for posts
 - Nested comments with a ranking algorithm similar to posts

General needs:
 - I've tried to create an MVC architecture, but different bits of functionality are implemented in different ways. I used the factory pattern for creating different types of "sorters" but right now, it's just complicating implementation.
 - performance and security audits

A note on the structure:
  - the views and models aren't very well defined. i'm thinking of calling them all "classes" and then call the real views "templates"

Todo list:
  - [x] tracking member-karma/creating a member pages
  - [x] allow logged-in members to post from the WordPress front-end
  - [ ] allow looking at others' profile details, posts submitted, etc.
  - [ ] allow editing/deleting one's own posts and comments (and handling the karma implications)
  - [ ] fix jquery on comments page to allow opening and closing replies at will
  - [ ] fix post and comment queries to correctly sort within first ~5 minutes of posting
  - [ ] add nonces to everything
  - [ ] write functions to work without Javascript.
  - [ ] abstract functions to allow customization/full functionality from admin area (defining posts, sorting algorithms, etc.)
