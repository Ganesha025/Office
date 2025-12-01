$(document).ready(function(){
    $('#sidebar a').click(function(e){
        e.preventDefault();
        var task = $(this).data('task');
        $('#content').html('<p>Loading...</p>');
        $('#content').load('tasks/' + task + '.php'); // Load task file dynamically
    });
});
