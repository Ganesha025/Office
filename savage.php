return $this->response->setJSON([
    'status' => 'success',
    'student_id' => $student_id,
    'message' => 'Student added successfully'
]);



 <div id="toast"
     class="fixed top-6 right-6 z-50 hidden px-4 py-3 rounded-lg shadow-lg text-white transition-all duration-300">
    <span id="toastMsg"></span>
</div>
<script>
function showToast(msg, type='success'){
    const toast = document.getElementById('toast');
    const text  = document.getElementById('toastMsg');

    toast.classList.remove('hidden','bg-green-600','bg-red-600','bg-yellow-600');
    toast.classList.add(type==='success'?'bg-green-600':type==='error'?'bg-red-600':'bg-yellow-600');

    text.textContent = msg;

    setTimeout(()=> toast.classList.add('opacity-0'), 2500);
    setTimeout(()=>{
        toast.classList.add('hidden');
        toast.classList.remove('opacity-0');
    },3000);
}
</script>

$('#formAddStudent').on('submit', function(e){
    e.preventDefault();

    const form = $(this);

    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        dataType: 'json',
        success: function(res){
            if(res.status === 'success'){
                closeModal();
                showToast(res.message || 'Student added successfully','success');
                form[0].reset();
                $('#formSubmitBtn').prop('disabled', true);
            } else {
                showToast(res.message || 'Something went wrong','error');
            }
        },
        error: function(){
            showToast('Server error. Try again','error');
        }
    });
});

