<?php
session_start();
include('../includes/conexao.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
	header('Location: login.php');
	exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Adiciona nova tarefa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['adicionar'])) {
	$descricao = $_POST['descricao'];
	if (!empty($descricao)) {
		$sql = "INSERT INTO tarefas (descricao, usuario_id) VALUES (?, ?)";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("si", $descricao, $usuario_id);
		$stmt->execute();
		// Redireciona para evitar reenvio do formulário ao atualizar a página
		header('Location: ' . $_SERVER['PHP_SELF']);
		exit();
	}
}

// Atualiza tarefa
if (isset($_POST['atualizar'])) {
	$id = $_POST['id'];
	$descricao = $_POST['descricao'];
	$sql = "UPDATE tarefas SET descricao = ? WHERE id = ? AND usuario_id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("sii", $descricao, $id, $usuario_id);
	$stmt->execute();
}

// Marca tarefa como completa
if (isset($_POST['completar'])) {
	$id = $_POST['id'];
	$sql = "UPDATE tarefas SET completa = !completa WHERE id = ? AND usuario_id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("ii", $id, $usuario_id);
	$stmt->execute();
}

// Exclui tarefa
if (isset($_POST['excluir'])) {
	$id = $_POST['id'];
	$sql = "DELETE FROM tarefas WHERE id = ? AND usuario_id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("ii", $id, $usuario_id);
	$stmt->execute();
}

// Busca todas as tarefas do usuário
$sql = "SELECT * FROM tarefas WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$tarefas = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<title>Lista de Tarefas</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<script src="https://cdn.tailwindcss.com"></script>
	<script>
		// Função para abrir o modal e preencher os campos
		function openEditModal(id, descricao) {
			document.getElementById('edit-tarefa-id').value = id;
			document.getElementById('edit-tarefa-descricao').value = descricao;
			document.getElementById('edit-modal').classList.remove('hidden');
		}

		// Função para fechar o modal
		function closeEditModal() {
			document.getElementById('edit-modal').classList.add('hidden');
		}
	</script>
</head>

<?php include('../includes/navbar.php'); ?>

<body class="bg-gray-100 flex flex-col min-h-screen">
	<div class="flex-grow flex items-center justify-center">
		<div class="w-96 rounded-lg bg-slate-900 shadow-lg p-8">
			<form method="POST">
				<div class="flex flex-col gap-4">
					<p class="text-center text-3xl text-gray-300 mb-4">Minhas Tarefas</p>
					<div class="flex items-center gap-2">
						<input type="text" name="descricao" placeholder="Nova tarefa" required class="bg-slate-900 flex-1 rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 focus:ring-offset-gray-800 text-gray-300">
						<button type="submit" name="adicionar" class="group flex justify-center p-2 rounded-md drop-shadow-xl bg-[#115e59] from-gray-800 to-black text-white font-semibold transition-all duration-500 hover:from-[#115e59] hover:to-[#134e4a] items-center">
							<i class="fa-solid fa-plus"></i>
							<span class="absolute opacity-0 group-hover:opacity-100 group-hover:text-gray-300 group-hover:text-sm group-hover:-translate-y-10 duration-700">
								Adicionar
							</span>
						</button>
					</div>
				</div>
			</form>

			<ul class="mt-4">
				<?php foreach ($tarefas as $tarefa) : ?>
					<li class="mb-2">
						<div class="flex flex-col gap-2 w-full sm:w-72 text-[10px] sm:text-xs z-50">
							<div class="succsess-alert cursor-default flex items-center justify-between w-full h-10 sm:h-14 rounded-lg bg-[#232531] px-[10px]">
								<div class="flex gap-2">
									<div>
										<form method="POST" class="flex items-center gap-2">
											<input type="hidden" name="id" value="<?php echo $tarefa['id']; ?>">
											<input type="text" name="descricao" value="<?php echo $tarefa['descricao']; ?>" <?php echo $tarefa['completa'] ? 'class="w-full h-12 sm:h-14 rounded-lg bg-[#232531] px-[10px] text-gray-300 text-xl line-through"' : ''; ?> class="cursor-default w-full h-12 sm:h-14 rounded-lg bg-[#232531] px-[10px] text-gray-300 text-xl" readonly onfocus="this.blur();">
											<button type="button" onclick="openEditModal(<?php echo $tarefa['id']; ?>, '<?php echo htmlspecialchars($tarefa['descricao']); ?>')" class="text-yellow-400 hover:text-yellow-500 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline text-sm"><i class="fa-regular fa-pen-to-square"></i></button>
											<button type="submit" name="completar" class="text-green-500 hover:text-green-600 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline text-sm"><?php echo $tarefa['completa'] ? '<i class="fa-regular fa-circle-xmark"></i>' : '<i class="fa-solid fa-check"></i>'; ?></button>
											<button type="submit" name="excluir" class="text-red-500 hover:text-red-600 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline text-sm"><i class="fa-regular fa-trash-can"></i></button>
										</form>
									</div>
								</div>
							</div>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>


	<!-- Modal de Edição -->
	<div id="edit-modal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
		<div class="bg-white p-4 rounded-lg w-96">
			<h2 class="text-xl font-semibold mb-4">Editar Tarefa</h2>
			<form method="POST" action="atualizar_tarefa.php">
				<input type="hidden" id="edit-tarefa-id" name="id">
				<div class="mb-4">
					<label for="edit-tarefa-descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
					<input type="text" id="edit-tarefa-descricao" name="descricao" class="mt-1 block w-full h-12 sm:h-14 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 sm:text-sm">
				</div>
				<div class="flex justify-end">
					<button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded-md mr-2">Cancelar</button>
					<button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded-md">Salvar</button>
				</div>
			</form>
		</div>
	</div>

	<script src="../assets/script.js"></script>
</body>

</html>