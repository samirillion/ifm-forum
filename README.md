# crowdsorter
Wordpress plugin for sorting things.

Current plugin functionality:
 - adds a few core pages (Login, Register, Aggregator) on plugin activation
 - adds a custom post type for adding links (with a karma counter that I have yet to connect to the current front-end)
 - allows upvoting for logged-in members
 - displays comments (which can only be added from the backend at the moment
 
Soon to be implemented:
 - the actual sorting algorithm for the posts
 - a sorting algorithm for the comment-threads
 - tracking member-karma
 
General needs:
 - Sensible abstraction. I've tried to create an MVC architecture, but different bits of functionality are implemented in different ways. I used the factory pattern for creating different types of "sorters" but right now, it's just complicating implementation.
 - performance and security audits

A note on the structure:
  - bootstrap.php just gets things up and running, and calls class-controller.php. does a few plugin init things.
  - class-controller.php pretty much routes everything at the moment, and if you can follow the trail of class instantiations and shortcodes you can figure out how everything works.
  - the views and models aren't very well defined. i'm thinking of calling them all "classes" and then call the real views "templates"
