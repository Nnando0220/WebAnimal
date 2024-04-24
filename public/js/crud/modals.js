document.addEventListener('DOMContentLoaded', function () {
    const exampleModal = document.getElementById('exampleModal');

    if (exampleModal) {
        const urlParams = new URLSearchParams(window.location.search);
        const modalEditParam = urlParams.get('modalEdit');

        if (window.location.search.includes('modal=opened')) {
            const myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
            myModal.show();
            $(exampleModal).on('hidden.bs.modal', function () {
                const newUrl = window.location.href.replace('?modal=opened', '');
                window.history.replaceState(null, null, newUrl);
            });
        }
        if (modalEditParam === 'opened') {
            const userIdParam = urlParams.get('userId');
            const postIdParam = urlParams.get('postId');
            if (userIdParam) {
                openEditModalUser(userIdParam);
            }
            if (postIdParam) {
                openEditModalPost(postIdParam);
            }
        }
    }
});

function openEditModalUser(identifier) {
    const exampleEditModal = new bootstrap.Modal(document.getElementById('exampleModalEdit_' + identifier));
    const exampleModal = document.getElementById('exampleModalEdit_' + identifier);
    exampleEditModal.show();
    $(exampleModal).on('hidden.bs.modal', function () {
        const newUrl = window.location.href.replace(`?modalEdit=opened&userId=${identifier}`, '');
        window.history.replaceState(null, null, newUrl);
    });
}

function openEditModalPost(identifier) {
    const exampleEditModal = new bootstrap.Modal(document.getElementById('exampleModalEdit_' + identifier));
    const exampleModal = document.getElementById('exampleModalEdit_' + identifier);
    exampleEditModal.show();
    $(exampleModal).on('hidden.bs.modal', function () {
        const newUrl = window.location.href.replace(`?modalEdit=opened&postId=${identifier}`, '');
        window.history.replaceState(null, null, newUrl);
    });
}
