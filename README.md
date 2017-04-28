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
  - [ ] tracking member-karma/creating a member pages
  - [ ] allow logged-in members to post from the WordPress front-end
