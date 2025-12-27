<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>KASC Risk Predictions</title>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/Ganesha025/Office@main/styles.css">
<!-- <script src="https://cdn.jsdelivr.net/gh/Ganesha025/Office@main/valid.js"></script> -->

<style>
    ::-webkit-scrollbar {
    display: none;
}main{
    padding: 60px;
    margin-top: 30px;
}*{
     font-family: 'Poppins', sans-serif;
     /* user-select: none; */
     font-size: 16px;
}#sample_card{
    box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 2px, rgba(0, 0, 0, 0.07) 0px 2px 4px, rgba(0, 0, 0, 0.07) 0px 4px 8px, rgba(0, 0, 0, 0.07) 0px 8px 16px, rgba(0, 0, 0, 0.07) 0px 16px 32px, rgba(0, 0, 0, 0.07) 0px 32px 64px;
}#sample_Charts div{
    box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
}#riskTable{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden}
#riskTable thead th{background:#1e35a3;color:#fff;font-weight:600;padding:12px 10px;border-bottom:2px solid #e5e7eb;white-space:nowrap;text-align:left}
#riskTable tbody td{padding:10px;border-bottom:1px solid #e5e7eb;color:#374151;font-size:14px}
#riskTable tbody tr:nth-child(even){background:#f9fafb}
#riskTable tbody tr:hover{background:#eef2ff;transition:.2s}
.low{color:#15803d;font-weight:600}
.medium{color:#ca8a04;font-weight:600}
.high{color:#dc2626;font-weight:700}
.dataTables_filter input,.dataTables_length select{border:1px solid #d1d5db;border-radius:6px;padding:6px 10px;outline:0}
.dataTables_filter input:focus{border-color:#1e35a3}
.dataTables_paginate .paginate_button{padding:6px 12px;margin:2px;border-radius:6px;border:1px solid #d1d5db;background:#fff;color:#1e35a3!important}
.dataTables_paginate .paginate_button:hover,.dataTables_paginate .paginate_button.current{background:#1e35a3!important;color:#fff!important;border-color:#1e35a3}
.dataTables_info{color:#4b5563;font-size:14px;margin-top:10px}
  #formAddStudent label{display:block;margin-bottom:0.25rem;font-weight:500}
  #formAddStudent input,#formAddStudent select{width:100%;padding:0.5rem;border:1px solid #ccc;border-radius:0.25rem}
  #formAddStudent input:focus,#formAddStudent select:focus{outline:none;border-color:#1e35a3;box-shadow:0 0 0 2px rgba(30,53,163,0.3)}
  .text-red-500{color:#f56565}
</style>
</head>
<body>
<header class="bg-white text-[#1e35a3] border-b border-gray-300 sticky top-0 z-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-20 relative">
      <div class="flex-shrink-0">
        <img src="https://portal.kongunaducollege.ac.in//uploads/banner/logo.png" alt="Logo" class="h-12 w-auto">
      </div>
      <div class="absolute left-1/2 transform -translate-x-1/2 text-center max-w-xs sm:max-w-md md:max-w-lg lg:max-w-xl">
        <h1 class="text-lg sm:text-2xl font-bold leading-tight">Kongunadu Arts and Science College</h1>
        <p class="font-bold text-sm sm:text-base">(AUTONOMOUS)</p>
      </div>
      <nav class="relative flex-shrink-0">
        <button id="accountBtn" class="flex items-center space-x-2 sm:space-x-3 bg-white border border-[#1e35a3] px-2 sm:px-4 py-1 sm:py-2 rounded hover:bg-[#f0f0f0] focus:outline-none font-bold text-[#1e35a3] text-sm sm:text-base">
          <span class="material-icons text-base sm:text-lg">account_circle</span>
          <span class="hidden sm:inline">Account</span>
          <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>
        <div id="accountDropdown" class="hidden absolute right-0 mt-2 w-36 sm:w-44 bg-white text-[#1e35a3] rounded shadow-lg z-50">
          <a href="#" class="block px-4 py-2 hover:bg-gray-100 font-bold text-sm sm:text-base">Profile</a>
          <a href="#" class="block px-4 py-2 hover:bg-gray-100 font-bold text-sm sm:text-base">Settings</a>
          <form method="POST" action="/logout">
            <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 font-bold text-sm sm:text-base">Logout</button>
          </form>
        </div>
      </nav>
    </div>
  </div>
</header>
<script>
const b=document.getElementById('accountBtn'),d=document.getElementById('accountDropdown');
b.onclick=()=>d.classList.toggle('hidden');
document.onclick=e=>{if(!b.contains(e.target)&&!d.contains(e.target))d.classList.add('hidden')};
</script>

<main>
<?php
$totalStudents = array_sum(array_column($students_by_department, 'total'));

$topDept = $topCount = 0;
foreach ($dropout_by_department as $d) {
    if ($d['total'] > $topCount) {
        $topCount = $d['total'];
        $topDept  = $d['department_name'];
    }
}

$cards = [
    [
        'title'  => 'Total Students',
        'value'  => $totalStudents,
        'sub'    => 'All',
        'border' => 'border-blue-500',
        'text'   => 'text-blue-600'
    ]
];

/* HIGH first */
foreach ($lowHigh as $i) {
    if ($i['risk_level'] === 'HIGH') {
        $cards[] = [
            'title'  => 'High Risk',
            'value'  => $i['COUNT(*)'],
            'sub'    => 'Students',
            'border' => 'border-red-500',
            'text'   => 'text-red-600'
        ];
    }
}

/* MEDIUM second */
foreach ($lowHigh as $i) {
    if ($i['risk_level'] === 'MEDIUM') {
        $cards[] = [
            'title'  => 'Medium Risk',
            'value'  => $i['COUNT(*)'],
            'sub'    => 'Students',
            'border' => 'border-yellow-400',
            'text'   => 'text-yellow-500'
        ];
    }
}

/* Department last */
$cards[] = [
    'title'  => 'Highest Risk Department',
    'value'  => $topCount,
    'sub'    => $topDept,
    'border' => 'border-red-600',
    'text'   => 'text-red-600'
];
?>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 pb-100">
<?php foreach($cards as $c): ?>
    <div class="relative group rounded-2xl p-[1px] 
        <?php
            switch($c['title']) {
                case 'Total Students': echo 'bg-gradient-to-br from-blue-400 to-indigo-600'; break;
                case 'High Risk': echo 'bg-gradient-to-br from-red-500 to-pink-600'; break;
                case 'Medium Risk': echo 'bg-gradient-to-br from-yellow-400 to-orange-500'; break;
                case 'Highest Risk Department': echo 'bg-gradient-to-br from-rose-500 to-red-700'; break;
                default: echo 'bg-gradient-to-br from-gray-400 to-gray-600'; break;
            }
        ?>
        hover:scale-[1.03] transition-all duration-300
    ">
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl p-5 h-full shadow-xl flex flex-col justify-between">

            <!-- Title -->
            <h3 class="text-sm font-semibold text-gray-500 tracking-wide uppercase">
                <?= esc($c['title']) ?>
            </h3>

            <!-- Value -->
            <div class="mt-4 flex items-end justify-between">
                <div>
                    <p class="text-4xl font-black text-gray-900 leading-none">
                        <?= esc($c['value']) ?>
                    </p>
                    <p class="text-sm text-gray-500 mt-1">
                        <?= esc($c['sub']) ?>
                    </p>
                </div>

                <!-- Icon bubble -->
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg
                    <?php
                        switch($c['title']) {
                            case 'Total Students': echo 'bg-blue-500 text-white'; break;
                            case 'High Risk': echo 'bg-red-600 text-white'; break;
                            case 'Medium Risk': echo 'bg-yellow-400 text-white'; break;
                            case 'Highest Risk Department': echo 'bg-red-500 text-white'; break;
                            default: echo 'bg-gray-500 text-white'; break;
                        }
                    ?>
                ">
                    <span class="material-symbols-outlined text-2xl">
                        <?php
                            switch($c['title']) {
                                case 'Total Students': echo 'groups'; break;
                                case 'High Risk': echo 'warning'; break;
                                case 'Medium Risk': echo 'report'; break;
                                case 'Highest Risk Department': echo 'apartment'; break;
                                default: echo 'info'; break;
                            }
                        ?>
                    </span>
                </div>
            </div>

        </div>
    </div>
<?php endforeach; ?>
</div>
<button id="openModalBtn" class="px-4 py-2 bg-blue-600 text-white rounded">Add Student</button>

<!-- Modal -->
<div id="addStudentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 transition-opacity duration-300 opacity-0">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative transform scale-95 transition-all duration-300 ease-in-out">
    <button id="closeAddStudentModal" class="absolute top-3 right-3 text-gray-500"><span class="material-symbols-outlined text-2xl">close</span></button>
    <h3 class="text-xl font-bold text-[#1e35a3] mb-4">Add New Student</h3>
    <form id="formAddStudent" action="Addstudent" method="post" class="grid grid-cols-2 gap-4">
      <div class="col-span-2">
        <label class="font-medium">Student Name <span class="text-red-500">*</span></label>
        <input type="text" id="inputStudentName" name="student_name" placeholder="Student Name" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#1e35a3]">
      </div>
      <div class="col-span-2">
        <label class="font-medium">Department <span class="text-red-500">*</span></label>
        <select id="department" class="w-full border rounded px-2 py-1" name="department_name">
            <option value="" selected>---Select---</option>
            <?php foreach($all_departments as $d): ?>
                <option value="<?= esc($d['department_name']) ?>" data-fee="<?= esc($d['department_fees_amount']) ?>">
                    <?= esc($d['department_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
      </div>
      <div class="col-span-2" id="deptFeeDisplay" class="text-gray-700 font-medium mt-1"></div>
      <div>
        <label class="font-medium">Incidents <span class="text-red-500">*</span></label>
        <input type="number" id="inputIncidents" name="incident_count" placeholder="Incidents" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#1e35a3]">
      </div>
      <div>
        <label class="font-medium">Internal Marks <span class="text-red-500">*</span></label>
        <input type="number" id="inputInternalMarks" name="avg_internal_marks" placeholder="Internal Marks" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#1e35a3]">
      </div>
      <div>
        <label class="font-medium">Attendance % <span class="text-red-500">*</span></label>
        <input type="number" id="inputAttendance" name="attendance_percentage" placeholder="Attendance %" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#1e35a3]">
      </div>
      <div class="col-span-2 hidden" id="paidSection">
        <label class="font-medium">Amount Paid <span class="text-red-500">*</span></label>
        <input type="number" id="paidAmount" name="paid_amount" placeholder="Enter Paid Amount" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#1e35a3]" min="0">
      </div>
      <div class="col-span-2 hidden" id="remainingSection">
        <label class="font-medium">Amount Remaining <span class="text-red-500">*</span></label>
        <input type="number" id="remainingAmount" name="remaining_amount" class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
      </div>
      <div class="col-span-2 flex justify-end space-x-2 mt-4">
        <button type="button" id="cancelAddStudentModal" class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-100">Cancel</button>
      </div>
    </form>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

</script>
<script>
const modal=document.getElementById('addStudentModal'),content=modal.querySelector('div');
document.getElementById('openModalBtn').onclick=()=>{modal.classList.remove('hidden');setTimeout(()=>{modal.classList.remove('opacity-0');content.classList.remove('scale-95');content.classList.add('scale-100');modal.classList.add('flex');},10);}
function closeModal(){modal.classList.add('opacity-0');content.classList.remove('scale-100');content.classList.add('scale-95');setTimeout(()=>{modal.classList.add('hidden');modal.classList.remove('flex');},300);}
document.getElementById('closeAddStudentModal').onclick=closeModal;
document.getElementById('cancelAddStudentModal').onclick=closeModal;
modal.onclick=e=>{if(e.target===modal)closeModal();}
document.getElementById('formAddStudent').onsubmit=e=>{closeModal();}
</script>

<div class="flex flex-col items-center mb-6 mt-20">
    <h2 class="text-xl font-semibold text-center">
        overAll Data
    </h2>
    <span class="mt-2 h-1 w-16 bg-[#1e35a3] rounded-full"></span>
</div>
<div class="container mx-auto mt-2 mb-20 py-10">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Risk Level Distribution -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-lg p-4 
                    h-[460px] sm:h-[360px] lg:h-[400px] flex flex-col">
            <h3 class="text-lg font-bold text-[#1e35a3] mb-2">
                Risk Level Distribution
            </h3>
            <div id="myPieChart" class="w-full flex-1 pb-10"></div>
        </div>

        <!-- High Risk Students by Department -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-lg p-4 
                    h-[460px] sm:h-[360px] lg:h-[400px] flex flex-col">
            <h3 class="text-lg font-bold text-[#1e35a3] mb-2">
                High Risk Students by Department
            </h3>
            <canvas id="deptBarChart" class="w-full flex-1 pb-5"></canvas>
        </div>

        <!-- Incident Analysis -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-lg p-4 
                    h-[450px] sm:h-[360px] lg:h-[400px] flex flex-col">
            <h3 class="text-lg font-bold text-[#1e35a3] mb-2">
                Incident Analysis
            </h3>
            <canvas id="incidentChart" class="w-full flex-1 pb-5"></canvas>
        </div>

    </div>
</div>


<script>

const incidentAnalysisData = <?= json_encode($incident_analysis); ?>;
const labels = incidentAnalysisData.map(i => i.risk_level || i.department_name);
const totalIncidents = incidentAnalysisData.map(i => i.total_incidents);

new Chart(document.getElementById('incidentChart'), {
    type: 'bar',
    data: {
        labels,
        datasets: [{
            label: 'Total Incidents',
            data: totalIncidents,
            backgroundColor: '#c57012',
            borderColor: '#c57012',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        layout: { padding: { bottom: 20 } },
        scales: {
            y: { beginAtZero: true, title: { display: true, text: 'Incident Count' } },
            x: { title: { display: true, text: 'Risk Level / Department' }, ticks: { padding: 5 } }
        }
    }
});

const students = <?= json_encode($students_risk_fee); ?>;
const riskScores = [...new Set(students.map(s => s.risk_score))].sort((a, b) => a - b);
const feeAmounts = [...new Set(students.map(s => s.fee_due_amount))].sort((a, b) => a - b);
const maxFee = Math.max(...students.map(s => s.fee_due_amount));
const series = feeAmounts.map(fee => ({
    name: "₹" + fee,
    data: riskScores.map(risk => {
        const s = students.find(s => s.risk_score == risk && s.fee_due_amount == fee);
        return { x: risk, y: fee, student: s?.student_name || "", fillColor: s ? (fee == 0 ? "#93ff93ff" : "#FF4560") : "#FFFFFF" }
    })
}));

const lowHighData = <?= json_encode($lowHigh); ?>;
new ApexCharts(document.querySelector("#myPieChart"), {
    chart: { type: 'pie', },
    series: lowHighData.map(i => +i["COUNT(*)"]),
    labels: lowHighData.map(i => i.risk_level + " Level"),
    colors: ['#c57012', '#1e35a3'],
    legend: { position: 'bottom', horizontalAlign: 'center', offsetY: 0 },
    responsive: [{ breakpoint: 640, options: { legend: { position: 'right' } } }]
}).render();

const deptData = <?= json_encode($dropout_by_department); ?>;
new Chart(document.getElementById('deptBarChart'), {
    type: 'bar',
    data: {
        labels: deptData.map(d => d.department_name),
        datasets: [{
            label: 'High Risk Students',
            data: deptData.map(d => +d.total),
            backgroundColor: '#1e35a3',
            borderColor: '#1e35a3',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        layout: { padding: { bottom: 20 } },
        scales: {
            y: { beginAtZero: true, title: { display: true, text: 'Number of Students' } },
            x: { title: { display: true, text: 'Department' }, ticks: { padding: 5 } }
        },
        plugins: { legend: { display: true } }
    }
});


</script>
<!-- <div class="bg-white p-4 rounded shadow mb-10 h-96 md:h-[500px] lg:h-[550px]">
    <div id="heatmap" class="w-full h-full"></div>
</div> -->


<div class="flex flex-col lg:flex-row lg:justify-start lg:items-start p-8 bg-white border border-white/20 rounded-xl shadow-lg mt-20 mb-20">

    <div id="deptChart" class="w-full lg:w-3/4 lg:flex-1"></div>

    <div class="w-full lg:w-1/4 lg:flex-none lg:ml-4 mt-4 lg:mt-0">
        <div id="studentCard" class="bg-white/30 backdrop-blur-md border border-white/20 rounded-xl shadow-lg p-4 h-full flex flex-col">
            <div class="flex justify-between items-center mb-6"> 
                <h3 id="studentCardTitle" class="text-xl font-bold text-[#1e35a3]"></h3>
                <span id="deptFee" class="text-gray-700 font-medium"></span>
            </div>

            <div id="studentList" class="flex flex-col gap-3 max-h-[420px] overflow-y-auto">
                
            </div>
        </div>
    </div>
</div>

<script>
const stackedData = <?= json_encode($stacked_bar) ?>;

// ApexCharts options
const deptOptions = {
    chart: { 
        type: 'bar', 
        height: window.innerWidth < 768 ? 400 : 550,
        stacked: true,
        events: {
            dataPointSelection: function(event, chartContext, config) {
                const dept = stackedData[config.dataPointIndex];
                showStudentCard(dept);
            }
        }
    },
    fill: { opacity: 1 },
    plotOptions: { bar: { horizontal: true, dataLabels: { position: 'center' } } },
    dataLabels: { enabled: true, formatter: v => v, style: { fontSize: '12px', fontWeight: 'bold' } },
    series: [
        { name: 'Paid', data: stackedData.map(d => parseInt(d.paid_count)) },
        { name: 'Unpaid', data: stackedData.map(d => parseInt(d.unpaid_count)) }
    ],
    xaxis: {
        categories: stackedData.map(d => {
            const words = d.department_name.split(' ');
            return words.length > 1 ? words.map(w => w[0].toUpperCase()).join('') : d.department_name;
        })
    },
    colors: ['#1e35a3', '#c57012'],
    legend: { position: 'top' },
    title: { text: 'Department wise fees payment status', align: 'left' }
};

const deptChart = new ApexCharts(document.querySelector("#deptChart"), deptOptions);
deptChart.render();

// Show department with max unpaid students on load
const maxUnpaidDept = stackedData.reduce((best, current) => {
    const bestCount = parseInt(best.unpaid_count);
    const currCount = parseInt(current.unpaid_count);
    if (currCount > bestCount) return current;
    if (currCount === bestCount && current.department_name.localeCompare(best.department_name) < 0) return current;
    return best;
});

showStudentCard(maxUnpaidDept);

function showStudentCard(dept) {
    const container = document.getElementById('studentList');
    const title = document.getElementById('studentCardTitle');
    const fee = document.getElementById('deptFee');

    container.innerHTML = '';
    title.textContent = dept.department_name;

    const totalUncollected = dept.unpaid_students.reduce(
        (sum, s) => sum + parseFloat(s.amount), 0
    );

    fee.innerHTML = `
        <div class="flex flex-col text-right">
            <span style="color:#1e35a3; font-weight:600;">
                Dept Fee : ₹ ${parseFloat(dept.department_fees_amount).toLocaleString('en-IN', { minimumFractionDigits: 2 })}
            </span>
            <span class="text-red-600 text-sm font-semibold">
                Uncollected : ₹ ${totalUncollected.toLocaleString('en-IN', { minimumFractionDigits: 2 })}
            </span>
        </div>
    `;

    dept.unpaid_students.forEach(s => {
        const paidAmt = dept.department_fees_amount - s.amount;
        const div = document.createElement('div');
        div.className = "bg-white/50 p-2 rounded-lg flex justify-between items-center shadow-sm";
        div.innerHTML = `
            <div class="flex flex-col">
                <div class="flex items-center space-x-2">
                    <span class="material-symbols-outlined text-red-600">arrow_circle_down</span>
                    <span class="font-bold">${s.name}</span>
                </div>
                <span class="text-green-600 text-sm ml-7">
                    + ₹${paidAmt.toLocaleString('en-IN', { minimumFractionDigits: 2 })}
                </span>
            </div>
            <span class="text-red-600 font-bold">
                - ₹ ${parseFloat(s.amount).toLocaleString('en-IN', { minimumFractionDigits: 2 })}
            </span>
        `;
        container.appendChild(div);
    });
}

</script>



<div class="flex flex-col items-center mb-6 mt-30">
    <h2 class="text-xl font-semibold text-center">
        Student Risk Predictions
    </h2>
    <span class="mt-2 h-1 w-16 bg-[#1e35a3] rounded-full"></span>
</div>

<div class="mb-4">
    <label for="departments" class="mr-2 font-medium">Select Department:</label>
    <select id="departments" class="border rounded px-2 py-1">
        <option value="">All Departments</option>
        <?php foreach($dropout_by_department as $d): ?>
            <option value="<?= esc($d['department_name']) ?>" <?= $d['department_name']==$defaultDept?'selected':'' ?>>
                <?= esc($d['department_name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="overflow-x-auto">
<table id="riskTable" class="min-w-full border-collapse table-auto text-sm md:text-base">
<thead class="bg-gray-100">
<tr>
<th class="px-3 py-2 border">Student ID</th>
<th class="px-3 py-2 border">Student Name</th>
<th class="px-3 py-2 border">Department</th>
<th class="px-3 py-2 border">Attendance</th>
<th class="px-3 py-2 border">Internal Marks</th>
<th class="px-3 py-2 border">Fee Due</th>
<th class="px-3 py-2 border">Incidents</th>
<th class="px-3 py-2 border">Risk Score</th>
<th class="px-3 py-2 border">Risk Level</th>
<th class="px-3 py-2 border">Actions</th>
</tr>
</thead>
<tbody>
<?php foreach($predictions as $r): ?>
<tr data-student-id="<?= esc($r['roll_no']) ?>" class="odd:bg-white even:bg-gray-50">
<td class="px-3 py-2 border"><?= esc($r['roll_no']) ?></td>
<td class="px-3 py-2 border"><?= esc($r['student_name']) ?></td>
<td class="px-3 py-2 border"><?= esc($r['department_name']) ?></td>

<td class="px-3 py-2 border editable" data-field="attendance_percentage"><?= esc($r['attendance_percentage']) ?></td>
<td class="px-3 py-2 border editable" data-field="avg_internal_marks"><?= esc($r['avg_internal_marks']) ?></td>
<td class="px-3 py-2 border editable" data-field="remaining_amount"><?= esc($r['fee_due_amount']) ?></td>
<td class="px-3 py-2 border editable" data-field="incident_count"><?= esc($r['incident_count']) ?></td>

<td class="px-3 py-2 border risk-score"><?= esc($r['risk_score']) ?></td>
<td class="px-3 py-2 border <?= strtolower($r['risk_level']) ?>"><?= esc($r['risk_level']) ?></td>

<td class="px-3 py-2 border">
<button class="saveBtn hidden text-green-600">Save</button>
<button class="deleteBtn text-red-600">Delete</button>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</main>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(function () {

    const table = $('#riskTable').DataTable({
        pageLength: 10
    });

    // ===============================
    // FILTER BY DEPARTMENT (AJAX LOAD)
    // ===============================
    $('#departments').on('change', function () {
        const dept = $(this).val();

        $.post('getStudentsByDept', { department: dept }, function (res) {
            table.clear();

            res.forEach(r => {
                table.row.add([
                    r.roll_no,
                    r.student_name,
                    r.department_name,
                    `<span class="editable" data-field="attendance_percentage">${r.attendance_percentage}</span>`,
                    `<span class="editable" data-field="avg_internal_marks">${r.avg_internal_marks}</span>`,
                    `<span class="editable" data-field="remaining_amount">${r.fee_due_amount}</span>`,
                    `<span class="editable" data-field="incident_count">${r.incident_count}</span>`,
                    r.risk_score,
                    `<span class="${r.risk_level.toLowerCase()}">${r.risk_level}</span>`,
                    `<button class="saveBtn hidden text-green-600">Save</button>
                     <button class="deleteBtn text-red-600">Delete</button>`
                ]).draw(false);
            });
        }, 'json');
    });

    // ===============================
    // INLINE EDIT
    // ===============================
    $('#riskTable').on('click', '.editable', function () {
        if ($(this).find('input').length) return;

        const value = $(this).text().trim();
        $(this).html(`<input type="number" class="border w-16 px-1" value="${value}">`);

        $(this).closest('tr').find('.saveBtn').removeClass('hidden');
    });

    // ===============================
    // SAVE (UPDATE)
    // ===============================
    $('#riskTable').on('click', '.saveBtn', function () {
        const row = $(this).closest('tr');

        // Student Register Number (ALWAYS column 0)
        const studentId = row.find('td:eq(0)').text().trim();

        let data = { student_id: studentId };

        row.find('.editable').each(function () {
            data[$(this).data('field')] = $(this).find('input').val();
        });

        $.post('update', data, function (res) {
            if (res.status === 'success') {
                location.reload();
            } else {
                alert(res.message);
            }
        }, 'json');
    });

    // ===============================
    // DELETE
    // ===============================
    $('#riskTable').on('click', '.deleteBtn', function () {
        if (!confirm('Delete this student?')) return;

        const row = $(this).closest('tr');
        const studentId = row.find('td:eq(0)').text().trim();

        $.post('delete', { student_id: studentId }, function (res) {
            if (res.status === 'success') {
                table.row(row).remove().draw();
            } else {
                alert(res.message);
            }
        }, 'json');
    });

});
</script>

</body>
</html>
