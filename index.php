<?php
require 'vendor/autoload.php'
use RedBean_Facade as R;

R::setup("mysql:host=localhost;dbname=redBeanTest;charset=utf8", "root", "root");
R::freeze(true);

$app = new \Slim\Slim(array(
	'debug' => true
	));

$autentication = function () {
	$app = new \Slim\Slim::getInstance();
	$user = $app->request->headers->get('HTTP_USER');
	$pass = $app->request->headers->get('HTTP_PASS');
	$pass = sha1($pass);

	//validar datos de acceso
	$isValid = R::findOne('user', 'USER=? AND pass=?', array($user, $pass));
	try{
		if(!$isValid) {

		} else {
			throw new Exception("Error, Usuaario no encontrado");
		}
	} catch(Exception $e) {
		$app->status(401);
		echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
		$app->stop();
	}
};

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


































/*

verify_properly_rediretc_to_car_price_page_from_car_rental_option_on_confirmation_page.story

verify_cross_sell_on_view_itinerary_page_for_RT_specific_hotel.story

verify_cross_sell_on_view_itinerary_page_for_RT_view_more_hotel.story

verify_hotel_rate_view_itinerary_for_round_trip_and_car_reservation_saved_in_a_trip.story

verify_hotel_rate_view_itinerary_for_round_trip_saved_in_a_trip.story





*/































