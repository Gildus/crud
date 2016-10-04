<?php

header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS');
header('Access-Control-Allow-Headers: X-PINGOTHER, Content-Type, Pagination');

/// http://localhost:8000/api/

if ($_SERVER['REQUEST_METHOD'] == 'GET') {


	if($_SERVER['REQUEST_URI'] == '/api/users') {
		$pagination = json_encode([
				'CurrentPage' => 1,
				'ItemsPerPage' => 2,
				'TotalItems' => 4,
				'TotalPages' => 2 
			]);
			
		header ('access-control-expose-headers: Pagination');
		header ('Pagination: '. $pagination);

		echo json_encode([			
				[
					'id' => 1,
					'names' => 'nombres',				
					'email' => 'correo@yopmail.com',
					'status' => 'Enabled'					
				],
				[
					'id' => 1,
					'names' => 'nombres',				
					'email' => 'correo@yopmail.com',
					'status' => 'Enabled'	
				],
				[
					'id' => 1,
					'names' => 'nombres',				
					'email' => 'correo@yopmail.com',
					'status' => 'Enabled'	
				],
				[
					'id' => 1,
					'names' => 'nombres',				
					'email' => 'correo@yopmail.com',
					'status' => 'Enabled'	
				]
		]);
		
		
		
	} elseif($_SERVER['REQUEST_URI'] == '/api/users/1/details') {
		
		echo json_encode([			
			'id' => 1,
			'names' => 'nombres',				
			'username' => 'nombreusuario',
			'email' => 'correo@yopmail.com',
			'status' => 'Enabled',
			'created_at' => '2016-01-30 15:33',						
			'statuses' => ['Enabled', 'Disabled']			
		]);
		
	} elseif ($_SERVER['REQUEST_URI'] == '/api/init/all')  {		
		
		echo json_encode([
			'statuses' => [
				'Enabled',
				'Disabled'
			]
		]);	
	}
	
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
	
	if ($_SERVER['REQUEST_URI'] == '/api/users/add')  {
	
			$dataRequest = json_decode(file_get_contents('php://input'), true);
			echo json_encode([			
				'success' => true				
			]);
	
	}
	
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	
	
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
	/*if ($_SERVER['REQUEST_URI'] == "/api/users/1")  {
		echo json_encode(['success' => true]);
	
	}*/
}

