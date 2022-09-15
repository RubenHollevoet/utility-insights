import threading
import mysql.connector
import random
import time

import p1_port

#global vars
data_push_interval = 10

utilityDb = mysql.connector.connect(
        user="app",
        password="t09x4f66",
        host="127.0.0.1",
        port="3306",
        database="utility_stats"
    )

cursor = utilityDb.cursor()


#db setup
utilityDb = mysql.connector.connect(
        user="app",
        password="t09x4f66",
        host="127.0.0.1",
        port="3306",
        database="utility_stats"
    )
cursor = utilityDb.cursor()

#load settings from DB
cursor.execute("SELECT value FROM settings WHERE `key` = 'electricity_meter_interval'")
data_push_interval = int(cursor.fetchone()[0])

print("start collection P1 port data from electricity meter. Registering power every ", data_push_interval, " seconds.")


#start collecting data
def printit():
    threading.Timer(int(data_push_interval), printit).start()


    p1 = p1_port.readSerialData()

    sql = "insert into data_electricity_meter (`created_at`, `consumption`, `production`) values(NOW(), %s, %s);"
    val = (p1['consumption_current'], p1['production_current'])
    cursor.execute(sql, val)
    utilityDb.commit()

    #todo: insert day and night values if day is almost over

printit()
# print(p1_port.readSerialData())