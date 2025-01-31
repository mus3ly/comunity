<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['login_set/registration'] = 'home/login_set/registration';
$route['home/affiliate'] = 'home/affliate';
$route['vendor/affiliate'] = 'vendor/affliate';
$route['login_set/login'] = 'home/login_set/login';
$route['vendor_logup/registration'] = 'home/vendor_logup/registration';
$route['product/(:any)/(:any)'] = 'home/product_view/$1/$2';
$route['profile'] = 'home/profile';
$route['login'] = 'home/login';
$route['verfication'] = 'home/verification';
$route['verfication_user'] = 'home/verfication_user';
$route['admin'] = 'admin/main_login';
$route['vendor'] = 'vendor/main';
$route['contact'] = 'home/contact';
$route['about'] = 'home/other_page/about';
$route['services'] = 'home/other_page/services';
$route['faq'] = 'home/faq';
$route['Articles'] = 'home/blog';
$route['directory'] = 'home/directory';

$route['directory/(:any)'] = 'home/directory/$1';
$route['404_override'] = 'home/error';
$route['sitemap.xml'] = 'home/sitemap';
$route['translate_uri_dashes'] = FALSE;
$route['([^/]+)/?'] = 'home/decide/$1';
$route['directory/(:any)'] = 'home/directory/$1';
