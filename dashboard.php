<?php 
    include_once("templates/header.php");
    include_once("process/orders.php");
?>
    <div id="main-container"> 
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>GERENCIAR PEDIDOS :</h2>
                </div>
                <div class="col-md-12 table-content">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col"><span>Pedido n°</span></th>
                                <th scope="col">Bordas</th>
                                <th scope="col">Massas</th>
                                <th scope="col">Sabores</th>
                                <th scope="col">Status</th>
                                <th scope="col">Ações</th>
                            </tr>   
                        </thead>
                        <tbody><!--descrição/conteudo dos pedidos-->
                            <?php foreach ($pizzas as $pizza): ?>
                                                         <tr>
                                 <td><?= $pizza["id"] ?></td>
                                 <td><?= $pizza["bordas"] ?></td>
                                 <td><?= $pizza["massas"] ?></td>
                                 <td><!--sabores-->
                                     <ul>
                                        <?php foreach ($pizza["sabores"] as $sabor): ?>
                                        <li><?= $sabor; ?></li>
                                        <?php endforeach; ?>
                                     </ul>
                                 </td>
                                 <td>
                                     <form action="process/orders.php" method="POST" class="form-group update-form">
                                         <input type="hidden" name="tipo" value="update">
                                         <input type="hidden" name="id" value="<?= $pizza["id"] ?>">
                                         <select name="status" class="form-control status-input">
                                            <?php foreach ($status as $atual): ?>
                                                
                                             <option value="<?= $atual["id"]?>" <?php echo ($atual["id"] == $pizza["status"]) ? "selected" : "";?>> <?= $atual["tipo"] ?> </option>

                                          <?php endforeach; ?>  
                                          <!--Se o id do status for igual ao da pizza marca como selecionado 
                                                    assim não há problema ao atualizar a pagina-->
                                         </select>
                                         <button type="submit" class="update-btn">
                                             <i class="fas fa-sync-alt"></i>
                                         </button>
                                     </form>
                                 </td>
                                 <td>
                                     <form action="process/orders.php" method="POST">
                                         <input type="hidden" name="tipo" value="delete">
                                         <input type="hidden" name="id" value="<?= $pizza["id"] ?>"><!--remoção dinamica/por id-->
                                         <button type="submit" class="delete-btn">
                                             <i class="fas fa-times"></i>
                                         </button>
                                     </form>
                                 </td>
                             </tr>

                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
