# Talking points:
## The Dream:
Like I said in my interview, I was inspired by Aaron Swartz, who did RSS and infogami and Reddit.

There's also this guy, who does aggregator theory:

https://stratechery.com/aggregation-theory/

Which really provides a useful abstraction 

To Implement the ActivityPub protocol and 

## Background:
- Reddit-lite forum plugin, using a similar "hot" algorithm for sorting the posts.
- phase one: first attempt at MVC architecture
- phase two: refactored to get into working shaping for Food in Neighborhoods
- phase three: refactoring again to add functionality and make more scalable

## Functionality && Refactor
### Functionality
- need to  add messaging

### Refactor
- would require a more RESTful api for rendering pages and doing CRUD
- decided to piece together router and templating system that would work with wordpress's:
    - existing templating system
    - new-ish restful json api (wp-api)
    - and their rewrite api for rendering pages
 
## How it Used to be Done
- creating rendering views with shortcodes on pages
- using a wordpress hook called admin_post_my_post_function() to create POST requests
- problematic because it has no predictable structure for creating http requests, and the actual views were rendered with shortcodes saved in the database! not where you want your views to be kept!



Go through general functionality
- Logging in
- Adding post
- Upvoting post