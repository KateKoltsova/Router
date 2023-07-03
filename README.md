Router class, written by hand from scratch for a general understanding of how routing works.

The Router class accepts routes from the configuration file, if necessary, receives parameters through reflection and performs the action specified in the routes.

The Class Router factory extends the ComponentFactory from the Aigletter\Contracts package.
Used to create an object of the Router class and write the path-action array from the configuration file using the addRoute Router function.
Since the routing is written for the Aigletter application, the path to the file with routes is specified in the config\main.php of the Aigletter\test-framework project.
