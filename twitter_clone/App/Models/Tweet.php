<?php 

namespace App\Models;

use MF\Model\Model;

class Tweet extends Model {
	private $id;
	private $id_usuario;
	private $tweet;
	private $data;

	//Como os atributos são privados precisamos usar os métodos mágicos para ter acesso a eles.

	//o __get() irá recuperar o atributo passado como parâmetro.
	//Não se esqueça de usar o $ nesses casos. Ele indica que estamos trabalhando com uma variável.
	public function __get($atributo) {
		return $this->$atributo;
	}

	//o set irá atribuir valores ao atributo passado como primeiro parâmetro.
	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}

	public function salvar() {

		$query = "insert into tweets(id_usuario, tweet) values(:id_usuario, :tweet)";

		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
		$stmt->bindValue(':tweet', $this->__get('tweet'));

		$stmt->execute();

		return $this;
	}

	//recuperar
	public function getAll() {

		$query = "
			select 
				t.id, 
				t.id_usuario, 
				u.nome, 
				t.tweet, 
				DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') as data
			from 
				tweets as t
				left join usuarios as u on (t.id_usuario = u.id) 
			where 
				id_usuario = :id_usuario
				or t.id_usuario in (select id_usuario_seguindo from usuarios_seguidores where id_usuario = :id_usuario)
			order by 
				t.data desc
		";

		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	//recuperar com paginação.
	public function getPorPagina($limit, $offset) {

		$query = "
			select 
				t.id, 
				t.id_usuario, 
				u.nome, 
				t.tweet, 
				DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') as data
			from 
				tweets as t
				left join usuarios as u on (t.id_usuario = u.id) 
			where 
				id_usuario = :id_usuario
				or t.id_usuario in (select id_usuario_seguindo from usuarios_seguidores where id_usuario = :id_usuario)
			order by 
				t.data desc
			limit 
				$limit
			offset 
				$offset
		";

		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	//recuperar total de tweets
	public function getTotalRegistros() {

		$query = "
			select 
				count(*) as total
			from 
				tweets as t
				left join usuarios as u on (t.id_usuario = u.id) 
			where 
				id_usuario = :id_usuario
				or t.id_usuario in (select id_usuario_seguindo from usuarios_seguidores where id_usuario = :id_usuario)			
		";

		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	public function remover($id_tweet) {

		$query = 'delete from tweets where id = :id_tweet';

		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_tweet', $id_tweet);
		$stmt->execute();

		return true;
		
	}
}

?>