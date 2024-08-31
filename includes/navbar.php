<?php

include('../includes/conexao.php');

// Inicializa a variável $nome como nula
$nome = null;

// Verifica se o usuário está logado e recupera o nome do banco de dados
if (isset($_SESSION['usuario_id'])) {
	$usuario_id = $_SESSION['usuario_id'];

	// Consulta para pegar o nome do usuário pelo ID
	$sql = "SELECT nome FROM usuarios WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $usuario_id);
	$stmt->execute();
	$stmt->bind_result($nome);
	$stmt->fetch();
	$stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<title>HTML + CSS</title>
	<script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
	<nav class="bg-teal-800">
		<div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
			<div class="relative flex h-16 items-center justify-between">
				<div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
					<!-- Mobile menu button -->
					<button type="button" class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-teal-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
						<span class="absolute -inset-0.5"></span>
						<span class="sr-only">Open main menu</span>
						<svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
							<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
						</svg>
						<svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
							<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
						</svg>
					</button>
				</div>

				<div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
					<div class="hidden sm:ml-6 sm:block">
						<div class="flex space-x-4">
							<?php if (!isset($_SESSION['usuario_id'])) : ?>
								<!-- Exibir "Inicio" se o usuário NÃO estiver logado -->
								<a href="../index.html" class="rounded-md bg-teal-900 px-3 py-2 text-sm font-medium text-white" aria-current="page">Inicio</a>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
					<?php if ($nome !== null) : ?>
						<!-- Exibir dropdown com nome do usuário se o usuário estiver logado -->
						<div class="relative ml-3">
							<details class="dropdown" style="position: relative">
								<summary class="text-white cursor-pointer rounded-full bg-teal-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-teal-800" style="list-style: none">
									<span class="sr-only">Open user menu</span>
									<span>Olá, <?php echo htmlspecialchars($nome); ?></span>
								</summary>
								<ul class="menu dropdown-content bg-gray-100 rounded-box z-[1] w-52 p-2 shadow mt-2" style="position: absolute; right: 0">
									<li>
										<a href="logout.php" class="block px-4 py-2 text-sm text-gray-700">Sign out</a>
									</li>
								</ul>
							</details>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<!-- Mobile menu, show/hide based on menu state. -->
		<div class="sm:hidden" id="mobile-menu">
			<div class="space-y-1 px-2 pb-3 pt-2">
				<?php if (!isset($_SESSION['usuario_id'])) : ?>
					<!-- Exibir "Inicio" se o usuário NÃO estiver logado no menu mobile -->
					<a href="#" class="block rounded-md bg-teal-900 px-3 py-2 text-base font-medium text-white" aria-current="page">Inicio</a>
				<?php endif; ?>
			</div>
		</div>
	</nav>
</body>

</html>