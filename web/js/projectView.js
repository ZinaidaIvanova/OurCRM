function currDate() {
    var date = new Date();
    var dd = date.getDate();
    if (dd < 10) {
        dd = '0' + dd;
    }
    var mm = date.getMonth() + 1;
    if (mm < 10) {
        mm = '0' + mm;
    }
    var yy = date.getFullYear();
    return yy + '-' + mm + '-' + dd;
}

$("[name=close-project_form]").change(function() {
    var par = $(this).parent();
    var title = par.children(".serviceset-info-title").text();
    console.log(title.match(/\d+/)[0]);
    var message = {"id": title.match(/\d+/)[0]};
    $.post("/controllers/ProjectController.php", data, onProjectClose, "json");
});

function onProjectClose(response)
{
    
}