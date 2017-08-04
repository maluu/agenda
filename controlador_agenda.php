<?php

function cadastrar($nome,$email,$telefone){  //função cadastrar um contato

    $contatosAuxiliar = pegarContatos();  //decodifica o arquivo "contatos.json" e retorna todos os contatos

    $contato = [  //a variável $contato recebe os parâmetros do formulário
        'id'      => uniqid(),
        'nome'    => $nome,
        'email'   => $email,
        'telefone'=> $telefone
    ];

    array_push($contatosAuxiliar, $contato);  //array_push pega o $contato e coloca no final do $contatosAuxiliar

    atualizarArquivo($contatosAuxiliar);//atualizo o arquivo
}


function pegarContatos($valor_buscado = null){  //função pegarContatos pega os contatos do arquivo contatos.json

    if ($valor_buscado == null){

        $contatosAuxiliar = file_get_contents('contatos.json');  //file_get_contents pega arquivo "contatos.json"

        $contatosAuxiliar = json_decode($contatosAuxiliar, true); //json_decode decodifica o arquivo

        return $contatosAuxiliar; //return retorna o arquivo
    } else {
        return buscarContato($valor_buscado);
    }
}


function excluirContato($id){  //função excluir contatos

    $contatosAuxiliar = pegarContatos(); //chamo a função para pegar os contatos

    foreach ($contatosAuxiliar as $posicao => $contato){ //para cada contatoAuxiliar, eu pego o dado do contato na posição que está

        if($id == $contato['id']) {      //se a a variável id (['id']) do contato é igual a variável id que estou procurando

            unset($contatosAuxiliar[$posicao]);  //excluir os dados do contato pelo id
        }
    }

    atualizarArquivo($contatosAuxiliar);
}

function editarContato($id){ //função editar o contato

    $contatosAuxiliar = pegarContatos();  //pegar os contatos

    foreach ($contatosAuxiliar as $contato){  //para cada contatoAuxiliar como contato

        if ($contato['id'] == $id){   //se e o id do contato é o mesmo que estou procurando

            return $contato;  //retornar o contato com seus dados
        }
    }
}

function salvarContatoEditado($id){  //função salvar o contato editado

    $contatosAuxiliar = pegarContatos();  //pegar os contatos

    foreach ($contatosAuxiliar as $posicao => $contato){  //para cada contatoAuxiliar como a posição do array contato

        if ($contato['id'] == $id){  //se o id do contato é o id é o mesmo então, editar os dados do contato

            $contatosAuxiliar[$posicao]['nome'] = $_POST['nome'];
            $contatosAuxiliar[$posicao]['email'] = $_POST['email'];
            $contatosAuxiliar[$posicao]['telefone'] = $_POST['telefone'];
            break;
        }
    }

    atualizarArquivo($contatosAuxiliar);  //atualiza o arquivo
}

function atualizarArquivo($contatosAuxiliar){  //função atualizar o arquivo

    $contatosJson = json_encode($contatosAuxiliar, JSON_PRETTY_PRINT);  //o arquivo "contatos.json" é codificado

    file_put_contents('contatos.json', $contatosJson);//recebe todos os dados de usuário no arquivo "contatos.json", substituindo anteriores
    ;
    header("Location: index.phtml");  //encaminha para página inicial
}

function buscarContato($nome){  //função buscar contato pelo nome;

    $contatosAuxiliar = pegarContatos(); //pegar os contatos;

    $contatosEncontrados = [];


    foreach ($contatosAuxiliar as $contato){  //para cada contatoAuxiliar como contato

        if ($contato['nome'] == $nome){  //se e o id do contato é o mesmo

            $contatosEncontrados[] = $contato;  //retornar o contato com seus dados
        }
    }

    return $contatosEncontrados;
}
//ROTAS
switch($_GET['acao']){
    case "cadastrar":
    cadastrar($_POST['nome'],$_POST['email'],$_POST['telefone']);
        break;
    case "editar":
        salvarContatoEditado($_POST['id']);
        break;
    case "excluir":
        excluirContato($_GET['id']);
        break;
}