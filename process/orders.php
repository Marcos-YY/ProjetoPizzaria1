<?php
//ações de pedido

include_once("conn.php");

$method = $_SERVER['REQUEST_METHOD'];

if($method === "GET"){

$pedidosQuery = $conn->query("SELECT * FROM pedidos;");
$pedidos = $pedidosQuery->fetchAll();

$pizzas = [];

//Montando pizza
foreach ($pedidos as $pedido) {
	$pizza = [];

	//definir array para a pizza
	$pizza["id"] = $pedido["id_pizza"];

	//resgatando pizza
	$pizzaQuery = $conn->prepare("SELECT * FROM pizza where id = :id_pizza");
	$pizzaQuery->bindParam(":id_pizza", $pizza["id"]);
	$pizzaQuery->execute();
	$pizzaData = $pizzaQuery->fetch(PDO::FETCH_ASSOC);

	//resgatando borda-------------------------------------------------------------
	$bordasQuery = $conn->prepare("SELECT * FROM bordas where id = :id_borda");
	$bordasQuery->bindParam(":id_borda", $pizzaData["id_borda"]);
	$bordasQuery->execute();
	$bordas = $bordasQuery->fetch(PDO::FETCH_ASSOC);
	$pizza["bordas"] = $bordas["tipo"];

	//resgatando massa-------------------------------------------------------------
	$massasQuery = $conn->prepare("SELECT * FROM massas where id = :id_massas");
	$massasQuery->bindParam(":id_massas", $pizzaData["id_massa"]);
	$massasQuery->execute();
	$massas = $massasQuery->fetch(PDO::FETCH_ASSOC);
	$pizza["massas"] = $massas["tipo"];

	//resgatando sabor-----------------------------------------------------------------------
	$saboresQuery = $conn->prepare("SELECT * FROM pizza_sabor where id_pizza = :id_pizza");
	$saboresQuery->bindParam(":id_pizza", $pizza["id"]);
	$saboresQuery->execute();
	$sabores = $saboresQuery->fetchAll(PDO::FETCH_ASSOC);

	//resgatando nome do sabor---------------------------------------------------------------
	$saboresDaPizza = [];
	$saborQuery = $conn->prepare("SELECT * FROM sabores where id = :id_sabor");

	foreach ($sabores as $sabor) {
		$saborQuery->bindParam(":id_sabor",$sabor["id_sabor"]);
		$saborQuery->execute();
		$saborPizza = $saborQuery->fetch(PDO::FETCH_ASSOC);
		array_push($saboresDaPizza, $saborPizza["nome"]);
	}
	$pizza["sabores"] = $saboresDaPizza;

	// adicionar o status do pedido-----------------------
    $pizza["status"] = $pedido["id_status"];
    // Adicionar o array de pizza, ao array das pizzas
    array_push($pizzas, $pizza);

    // Resgatando os status-------------------------------
    $statusQuery = $conn->query("SELECT * FROM status;");
    $status = $statusQuery->fetchAll();
}

} else if($method === "POST"){

	// verificando tipo de POST
	$type = $_POST["tipo"];

	//deletar pedido
	if($type === "delete"){//Removendo pedido----------------------------------------------------------------

		$pizzaId = $_POST["id"];

		$deleteQuery = $conn->prepare("DELETE FROM pedidos WHERE id_pizza = :id_pizza;");
		$deleteQuery->bindParam(":id_pizza", $pizzaId, PDO::PARAM_INT);
		$deleteQuery->execute();

		$_SESSION["msg"] = "Pedido removido com sucesso!";
		$_SESSION["status"] = "success";

	}else if($type === "update") {//atualizando o status no sistema-------------------------------------------

      $pizzaId = $_POST["id"];
      $statusId = $_POST["status"];

      $updateQuery = $conn->prepare("UPDATE pedidos SET id_status = :id_status WHERE id_pizza = :id_pizza");

      $updateQuery->bindParam(":id_pizza", $pizzaId, PDO::PARAM_INT);
      $updateQuery->bindParam(":id_status", $statusId, PDO::PARAM_INT);

      $updateQuery->execute();

      $_SESSION["msg"] = "Pedido atualizado com sucesso!";
      $_SESSION["status"] = "success";

    }



	//retornar usuario pro dashboard
	header("Location: ../dashboard.php");

}


?>