function deleteUser(id){
    Swal.fire({
        title: "Deletar conta do usuário?",
        text: "Você tem certeza que deseja deletar o usuário? Essa ação é permanente.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, excluir!",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            const url = '/admin/deletar-usuario/' + id;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'DELETE',
                url: url,
                data: {
                    userId: id,
                },
                dataType: 'json',
                success: function () {
                    Swal.fire({
                        title: "Sucesso!",
                        text: "Usuário deletado com sucesso!",
                        icon: "success",
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        title: "Erro!",
                        text: "Deleção do usuario não concluída!"+error,
                        icon: "warning",
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        } else {

        }
    });
}

function deleteUserPost(userId, postId){
    Swal.fire({
        title: "Deletar post do usuário?",
        text: "Você tem certeza que deseja deletar o post? Esta ação é permanente.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, excluir!",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            const url = '/admin/deletar-post/' + userId  + '/' + postId;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'DELETE',
                url: url,
                data: {
                    postId: postId,
                },
                dataType: 'json',
                success: function () {
                    Swal.fire({
                        title: "Sucesso!",
                        text: "Post deletado com sucesso!",
                        icon: "success",
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        title: "Erro!",
                        text: "Deleção do post não concluída!"+error,
                        icon: "warning",
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        } else {

        }
    });
}
