# Ifm Forum Plugin: 
WordPress forum plugin with Reddit-like functionality on top; Larave-like syntax under the hood.

- Define routes easily
- Handle routes with controllers
- uses wp-api, so you can still use this as part of a "headless WordPress" setup. 

## Big Picture:
I was inspired by Aaron Swartz, who did RSS and infogami and Reddit.

There's also this guy, who does aggregator theory:

https://stratechery.com/aggregation-theory/˝

Which really provides a useful abstraction for what exactly any kind of information technology "is."

- First, it's easy to forget, these platforms are purely for human communication. They are "complex symbolic systems."
- The main thing these platforms offer is their ability to organize information. They are "aggregators."

Ultimately, I want this to be a forum that can talk to other forums via the ActivityPub protocol, which is what [Mastodon](https://mastodon.social/) and [Peertube](https://peertube.social/) and a number of other federated networks run on now.

I also want others to be able to use and extend it. So it would kind of be like an ActivityPub impementation starter kit, that would be accessible to anyone who knows how to write WordPress plugin or to anyone who knows an MVC framework (like Laravel). But that's still down the road a little.

## History:
- Reddit-lite forum plugin, using a similar "hot" algorithm for sorting the posts.
- phase one: first attempt at MVC architecture
- phase two: refactored to get into working shaping for Food in Neighborhoods
- phase three: refactoring again to add functionality and make more scalable turn into Laravel-lite. But "light" in the sense of opinionated, not in the sense of less out of the box.

## Functionality and Refactor
- Need to add private messaging to make it really useful
- would require a more RESTful api for rendering pages and doing CRUD (plus good practice to try)
- decided to piece together router and templating system that would work with wordpress's:
    - existing templating system
    - new-ish restful json api (wp-api)
    - `add_rewrite_rule()` for adding custom rewrite rules based on paths
 
## How it Used to be Done
- creating rendering views with shortcodes on pages
- using a wordpress hook called admin_post_my_post_function() to create POST requests
- problematic because it has no predictable structure for creating http requests, and the actual views were rendered with shortcodes saved in the database! Not where you want your mvc structure to be kept.

## How it's done now
- views are rendered with a router that uses `add_rewrite_rule`
https://carlalexander.ca/designing-system-wordpress-routing/
- data is managed via a wrapper around the wordpress WP-API
https://developer.wordpress.org/rest-api/extending-the-rest-api/controller-classes/
- all routes are added onto the same class from `app/routes.php` 
- a callback is specified in the route as a controller method (e.g., `IfmPostsController@main`)

## Quick tour of frontend and backend
- Frontend:
    - View posts,
    - View post
    - Create a new account
    - Create a post
    - View post
- Backend:
(Some of this is just putting things where I'll expect them later. For example, I haven't really done too much with class inheritance.)
 - bootstrap
 - seeds
 - app
 - includes
    - router facade
    - quick word on importer

## [Tutorial](./TUTORIAL.md)
 - Add some functionality
 - Push to staging