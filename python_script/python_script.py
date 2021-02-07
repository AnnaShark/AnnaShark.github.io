
import numpy as np
import pandas as pd
from sklearn.preprocessing import LabelEncoder
from sklearn.model_selection import train_test_split
from sklearn.naive_bayes import GaussianNB
import sys
import pickle
import warnings


warnings.filterwarnings("ignore")

TSH = float(sys.argv[1])
T3 = float(sys.argv[2])
TT4 = float(sys.argv[3])
T4U = float(sys.argv[4])
FTI = float(sys.argv[5])

filename = '/Applications/XAMPP/xamppfiles/htdocs/final_proj/AnnaShark.github.io/python_script/modelo_entrenado.sav'
loaded_model = pickle.load(open(filename, 'rb'))

prediction = loaded_model.predict([[TSH,T3,TT4,T4U,FTI]])

print(prediction[0])


