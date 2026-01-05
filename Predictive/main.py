import pandas as pd
import numpy as np
import matplotlib.pyplot as plt

# Create dataset (no file needed)
df = pd.DataFrame({
    "Student": ["A", "B", "C", "D", "E"],
    "Marks": [72, 85, 90, 66, 78]
})

# Pandas → NumPy
marks_array = df["Marks"].to_numpy()

# NumPy computation
average = np.mean(marks_array)

# Visualization using Matplotlib
plt.plot(df["Student"], marks_array, marker='o')
plt.title(f"Student Marks (Average = {average:.2f})")
plt.xlabel("Student")
plt.ylabel("Marks")
plt.grid(True)
plt.show()
