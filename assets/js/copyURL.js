const copyButtons = document.getElementsByClassName('copy-button');
const copyText = document.getElementsByClassName('copy-text');

for (let i = 0; i < copyButtons.length; i++) {
    copyButtons[i].addEventListener('click', function () {
        copyText[i].select();
        // const text = copyText[i].textContent;
        document.execCommand('copy');
        alert('URL copiée !');
    });
}
