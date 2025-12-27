<script>
$(function(){
    function vName(n){return/^[A-Za-z\s.]*$/.test(n)&&((n.match(/\./g)||[]).length<=1)&&((n.match(/ /g)||[]).length<=2)&&n.trim()!==''}
    function vForm(){
        let s=$('#inputStudentName').val().trim(),d=$('#department').val(),i=parseInt($('#inputIncidents').val())||0,m=parseInt($('#inputInternalMarks').val())||0,a=parseInt($('#inputAttendance').val())||0,p=parseFloat($('#paidAmount').val())||0,r=parseFloat($('#remainingAmount').val())||0,f=true
        if(!vName(s)||d===''||i>10||i<0||m>50||m<0||a>100||a<0)f=false
        if($('#paidSection').is(':visible')&&(p<0||r<0))f=false
        $('#formSubmitBtn').prop('disabled',!f)
    }
    $('#formAddStudent input,#formAddStudent select').on('input change',vForm)
    $('#inputStudentName').on('keypress',function(e){let c=e.key,t=$(this).val();if(!/^[A-Za-z .]$/.test(c)||(c==='.'&&(t.match(/\./g)||[]).length>=1)||(c===' '&&(t.match(/ /g)||[]).length>=2))e.preventDefault()})
    $('#inputAttendance').on('input',function(){if(this.value>100)this.value=100})
    $('#inputIncidents').on('input',function(){if(this.value>10)this.value=10})
    $('#inputInternalMarks').on('input',function(){if(this.value>50)this.value=50})
    $('<button>',{type:'submit',id:'formSubmitBtn',class:'px-4 py-2 bg-[#1e35a3] text-white rounded hover:bg-indigo-700',text:'Submit',disabled:true}).appendTo('#formAddStudent .flex')
})
let deptSelect=document.getElementById('department'),feeDisplay=document.getElementById('deptFeeDisplay'),paidInput=document.getElementById('paidAmount'),remainingInput=document.getElementById('remainingAmount'),paidSection=document.getElementById('paidSection'),remainingSection=document.getElementById('remainingSection'),departmentFee=0
deptSelect.addEventListener('change',()=>{let o=deptSelect.options[deptSelect.selectedIndex],f=o.getAttribute('data-fee');departmentFee=f?parseFloat(f):0;if(departmentFee>0){feeDisplay.textContent=`Department Fee: ₹${departmentFee.toLocaleString('en-IN',{minimumFractionDigits:2,maximumFractionDigits:2})}`;paidSection.classList.remove('hidden');remainingSection.classList.remove('hidden')}else{feeDisplay.textContent='';paidSection.classList.add('hidden');remainingSection.classList.add('hidden')}paidInput.value='';remainingInput.value=''})
paidInput.addEventListener('input',()=>{let p=paidInput.value.replace(/[^0-9]/g,'');if(p==='')p='0';p=parseInt(p);if(p>departmentFee)p=departmentFee;paidInput.value=p;remainingInput.value=(departmentFee-p).toFixed(2);$('#formAddStudent input,#formAddStudent select').trigger('input')})
paidInput.addEventListener('keypress',e=>{if(e.key==='e'||e.key==='+'||e.key==='-')e.preventDefault()})
remainingInput.addEventListener('keypress',e=>false)
function numbersOnly(el, allowDecimal = false) {
    el.addEventListener('keypress', e => {
        if (['e','E','+','-'].includes(e.key)) e.preventDefault()
        if (!allowDecimal && e.key === '.') e.preventDefault()
        if (!/^\d$/.test(e.key)) e.preventDefault()
    })

    el.addEventListener('paste', e => {
        const t = (e.clipboardData || window.clipboardData).getData('text')
        if (!/^\d+$/.test(t)) e.preventDefault()
    })

    el.addEventListener('input', () => {
        el.value = el.value.replace(/\D/g, '')
    })
}
numbersOnly(document.getElementById('inputAttendance'))
numbersOnly(document.getElementById('inputIncidents'))
numbersOnly(document.getElementById('inputInternalMarks'))
numbersOnly(document.getElementById('paidAmount'))
numbersOnly(document.getElementById('remainingAmount'))

</script>
<script>
const modal=document.getElementById('addStudentModal'),content=modal.querySelector('div');
document.getElementById('openModalBtn').onclick=()=>{
    modal.classList.remove('hidden');setTimeout(()=>{
    modal.classList.remove('opacity-0');content.classList.remove('scale-95');
    content.classList.add('scale-100');modal.classList.add('flex');},10);
    document.getElementById('inputStudentName').focus();
}
function closeModal(){modal.classList.add('opacity-0');content.classList.remove('scale-100');content.classList.add('scale-95');setTimeout(()=>{modal.classList.add('hidden');modal.classList.remove('flex');},300);}
document.getElementById('closeAddStudentModal').onclick=closeModal;
document.getElementById('cancelAddStudentModal').onclick=closeModal;
modal.onclick=e=>{if(e.target===modal)closeModal();}
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
                 setTimeout(() => {
            window.location.reload(); // ✅ calls index()
        }, 1000);
            } else {
                closeModal();
                showToast(res.message || 'Something went wrong','error');
            }
        },
        error: function(){
            showToast('Server error. Try again','error');
        }
    });
});
</script>
<script>
$(function(){
    function vName(n){return/^[A-Za-z\s.]*$/.test(n)&&((n.match(/\./g)||[]).length<=1)&&((n.match(/ /g)||[]).length<=2)&&n.trim()!==''}
    function vForm(){
        let s=$('#inputStudentName').val().trim(),d=$('#department').val(),i=parseInt($('#inputIncidents').val())||0,m=parseInt($('#inputInternalMarks').val())||0,a=parseInt($('#inputAttendance').val())||0,p=parseFloat($('#paidAmount').val())||0,r=parseFloat($('#remainingAmount').val())||0,f=true
        if(!vName(s)||d===''||i>10||i<0||m>50||m<0||a>100||a<0)f=false
        if($('#paidSection').is(':visible')&&(p<0||r<0))f=false
        $('#formSubmitBtn').prop('disabled',!f)
    }
    $('#formAddStudent input,#formAddStudent select').on('input change',vForm)
    $('#inputStudentName').on('keypress',function(e){let c=e.key,t=$(this).val();if(!/^[A-Za-z .]$/.test(c)||(c==='.'&&(t.match(/\./g)||[]).length>=1)||(c===' '&&(t.match(/ /g)||[]).length>=2))e.preventDefault()})
    $('#inputAttendance').on('input',function(){if(this.value>100)this.value=100})
    $('#inputIncidents').on('input',function(){if(this.value>10)this.value=10})
    $('#inputInternalMarks').on('input',function(){if(this.value>50)this.value=50})
    $('<button>',{type:'submit',id:'formSubmitBtn',class:'px-4 py-2 bg-[#1e35a3] text-white rounded hover:bg-indigo-700',text:'Submit',disabled:true}).appendTo('#formAddStudent .flex')
})
let deptSelect=document.getElementById('department'),feeDisplay=document.getElementById('deptFeeDisplay'),paidInput=document.getElementById('paidAmount'),remainingInput=document.getElementById('remainingAmount'),paidSection=document.getElementById('paidSection'),remainingSection=document.getElementById('remainingSection'),departmentFee=0
deptSelect.addEventListener('change',()=>{let o=deptSelect.options[deptSelect.selectedIndex],f=o.getAttribute('data-fee');departmentFee=f?parseFloat(f):0;if(departmentFee>0){feeDisplay.textContent=`Department Fee: ₹${departmentFee.toLocaleString('en-IN',{minimumFractionDigits:2,maximumFractionDigits:2})}`;paidSection.classList.remove('hidden');remainingSection.classList.remove('hidden')}else{feeDisplay.textContent='';paidSection.classList.add('hidden');remainingSection.classList.add('hidden')}paidInput.value='';remainingInput.value=''})
paidInput.addEventListener('input',()=>{let p=paidInput.value.replace(/[^0-9]/g,'');if(p==='')p='0';p=parseInt(p);if(p>departmentFee)p=departmentFee;paidInput.value=p;remainingInput.value=(departmentFee-p).toFixed(2);$('#formAddStudent input,#formAddStudent select').trigger('input')})
paidInput.addEventListener('keypress',e=>{if(e.key==='e'||e.key==='+'||e.key==='-')e.preventDefault()})
remainingInput.addEventListener('keypress',e=>false)
function numbersOnly(el, allowDecimal = false) {
    el.addEventListener('keypress', e => {
        if (['e','E','+','-'].includes(e.key)) e.preventDefault()
        if (!allowDecimal && e.key === '.') e.preventDefault()
        if (!/^\d$/.test(e.key)) e.preventDefault()
    })

    el.addEventListener('paste', e => {
        const t = (e.clipboardData || window.clipboardData).getData('text')
        if (!/^\d+$/.test(t)) e.preventDefault()
    })

    el.addEventListener('input', () => {
        el.value = el.value.replace(/\D/g, '')
    })
}
numbersOnly(document.getElementById('inputAttendance'))
numbersOnly(document.getElementById('inputIncidents'))
numbersOnly(document.getElementById('inputInternalMarks'))
numbersOnly(document.getElementById('paidAmount'))
numbersOnly(document.getElementById('remainingAmount'))

</script>
<script>
const modal=document.getElementById('addStudentModal'),content=modal.querySelector('div');
document.getElementById('openModalBtn').onclick=()=>{
    modal.classList.remove('hidden');setTimeout(()=>{
    modal.classList.remove('opacity-0');content.classList.remove('scale-95');
    content.classList.add('scale-100');modal.classList.add('flex');},10);
    document.getElementById('inputStudentName').focus();
}
function closeModal(){modal.classList.add('opacity-0');content.classList.remove('scale-100');content.classList.add('scale-95');setTimeout(()=>{modal.classList.add('hidden');modal.classList.remove('flex');},300);}
document.getElementById('closeAddStudentModal').onclick=closeModal;
document.getElementById('cancelAddStudentModal').onclick=closeModal;
modal.onclick=e=>{if(e.target===modal)closeModal();}
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
                 setTimeout(() => {
            window.location.reload(); // ✅ calls index()
        }, 1000);
            } else {
                closeModal();
                showToast(res.message || 'Something went wrong','error');
            }
        },
        error: function(){
            showToast('Server error. Try again','error');
        }
    });
});
</script>
