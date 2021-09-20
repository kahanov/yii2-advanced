$.ajaxSetup({
    data: {
        _csrf: yii.getCsrfToken()
    }
});