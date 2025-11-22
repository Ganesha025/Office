const southIndiaStatesAndDistricts = [
  {
    state: "Andhra Pradesh",
    districts: [
      "Anantapur", "Chittoor", "East Godavari", "West Godavari", "Guntur", "Kadapa", "Krishna", "Kurnool", "Prakasam", "Srikakulam", "Visakhapatnam", "Vizianagaram", "West Godavari", "Y.S.R. Kadapa"
    ]
  },
  {
    state: "Karnataka",
    districts: [
      "Bagalkot", "Ballari", "Belagavi", "Bengaluru Rural", "Bengaluru Urban", "Bidar", "Chamarajanagar", "Chikkamagaluru", "Chikkaballapura", "Chitradurga", "Dakshina Kannada", "Davangere", "Dharwad", "Gadag", "Hassan", "Haveri", "Kalaburagi", "Kodagu", "Kolar", "Koppal", "Mandya", "Mysuru", "Raichur", "Ramanagara", "Shivamogga", "Tumakuru", "Udupi", "Uttara Kannada", "Vijayapura", "Yadgir"
    ]
  },
  {
    state: "Kerala",
    districts: [
      "Alappuzha", "Ernakulam", "Idukki", "Kottayam", "Kozhikode", "Malappuram", "Palakkad", "Pathanamthitta", "Thiruvananthapuram", "Thrissur", "Wayanad"
    ]
  },
  {
    state: "Tamil Nadu",
    districts: [
      "Ariyalur", "Chengalpattu", "Chennai", "Coimbatore", "Cuddalore", "Dharmapuri", "Dindigul", "Erode", "Kanchipuram", "Kanyakumari", "Karur", "Krishnagiri", "Madurai", "Nagapattinam", "Namakkal", "Nilgiris", "Perambalur", "Pudukkottai", "Ramanathapuram", "Salem", "Sivaganga", "Tenkasi", "Thanjavur", "Theni", "Tirunelveli", "Tirupur", "Tiruvallur", "Tiruvannamalai", "Vellore", "Viluppuram", "Virudhunagar"
    ]
  },
  {
    state: "Telangana",
    districts: [
      "Adilabad", "Hyderabad", "Jagtial", "Jangaon", "Jayashankar Bhupalapally", "Jogulamba Gadwal", "Kamareddy", "Karimnagar", "Khammam", "Komaram Bheem Asifabad", "Mahabubabad", "Mahabubnagar", "Mancherial", "Medak", "Medchal-Malkajgiri", "Mulugu", "Nalgonda", "Nirmal", "Nizamabad", "Peddapalli", "Rajanna Sircilla", "Rangareddy", "Sangareddy", "Siddipet", "Suryapet", "Vikarabad", "Wanaparthy", "Warangal Rural", "Warangal Urban", "Yadadri Bhuvanagiri"
    ]
  }
];
const courseData = {
    "BSc": [
        "Computer Science", "Information Technology", "Mathematics",
        "Physics", "Chemistry", "Biotechnology", "Electronics",
        "Statistics", "Geology", "Environmental Science", "Botany",
        "Zoology", "Microbiology", "Astronomy", "Genetics",
        "Forensic Science", "Biochemistry", "Data Science", "Nanotechnology",
        "Neuroscience", "Oceanography", "Agricultural Science", "Material Science"
    ],
    "BA": [
        "English Literature", "Political Science", "Economics",
        "Psychology", "History", "Sociology", "Philosophy",
        "Anthropology", "Linguistics", "Journalism", "Fine Arts",
        "Performing Arts", "Religious Studies", "Geography",
        "Archaeology", "Mass Communication", "Education", "Library Science"
    ],
    "BCom": [
        "General", "Accounting & Finance", "Corporate Secretaryship",
        "Banking Management", "Insurance", "Taxation", "E-Commerce",
        "Business Analytics", "Finance & Investment", "Marketing",
        "Human Resource Management", "International Business",
        "Retail Management", "Supply Chain Management", "Financial Planning",
        "Entrepreneurship", "Commerce & Law"
    ],
    "Engineering": [
        "Computer Science", "Mechanical", "Civil",
        "Electrical", "Electronics & Communication", "Chemical",
        "Aerospace", "Automobile", "Industrial", "Biomedical",
        "Environmental", "Mechatronics", "Mining", "Petroleum",
        "Robotics", "Nanotechnology Engineering", "Marine Engineering",
        "Production Engineering", "Textile Engineering"
    ],
    "Medical & Health Sciences": [
        "MBBS", "BDS", "Nursing", "Pharmacy", "Physiotherapy",
        "Occupational Therapy", "Medical Lab Technology", "Nutrition",
        "Public Health", "Ayurveda", "Homeopathy", "Yoga Therapy",
        "Optometry", "Radiology", "Speech Therapy", "Health Informatics",
        "Dietetics", "Genetic Counseling", "Emergency Medical Services"
    ],
    "Design & Arts": [
        "Fashion Design", "Interior Design", "Graphic Design",
        "Animation", "Film & Media", "Product Design", "Photography",
        "Performing Arts", "Fine Arts", "Textile Design", "Ceramic Design",
        "Jewelry Design", "Game Design", "UI/UX Design", "Sound Design"
    ],
    "Law & Social Sciences": [
        "LLB", "Criminology", "Social Work", "Public Administration",
        "Human Rights", "International Relations", "Political Theory",
        "Labour Law", "Cyber Law", "Corporate Law", "Sociology",
        "Development Studies"
    ],
    "Vocational & Applied Sciences": [
        "Hotel Management", "Travel & Tourism", "Event Management",
        "Culinary Arts", "Sports Science", "Agriculture", "Forestry",
        "Fisheries", "Animal Husbandry", "Horticulture", "Landscape Design",
        "Renewable Energy", "Automotive Technology", "Aeronautical Technology",
        "Maritime Studies"
    ],
    "Computer & IT Courses": [
        "Artificial Intelligence", "Machine Learning", "Data Analytics",
        "Cloud Computing", "Cybersecurity", "Blockchain Technology",
        "Web Development", "App Development", "Game Development",
        "Software Engineering", "UI/UX Design", "DevOps", "Networking",
        "Database Management", "Robotics Programming", "IoT"
    ]
};

