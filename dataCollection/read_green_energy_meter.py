import RPi.GPIO as GPIO
import time
import mysql.connector

#GPIO setup
BUTTON_PIN = 16
GPIO.setmode(GPIO.BCM)

#measurements
MIN_POWER_THRESHOLD = 20 #below 50 kW we will record 0 kW
POWER_SPIKE_IGNORE = 5500 #ignore power peaks above this value
data_push_interval = 10
pulseCount = 0
lastPulse = 0
lastInterval = 0

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
cursor.execute("SELECT value FROM settings WHERE `key` = 'green_energy_interval'")
data_push_interval = int(cursor.fetchone()[0])

print("start collection green energy meter from electric pulses. Registering power every ", data_push_interval, " seconds.")

def pulse_callback(channel):
    global pulseCount, lastPulse, lastInterval
    pulseCount += 1
    currentTime = time.time()
    if lastPulse != 0 and (3600/(currentTime - lastPulse)) < POWER_SPIKE_IGNORE:
        lastInterval = currentTime - lastPulse
    lastPulse = currentTime

GPIO.setup(BUTTON_PIN, GPIO.IN, pull_up_down=GPIO.PUD_DOWN)

GPIO.add_event_detect(BUTTON_PIN, GPIO.RISING,
                      callback=pulse_callback,
                      bouncetime=250)

try:
    while True:
        time.sleep(data_push_interval)
        power = 0
        thresholdOk = (time.time() - lastPulse) < (3600/MIN_POWER_THRESHOLD)
        if lastInterval > 0 and thresholdOk:
            power = 3600/lastInterval
        totalPower = pulseCount;

        #write results to DB
        sql = "INSERT INTO data_green_energy (`created_at`, `power`) VALUES(NOW(), %s);"
        val = (power, )
        cursor.execute(sql, val)
        utilityDb.commit()


except KeyboardInterrupt:
    GPIO.cleanup()