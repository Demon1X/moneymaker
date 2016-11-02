<?php
	// Отображение ошибок
	error_reporting (E_ALL);
	
	// Инициализация сессии
	session_start ();
	
	// Подключаем конфиг
	require_once 'lib/config.php';
	
	// Подключаем библиотеку с путями
	require_once 'lib/path.php';

	// Подключаем основную библиотеку
	require_once 'lib/core.php';

	// Установка основных настроек
	install_settings ();
	
	// Редирект на HTTPS если это необходимо
	if (USE_HTTPS) go_https();

	// Подключение языкового файла
	require_once lang();
	
	// Блокировка если идут технические работы
	if (TECHNICAL_WORK AND !is_admin())
	{
		require_once 'module/front/techwork.php';
		require_once 'template/tpl/front/techwork.tpl';
		exit;
	}
	
	
	// Подключение контроллера и его шаблона
	if (isset($_REQUEST['route'])) 
	{		
		$route = $_REQUEST['route'];
		$route = rtrim($route, '/');
		$route = explode('/', $route);
		$module = $route[0]; 
		
				
		// Если контроллер существует, то поключаем его
		if (isset($path[$module]))
		{	
			require_once 'module/'.$path[$module].'.php'; 
			require_once 'template/tpl/'.$path[$module].'.tpl'; 
		}
		else
		{	
			// Если нет, то подключаем дефолтный контроллер
			require_once 'module/'.$path[DEFAULT_MODULE].'.php'; 
			require_once 'template/tpl/'.$path[DEFAULT_MODULE].'.tpl'; 
		}
	}
	else
	{		
		// Если конроллер не указан, то подключаем дефолтный
		require_once 'module/'.$path[DEFAULT_MODULE].'.php'; 
		require_once 'template/tpl/'.$path[DEFAULT_MODULE].'.tpl'; 
    }

	
?>