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
|	https://codeigniter.com/user_guide/general/routing.html
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
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Website Router
$route['auth/login'] = 'auth/login';
$route['website/province/detail'] = 'province/detail';
$route['website/district/detail'] = 'district/detail';
$route['website/regency/detail'] = 'regency/detail';
$route['website/village/detail'] = 'village/detail';
$route['website/home/all'] = 'home/all';
$route['website/about/all'] = 'about/all';
$route['website/general'] = 'general/all';
$route['website/product/type'] = 'product/type';
$route['website/product/category'] = 'product/category';
$route['website/business/all'] = 'business/all';
$route['website/business/mitra'] = 'business/mitra';
$route['website/product/group'] = 'product/group';
$route['website/product/detail'] = 'product/detailz';
$route['website/product/detailsearch'] = 'product/searchs';
$route['website/product/detaildrug'] = 'product/drug';
$route['website/product/detailhealth'] = 'product/health';
$route['website/product/search'] = 'productz/search';
$route['website/career/all'] = 'career/all';
$route['website/career/detail'] = 'career/detail';
$route['website/career/submit'] = 'career/submit';
$route['website/news/all'] = 'news/all';
$route['website/news/detail'] = 'news/detail';
$route['website/general/sendmail'] = 'general/sendmail';
$route['website/general/page'] = 'general/page';
// $route['user/register'] = 'users/register';
// Website Router
// CMS Router
$route['cms/home/about/update'] = 'cms/home/update_about';
$route['cms/home/product/update'] = 'cms/home/update_product';
$route['cms/home/aq/update'] = 'cms/home/update_aq';

$route['cms/about/head/update'] = 'cms/about/update_about';
$route['cms/about/company/update'] = 'cms/about/update_company';
$route['cms/about/president/update'] = 'cms/about/update_president';
$route['cms/about/vm/update'] = 'cms/about/update_vm';
$route['cms/about/value/update'] = 'cms/about/update_value';
$route['cms/about/certification/update'] = 'cms/about/update_certification';
$route['cms/about/office/update'] = 'cms/about/update_office';
$route['cms/about/award/update'] = 'cms/about/update_award';

$route['cms/product/type/update'] = 'cms/product/update_type';
$route['cms/product/category/update/(:any)'] = 'cms/product/update_category/$1';
$route['cms/product/category/update'] = 'cms/product/update_category';
$route['cms/product/category/delete/(:any)'] = 'cms/product/delete_category/$1';
$route['cms/product/group/update/(:any)'] = 'cms/product/update_group/$1';
$route['cms/product/group/update'] = 'cms/product/update_group';
$route['cms/product/group/delete/(:any)'] = 'cms/product/delete_group/$1';
$route['cms/product/product/update/(:any)'] = 'cms/product/update_product/$1';
$route['cms/product/product/update'] = 'cms/product/update_product';
$route['cms/product/product/delete/(:any)'] = 'cms/product/delete_product/$1';

$route['cms/business/distribution/update'] = 'cms/business/update_distribution';
$route['cms/business/distribution/update/(:any)'] = 'cms/business/update_distribution/$1';
$route['cms/business/distribution/delete/(:any)'] = 'cms/business/delete_distribution/$1';
$route['cms/business/mitra/update'] = 'cms/business/update_mitra';

$route['cms/news/news/update'] = 'cms/news/update_news';
$route['cms/news/news/update/(:any)'] = 'cms/news/update_news/$1';
$route['cms/news/news/uploader'] = 'cms/news/uploader_news';
$route['cms/news/news/delete/(:any)'] = 'cms/news/delete_news/$1';

$route['cms/career/join/update'] = 'cms/career/update_join';
$route['cms/career/join/update/(:any)'] = 'cms/career/update_join/$1';
$route['cms/career/join/delete/(:any)'] = 'cms/career/delete_join/$1';
$route['cms/career/category/update'] = 'cms/career/update_category';
$route['cms/career/category/update/(:any)'] = 'cms/career/update_category/$1';
$route['cms/career/category/delete/(:any)'] = 'cms/career/delete_category/$1';

$route['cms/general/privacy/update'] = 'cms/general/update_privacy';
$route['cms/general/terms/update'] = 'cms/general/update_terms';
$route['cms/general/farma/update'] = 'cms/general/update_farma';
$route['cms/general/faq/update'] = 'cms/general/update_faq';
$route['cms/general/smtp/update'] = 'cms/general/update_smtp';
