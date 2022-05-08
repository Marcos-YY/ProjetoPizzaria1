<?php
//montagem da pizza

include_once("conn.php");

$method = $_SERVER["REQUEST_METHOD"];

//resgate dos dados no banco
if($method === "GET"){
	
	$bordasQuery = $conn->query("select * from bordas;");
	$bordas = $bordasQuery->fetchAll(); //transferindo os dados para um array

	$massasQuery = $conn->query("select * from massas;");
	$massas = $massasQuery->fetchAll();

	$saboresQuery = $conn->query("select * from sabores;");
	$sabores = $saboresQuery->fetchAll();

//criaçao do pedido
	} else if ($method === "POST") {
	
	$data = $_POST;
	$borda = $data["borda"];
	$massa = $data["massa"];
	$sabores = $data["sabores"];

	//validando sabores(maximo 3)
	if(count($sabores) > 3) {

      $_SESSION["msg"] = "Selecione no máximo 3 sabores!";
      $_SESSION["status"] = "warning";

    } else {

		//salvando borda e massa na pizza -- statement
		$stmt = $conn->prepare("INSERT INTO pizza (id_borda, id_massa) VALUES (:borda,:massa)");	
		//validando inputs
		$stmt->bindParam(":borda", $borda, PDO::PARAM_INT);
		$stmt->bindParam(":massa", $massa, PDO::PARAM_INT);
		$stmt->execute();


		// resgatar ultimo id da ultima pizza
		$pizzaId = $conn->lastInsertId();
		$stmt = $conn->prepare("INSERT INTO pizza_sabor(id_pizza, id_sabor) VALUES (:pizza, :sabor)");


		//repetição ate terminar de salvar todos os sabores
		foreach ($sabores as $sabor) {	
		$stmt->bindParam(":pizza", $pizzaId, PDO::PARAM_INT);
		$stmt->bindParam(":sabor", $sabor, PDO::PARAM_INT);
		$stmt->execute();
	   }

	//criar pedido
	 $stmt = $conn->prepare("INSERT INTO pedidos(id_pizza,id_status) VALUES (:pizza, :status)");
	 $statusId = 1;//em preparo
	 $stmt->bindParam(":pizza",$pizzaId);
	 $stmt->bindParam(":status",$statusId);
	 $stmt->execute();

	 

	 //validar pedido
	 $_SESSION['msg'] = "Pedido realizado com sucesso";
	 $_SESSION['status'] = "success";

	}header("Location: ..");//retorna a pagina inicial
	
}

//criação do pedido

?>