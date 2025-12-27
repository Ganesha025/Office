function showStudentCard(dept) {
    const container = document.getElementById('studentList');
    const title = document.getElementById('studentCardTitle');
    const fee = document.getElementById('deptFee');

    container.innerHTML = '';
    title.textContent = dept.department_name;

    const totalUncollected = dept.unpaid_students.reduce(
        (sum, s) => sum + parseFloat(s.amount), 0
    );

    // Total students in dept
    const totalStudents = dept.total_students || (dept.unpaid_students.length + dept.paid_count || 0);

    // Students paid = total - unpaid
    const paidStudentsCount = totalStudents - dept.unpaid_students.length;

    // Total collected = dept fee * paid students
    const totalCollected = dept.department_fees_amount * paidStudentsCount;

    fee.innerHTML = `
        <div class="flex flex-col text-right">
            <span style="color:#1e35a3; font-weight:600;">
                Dept Fee (per student): ₹ ${parseFloat(dept.department_fees_amount).toLocaleString('en-IN', { minimumFractionDigits: 2 })}
            </span>
            <span class="text-green-600 text-sm font-semibold">
                Total Collected: ₹ ${totalCollected.toLocaleString('en-IN', { minimumFractionDigits: 2 })}
            </span>
            <span class="text-red-600 text-sm font-semibold">
                Uncollected: ₹ ${totalUncollected.toLocaleString('en-IN', { minimumFractionDigits: 2 })}
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
