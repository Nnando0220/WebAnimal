function displaySelectedFiles(input) {
    const files = input.files;
    const postId = input.id.split('_')[2];
    let selectedFilesDiv;

    if (files.length === 0) {
        selectedFilesDiv = document.getElementById('selectedFiles');
        selectedFilesDiv.style.display = 'none';
        return;
    }

    if (!postId) {
        selectedFilesDiv = document.getElementById('selectedFiles');
        styleSelectorImages(selectedFilesDiv, files);
    } else {
        selectedFilesDiv = document.getElementById('selectedFiles_' + postId);
        styleSelectorImages(selectedFilesDiv, files);
    }
}

function styleSelectorImages(selectedFilesDiv, files) {
    const selectorImages = document.getElementsByClassName('seletor_images');
    selectedFilesDiv.style.display = 'block';
    selectedFilesDiv.innerHTML = '';
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        selectedFilesDiv.innerHTML += `<p>${file.name}</p>`;
    }
}
