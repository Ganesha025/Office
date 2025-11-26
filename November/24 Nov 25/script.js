document.addEventListener("DOMContentLoaded", () => {
    populateStates();
    $("#stateSelect").on("change", updateDistricts);

    const b = (e, v) => e.style.border = v ? "1px solid green" : "1px solid red"; 
    const m = (e, v, t) => { 
        b(e, v); 
        if (e.nextElementSibling) e.nextElementSibling.textContent = v ? "" : t; 
    };

    const block = (el, fn) => {
        if (!el) return;
        el.addEventListener("keydown", fn);
        el.addEventListener("keyup", fn);
        el.addEventListener("blur", fn);
        el.addEventListener("focus", fn);
    };
const dobField = document.querySelectorAll(".val-dobs");
dobField.forEach(el => {
    const today = new Date();
    const minAgeDate = new Date(today.setFullYear(today.getFullYear() - 17));
    const minDate = minAgeDate.toISOString().split("T")[0];
    
    el.setAttribute("max", minDate);
    
    block(el, e => {
        if (e.target.value && new Date(e.target.value) > minAgeDate) {
            m(e.target, false, "Age must be 17 or older");
        } else {
            m(e.target, true, "");
        }
    });
});
document.querySelectorAll(".val-age").forEach(el => {
    block(el, e => {    
        const t = e.target;
        const validateAge = () => {
            const age = parseInt(t.value, 10);
            const valid = age >= 0 && age <= 200 && t.value.length <= 3;
            m(t, valid, "Age must be a valid number between 0 and 200 (max 3 digits)");
        };
        if (e.type === "keydown") {
            if (!/[0-9]/.test(e.key) && e.key.length === 1) e.preventDefault();
            if (t.value.length >= 3 && e.key.length === 1) e.preventDefault();
        }
        if (e.type === "paste") {
            setTimeout(validateAge, 0);
        }
        validateAge();
    });
});
document.querySelectorAll(".val-gender").forEach(el => {
    block(el, e => {
        const t = e.target;
        
        const validateGender = () => {
            const valid = t.value !== "";
            m(t, valid, "Please select a gender");
        };

        validateGender();
    });
});

document.querySelectorAll(".val-location").forEach(el => {
    block(el, e => {
        if (e.type === "keydown") {
            if (!/[A-Za-z]/.test(e.key) && e.key.length === 1) e.preventDefault();
            if (e.target.value.length >= 25 && e.key.length === 1) e.preventDefault();
        }

        m(e.target, e.target.value.length > 0 && e.target.value.length <= 25 && /^[A-Za-z]+$/.test(e.target.value), "Letters only, max 25 characters");
    });
});

    document.querySelectorAll(".val-otp").forEach(el => {
        block(el, e => {
            if (e.type === "keydown") {
                if (!/^[0-9]$/.test(e.key) && e.key.length === 1) e.preventDefault();
                if (e.target.value.length >= 4 && e.key.length === 1) e.preventDefault();
            }
            m(e.target, e.target.value.length === 4, "4 digits");
        });
    });
document.querySelectorAll(".val-experience").forEach(el => {
    block(el, e => {
        if (e.type === "keydown") {
            if (!/[0-9]/.test(e.key) && e.key.length === 1) e.preventDefault();
            if (e.target.value.length >= 2 && e.key.length === 1) e.preventDefault();
        }

        if (+e.target.value >= 70) e.target.value = "69";
        
        m(e.target, e.target.value !== "" && +e.target.value < 70, "Valid experience (0-69)");
    });
});
document.querySelectorAll(".val-salary").forEach(el => {
    block(el, e => {
        if (e.type === "keydown") {
            if (!/[0-9]/.test(e.key) && e.key.length === 1) e.preventDefault();
            if (e.target.value.length >= 7 && e.key.length === 1) e.preventDefault();
        }if (+e.target.value >= 9000000) e.target.value = "8999999";        
        m(e.target, e.target.value !== "" && +e.target.value < 9000000, "Valid salary (< 90 Lakhs)");
    });
});
document.querySelectorAll(".val-github").forEach(el => {
    block(el, e => {
        const t = e.target;
const validateGithubURL = () => {
            const regex = /^https:\/\/github\.com\/[A-Za-z0-9-_.]{1,39}$/;
            const valid = regex.test(t.value);
            m(t, valid, "Invalid GitHub URL (https://github.com/username)");
        };if (e.type === "keydown") {
            if (!/[A-Za-z0-9/-_.]/.test(e.key) && e.key.length === 1) e.preventDefault();
        }if (e.type === "paste") {
            setTimeout(validateGithubURL, 0);
        }
        validateGithubURL();
    });
});
const endedDate = document.querySelectorAll(".val-ended-date");
endedDate.forEach(el => {
    const today = new Date().toISOString().split("T")[0];
    el.setAttribute("min", today);
    block(el, e => {
        if (e.target.value && e.target.value < today) {
            m(e.target, false, "Select a future date");
        } else {
            m(e.target, true, "");
        }
    });
});

document.querySelectorAll(".val-mobile").forEach(el => {
    block(el, e => {
        const t = e.target;
        if (e.type === "keydown") {
            if (!/^[0-9]$/.test(e.key) && e.key.length === 1) e.preventDefault();
            if (t.value.length >= 10 && e.key.length === 1) e.preventDefault();
        }const valid = /^[6-9][0-9]{9}$/.test(t.value); 
        m(t, valid, "Mobile number must start with 6-9 and be 10 digits");
    });
});document.querySelectorAll(".val-pincode").forEach(el => {
        block(el, e => {
            if (e.type === "keydown") {
                if (!/^[0-9]$/.test(e.key) && e.key.length === 1) e.preventDefault();
                if (e.target.value.length >= 6 && e.key.length === 1) e.preventDefault();
            }
            m(e.target, e.target.value.length === 6, "6 digits");
        });
    });
document.querySelectorAll(".val-registration-date").forEach(el => {
    const today = new Date().toISOString().split("T")[0];
    el.setAttribute("type", "date");
    el.setAttribute("max", today);
    block(el, e => m(e.target, e.target.value === endedDate || e.target.value === today, "Select the ended date or today's date"));
});
document.querySelectorAll(".val-username").forEach(el => {
    block(el, e => {
        const t = e.target;
        if (e.type === "keydown") {
            if (!/^[A-Za-z]$/.test(e.key) && e.key.length === 1) e.preventDefault(); // Allow only letters
            if (t.value.length >= 25 && e.key.length === 1) e.preventDefault(); 
        }
        m(t, /^[A-Za-z]{1,25}$/.test(t.value), "Letters only, max 25 characters");
    });
});document.querySelectorAll(".val-address").forEach(el => {
        block(el, e => {
            if (e.type === "keydown" && e.target.value.length >= 250 && e.key.length === 1) e.preventDefault();
            m(e.target, e.target.value.trim() !== "", "Required");
        });
    });document.querySelectorAll(".val-mark").forEach(el => {
        block(el, e => {
            if (e.type === "keydown") {
                if (!/^[0-9]$/.test(e.key) && e.key.length === 1) e.preventDefault();
                if (e.target.value.length >= 3 && e.key.length === 1) e.preventDefault();
            }
            if (+e.target.value > 100) e.target.value = "100";
            m(e.target, e.target.value !== "", "Required");
        });
    });document.querySelectorAll(".val-aadhar").forEach(el => {
        block(el, e => {
            if (e.type === "keydown") {
                if (!/^[0-9]$/.test(e.key) && e.key.length === 1) e.preventDefault();
                if (e.target.value.length >= 12 && e.key.length === 1) e.preventDefault();
            }
            m(e.target, e.target.value.length === 12, "12 digits");
        });
    });

    const dob = document.querySelector(".val-dob");
    if (dob) {
        const today = new Date().toISOString().split("T")[0];
        dob.max = new Date(Date.now() - 86400000).toISOString().split("T")[0];
        block(dob, e => m(e.target, e.target.value && e.target.value < today, "Invalid"));
    }

    document.querySelectorAll(".val-initial").forEach(el => {
        block(el, e => {
            if (e.type === "keydown") {
                if (!/^[A-Za-z]$/.test(e.key) && e.key.length === 1) e.preventDefault();
                if (e.target.value.length >= 2 && e.key.length === 1) e.preventDefault();
            }
            m(e.target, e.target.value.length === 2, "2 letters");
        });
    });

    document.querySelectorAll(".val-year").forEach(el => {
    block(el, e => {
        if (e.type === "keydown") {
            if (!/[0-9]/.test(e.key) && e.key.length === 1) e.preventDefault();
            if (e.target.value.length >= 4 && e.key.length === 1) e.preventDefault();
        }if (+e.target.value > 2100) e.target.value = "2100";
        if (+e.target.value < 1900) e.target.value = "1900";
        m(e.target, e.target.value.length === 4 && +e.target.value >= 1900 && +e.target.value <= 2100, "4 digits, between 1900 and 2100");
    });
});
document.querySelectorAll(".val-email").forEach(el => {
    block(el, e => {
        const t = e.target;
        const atIndex = t.value.indexOf('@');

        if (e.type === "keydown") {
            if (atIndex !== -1) {
                if (/[0-9]/.test(e.key)) e.preventDefault();
                if (e.key === " " || e.key === "@" || /[^A-Za-z._]/.test(e.key)) e.preventDefault();
            } else if (!/[A-Za-z0-9@._]/.test(e.key)) {
                e.preventDefault();
            }
        }

        m(t, /^[A-Za-z0-9._]+@[A-Za-z0-9]+\.[A-Za-z]{2,}$/.test(t.value), "Invalid email");
    });
});

document.querySelectorAll(".val-vehicle").forEach(el => {
    block(el, e => {
        const t = e.target;
        if (!/[A-Za-z0-9]/.test(e.key) && e.key.length === 1) e.preventDefault();
        if (t.value.length >= 10 && e.key.length === 1) e.preventDefault();
        t.value = t.value.toUpperCase();
        m(t, /^[A-Za-z0-9]{1,10}$/.test(t.value), "Max 10 characters, no special characters");
    });
});

});

document.querySelectorAll('input').forEach(input => {
    input.addEventListener('copy', (e) => {
        e.preventDefault();
    });
    input.addEventListener('paste', (e) => {
        e.preventDefault();
    });
    input.addEventListener('cut', (e) => {
        e.preventDefault();
    });
});
 const southIndiaStatesAndDistricts = [
            {state: "Andhra Pradesh", districts: ["Anantapur", "Chittoor", "East Godavari", "Guntur", "Krishna", "Kurnool", "Prakasam", "Srikakulam", "Sri Potti Sriramulu Nellore", "Visakhapatnam", "Vizianagaram", "West Godavari", "YSR Kadapa"]},
            {state: "Karnataka", districts: ["Bagalkot", "Ballari", "Belagavi", "Bengaluru Rural", "Bengaluru Urban", "Bidar", "Chamarajanagar", "Chikkaballapur", "Chikkamagaluru", "Chitradurga", "Dakshina Kannada", "Davanagere", "Dharwad", "Gadag", "Hassan", "Haveri", "Kalaburagi", "Kodagu", "Kolar", "Koppal", "Mandya", "Mysuru", "Raichur", "Ramanagara", "Shivamogga", "Tumakuru", "Udupi", "Uttara Kannada", "Vijayapura", "Yadgir"]},
            {state: "Kerala", districts: ["Alappuzha", "Ernakulam", "Idukki", "Kannur", "Kasaragod", "Kollam", "Kottayam", "Kozhikode", "Malappuram", "Palakkad", "Pathanamthitta", "Thiruvananthapuram", "Thrissur", "Wayanad"]},
            {state: "Tamil Nadu", districts: ["Ariyalur", "Chengalpattu", "Chennai", "Coimbatore", "Cuddalore", "Dharmapuri", "Dindigul", "Erode", "Kallakurichi", "Kanchipuram", "Kanyakumari", "Karur", "Krishnagiri", "Madurai", "Nagapattinam", "Namakkal", "Nilgiris", "Perambalur", "Pudukkottai", "Ramanathapuram", "Ranipet", "Salem", "Sivaganga", "Tenkasi", "Thanjavur", "Theni", "Thoothukudi", "Tiruchirappalli", "Tirunelveli", "Tirupathur", "Tiruppur", "Tiruvallur", "Tiruvannamalai", "Tiruvarur", "Vellore", "Viluppuram", "Virudhunagar"]},
            {state: "Telangana", districts: ["Adilabad", "Bhadradri Kothagudem", "Hyderabad", "Jagtial", "Jangaon", "Jayashankar Bhupalpally", "Jogulamba Gadwal", "Kamareddy", "Karimnagar", "Khammam", "Kumuram Bheem", "Mahabubabad", "Mahabubnagar", "Mancherial", "Medak", "Medchal", "Mulugu", "Nagarkurnool", "Nalgonda", "Narayanpet", "Nirmal", "Nizamabad", "Peddapalli", "Rajanna Sircilla", "Rangareddy", "Sangareddy", "Siddipet", "Suryapet", "Vikarabad", "Wanaparthy", "Warangal Rural", "Warangal Urban", "Yadadri Bhuvanagiri"]}
        ];

  const toast = (msg, type = 'success') => {
        const t = $(`<div class="toast ${type}">${msg}</div>`).appendTo('body');
        setTimeout(() => t.addClass('show'), 10);
        setTimeout(() => { t.removeClass('show'); setTimeout(() => t.remove(), 300); }, 3000);
    };


       const populateStates = () => {
        const stateSelect = $("#stateSelect");
        southIndiaStatesAndDistricts.forEach(stateObj => 
            stateSelect.append($("<option>").val(stateObj.state).text(stateObj.state))
        );
    };
    const updateDistricts = () => {
        const stateSelect = $("#stateSelect");
        const districtSelect = $("#districtSelect");
        const selectedState = stateSelect.val();
        const stateObj = southIndiaStatesAndDistricts.find(state => state.state === selectedState);
        districtSelect.empty().append('<option value="">Select District</option>');
        districtSelect.prop('disabled', !selectedState);
        stateObj?.districts.forEach(district => 
            districtSelect.append($("<option>").val(district).text(district))
        );
    };