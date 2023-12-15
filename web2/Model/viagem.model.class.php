<?php
require_once('./../Model/db.model.php');
require_once('./../Model/usuario.model.class.php');
require_once('./../Model/imagem.class.php');

class Viagem{
    private $id;
    private $nome;
    private $descricao;
    private $caminho_imagem;

    function __get($name){
        return $this->$name;
    }

    function __set($name,$value){
        $this->$name = $value;
    }

    function __construct($nome, $descricao=null, $caminho_imagem=null){
        $this->nome = $nome;
        $this->descricao = ($descricao == '' ? null : $descricao);
        $this->caminho_imagem = $caminho_imagem == '' ? null : $caminho_imagem;
    }

    static function getAll(){
        $sql = "SELECT * FROM viagens ORDER BY nome";
        $listaFinal = array();

        $con = conectar();
        $query = $con->prepare($sql);
        $query->execute();

        while ( $reg = $query->fetch() ) {
            $viagem = new Viagem(
                $reg['nome'], $reg['descricao'], $reg['caminho_imagem']);

            $listaFinal[] = $viagem; 
        }

        return $listaFinal;
    }
    if (empty($_POST["nome"])) {
        $erro["nome"] = "O nome é obrigatório.";
    } else {
        $nome = $_POST["nome"];
    }

    if (empty($_POST["descricao"])) {
        $erro["descricao"] = "A descrição é obrigatória.";
    } else {
        $descricao = $_POST["descricao"];
    }

    if (empty($_FILES["imagem"])) {
        $erro["imagem"] = "A imagem é obrigatória.";
    } else {
                $arquivo = $_FILES["imagem"];
        $extensao = pathinfo($arquivo["name"], PATHINFO_EXTENSION);
        $nome_imagem = uniqid() . "." . $extensao;
        move_uploaded_file($arquivo["tmp_name"], "./uploads/" . $nome_imagem);
        $imagem = "uploads/" . $nome_imagem;
    }

    if (count($erro) > 0) {
        echo "<ul>";
        foreach ($erro as $campo => $mensagem) {
            echo "<li>$campo: $mensagem</li>";
        }
        echo "</ul>";
    } 

    function excluirImagem($id)
{
    $sql = "SELECT imagem FROM viagens WHERE id = $id";
    $resultado = pg_query($GLOBALS["conexao"], $sql);
    if ($resultado) {
        $linha = pg_fetch_assoc($resultado);
        $imagem = $linha["imagem"];
    }

    // Exclui a imagem
    if ($imagem) {
        unlink($imagem);
    }
}





?>