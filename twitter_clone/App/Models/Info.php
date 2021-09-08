<?php 

/*Como estamos usando namespace, é importante que ao usarmos uma instância de PDO coloquemos uma
barra invertida antes (\), para indicar que que ele é instanciado a partir da raiz do PHP.
Se não fizermos isso quando estivermos usando namespace, ocorrerá um erro.*/
namespace App\Models;

use MF\Model\Model;

class Info extends Model {

	

	//Esse método simplesmente buscará os resultados no banco de dados.
	public function getInfo() {
		$query = "select titulo, descricao from tb_info";

		//o metodo query() prepara e execucta um comando sql.
		//fetchAll retornará o resultado da execução de query().
		return $this->db->query($query)->fetchAll();
	}
}



?>