<?php
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