<?php
/**
 * @package Swiftlet
 * @copyright 2009 ElbertF http://elbertf.com
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
 */

if ( !isset($app) ) die('Direct access to this file is not allowed');

switch ( $hook )
{
	case 'info':
		$info = array(
			'name'         => 'dashboard',
			'version'      => '1.0.0',
			'compatible'   => array('from' => '1.2.0', 'to' => '1.2.*'),
			'dependencies' => array('db', 'perm', 'session'),
			'hooks'        => array('init' => 5, 'install' => 1, 'menu' => 2, 'remove' => 1, 'unit_tests' => 1)
			);

		break;
	case 'install':
		if ( !empty($app->perm->ready) )
		{
			$app->perm->create('Administration', 'dashboard access', 'Access to the dashboard');
		}

		break;
	case 'remove':
		if ( !empty($app->perm->ready) )
		{
			$app->perm->delete('dashboard access');
		}

		break;
	case 'init':
		if ( !empty($app->perm->ready) )
		{
			require($contr->classPath . 'dashboard.php');

			$app->dashboard = new dashboard($app);
		}

		break;
	case 'menu':
		if ( !empty($app->perm->ready) )
		{
			if ( $app->perm->check('dashboard access') )
			{
				$params['Dashboard'] = $view->rootPath . 'admin/';
			}
		}

		break;
	case 'unit_tests':
		$r = post_request('http://' . $_SERVER['SERVER_NAME'] . $contr->absPath . 'admin/index.php', array(), TRUE);

		$params[] = array(
			'test' => '<code>/admin/</code> should be inaccessible for guests.',
			'pass' => $r['info']['http_code'] == '302'
			);

		break;
}
