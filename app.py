import os
import joblib
import numpy as np
from sklearn.linear_model import SGDRegressor
from sklearn.preprocessing import StandardScaler
from sklearn.pipeline import Pipeline

MODEL_PATH = "risk_model.joblib"

# ==================================================
# Load or create model
# ==================================================
if os.path.exists(MODEL_PATH):
    model = joblib.load(MODEL_PATH)
else:
    scaler = StandardScaler()
    regressor = SGDRegressor(
        loss="squared_error",
        learning_rate="adaptive",
        eta0=0.01,
        alpha=0.0005,        # regularization
        random_state=42
    )

    model = Pipeline([
        ("scaler", scaler),
        ("regressor", regressor)
    ])

    # Proper initialization
    X_init = np.zeros((1, 4))
    y_init = np.array([0])

    scaler.partial_fit(X_init)
    regressor.partial_fit(scaler.transform(X_init), y_init)

    joblib.dump(model, MODEL_PATH)

# ==================================================
print("🎓 Risk Predictor (Self-Learning Model)")
print("Feature order: incidents, marks, attendance, pending")
print("Ctrl+C to exit\n")

# ==================================================
while True:
    try:
        incidents = int(input("Incident count (0–10): "))
        marks = float(input("Internal marks (0–50): "))
        attendance = float(input("Attendance %: "))
        pending = float(input("Pending fees: "))

        # -----------------------------
        # Feature vector (FIXED ORDER)
        # -----------------------------
        X = np.array([[incidents, marks, attendance, pending]])

        # Scale & predict
        X_scaled = model.named_steps["scaler"].transform(X)
        predicted = model.named_steps["regressor"].predict(X_scaled)[0]

        # -----------------------------
        # Hard constraints (PHP safety)
        # -----------------------------
        if incidents == 10:
            predicted = 100
        if incidents >= 7:
            predicted = max(predicted, 50)
        if marks < 20 and attendance < 60:
            predicted = max(predicted, 75)

        predicted = int(round(np.clip(predicted, 0, 100)))


        print(f"\n⚠️ Predicted Risk: {predicted}/100")

        # -----------------------------
        # User feedback
        # -----------------------------
        ok = input("Is this correct? (y/n): ").strip().lower()

        if ok == "n":
            true_risk = int(input("Enter correct risk (0–100): "))
        else:
            true_risk = predicted

        true_risk = max(0, min(true_risk, 100))

        # -----------------------------
        # Incremental learning (CORRECT)
        # -----------------------------
        model.named_steps["scaler"].partial_fit(X)
        X_scaled = model.named_steps["scaler"].transform(X)
        model.named_steps["regressor"].partial_fit(X_scaled, [true_risk])

        joblib.dump(model, MODEL_PATH)
        print("✅ Model updated & saved\n")

    except KeyboardInterrupt:
        print("\n👋 Exiting...")
        break
    except Exception as e:
        print(f"❌ Error: {e}\nTry again.\n")