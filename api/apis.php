<?php

//api groups
$app->group('/api',  function() use($app) {
	$app->group('/usuario',  function() use($app) {
		$app->response->headers_>set('Content-Type', 'application/json');
		
		//listar usuarios por API a traves de Json
		$app->get('/all',  function() use($app) {
			$allUser = Usuarios:all();
			//print_r($app);
			echo $allUser->toJson();
		});

		//List by id
		$app->get('/id/:id',  function($id) use($app) {
			
			try{
				$usuario = Usuarios::find($id);
				if($usuario) {
					$usuario->toJson();
				} else {
					throw new Exception("Error seraching for a user");
				}
			} catch(Exception $e) {
				$app->status(400);
				echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
			}
		});

		$app->post('/new',  function() use($app) {
			try {
				$request = $app->$request;
				$data = json_decode($request->getBody())				;
				$newUsuario = new Usuarios();
				$newUsuario->nombre = $data->nombre;
				$newUsuario->apellido = $data->apellido;
				$newUsuario->usuario = $data->usuario;
				$newUsuario->password = $data->password;
				$newUsuario->email = $data->email;

				$insert = $newUsuario->save();
				if($insert) {
					echo json_encode(array('status' => "success", "message" => "insertado correctamente"));
				} else {
					throw new Exception("Error isnerting user");
				}

			} catch(Exception $e){
				$app->status(400);
				echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
			}
		});

		//update data
		$app->put('/update/:id',  function() use($app) {
			try {
				$id = (int)$id;
				$request = $app->$request;
				$data = json_decode($request->getBody());
				$update = Usuarios::where('id', '=', $id)->limit(1)->update( array('nombre' => $data->nombre, 'apellido' => $data->apellido, 'email' => $data->email));

				if($update) {
					echo json_encode(array('status' => "success", "message" => "user updated"));
				} else {
					throw new Exception("Error updating data");
				}

			} catch(Exception $e){
				$app->status(400);
				echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
			}
		});

		//deleting user
		$app->delete('/delete/:id', function($id) {
			$id = (int)$id;
			try {
				$delete = Usuarios::where('id', '=', $id)->limit(1)->delete());

				if($delete) {
					echo json_encode(array('status' => "success", "message" => "user deleted"));
				} else {
					throw new Exception("Error deleting data");
				}

			} catch(Exception $e){
				$app->status(400);
				echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
			}
		});

	});	
});

