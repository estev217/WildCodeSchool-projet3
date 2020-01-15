const imageInput = document.getElementById('image_imageFile');

imageInput.addEventListener('change',function (e) {
    const fileName = imageInput.files[0].name;
    const nextSibling = e.target.nextElementSibling;
    nextSibling.innerText = fileName;
});
