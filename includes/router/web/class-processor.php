<?php

/**
 * The Router_Web_Processor is in charge of the interaction between the routing system and
 * the rest of WordPress.
 *
 * @author Carl Alexander <contact@carlalexander.ca>
 */

namespace IFM;

class Router_Web_Processor
{
    /**
     * The matched route found by the router.
     *
     * @var Route
     */
    private $matched_route;

    /**
     * The router.
     *
     * @var Router
     */
    private $router;

    /**
     * The routes we want to register with WordPress.
     *
     * @var Route[]
     */
    private $routes;

    /**
     * Constructor.
     *
     * @param Router_Web_Router  $router
     * @param Router_Web_Route[] $routes
     */
    public function __construct(Router_Web_Router $router, array $routes = array())
    {
        $this->router = $router;
        $this->routes = $routes;
    }

    /**
     * Initialize processor with WordPress.
     *
     * @param Router_Web_Router  $router
     * @param Router_Web_Route[] $routes
     */
    public static function init(Router_Web_Router $router, array $routes = array())
    {
        $self = new self($router, $routes);

        add_action('init', array($self, 'register_routes'));
        add_action('parse_request', array($self, 'match_request'));
        add_action('template_include', array($self, 'load_route_template'));
        add_action('template_redirect', array($self, 'call_route_hook'));
    }

    /**
     * Checks to see if a route was found. If there's one, it calls the route hook.
     */
    public function call_route_hook()
    {
        if (!$this->matched_route instanceof Router_Web_Route || !$this->matched_route->has_hook()) {
            return;
        }

        do_action($this->matched_route->get_hook());
    }

    /**
     * Checks to see if a route was found. If there's one, it loads the route template.
     *
     * @param string $template
     *
     * @return string
     */
    public function load_route_template($template)
    {
        if (!$this->matched_route instanceof Router_Web_Route || !$this->matched_route->has_template()) {
            return $template;
        }

        $plugin_template_path = IFM_VIEW . $this->matched_route->get_template() . '.php';


        $theme_template = get_query_template($this->matched_route->get_template());

        if (!empty($theme_template)) {
            $template = $theme_template;
        } elseif (file_exists($plugin_template_path)) {
            $template = $plugin_template_path;
        }

        return $template;
    }

    /**
     * Attempts to match the current request to a route.
     *
     * @param WP $environment
     */
    public function match_request(\WP $environment)
    {
        $matched_route = $this->router->match($environment->query_vars);

        if ($matched_route instanceof Router_Web_Route) {
            $this->matched_route = $matched_route;
        }

        if ($matched_route instanceof \WP_Error && in_array('route_not_found', $matched_route->get_error_codes())) {
            wp_die($matched_route, 'Route Not Found', array('response' => 404));
        }
    }

    /**
     * Register all our routes into WordPress.
     */
    public function register_routes()
    {
        $routes = apply_filters('ifm_plugin_routes', $this->routes);

        foreach ($routes as $name => $route) {
            $this->router->add_route($name, $route);
        }

        $this->router->compile();

        $routes_hash = md5(serialize($routes));

        if ($routes_hash != get_option('ifm_plugin_routes_hash')) {
            flush_rewrite_rules();
            update_option('ifm_plugin_routes_hash', $routes_hash);
        }
    }
}
