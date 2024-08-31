<?php
session_start();
include('../includes/conexao.php');


$mensagem = ''; // Inicializa a variável de mensagem

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$nome = $_POST['nome'];
	$email = $_POST['email'];
	$senha = $_POST['senha'];

	// Validação da senha
	if (strlen($senha) < 6 || !preg_match('/[A-Z]/', $senha) || !preg_match('/[\W]/', $senha)) {
		$mensagem = "A senha deve ter no mínimo 6 caracteres, incluindo uma letra maiúscula e um caractere especial!";
	} else {
		$senha_hashed = password_hash($senha, PASSWORD_DEFAULT);

		// Verifica se o email já está cadastrado
		$sql = "SELECT id FROM usuarios WHERE email = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("s", $email);
		$stmt->execute();
		$stmt->store_result();

		if ($stmt->num_rows > 0) {
			$mensagem = "Email já cadastrado!";
		} else {
			$sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("sss", $nome, $email, $senha_hashed);

			if ($stmt->execute()) {
				$mensagem = "Cadastro realizado com sucesso!";
			} else {
				$mensagem = "Erro ao cadastrar!";
			}
		}

		$stmt->close();
	}
}
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://cdn.tailwindcss.com"></script>
	<link rel="stylesheet" href="../assets/style.css">
	<title>Cadastro</title>
</head>

<?php include('../includes/navbar.php'); ?>

<body>
	<main class="flex flex-col items-center gap-8 py-16 max-w-[1280px] mx-auto">
		<div class="relative flex w-96 flex-col rounded-xl bg-white bg-clip-border text-gray-700 shadow-md">
			<div class="relative mx-4 -mt-6 mb-4 grid h-28 place-items-center overflow-hidden rounded-xl bg-gradient-to-tr from-teal-400 to-teal-500 bg-clip-border text-white shadow-lg shadow-teal-600/40">
				<h3 class="block font-sans text-3xl font-semibold leading-snug tracking-normal text-white antialiased">
					Sign Up
				</h3>
			</div>
			<div class="flex flex-col gap-4 p-6">
				<form method="POST">
					<div class="relative h-11 w-full min-w-[200px] mb-4">
						<input type="text" id="nome" name="nome" placeholder="" class="peer h-full w-full rounded-md border border-teal-300 border-t-transparent bg-transparent px-3 py-3 font-sans text-sm font-normal text-teal-700 outline outline-0 transition-all placeholder-shown:border placeholder-shown:border-teal-200 placeholder-shown:border-t-teal-200 focus:border-2 focus:border-teal-500 focus:border-t-transparent focus:outline-0 disabled:border-0 disabled:bg-teal-50" required />
						<label for="nome" class="before:content[' '] after:content[' '] pointer-events-none absolute left-0 -top-1.5 flex h-full w-full select-none text-[11px] font-normal leading-tight text-teal-400 transition-all before:pointer-events-none before:mt-[6.5px] before:mr-1 before:box-border before:block before:h-1.5 before:w-2.5 before:rounded-tl-md before:border-t before:border-l before:border-teal-200 before:transition-all after:pointer-events-none after:mt-[6.5px] after:ml-1 after:box-border after:block after:h-1.5 after:w-2.5 after:flex-grow after:rounded-tr-md after:border-t after:border-r after:border-teal-200 after:transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:leading-[4.1] peer-placeholder-shown:text-teal-500 peer-placeholder-shown:before:border-transparent peer-placeholder-shown:after:border-transparent peer-focus:text-[11px] peer-focus:leading-tight peer-focus:text-teal-500 peer-focus:before:border-t-2 peer-focus:before:border-l-2 peer-focus:before:!border-teal-500 peer-focus:after:border-t-2 peer-focus:after:border-r-2 peer-focus:after:!border-teal-500 peer-disabled:text-transparent peer-disabled:before:border-transparent peer-disabled:after:border-transparent peer-disabled:peer-placeholder-shown:text-teal-500">
							Nome
						</label>
					</div>
					<div class="relative h-11 w-full min-w-[200px] mb-4">
						<input type="email" id="email" name="email" placeholder="" class="peer h-full w-full rounded-md border border-teal-300 border-t-transparent bg-transparent px-3 py-3 font-sans text-sm font-normal text-teal-700 outline outline-0 transition-all placeholder-shown:border placeholder-shown:border-teal-200 placeholder-shown:border-t-teal-200 focus:border-2 focus:border-teal-500 focus:border-t-transparent focus:outline-0 disabled:border-0 disabled:bg-teal-50" required />
						<label class="before:content[' '] after:content[' '] pointer-events-none absolute left-0 -top-1.5 flex h-full w-full select-none text-[11px] font-normal leading-tight text-teal-400 transition-all before:pointer-events-none before:mt-[6.5px] before:mr-1 before:box-border before:block before:h-1.5 before:w-2.5 before:rounded-tl-md before:border-t before:border-l before:border-teal-200 before:transition-all after:pointer-events-none after:mt-[6.5px] after:ml-1 after:box-border after:block after:h-1.5 after:w-2.5 after:flex-grow after:rounded-tr-md after:border-t after:border-r after:border-teal-200 after:transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:leading-[4.1] peer-placeholder-shown:text-teal-500 peer-placeholder-shown:before:border-transparent peer-placeholder-shown:after:border-transparent peer-focus:text-[11px] peer-focus:leading-tight peer-focus:text-teal-500 peer-focus:before:border-t-2 peer-focus:before:border-l-2 peer-focus:before:!border-teal-500 peer-focus:after:border-t-2 peer-focus:after:border-r-2 peer-focus:after:!border-teal-500 peer-disabled:text-transparent peer-disabled:before:border-transparent peer-disabled:after:border-transparent peer-disabled:peer-placeholder-shown:text-teal-500">
							Email
						</label>
					</div>
					<div class="relative h-11 w-full min-w-[200px]">
						<input type="password" id="senha" name="senha" placeholder="" class="peer h-full w-full rounded-md border border-teal-300 border-t-transparent bg-transparent px-3 py-3 font-sans text-sm font-normal text-teal-700 outline outline-0 transition-all placeholder-shown:border placeholder-shown:border-teal-200 placeholder-shown:border-t-teal-200 focus:border-2 focus:border-teal-500 focus:border-t-transparent focus:outline-0 disabled:border-0 disabled:bg-teal-50" required />
						<label class="before:content[' '] after:content[' '] pointer-events-none absolute left-0 -top-1.5 flex h-full w-full select-none text-[11px] font-normal leading-tight text-teal-400 transition-all before:pointer-events-none before:mt-[6.5px] before:mr-1 before:box-border before:block before:h-1.5 before:w-2.5 before:rounded-tl-md before:border-t before:border-l before:border-teal-200 before:transition-all after:pointer-events-none after:mt-[6.5px] after:ml-1 after:box-border after:block after:h-1.5 after:w-2.5 after:flex-grow after:rounded-tr-md after:border-t after:border-r after:border-teal-200 after:transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:leading-[4.1] peer-placeholder-shown:text-teal-500 peer-placeholder-shown:before:border-transparent peer-placeholder-shown:after:border-transparent peer-focus:text-[11px] peer-focus:leading-tight peer-focus:text-teal-500 peer-focus:before:border-t-2 peer-focus:before:border-l-2 peer-focus:before:!border-teal-500 peer-focus:after:border-t-2 peer-focus:after:border-r-2 peer-focus:after:!border-teal-500 peer-disabled:text-transparent peer-disabled:before:border-transparent peer-disabled:after:border-transparent peer-disabled:peer-placeholder-shown:text-teal-500">
							Senha
						</label>
					</div>
					<div class="form-control mt-6">
						<button data-ripple-light="true" type="submit" class="block w-full select-none rounded-lg bg-gradient-to-tr from-teal-500 to-teal-400 py-3 px-6 text-center align-middle font-sans text-xs font-bold uppercase text-white shadow-md shadow-teal-500/20 transition-all hover:shadow-lg hover:shadow-teal-500/40 active:opacity-[0.85] disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
							Cadastrar
						</button>
					</div>
				</form>
			</div>
			<div class="p-6 pt-0">
				<p class="mt-6 flex justify-center font-sans text-sm font-light leading-normal text-inherit antialiased">
					Já tem conta?
					<a class="ml-1 block font-sans text-sm font-bold leading-normal text-teal-500 antialiased" href="login.php">
						Faça login!
					</a>
				</p>
			</div>
		</div>
	</main>

	<!-- Modal -->
	<div id="feedbackModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
		<div class="bg-white rounded-lg shadow-lg w-96">
			<div class="p-4">
				<p class="mt-2"><?php echo htmlspecialchars($mensagem); ?></p>
				<div class="mt-4 flex justify-end">
					<button id="closeModal" class="bg-teal-500 text-white px-4 py-2 rounded-lg">Fechar</button>
				</div>
			</div>
		</div>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			var feedbackModal = document.getElementById('feedbackModal');
			var closeModalButton = document.getElementById('closeModal');

			<?php if ($mensagem != '') : ?>
				feedbackModal.classList.remove('hidden');
			<?php endif; ?>

			closeModalButton.addEventListener('click', function() {
				feedbackModal.classList.add('hidden');
			});
		});
	</script>

	<script src="../assets/script.js"></script>
</body>

</html>