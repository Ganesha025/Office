import joblib
import numpy as np
from sklearn.ensemble import RandomForestRegressor
import pandas as pd

df = pd.read_csv(
    "Model.csv",
    header=None,
    names=["attendance", "marks", "fee_due", "incidents"]
)

def calculate_risk(attendance, marks, pending, incidents):
    attendance = float(attendance)
    marks = float(marks)
    pending = float(pending)
    # department_fee = float(department_fee)
    incidents = int(incidents)

    risk = 0

    if incidents == 10:
        risk = 100
        if marks < 20 or attendance < 75:
            risk = 100
        elif marks >= 35 and attendance >= 85 or pending == 0:
            risk = 70
        else:
            risk += round((50 - min(marks, 50)) / 50 * 15)

    elif incidents == 9:
        risk = 85
        if marks < 20 or attendance < 75:
            risk = 100
        elif marks >= 35 and attendance >= 85 or pending == 0:
            risk = 70
        else:
            risk += round((50 - min(marks, 50)) / 50 * 15)

    elif incidents == 8:
        risk = 75
        if marks < 20:
            risk += 15
        elif marks >= 30 and attendance >= 80:
            risk -= 10
        else:
            risk += round((50 - min(marks, 50)) / 50 * 12)

    elif incidents == 7:
        risk = 65
        if marks < 20 or attendance < 65:
            risk += 10
        elif marks >= 35 and attendance >= 85:
            risk -= 5
        else:
            risk += round((50 - min(marks, 50)) / 50 * 10)

    elif incidents == 6:
        risk = 55
        if marks < 20:
            risk += 10
        elif marks >= 30 and attendance >= 80:
            risk -= 5
        else:
            risk += round((50 - min(marks, 50)) / 50 * 8)

    elif incidents == 5:
        risk = 50
        if marks < 20 or attendance < 75:
            risk += 10
        elif marks >= 35 and attendance >= 85:
            risk -= 5
        else:
            risk += round((50 - min(marks, 50)) / 50 * 7)

    elif incidents == 4:
        risk = 40
        if marks < 20:
            risk += 5
        else:
            risk += round((50 - min(marks, 50)) / 50 * 5)

    elif incidents == 3:
        risk = 35
        if marks < 20:
            risk += 5
        else:
            risk += round((50 - min(marks, 50)) / 50 * 4)

    elif incidents == 2:
        risk = 20
        if marks < 20:
            risk += 15
        else:
            risk += round((50 - min(marks, 50)) / 50 * 2)

    elif incidents == 1:
        risk = 10
        if marks >= 40:
            risk -= 5
        else:
            risk += round((50 - min(marks, 50)) / 50 * 1)
    elif incidents==0:
        risk=0
        if attendance>=80 or marks>=30:
            risk=0
        else:
            risk=2
        
    else:
        risk = 0

    # 📌 Marks-based adjustments
    if marks < 20:
        if incidents >= 5:
            risk += round((50 - marks) / 50 * 30)
        else:
            risk += 25 if marks < 10 else 15

    elif marks < 30:
        if incidents >= 5:
            risk += round((50 - marks) / 50 * 10)
        else:
            risk += 5

    elif marks >= 40:
        risk -= 10

    # 📌 Pending fees
    # if pending > 0:
        # pending_percent = min(1.0, pending / max(1, department_fee))
        # risk += round(pending_percent * 25)

    # 📌 Attendance
    if attendance < 50:
        risk += 20
    elif attendance < 65:
        risk += 12
    elif attendance <= 75:
        risk += 0
    elif attendance >= 90:
        risk -= 5

    # 📌 Hard constraints
    if incidents >= 7 and risk < 50:
        risk = 50

    if marks < 20 and attendance < 75:
        risk = max(risk, 75)
    if incidents==0 and marks>=45 and attendance >=85 or pending==0:
        risk=0

    return max(0, min(int(round(risk)), 100))


# 🔁 Generate training data
X, y = [], []

for _, row in df.iterrows():
    incidents = row["incidents"]
    marks = row["marks"]
    attendance = row["attendance"]
    fee_due = row["fee_due"]
    # dept_fee = row["department_fee"]

    risk = calculate_risk(attendance, marks, fee_due, incidents)

    X.append([incidents, marks, attendance, fee_due])
    y.append(risk)

X = np.array(X)
y = np.array(y)

model = RandomForestRegressor(
    n_estimators=300,   # number of trees
    max_depth=10,       # limit tree depth
    random_state=42,
    n_jobs=-1           # use all CPU cores
)


model.fit(X, y)

joblib.dump(model, "risk_model.joblib")
print("✅ Model trained with feature order: incidents, marks, attendance, fee_due")
