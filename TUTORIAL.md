# Ifm Forum Development Tutorial: Implement Direct Messaging
This is a high level view of adding new functionality to the Ifm Forum app. In this tutorial, I will be implementing a simple direct messaging system for the application.

## Create a Route.
In `routes/web.php`, you can create a route to a page, using a callback on a controller class.
```
IfmWeb::render('/my-messages', 'IfmMessagingController@main');
```

So when I go to https://foodinnneighborhoods.com/my-messages, a method on the `class IfmMessagingController` called `main` will be called. Now I need to create that controller class and add that method!

For now, create a file: `app/controllers/class-messaging-control.php` and paste in this code:
```
<?php
class IfmMessagingController {
    public function main() {
        return "<h1>Hello Message World</h1>";
    }
}
```

I also want to require the file in the `run_ifm()` function like this:
```
require(IFM_APP . 'controllers/class-messaging-controller.php');
```
to make the class available everywhere.

And, that's actually it! You've successfully rendered a route via a controller method.


