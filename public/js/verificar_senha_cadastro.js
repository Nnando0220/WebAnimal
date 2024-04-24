document.addEventListener('DOMContentLoaded', function () {
    const senhaInput = document.getElementById('senha_cadastro');

    senhaInput.addEventListener('input', function () {
        const senha = senhaInput.value;

        const lowercaseRegex = /[a-z]/;
        const uppercaseRegex = /[A-Z]/;
        const digitRegex = /\d/;
        const specialCharRegex = /[@#$%^&+=!]/;

        const isLowercaseValid = lowercaseRegex.test(senha);
        const isUppercaseValid = uppercaseRegex.test(senha);
        const isDigitValid = digitRegex.test(senha);
        const isSpecialCharValid = specialCharRegex.test(senha);
        const isLengthValid = senha.length >= 8;

        updateIcon('lowercaseIcon', isLowercaseValid);
        updateIcon('uppercaseIcon', isUppercaseValid);
        updateIcon('numberIcon', isDigitValid);
        updateIcon('specialCharIcon', isSpecialCharValid);
        updateIcon('minCaracteres', isLengthValid);
    });
});

function updateIcon(iconId, isValid) {
    const iconElement = document.getElementById(iconId);

    if (isValid) {
        iconElement.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
    } else {
        iconElement.innerHTML = '<i class="fas fa-times-circle text-danger"></i>';
    }
}
