<?php


$app->get("/", function() {
	echo "Pantalla de inicio";
});



$app->group("/api", function() use($app) {
	$app->group("/users", function() use($app) {
		$app->response->headers->set("Content-Type", "Application/json")
		
		//Listar
		
		$app->get("/all", function() use($app) {
		try {
			$all = R::find('usuarios');
			$all_users = R::exportAll($all);
			echo json_encode($all_users);
		}
			catch(Exception $e) {
				$app->status(404);
				echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
			}
		});
		

		//Listar por id
		$app->get("/id/:id", function($id) use($app) {
		try {
			
			$all = R::load('usuarios', $id);
			$usuario = R::exportAll($all);
			
			if($usuario->id) {
				$user = R::$usuario->export();
				echo json_encode($user);
			} else {
				throw new Exception("Error a listar el usuario");
			}
		}
			catch(Exception $e) {
				$app->status(404);
				echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
			}
		});


		//insertar usuario
		$app->post("/new", function() use($app) {
		try {
			
			$request = $app->request;
			$data = json_decode($request->getBody());
			$usuario = R::dispense('usuarios');

			$usuario->nombre = $data->nombre;
			$usuario->apellido = $data->apellido;
			$usuario->email = $data->email;
			$usuario->usuario = $data->usuario;
			$usuario->password= $data->password;

			$insertado = R::store($usuario);
			
			if($isnertado) {
				echo json_encode(array('status' => 'success', "message" => "Insertado Correctamente"));
			} else {
				throw new Exception("Error a listar el usuario");
			}
		}
			catch(Exception $e) {
				$app->status(404);
				echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
			}
		});


		//actualizar usuario
		$app->put("/update/:id", function($id) use($app) {
		try {
			
			$id = (int)$id;
			$request = $app->request;
			$data = json_decode($request->getBody());
			$usuario = R::load('usuarios',$id);			
			
			if($usuario->id) {

				//actualizo los datos del usuario
				$usuario->nombre = $data->nombre;
				$usuario->apellido = $data->apellido;
				$usuario->email = $data->email;
				$usuario->usuario = $data->usuario;
				$usuario->password= $data->password;

				R::store($usuario);
				echo json_encode(array('status' => 'success', "message" => "Actualizado correctamente"));
			} else {
				throw new Exception("Error al actualizar el usuario");
			}
		}
			catch(Exception $e) {
				$app->status(404);
				echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
			}
		});


		//eliminar usuario
		$app->delete("/delete/:id", function($id) use($app) {
			$id = (int)$id;
			try {
				
				$usuario = R::load('usuarios',$id);			
				
				if($usuario->id) {
					R::trash($usuario);
					echo json_encode(array('status' => 'success', "message" => "Eliminado correctamente"));
				} else {
					throw new Exception("Error al eliminar el usuario");
				}
			}
				catch(Exception $e) {
					$app->status(404);
					echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
				}
		});



	});

});
