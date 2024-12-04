<script>
    const CREATED_ACTION = "created"
    const UPDATED_ACTION = "updated"
    const DELETED_ACTION = "deleted"

    const CREATED_MESSAGE = "新規作成に成功しました"
    const UPDATED_MESSAGE = "正常に更新されました"
    const DELETED_MESSAGE = "正常に削除されました"

    $(document).ready(() => {
        if (localStorage.getItem('update-success')) {
            showActionToast({
                action: UPDATED_ACTION
            })

            localStorage.removeItem('update-success');
        }

        if (localStorage.getItem('create-success')) {
            showActionToast({
                action: CREATED_ACTION
            })

            localStorage.removeItem('create-success');
        }
    })

    const showActionToast = ({
        heading = '成功',
        icon = 'success',
        position = 'top-right',
        action = CREATED_ACTION
    }) => {
        const toastMessage = getToastMessage(action) || '正常に実行されました';
        $.toast({
            heading: heading,
            text: toastMessage,
            icon: icon,
            position: position
        });
    }

    const getToastMessage = (action) => {
        switch (action) {
            case CREATED_ACTION:
                return CREATED_MESSAGE;
            case UPDATED_ACTION:
                return UPDATED_MESSAGE;
            case DELETED_ACTION:
                return DELETED_MESSAGE;
            default:
                return null;
        }
    }

</script>