<?php
session_start();
include('../includes/conexao.php'); // Inclua seu arquivo de conexão

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os dados do formulário
    $id = $_POST['id'];
    $descricao = $_POST['descricao'];

    // Valida os dados (exemplo básico)
    if (empty($id) || empty($descricao)) {
        $_SESSION['mensagem'] = 'Dados inválidos. Por favor, tente novamente.';
        header('Location: tarefas.php'); // Redireciona para a página de tarefas
        exit;
    }

    // Prepara a consulta SQL para atualizar a tarefa
    $sql = "UPDATE tarefas SET descricao = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $descricao, $id);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = 'Tarefa atualizada com sucesso!';
    } else {
        $_SESSION['mensagem'] = 'Erro ao atualizar a tarefa.';
    }

    $stmt->close();
    $conn->close();

    // Redireciona para a página de tarefas
    header('Location: tarefas.php');
    exit;
} else {
    // Se não for um POST, redireciona para a página de tarefas
    header('Location: tarefas.php');
    exit;
}
