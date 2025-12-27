<div class="flex flex-col lg:flex-row lg:justify-start lg:items-start p-8 bg-white border border-[#1e35a3] rounded-xl mb-10">
    <!-- Chart on the left -->
    <div id="deptChart" class="w-full lg:w-3/4 lg:flex-1" style="padding-top:5px;"></div>

    <!-- Student card on the right -->
    <div class="w-full lg:w-1/4 lg:flex-none lg:ml-4 mt-4 lg:mt-0" style="box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px; border-radius: 10px;">
        <div id="studentCard" class="bg-white/30 backdrop-blur-md border border-white/20 rounded-xl shadow-lg p-4 h-full flex flex-col">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6"> <!-- increased margin -->
                <h3 id="studentCardTitle" class="text-xl font-bold text-[#1e35a3]"></h3>
                <span id="deptFee" class="text-gray-700 font-medium"></span>
            </div>
          <hr border="1" class="border-gray-400 mb-4">
            <!-- Student list / table -->
            <div id="studentList" class="flex flex-col gap-3 max-h-[420px] overflow-y-auto">
                <!-- Example rows -->
                
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
            if (words.length > 1) {
            // Take first letter of each word and make it uppercase
            return words.map(w => w[0].toUpperCase()).join('');
        }
        return d.department_name; // single-word departments stay the same
    
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
    
    // Department name + fee below
    title.innerHTML = `
        <div class="flex flex-col">
            <span class="font-bold text-lg">${dept.department_name}</span>
            <span style="color:#1e35a3; font-weight:600; font-size: 0.875rem;">
                Fee : ₹ ${parseFloat(dept.department_fees_amount).toLocaleString('en-IN', { minimumFractionDigits: 2 })}
            </span>
        </div>
    `;

    // Calculate total uncollected
    const totalUncollected = dept.unpaid_students.reduce(
        (sum, s) => sum + parseFloat(s.amount), 0
    );

    // Total students in dept
    const totalStudents = dept.total_students || (dept.unpaid_students.length + dept.paid_count || 0);

    // Students paid = total - unpaid
    const paidStudentsCount = totalStudents - dept.unpaid_students.length;

    // Total collected = dept fee * paid students
    const totalCollected = dept.department_fees_amount * paidStudentsCount;

    // Collected / Uncollected summary
    fee.innerHTML = `
        <div class="flex flex-col text-right">
            <span class="text-green-600 text-sm font-bold">
                Collected: ₹ ${totalCollected.toLocaleString('en-IN', { minimumFractionDigits: 2 })}
            </span>
            <span class="text-red-600 text-sm">
                Pending: ₹ ${totalUncollected.toLocaleString('en-IN', { minimumFractionDigits: 2 })}
            </span>
        </div>
    `;

    // List of unpaid students
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
