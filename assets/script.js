
function validarSenha() {
	const senha = document.getElementById('senha').value;
	const regex = /^(?=.*[A-Z])(?=.*[\W]).{6,}$/;
		if (!regex.test(senha)) {
			alert('A senha deve ter no mínimo 6 caracteres, incluindo uma letra maiúscula e um caractere especial!');
			return false;
			}
		return true;
		}

